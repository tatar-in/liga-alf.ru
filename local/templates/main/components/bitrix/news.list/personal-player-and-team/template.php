<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

//echo '<pre>';print_r($arResult);echo '</pre>';

?>

<h3><?=$arResult['NAME'];?></h3>
<a class="btn btn-primary my-3" href="change.php?edit=Y">Добавить</a>

	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>

<div class="table-responsive">
	<table class="table table-hover table-sm ">
  		<tbody>
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<tr id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<td class="align-middle">
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
									<img
										class="rounded float-left mr-1"
										src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
										<?/*width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
										height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"*/?>										
										alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
										style="max-height: 48px;max-width: 48px;"
										/>
								</a>
							<?else:?>
									<img
										class="rounded float-left mr-1"
										src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
										<?/*width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
										height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"*/?>
										alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
										style="max-height: 48px;max-width: 48px;"
										/>
							<?endif;?>
						<?endif?>
						<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
							<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
						<?endif?>
						<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
							<?/*if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a>
							<?else:*/?>
								<?echo $arItem["NAME"]?>
							<?/*endif;*/?>
						<?endif;?>
						<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<?echo $arItem["PREVIEW_TEXT"];?>
						<?endif;?>
						<?/*if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
							<div style="clear:both"></div>
						<?endif*/?>
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
					</td>
					<td>
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="badge badge-pill badge-warning">Редактировать</a> 
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>&active=N" class="badge badge-pill badge-danger">Удалить</a>
						
						
					</td>
				</tr>
			<?endforeach;?>
		</tbody>
	</table>
</div>

	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>