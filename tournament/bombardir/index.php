<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Бомбардиры");
?>

<?if($_GET["TOURNAMENT"]){
	$APPLICATION->IncludeComponent("liga:super.component", 
		"tournament_item", 
		Array(
			"IBLOCK_ID" => "3",
			"SECTION_ID" => $_GET["TOURNAMENT"],
			"DIR" => dirname($_SERVER["REQUEST_URI"]),
			"CACHE_TIME" => "3600",	// Время кеширования (сек.)
			"CACHE_TYPE" => "A",	// Тип кеширования
		),
		false
	);


	$APPLICATION->AddChainItem("Бомбардиры", "bombardir/");


	$APPLICATION->IncludeComponent(
		"liga:super.component", "stat_bombardir", 
		Array(
			"TYPE" => "bombardir",
			"SECTION_ID" => $_GET["TOURNAMENT"],
			"CACHE_TIME" => "3600",	// Время кеширования (сек.)
			"CACHE_TYPE" => "A",	// Тип кеширования
		),
		false
	);
}
else{
	Bitrix\Iblock\Component\Tools::process404(
       'Не найден', //Сообщение
       true, // Нужно ли определять 404-ю константу
       true, // Устанавливать ли статус
       true, // Показывать ли 404-ю страницу
       false // Ссылка на отличную от стандартной 404-ю
	);
}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>