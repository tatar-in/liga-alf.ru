<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
// echo "<pre>";print_r($arResult);echo "</pre>";
?>

	<?if (!empty($arResult)):?>
		<div class="col-md-6">
			<?foreach($arResult as $arItem):?>
				<?if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;?>
				<?if ($arItem["LINK"] == "/tournament/") :?>
					<h6 class="pt-3 font-weight-bold text-uppercase"><a class="text-reset" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></h6>
					<ul class="list-unstyled two-columns">
						<?foreach ($arItem["SUBITEMS"] as $value) :?>
							<li class="pt-2"><a class="text-reset" href="<?=$value["LINK"]?>"><?=$value["TEXT"]?></a></li>
						<?endforeach;?>
					</ul>
				<?endif;?>
			<?endforeach?>
		</div>
		<div class="col-md-2">
			<h6 class="pt-3 font-weight-bold text-uppercase">О лиге</h6>
			<ul class="list-unstyled">
				<?foreach($arResult as $arItem):?>
					<?if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;?>
					<?if ($arItem["LINK"] != "/tournament/") :?>
						<li class="pt-2"><a class="text-reset" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
					<?endif;?>
				<?endforeach?>
			</ul>
		</div>
	<?endif?>
