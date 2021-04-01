<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");


if($_GET['CODE'] && $_GET['active']) {
	$APPLICATION->SetTitle("Удаление");
}
elseif($_GET['CODE']){
	$APPLICATION->SetTitle("Изменение");
}
else{
	$APPLICATION->SetTitle("Добавление");
}

$APPLICATION->AddChainItem($APPLICATION->GetTitle() );

?>


<?$APPLICATION->IncludeComponent(
	"liga:iblock.element.add.form", 
	"personal-calendar", 
	array(
		"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "Дата и время",
		"CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
		"CUSTOM_TITLE_DETAIL_PICTURE" => "",
		"CUSTOM_TITLE_DETAIL_TEXT" => "",
		"CUSTOM_TITLE_IBLOCK_SECTION" => "",
		"CUSTOM_TITLE_NAME" => "",
		"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
		"CUSTOM_TITLE_PREVIEW_TEXT" => "",
		"CUSTOM_TITLE_TAGS" => "",
		"DEFAULT_INPUT_SIZE" => "30",
		"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
		"ELEMENT_ASSOC" => "CREATED_BY",
		"GROUPS" => array(
			0 => "1",
			1 => "7",
		),
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "liga",
		"LEVEL_LAST" => "Y",
		"LIST_URL" => "/personal/tournament/?SECTION_ID={$_GET['SECTION_ID']}",
		"MAX_FILE_SIZE" => "0",
		"MAX_LEVELS" => "100000",
		"MAX_USER_ENTRIES" => "100000",
		"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
		"PROPERTY_CODES" => array(
			0 => "18",
			1 => "NAME",
			2 => "DATE_ACTIVE_FROM",
			3 => "IBLOCK_SECTION",
			4 => "4",
			5 => "5",
		),
		"PROPERTY_CODES_REQUIRED" => array(
			0 => "18",
			1 => "DATE_ACTIVE_FROM",
			2 => "18",
			3 => "4",
			4 => "5",
		),
		"RESIZE_IMAGES" => "N",
		"SEF_FOLDER" => "/personal/tournament/",
		"SEF_MODE" => "Y",
		"STATUS" => "ANY",
		"STATUS_NEW" => "N",
		"USER_MESSAGE_ADD" => "Добавлено",
		"USER_MESSAGE_EDIT" => "Сохранено",
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => "personal-calendar"
	),
	false
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>