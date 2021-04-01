<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
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
}?>

<?
 $APPLICATION->AddChainItem("Календарь", "/tournament/calendar/?SECTION_ID=".$_GET["SECTION_ID"]);
?>


<?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"calendar", 
	array(
		"ADD_ELEMENT_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y H:i",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_DISPLAY_TOP_PAGER" => "Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "DATE_ACTIVE_FROM",
			1 => "ACTIVE_FROM",
			2 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "STAGE",
			1 => "TEAM_1",
			2 => "TEAM_2",
			3 => "STRUCTURE_TEAM_1",
			4 => "STRUCTURE_TEAM_2",
			5 => "GOALS_TEAM_1",
			6 => "GOALS_TEAM_2",
			7 => "YELLOW_CARDS_TEAM_1",
			8 => "YELLOW_CARDS_TEAM_2",
			9 => "TWO_YELLOW_CARDS_TEAM_1",
			10 => "TWO_YELLOW_CARDS_TEAM_2",
			11 => "RED_CARDS_TEAM_1",
			12 => "RED_CARDS_TEAM_2",
			13 => "AUTO_GOALS_TEAM_1",
			14 => "AUTO_GOALS_TEAM_2",
			15 => "PENALTY_TEAM_1",
			16 => "PENALTY_TEAM_2",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "Y",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"FILTER_PROPERTY_CODE" => array(
			0 => "STAGE",
			1 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "3",
		"IBLOCK_TYPE" => "liga",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y H:i",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "18",
			2 => "4",
			3 => "5",
			4 => "6",
			5 => "7",
			6 => "",
		),
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "100",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "main",
		"PAGER_TITLE" => "Игры",
		"PREVIEW_TRUNCATE_LEN" => "",
		"SEF_FOLDER" => "",
		"SEF_MODE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "Y",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"USE_SHARE" => "N",
		"COMPONENT_TEMPLATE" => "calendar",
		"TEMPLATE_THEME" => "blue",
		"MEDIA_PROPERTY" => "",
		"SLIDER_PROPERTY" => "",
		"FILE_404" => "",
		"VARIABLE_ALIASES" => array(
			"SECTION_ID" => "",
			"ELEMENT_ID" => "ID",
		)
	),
	false
);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>