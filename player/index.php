<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"player-and-team", 
	array(
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
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
		"CATEGORY_CODE" => "CATEGORY",
		"CATEGORY_IBLOCK" => array(
			0 => "2",
		),
		"CATEGORY_ITEMS_COUNT" => "5",
		"CATEGORY_THEME_2" => "list",
		"CHECK_DATES" => "Y",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "N",
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_PAGER_SHOW_ALL" => "N",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DISPLAY_AS_RATING" => "rating",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "Y",
		"FILTER_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"FILTER_NAME" => "",
		"FILTER_PROPERTY_CODE" => array(
			0 => "SURNAME",
			1 => "NAME",
			2 => "",
		),
		"FORUM_ID" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "liga",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"MAX_VOTE" => "5",
		"MESSAGES_PER_PAGE" => "10",
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "main",
		"PAGER_TITLE" => "",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"PREVIEW_TRUNCATE_LEN" => "",
		"REVIEW_AJAX_POST" => "Y",
		"SEF_MODE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "Y",
		"SHOW_LINK_TO_FORUM" => "Y",
		"SORT_BY1" => "NAME",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"URL_TEMPLATES_READ" => "",
		"USE_CAPTCHA" => "Y",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_RATING" => "N",
		"USE_REVIEW" => "N",
		"USE_RSS" => "N",
		"USE_SEARCH" => "N",
		"USE_SHARE" => "N",
		"VOTE_NAMES" => array(
			0 => "1",
			1 => "2",
			2 => "3",
			3 => "4",
			4 => "5",
			5 => "",
		),
		"COMPONENT_TEMPLATE" => "player-and-team",
		"TEMPLATE_THEME" => "blue",
		"MEDIA_PROPERTY" => "",
		"SLIDER_PROPERTY" => "",
		"SEF_FOLDER" => "/player/",
		"FILE_404" => "",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "",
			"detail" => "#ELEMENT_ID#/",
		)
	),
	false
);?>

<?// вызов компонента расчета 
if ($_GET["TOURNAMENT"]) {
	$APPLICATION->IncludeComponent("liga:super.component", "player_item", Array(
			"IBLOCK_ID" => "3", // инфоблок, по которому формировать расчет (инфоблок матчей)
			"PLAYER" => substr(dirname($_SERVER["REQUEST_URI"]), 8), // вырезаем ID 
			"SECTION_ID" => $_GET["TOURNAMENT"],
			"DIR" => dirname($_SERVER["REQUEST_URI"]),
			"CACHE_TIME" => "3600",	// Время кеширования (сек.)
			"CACHE_TYPE" => "A",	// Тип кеширования
		),
		false
	);
}
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>