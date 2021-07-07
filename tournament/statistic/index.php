<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Статистика");
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

<?
 $APPLICATION->AddChainItem("Статистика", "statistic/");
?>


<style>
	.nav-tabs .mynav-link {
		border:3px solid transparent;
	}
	.nav-tabs .mynav-link:focus,
	.nav-tabs .mynav-link:hover {
		border-color:#fff #fff #0056b3;
	}
	.nav-tabs .nav-item.show .mynav-link,
	.nav-tabs .mynav-link.active {
		border-color:#fff #fff #495057;
	}
</style>

<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
	<li class="nav-item" role="presentation">
		<a class="nav-link mynav-link active" id="bombardir-tab" data-toggle="tab" href="#bombardir" role="tab" aria-controls="bombardir" aria-selected="true">
			Бомбардиры
		</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link mynav-link" id="autogoals-tab" data-toggle="tab" href="#autogoals" role="tab" aria-controls="autogoals" aria-selected="false">
			Автоголы
		</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link mynav-link" id="warning-tab" data-toggle="tab" href="#warning" role="tab" aria-controls="warning" aria-selected="false">
			Нарушения
		</a>
	</li>
</ul>
<div class="tab-content" id="myTabContent">
	<div class="tab-pane fade show active" id="bombardir" role="tabpanel" aria-labelledby="bombardir-tab">
		<?$APPLICATION->IncludeComponent(
			"liga:super.component", "stat_bombardir", 
			Array(
				"TYPE" => "bombardir",
				"SECTION_ID" => $_GET["TOURNAMENT"],
				"CACHE_TIME" => "3600",	// Время кеширования (сек.)
				"CACHE_TYPE" => "A",	// Тип кеширования
			),
			false
		);?>
	</div>
	<div class="tab-pane fade" id="autogoals" role="tabpanel" aria-labelledby="autogoals-tab">
		<?$APPLICATION->IncludeComponent(
			"liga:super.component", "stat_own", 
			Array(
				"TYPE" => "autogoals",
				"SECTION_ID" => $_GET["TOURNAMENT"],
				"CACHE_TIME" => "3600",	// Время кеширования (сек.)
				"CACHE_TYPE" => "A",	// Тип кеширования
			),
			false
		);?>
	</div>
	<div class="tab-pane fade" id="warning" role="tabpanel" aria-labelledby="warning-tab">
		<?$APPLICATION->IncludeComponent(
			"liga:super.component", "stat_warning", 
			Array(
				"TYPE" => "warning",
				"SECTION_ID" => $_GET["TOURNAMENT"],
				"CACHE_TIME" => "3600",	// Время кеширования (сек.)
				"CACHE_TYPE" => "A",	// Тип кеширования
			),
			false
		);?>
	</div>
</div>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>