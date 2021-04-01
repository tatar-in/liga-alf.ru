<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
// echo '<pre>'; print_r($arResult); echo '</pre>';
?>

<?if(count($arResult["SECTIONS"])>0):?>
	<?foreach ($arResult["SECTIONS"] as $key => $value) {?>
		<?if($key == "ACTIVE"){?>
			<h6 class="text-muted">Действующие</h6>
		<?}
		else{?>
			<h6 class="text-muted">Завершенные</h6>
		<?}?>
		<div class="row photo-block">
			<?foreach($value as $arItem):?>
				<? $file = CFile::ResizeImageGet($arItem["PICTURE"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true); ?>
				<div class="col photo-block-list">
		    		<a href="<?="/tournament/calendar".$arItem["SECTION_PAGE_URL"]?>" class="stretched-link">
			    		<?if(is_array($file)):?>
							<img src="<?=$file["src"]?>" />
						<?endif?>
						<p class="text-break w-100 px-1">
							<?=$arItem["NAME"]?>
						</p>
					</a>
				</div>

			<?endforeach;?>
		</div>
	<?}?>
	
<?endif?>



