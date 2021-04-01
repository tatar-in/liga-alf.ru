<?
// echo "<pre>";print_r($arResult);echo "</pre>";
?>

<h3><?=$arResult["NAME"]?></h3>


<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<?foreach($arResult["ITEMS"] as $key => $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>

	<div class="row" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="col">
			<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
					<?$file = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
						<img
							class="rounded float-left d-none d-md-block mr-4"
							src="<?=$file["src"]?>"
							/>
					</a>
				<?else:?>
					<img
						class="rounded float-left d-none d-md-block mr-4"
						src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
						/>
				<?endif;?>
			<?endif?>

			<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
				<div class="h5" >
					<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
						<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
							<?echo $arItem["NAME"]?>
						</a>
					<?else:?>
						<?echo $arItem["NAME"]?>
					<?endif;?>
				</div>
			<?endif;?>
			<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
				<div class="text-muted mb-2">
					<?echo $arItem["DISPLAY_ACTIVE_FROM"]?>
				</div>
			<?endif?>
			<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
				<?echo $arItem["PREVIEW_TEXT"];?>
			<?endif;?>
			<?if($arItem["DETAIL_TEXT"]):?>
				<div class="">
					<?$str=substr(strip_tags($arItem["DETAIL_TEXT"]),0,100)."...";?>
					<?echo $str;?>
				</div>
			<?endif;?>
			<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<div style="clear:both"></div>
			<?endif?>
			<?/*
			<?foreach($arItem["FIELDS"] as $code=>$value):?>
				<small>
				<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
				</small><br />
			<?endforeach;?>
			<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
				<small>
				<?=$arProperty["NAME"]?>:&nbsp;
				<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
					<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
				<?else:?>
					<?=$arProperty["DISPLAY_VALUE"];?>
				<?endif?>
				</small><br />
			<?endforeach;?>
			*/?>
		</div>
	</div>
	<hr>
	<?if($key>=2) break;?>
<?endforeach;?>
<?if(count($arResult["ITEMS"])>3):?>
	<a href="/news/">Все новости</a>
	<br>
<?endif;?>


<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>