<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Таблица");
?>

<?if($_GET["SECTION_ID"]){
	$APPLICATION->IncludeComponent("liga:super.component", 
		"tournament_item", 
		Array(
			"IBLOCK_ID" => "3",
			"SECTION_ID" => $_GET["SECTION_ID"],
			"DIR" => dirname($_SERVER["REQUEST_URI"]),
			"CACHE_TIME" => "3600",	// Время кеширования (сек.)
			"CACHE_TYPE" => "A",	// Тип кеширования
		),
		false
	);



 $APPLICATION->AddChainItem("Таблица", "table/");



	$APPLICATION->IncludeComponent("liga:super.component", 
		"table", 
		Array(
		"IBLOCK_ID" => "3",
		"SECTION_ID" => $_GET["SECTION_ID"],
		"CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
	),
	false
);
}?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>