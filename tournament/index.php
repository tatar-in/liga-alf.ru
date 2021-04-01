<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Турниры");
?>


	<h3><?$APPLICATION->ShowTitle();?></h3>

	<?
	$APPLICATION->IncludeComponent("liga:super.component", 
		"tournament_list", 
		Array(
			"IBLOCK_ID" => "3",
			"CACHE_TIME" => "3600",	// Время кеширования (сек.)
			"CACHE_TYPE" => "A",	// Тип кеширования
		),
		false
	);
	?>





	


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>