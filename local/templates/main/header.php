<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link type="image/x-icon" rel="shortcut icon" href="<?=SITE_TEMPLATE_PATH?>/image/favicon.ico">
	<link type="image/png" sizes="16x16" rel="icon" href="<?=SITE_TEMPLATE_PATH?>/image/favicon-16x16.png">
	<link type="image/png" sizes="32x32" rel="icon" href="<?=SITE_TEMPLATE_PATH?>/image/favicon-32x32.png">
	<link type="image/png" sizes="96x96" rel="icon" href="<?=SITE_TEMPLATE_PATH?>/image/favicon-96x96.png">
	<link type="image/png" sizes="120x120" rel="icon" href="<?=SITE_TEMPLATE_PATH?>/image/favicon-120x120.png">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous" >
	<link href="<?=SITE_TEMPLATE_PATH?>/css/select2.min.css" rel="stylesheet" />
	
	<script src="<?=SITE_TEMPLATE_PATH?>/js/jquery-3.5.1.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/select2.min.js"></script>
	
	<?$APPLICATION->ShowHead();?>
	<title><?$APPLICATION->ShowTitle()?></title>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#FFFFFF">

<?$APPLICATION->ShowPanel()?>

<div class="bg-dark">
	<div class="container">
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu", 
			"main", 
			array(
				"ALLOW_MULTI_SELECT" => "N",
				"CHILD_MENU_TYPE" => "left",
				"DELAY" => "N",
				"MAX_LEVEL" => "2",
				"MENU_CACHE_GET_VARS" => array(
				),
				"MENU_CACHE_TIME" => "3600",
				"MENU_CACHE_TYPE" => "N",
				"MENU_CACHE_USE_GROUPS" => "Y",
				"ROOT_MENU_TYPE" => "top",
				"USE_EXT" => "Y",
				"COMPONENT_TEMPLATE" => "main",
				"MENU_THEME" => "site"
			),
			false
		);?>
	</div>
</div>


<div class="container">


<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb", 
	"main", 
	array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0",
		"COMPONENT_TEMPLATE" => "main"
	),
	false
);?>




