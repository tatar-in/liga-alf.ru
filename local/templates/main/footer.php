<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

</div>
<div class="mt-5 bg-dark text-white">
	<div class="container py-3">
		<div class="row">
			<div class="col-md-4" style="min-width:130px;">
				<div class="text-center float-lg-left">
				    <a class="text-reset text-decoration-none" href="/">
						<img src="<?=SITE_TEMPLATE_PATH?>/image/logo-100.png" class="d-inline-block align-middle" width="100" height="100">
						<span class="text-white h3 font-weight-bolder align-middle text-nowrap">Liga ALF</span>
					</a>
				</div>
			</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu", 
				"footer", 
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
		<div class="row">
			<div class="col-md-10">

			</div>
			<div class="col-md-2">
				<span style="font-size: 65%;">Created by <a class="text-reset" href="https://vk.com/id22455842" target="_blank">Rinat</a></span>
			</div>

		</div>
	</div>
</div>





	<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="<?=SITE_TEMPLATE_PATH?>/js/popper.min.js"></script>
	<script src="<?=SITE_TEMPLATE_PATH?>/js/bootstrap.min.js"></script>


	<!-- Yandex.Metrika counter -->
	<script type="text/javascript" >
	   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
	   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
	   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

	   ym(69610825, "init", {
	        clickmap:true,
	        trackLinks:true,
	        accurateTrackBounce:true,
	        webvisor:true
	   });
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/69610825" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->

</body>
</html>