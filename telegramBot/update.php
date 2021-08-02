<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once "functions.php";

if($_GET['command'])
{
	$command = explode('_', $_GET['command']);
	if($command[0] == 'lastposts')
		sendPostToTelegramChannel(getLastVKGroupWallPosts($command[1]));
	else
		sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => 'Ошибка крона. Команда: '.$_GET['command']));

	exit();
}

$data = file_get_contents('php://input');
$data = json_decode($data, true);
file_put_contents(__DIR__ . '/message.txt', print_r($data, true));

if (empty($data['update_id'])) exit();


// Ответ бота
if ($data['message'])
{
	if($data['message']['forward_from'] || $data['message']['forward_date'])
		sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Пересылка сообщений пока не поддерживается", 'reply_to_message_id' => $data['message']['message_id']));
	if($data['message']['location'])
		sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Прием геолокации пока не поддерживается", 'reply_to_message_id' => $data['message']['message_id']));
	if($data['message']['contact'])
		sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Прием контактов пока не поддерживается", 'reply_to_message_id' => $data['message']['message_id']));
	if($data['message']['poll'])
		sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Прием опросов пока не поддерживается", 'reply_to_message_id' => $data['message']['message_id']));
	if($data['message']['video'])
		sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Прием видео пока не поддерживается", 'reply_to_message_id' => $data['message']['message_id']));
	if($data['message']['document'])
		sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Прием файлов пока не поддерживается", 'reply_to_message_id' => $data['message']['message_id']));
	if($data['message']['text'])
	{
		$text = $data['message']['text'];
		if($text == "/start")
			sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Приветствую тебя, ".$data['message']['from']['first_name']."!\nЯ - telegram бот АЛФ, готов выполнять для тебя различные команды. Вот, что я умею - /help"));
		elseif($text == "/help")
			sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "<b>Выполню для любого:</b>\n\n".
				"/laststage - прошедшие игры\n".
				"/nextstage - следующие игры\n".
				"/choosestage - выбрать игры самостоятельно\n".
				"\nОстались вопросы?! Пиши мне <a href='tg://user?id=121231592'>в личку</a>", 
				'parse_mode' => 'html'));
		elseif($text == "/laststage")
			sendMatchesByTelegramBot($data['message']['chat']['id'], array('datefrom' => date("d.m.Y", strtotime("-32 days")), 'dateto' => date("d.m.Y", strtotime("-1 day"))), 'laststage');	
		elseif($text == "/nextstage")
			sendMatchesByTelegramBot($data['message']['chat']['id'], array('datefrom' => date("d.m.Y"), 'dateto' => date("d.m.Y", strtotime("+31 days"))), 'nextstage');	
		elseif($text == "/choosestage")
			chooseStageByTelegramBot($data['message']['chat']['id'], $data['message']['text']);
		else
			sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Такую команду я не знаю. Вот, что я умею - /help"));
	}
}
elseif($data['edited_message']) 
	sendTelegram('sendMessage', array('chat_id' => $data['edited_message']['chat']['id'], 'text' => "Редактирование сообщений пока не поддерживается", 'reply_to_message_id' => $data['edited_message']['message_id']));
elseif($data['callback_query'])
	if(strpos($data['callback_query']['data'], "/choosestage") == 0)
		chooseStageByTelegramBot($data['callback_query']['message']['chat']['id'],  $data['callback_query']['data'], $data['callback_query']['message']['message_id']);



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>

