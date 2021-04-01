<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

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
	"personal-player-and-team", 
	array(
		"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
		"CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
		"CUSTOM_TITLE_DETAIL_PICTURE" => "Картинка",
		"CUSTOM_TITLE_DETAIL_TEXT" => "Описание",
		"CUSTOM_TITLE_IBLOCK_SECTION" => "",
		"CUSTOM_TITLE_NAME" => "",
		"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
		"CUSTOM_TITLE_PREVIEW_TEXT" => "",
		"CUSTOM_TITLE_TAGS" => "",
		"DEFAULT_INPUT_SIZE" => "30",
		"DETAIL_TEXT_USE_HTML_EDITOR" => "Y",
		"ELEMENT_ASSOC" => "CREATED_BY",
		"GROUPS" => array(
			0 => "1",
			1 => "8",
		),
		"IBLOCK_ID" => "5",
		"IBLOCK_TYPE" => "liga",
		"LEVEL_LAST" => "Y",
		"LIST_URL" => "/personal/news/",
		"MAX_FILE_SIZE" => "0",
		"MAX_LEVELS" => "100000",
		"MAX_USER_ENTRIES" => "100000",
		"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
		"PROPERTY_CODES" => array(
			0 => "NAME",
			1 => "DETAIL_TEXT",
			2 => "DETAIL_PICTURE",
		),
		"PROPERTY_CODES_REQUIRED" => array(
			0 => "NAME",
			1 => "DETAIL_TEXT",
		),
		"RESIZE_IMAGES" => "Y",
		"SEF_MODE" => "Y",
		"STATUS" => "ANY",
		"STATUS_NEW" => "N",
		"USER_MESSAGE_ADD" => "Добавлено",
		"USER_MESSAGE_EDIT" => "Сохранено",
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => "personal-player-and-team",
		"SEF_FOLDER" => "/personal/news/"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>