<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?>

<?if (!$USER->IsAuthorized()):?>
<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "template1", Array(
	"FORGOT_PASSWORD_URL" => "/personal/crazy.php",	// Страница забытого пароля
		"PROFILE_URL" => "/personal/",	// Страница профиля
		"REGISTER_URL" => "",	// Страница регистрации
		"SHOW_ERRORS" => "Y",	// Показывать ошибки
	),
	false
);?>
<?else:?>

<h3 class="text-center">Персональный раздел</h3>
<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"personal-menu", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"COMPONENT_TEMPLATE" => "personal-menu"
	),
	false
);?>
<?endif;?>





<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>