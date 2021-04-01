<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("404 Страница не найдена");
?>

<div class="text-center my-5 mx-auto" style="min-height: 300px;">
	<h3>Страница не найдена</h3>

	<p>
		Возможно, она была удалена или даже никогда не существовала.<br>
		Проверьте правильность написания адреса или перейдите <a href="/">на главную страницу</a>.
	</p>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>