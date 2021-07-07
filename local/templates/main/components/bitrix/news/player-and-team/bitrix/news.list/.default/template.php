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
?>

<h3><?=$arResult["NAME"]?></h3>

<form class="form-inline pt-3 my-2" method="GET" action="<?=explode("?", $_SERVER['REQUEST_URI'])[0]?>">
	<input class="form-control mr-sm-2 mb-2" name="search" placeholder="Поиск" type="search" value="<?=$_GET['search']?>">
	<button class="btn btn-outline-secondary mr-2 mb-2" type="submit">Найти</button>
	<?if(!empty($_GET['search'])){?><a class="mb-2" href="<?=explode("?", $_SERVER['REQUEST_URI'])[0]?>">Сбросить</a><?}?>
</form>

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<div class="table-responsive">
	<table class="table table-sm table-hover">
		<thead>
		    <tr>
		      <th scope="col"><?=$arResult['NAME']?></th>
		    </tr>
  		</thead>
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
								<?$file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
									<img
										class="rounded float-left mr-2"
										src="<?=$file["src"]?>"
										/>
								</a>
							<?else:?>
									<img
										class="rounded float-left mr-2"
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
							<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
								<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><span class="align-middle"><?echo $arItem["NAME"]?></span></a>
							<?else:?>
								<span class="align-middle"><?echo $arItem["NAME"]?></span>
							<?endif;?>
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
				</tr>
			<?endforeach;?>
		</tbody>
	</table>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>