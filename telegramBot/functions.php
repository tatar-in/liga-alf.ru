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
		$ch = curl_init('https://api.vk.com/method/' . $method . '?access_token=' . VK . '&' . $params); //'domain=alf_alexin&count=10&v=5.84&);  
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


?>