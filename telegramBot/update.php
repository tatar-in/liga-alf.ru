<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once "functions.php";

if($_GET['command'])
{
	$command = explode('_', $_GET['command']);
	if($command[0] == 'lastposts')
	{
		$posts = sendPostToTelegramChannel(getLastVKGroupWallPosts($command[1]));
	}
	else
	{
		sendTelegram('sendMessage', array('chat_id' => 121231592, 'text' => 'Ошибка крона. Команда: '.$_GET['command']));
	}

	exit();
}

$data = file_get_contents('php://input');
$data = json_decode($data, true);
file_put_contents(__DIR__ . '/message.txt', print_r($data, true));

if (empty($data['message']['chat']['id'])) exit();


// Ответ бота
if (!empty($data['message']['text']))
{
	sendTelegram('sendMessage', array('chat_id' => $data['message']['chat']['id'], 'text' => "Вы написали:\n\n".$data['message']['text']));
}

?>

