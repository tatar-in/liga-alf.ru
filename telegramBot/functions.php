<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

include_once "private.php";

// Отправляем в telegram канал
function sendPostToTelegramChannel($posts)
{
	// открываем базу
	$db = createDatabase('vk.db');

	foreach ($posts as $key => $value) 
	{
		$types = array_unique(explode(";", $value['attachment_type']));
		if(count($types) == 1 && in_array('photo', $types))
		{
			$photos = explode(";", $value['attachments']);
			if(count($photos) > 1)
			{
				$media = [];
				foreach ($photos as $k => $v) {
					$media[$k] = array('type' => 'photo', 'media' => $v);
					if($k==0) $media[$k]['caption'] = $value['description'];
				}
				$res = sendTelegram('sendMediaGroup', array('chat_id' => -1001566318537, 'media' => json_encode($media)));
			}
			else
			{
				$res = sendTelegram('sendPhoto', array('chat_id' => -1001566318537, 'photo' => $value['attachments'], 'caption' => $value['description']));
			}
		}
		else
		{
			$res = sendTelegram('sendMessage', array('chat_id' => -1001566318537, 'text' => $value['description']."\n\nhttps://vk.com/club".substr($value['group_id'], 1)."?w=wall".$value['group_id']."_".$value['id']));
		}

		// отправляем сообщение при ошибке обновления
		if(!$db->exec("UPDATE posts SET published_tlgrm = ".$res['result']['message_id']." WHERE id = ".$value['id']))
		{
			sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => "Ошибка sendPostToTelegramChannel. Обновление в БД: UPDATE posts SET published_tlgrm = ".$res['result']['message_id']." WHERE id = ".$value['id']));
		}
	}
	$db->close();
}

// Получаем все посты и добавляем в базу
function getAllVKGroupWallPosts($id)
{
	// получаем количество постов
	$query = http_build_query(array('owner_id' => $id, 'count' => 5, 'v' => 5.131));
	$response = getVk('wall.get', $query);
	$count = $response['response']['count'];
	
	// создаем или открываем базу
	$db = createDatabase('vk.db');

	// постранично получаем список постов (max 100 постов на странице - ограничение VK)
	for ($i = 0; $i <= $count; $i+=100) 
	{ 
		$query = array('owner_id' => $id, 'count' => 100, 'v' => 5.131);
		if ($i != 0) $query['offset'] = $i; // добавляем смещение , если страница не первая
		$request = http_build_query($query);
		$response = getVk('wall.get', $request);

		// проходимся по каждому посту
		foreach ($response['response']['items'] as $key => $value) 
		{
			if ($value['marked_as_ads'] == 1) continue; // пропускаем пост, если это реклама
			$result = array('id' => $value['id'], 
							'group_id' => $value['owner_id'],
							'description' => $value['text'], 
							'date' => $value['date'],
							'attachment_type' => '',
							'attachments' => '');

			if (!empty($value['attachments'])) 
			{
				// проходимся по вложениям
				foreach ($value['attachments'] as $k => $v) 
				{
					$result['attachment_type'] = ($result['attachment_type'] == '') ? $v['type'] : $result['attachment_type'] . ";" . $v['type'];
					if ($v['type'] == 'photo')
					{
						$el = end($v['photo']['sizes']); // берем последнюю, самую крупную фотку
						$result['attachments'] = ($result['attachments'] == '') ? $el['url'] : $result['attachments'] . ";" . $el['url'];
					}
					elseif ($v['type'] == 'video') 
					{
						$el = "owner_id=".$v['video']['owner_id']."&id=".$v['video']['id']."&access_key=".$v['video']['access_key'];
						$result['attachments'] = ($result['attachments'] == '') ? $el : $result['attachments'] . ";" . $el;
					}
					else
					{
						$result['attachments'] = ($result['attachments'] == '') ? ' ' : $result['attachments'] . "; ";
					}
				}
			}

			// ищем пост в базе
			$row = $db->querySingle('SELECT id FROM posts WHERE id='.$value['id']);
			if($row == null) // поста нет в БД, добавляем
			{
				if(!$db->exec("INSERT INTO posts (" . implode(',', array_keys($result)). ") VALUES (".$result['id'].", ".$result['group_id'].", '".htmlentities($result['description'], ENT_QUOTES, 'UTF-8')."', ".$result['date'].", '".$result['attachment_type']."', '".$result['attachments']."')")) 
				{
					sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => "Ошибка getAllVKGroupWallPosts. Добавление поста в БД: INSERT INTO posts (" . implode(',', array_keys($result)). ") VALUES (".$result['id'].", '".htmlentities($result['description'], ENT_QUOTES, 'UTF-8')."', ".$result['date'].", '".$result['attachment_type']."', '".$result['attachments']."')"));
					exit();
				}
			}
			elseif($row == false) // ошибка в запросе к БД
			{
				sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => 'Ошибка getAllVKGroupWallPosts. Запрос к БД: SELECT id FROM posts WHERE id='.$value['id']));
				exit();
			}
		}
	}
	$db->close();
}

// Получаем последние посты. При отсутствии в БД, добавляем в базу и возвращаем новые посты
function getLastVKGroupWallPosts($id)
{
	// получаем список последних постов
	$query = array('owner_id' => $id, 'count' => 50, 'v' => 5.131);
	$request = http_build_query($query);
	$response = getVk('wall.get', $request);

	// открываем или создаем БД
	$db = createDatabase('vk.db');
	
	// формируем массив новых постов
	$res = [];

	// проходимся по постам
	foreach ($response['response']['items'] as $key => $value) 
	{
		if ($value['marked_as_ads'] == 1) continue; // пропускаем пост, если это реклама

		// ищем пост в базе
		$row = $db->querySingle('SELECT id FROM posts WHERE id='.$value['id']);
		if($row == null) // поста нет в БД, добавляем 
		{
			$result = array('id' => $value['id'], 
							'group_id' => $value['owner_id'],
							'description' => $value['text'], 
							'date' => $value['date'],
							'attachment_type' => '',
							'attachments' => '');
			if (!empty($value['attachments'])) 
			{
				foreach ($value['attachments'] as $k => $v) 
				{
					$result['attachment_type'] = ($result['attachment_type'] == '') ? $v['type'] : $result['attachment_type'] . ";" . $v['type'];
					if ($v['type'] == 'photo')
					{
						$el = end($v['photo']['sizes']);
						$result['attachments'] = ($result['attachments'] == '') ? $el['url'] : $result['attachments'] . ";" . $el['url'];
					}
					elseif ($v['type'] == 'video') 
					{
						$el = "owner_id=".$v['video']['owner_id']."&id=".$v['video']['id']."&access_key=".$v['video']['access_key'];
						$result['attachments'] = ($result['attachments'] == '') ? $el : $result['attachments'] . ";" . $el;
					}
					else
					{
						$result['attachments'] = ($result['attachments'] == '') ? ' ' : $result['attachments'] . "; ";
					}
				}
			}

			if(!$db->exec("INSERT INTO posts (" . implode(',', array_keys($result)). ") VALUES (".$result['id'].", ".$result['group_id'].", '".htmlentities($result['description'], ENT_QUOTES, 'UTF-8')."', ".$result['date'].", '".$result['attachment_type']."', '".$result['attachments']."')")) 
			{
				sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => "Ошибка getLastVKGroupWallPosts. Добавление поста в БД: INSERT INTO posts (" . implode(',', array_keys($result)). ") VALUES (".$result['id'].", '".htmlentities($result['description'], ENT_QUOTES, 'UTF-8')."', ".$result['date'].", '".$result['attachment_type']."', '".$result['attachments']."')"));
				exit();
			}

			$res[$key] = $result;
		}
		elseif($row == false) // ошибка в запросе к БД
		{
			sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => 'Ошибка getLastVKGroupWallPosts. Запрос к БД: SELECT id FROM posts WHERE id='.$value['id']));
			exit();
		}
	}
	$db->close();

	return $res;
}

// Отправка расписания матчей. Обработка команд /laststage, /nextstage и вывод ответа функции chooseStageByTelegramBot
function sendMatchesByTelegramBot($chat_id, $params = null, $command = null)
{
	$query = http_build_query($params);
	$response = getSite('getmatches', $query);
	$result = $response['result']; 

	// проходимся по страницам API и собираем результат
	$pages = $response['pages'];
	for ($i = 2; $i <= $pages; $i++) 
	{ 
		$params['page'] = $i;
		$query = http_build_query($params);
		$response = getSite('getmatches', $query);
		$result = array_merge($result, $response['result']);
	}	
	
	// переворачиваем массив, т.к. сортировка в API по убыванию, а нам нужны ближайшие игры 
	// (актуально, если расписание на несколько туров вперед)
	if($command == "nextstage") $result = array_reverse($result);
	
	foreach ($result as $key => $value) 
	{
		// переформируем массив игр так, чтобы иметь возможность получать информацию о матче по id
		$matches[$value['id']] = $value;
		// из принадлежности матча к турнирам формируем массив турниров таким образом, чтобы последний турнир содержал массив этапов
		// (достаточно универсальный вариант формирования вывода, т.к. всю необходимую информацию содержат выбранные матчи - навигационная цепочка турниров)
		foreach ($value['tournament'] as $k => $v) 
		{
			$tournament[$v['id']]['id'] = $v['id']; 
			$tournament[$v['id']]['name'] = $v['name']; 
			$tournament[$v['id']]['parent'] = $v['parent']; 
			$tournament[$v['id']]['statistic'] = $v['statistic'];
			if($k == count($value['tournament'])-1) $tournament[$v['id']]['stages'][$value['stage']][$value['id']] = $value['id'];
		}
	}
	
	// проходимся по турнирам и формируем ответ
	foreach ($tournament as $key => $value)
	{
		$str .= (!$value['parent']) ? "<b>".strtoupper($value['name'])."</b>\n\n" : "<b>".$value['name']."</b>\n\n";
		// если турнир последний, т.е. содержит этапы, перебираем этапы 
		if($value['stages']) {
			foreach ($value['stages'] as $k => $v) {
				$str .= $k."\n\n";
				// выводим матчи
				foreach ($v as $k1 => $v1) {
					$str .= $matches[$v1]['date'].' <a href="'.$matches[$v1]['url'].'">'.$matches[$v1]['name']."</a> ".((isset($matches[$v1]['score_1']) && isset($matches[$v1]['score_2'])) ? $matches[$v1]['score_1'].":".$matches[$v1]['score_2'] : "-:-")."\n";
				}
				break; // ограничиваемся одним ближайшим этапом
			}
			$str .= "\n";
		}
		// если по турниру осуществляется рассчет статистики, выводим дополнительные ссылки
		if($value['statistic'] == 1) {
			$str .= '<a href="https://liga-alf.ru/tournament/table/?TOURNAMENT='.$value['id'].'">Таблица</a>'."\n";
			$str .= '<a href="https://liga-alf.ru/tournament/bombardir/?TOURNAMENT='.$value['id'].'">Бомбардиры</a>'."\n";
			$str .= '<a href="https://liga-alf.ru/tournament/warning/?TOURNAMENT='.$value['id'].'">Карточки</a>'."\n";
			$str .= '<a href="https://liga-alf.ru/tournament/autogoals/?TOURNAMENT='.$value['id'].'">Автоголы</a>'."\n";
			$str .= "\n";
		}
	}
	sendTelegram('sendMessage', array('chat_id' => $chat_id, 'text' => $str, 'parse_mode' => 'html'));
}

// Обработка команды /choosestage - варианты выбора турниров и этапов (туров)
function chooseStageByTelegramBot($chat_id, $command, $message_id = null)
{	
	$commands = explode("_", $command);
	if(count($commands) == 1) $params = array('depth' => 1); // ищем по верхнеуровневым турнирам (шаг 1)
	elseif (count($commands) == 2) $params = array();		// выбираем все турниры, чтобы потом обработать
	$query = http_build_query($params);
	$response = getSite('gettournaments', $query);
	$result = $response['result']; 

	// формируем варианты верхнеуровневых турниров (шаг 1)
	if(count($commands) == 1){
		// формируем кнопки для inline клавиатуры
		foreach ($result as $key => $value) {
			$buttons[] = array('text' => $value['name'], 'callback_data' => $commands[0]."_".$value['id']);
			if(count($buttons) == 2){
				$keyboard['inline_keyboard'][] = $buttons;
				$buttons = [];
			}
		}
		$keyboard['inline_keyboard'][] = $buttons;	// оставшиеся кнопки (если их меньше 2)
		sendTelegram('sendMessage', array('chat_id' => $chat_id, 'text' => "Выбери турнир, информация по матчам которого тебя интересует:", 'reply_markup' => json_encode($keyboard)));
	}
	// проверяем выбранный турнир и выдаем либо варианты вложенных турниров, либо варианты этапов/туров (шаг 2 или далее)
	elseif (count($commands) == 2) {
		// переформируем массив турниров, чтобы потом по id турнира получать необходимую информацию
		foreach ($result as $key => $value) 
			$tournaments[$value['id']] = $value;

		foreach ($result as $key => $value) {
			// если выбранный пользователем турнир содержит вложенные несвязанные турниры, формируем варианты вложенных турниров
			if($commands[1] == $value['id'] && $value['sub']){
				foreach ($value['sub'] as $k => $v) {
					$buttons[] = array('text' => $tournaments[$v]['name'], 'callback_data' => $commands[0]."_".$v);
					if(count($buttons) == 2){
						$keyboard['inline_keyboard'][] = $buttons;
						$buttons = [];
					}
				}
				$keyboard['inline_keyboard'][] = $buttons;	// оставшиеся кнопки (если их меньше 2)
				sendTelegram('editMessageText', array('chat_id' => $chat_id, 'message_id' => $message_id, 'text' => "Выбранный турнир содержит другие вложенные турниры, ведущие свою собственную статистику.\nУточни интересующий турнир:", 'reply_markup' => json_encode($keyboard)));			
			}
			// если выбранный пользователем турнир конечный, формируем варианты этапов/туров
			elseif ($commands[1] == $value['id'] && $value['stage']) {
				foreach ($value['stage'] as $k => $v) {
					$buttons[] = array('text' => $v, 'callback_data' => $commands[0]."_".$value['id']."_".$v);
					if(count($buttons) == 3){
						$keyboard['inline_keyboard'][] = $buttons;
						$buttons = [];
					}
				}
				$keyboard['inline_keyboard'][] = $buttons;	// оставшиеся кнопки (если их меньше 3)
				sendTelegram('editMessageText', array('chat_id' => $chat_id, 'message_id' => $message_id, 'text' => "Выбери этап турнира, информация по матчам которого тебя интересует:", 'reply_markup' => json_encode($keyboard)));			
			}		
		}
	}
	// заключительный шаг - отправка запроса в функцию sendMatchesByTelegramBot для формирования ответа
	elseif(count($commands) == 3){
		sendTelegram('editMessageText', array('chat_id' => $chat_id, 'message_id' => $message_id, 'text' => "Начал работать...", 'reply_markup' => ""));
		sendMatchesByTelegramBot($chat_id, array('tournament' => $commands[1], 'stage' => $commands[2]));
	}
}

//------------Database----------------------------------------------------------------------------------------------------------------

	function createDatabase($filename)
	{
		if(file_exists($filename)) return new SQLite3($filename);

		$db = new SQLite3($filename);

		if ($filename == "vk.db")
		{
			$db->exec('CREATE TABLE posts ("id" INTEGER PRIMARY KEY NOT NULL, "group_id" INTEGER, "description" TEXT, "date" INTEGER, "attachment_type" TEXT, "attachments" TEXT, "published_tlgrm" INTEGER, "published_site" INTEGER)');
		}
		elseif ($filename == "tlgrm.db") 
		{
			$db->exec('CREATE TABLE commands ("id" INTEGER PRIMARY KEY NOT NULL, "description" TEXT)');
		}

		return $db;
	}

//------------Telegram API----------------------------------------------------------------------------------------------------------------


	// Функция вызова методов API telegram.
	function sendTelegram($method, $params)
	{
		$ch = curl_init('https://api.telegram.org/bot' . TLGRM . '/' . $method);  
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$res = curl_exec($ch);
		curl_close($ch);
	 
		$res = json_decode($res, true);

		// если не успешный запрос, останавливаем и отправляем сообщение
		if(!$res['ok'])
		{
			sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => 'Ошибка sendTelegram. Описание ошибки telegram: '.$res['description']));
			exit();	
		}

		return $res;
	}

//------------VK API----------------------------------------------------------------------------------------------------------------


	// Функция вызова методов API VK
	function getVk($method, $params)
	{
		$ch = curl_init('https://api.vk.com/method/' . $method . '?access_token=' . VK . '&' . $params);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		curl_close($ch);

		$res = json_decode($res, true);

		// если не успешный запрос, останавливаем и отправляем сообщение
		if(empty($res['response']))
		{
			sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => 'Ошибка getVk. Запрос: https://api.vk.com/method/' . $method . '?access_token=' . VK . '&' . $params));
			exit();	
		}
	 
		return $res;
	}

//------------Site API----------------------------------------------------------------------------------------------------------------

	function getSite($action, $params = null)
	{
		$params = ($params) ? '&'.$params : "";
		$ch = curl_init('https://liga-alf.ru/api/?action=' . $action . $params);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res = curl_exec($ch);
		curl_close($ch);

		$res = json_decode($res, true);

		// если не успешный запрос, останавливаем и отправляем сообщение
		if(!$res['ok'] && $res['message'] == "Action undefined")
		{
			sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => 'Ошибка getSite. Описание ошибки site: '.$res['message'].'. Запрос: https://liga-alf.ru/api/?'.$action.$params));
			exit();	
		}

		return $res;
	}


?>