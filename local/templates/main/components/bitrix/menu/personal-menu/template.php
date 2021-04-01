<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<div class="list-group-flush text-center">

<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<a href="<?=$arItem["LINK"]?>" class="list-group-item list-group-item-action active"><?=$arItem["TEXT"]?></a>
	<?else:?>
		<a href="<?=$arItem["LINK"]?>" class="list-group-item list-group-item-action"><?=$arItem["TEXT"]?></a>
	<?endif?>
	
<?endforeach?>

</div>
<?endif?>
