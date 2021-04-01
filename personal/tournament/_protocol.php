<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Протокол");


$APPLICATION->AddChainItem($APPLICATION->GetTitle() );

?>


<?$APPLICATION->IncludeComponent(
	"liga:iblock.element.add.form", 
	"personal-protocol", 
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
			0 => "6",
			1 => "7",
			2 => "8",
			3 => "9",
			4 => "10",
			5 => "11",
			6 => "12",
			7 => "13",
			8 => "16",
			9 => "17",

		),
		"PROPERTY_CODES_REQUIRED" => array(
			0 => "6",
			1 => "7",
		),
		"RESIZE_IMAGES" => "N",
		"SEF_FOLDER" => "/personal/tournament/",
		"SEF_MODE" => "Y",
		"STATUS" => "ANY",
		"STATUS_NEW" => "N",
		"USER_MESSAGE_ADD" => "Добавлено",
		"USER_MESSAGE_EDIT" => "Сохранено",
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => "personal-protocol"
	),
	false
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>