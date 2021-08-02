<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


$APPLICATION->IncludeComponent("liga:super.component", 
	"api", 
	Array(
		"IBLOCK_PLAYER_ID" => 1,
		"IBLOCK_TEAM_ID" => 2,
		"IBLOCK_TOURNAMENT_ID" => 3,
		"ACTION" => $_GET['action'],
		"ID" => $_GET['id'],
		"NAME" => $_GET['name'],
		"STAGE" => $_GET['stage'],
		"DEPTH" => $_GET['depth'],
		"DATEFROM" => $_GET['datefrom'],
		"DATETO" => $_GET['dateto'], 
		"TOURNAMENT" => $_GET['tournament'],
		"PAGE" => $_GET['page'],
		"CACHE_TIME" => "0",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
	),
	false
);

header('Content-Type: application/json');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>