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


// echo '<pre>';print_r($arResult);echo '</pre>';

?>


<div class="row">
	<div class="col text-center">
		<?=$arResult["DISPLAY_ACTIVE_FROM"];?>
	</div>
</div>
<div class="row">
	<div class="col text-center font-weight-bold">
		<?=$arResult["DISPLAY_PROPERTIES"]["STAGE"]["VALUE"];?>
	</div>
</div>
<div class="row align-items-center">
	<div class="col-4 text-center">
		<a href="<?=$arResult["DISPLAY_PROPERTIES"]["TEAM_1"]["LINK_ELEMENT_VALUE"][$arResult["PROPERTY_4"]]["DETAIL_PAGE_URL"]."?TOURNAMENT=".$_GET["TOURNAMENT"];?>" class="text-reset">
			<div class="mx-auto" style="max-width: 100px;">
				<?$file = CFile::ResizeImageGet($arResult["DISPLAY_PROPERTIES"]["TEAM_1"]["LINK_ELEMENT_VALUE"][$arResult["PROPERTY_4"]]["PREVIEW_PICTURE"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_EXACT, true);?>
				<img src="<?=$file["src"]?>" class="w-100">
			</div>
			<h5 class="text-truncate">
				<?=$arResult["DISPLAY_PROPERTIES"]["TEAM_1"]["LINK_ELEMENT_VALUE"][$arResult["PROPERTY_4"]]["NAME"];?>
			</h5>
		</a>
	</div>
	<div class="col-4 text-center align-middle">
		<?if(count($arResult["DISPLAY_PROPERTIES"]["STRUCTURE_TEAM_1"]["VALUE"])>0 && count($arResult["DISPLAY_PROPERTIES"]["STRUCTURE_TEAM_2"]["VALUE"])>0)
		{
			echo '<span class="h1 font-weight-bold align-middle">';
			echo (is_array($arResult["DISPLAY_PROPERTIES"]["GOALS_TEAM_1"]["VALUE"]) ? count($arResult["DISPLAY_PROPERTIES"]["GOALS_TEAM_1"]["VALUE"]) : 0) + (is_array($arResult["DISPLAY_PROPERTIES"]["AUTO_GOALS_TEAM_2"]["VALUE"]) ? count($arResult["DISPLAY_PROPERTIES"]["AUTO_GOALS_TEAM_2"]["VALUE"]) : 0);
			echo ":";
			echo (is_array($arResult["DISPLAY_PROPERTIES"]["GOALS_TEAM_2"]["VALUE"]) ? count($arResult["DISPLAY_PROPERTIES"]["GOALS_TEAM_2"]["VALUE"]) : 0) + (is_array($arResult["DISPLAY_PROPERTIES"]["AUTO_GOALS_TEAM_1"]["VALUE"]) ? count($arResult["DISPLAY_PROPERTIES"]["AUTO_GOALS_TEAM_1"]["VALUE"]) : 0);
			echo '</span>';
			if(is_array($arResult["PROPERTIES"]["PENALTY_TEAM_1"]["VALUE"]) || is_array($arResult["PROPERTIES"]["PENALTY_TEAM_2"]["VALUE"])){
				echo '<br><span class="h4 text-muted">(';
				echo (is_array($arResult["DISPLAY_PROPERTIES"]["PENALTY_TEAM_1"]["VALUE"]) ? count($arResult["DISPLAY_PROPERTIES"]["PENALTY_TEAM_1"]["VALUE"]) : 0);
				echo ":";
				echo (is_array($arResult["DISPLAY_PROPERTIES"]["PENALTY_TEAM_2"]["VALUE"]) ? count($arResult["DISPLAY_PROPERTIES"]["PENALTY_TEAM_2"]["VALUE"]) : 0);
				echo ")</span>";
			}
		}
		else
		{
			echo '<span class="h1 font-weight-bold align-middle">';
			echo "vs";
			echo '</span>';
		}
		?>
	</div>
	<div class="col-4 text-center">
		<a href="<?=$arResult["DISPLAY_PROPERTIES"]["TEAM_2"]["LINK_ELEMENT_VALUE"][$arResult["PROPERTY_5"]]["DETAIL_PAGE_URL"]."?TOURNAMENT=".$_GET["TOURNAMENT"];?>" class="text-reset">
			<div class="mx-auto" style="max-width: 100px;">
				<?$file = CFile::ResizeImageGet($arResult["DISPLAY_PROPERTIES"]["TEAM_2"]["LINK_ELEMENT_VALUE"][$arResult["PROPERTY_5"]]["PREVIEW_PICTURE"], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_EXACT, true);?>
				<img src="<?=$file["src"]?>" class="w-100">
			</div>
			<h5 class="text-truncate">
					<?=$arResult["DISPLAY_PROPERTIES"]["TEAM_2"]["LINK_ELEMENT_VALUE"][$arResult["PROPERTY_5"]]["NAME"];?>
			</h5>
		</a>
	</div>
</div>
<div class="row mt-5">
	<?for ($i=1; $i<=2; $i++):?>
		<div class="col-md-6">
			<div class="table-responsive">
				<?if(count($arResult["DISPLAY_PROPERTIES"]["STRUCTURE_TEAM_1"]["VALUE"])>0 && count($arResult["DISPLAY_PROPERTIES"]["STRUCTURE_TEAM_2"]["VALUE"])>0):?>
					<h5 class="d-block d-md-none"><?=$arResult["DISPLAY_PROPERTIES"]["TEAM_".$i]["LINK_ELEMENT_VALUE"][$arResult["DISPLAY_PROPERTIES"]["TEAM_".$i]["VALUE"]]["NAME"]?></h5>
				<?endif;?>
				<table class="table table-hover table-sm">
					<tbody>
						<?foreach ($arResult["DISPLAY_PROPERTIES"]["STRUCTURE_TEAM_".$i]["LINK_ELEMENT_VALUE"] as $key => $value) {?>
							<tr>
								<td class="text-nowrap text-truncate">
									<a href="<?=$value["DETAIL_PAGE_URL"]."?TOURNAMENT=".$_GET["TOURNAMENT"];?>" class=text-reset>
										<?if ($value["PREVIEW_PICTURE"]):?>
											<?$file = CFile::ResizeImageGet($value["PREVIEW_PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
											<img class="rounded float-left mr-2" src="<?=$file["src"]?>" />
										<?endif;?>
										<?=$value["NAME"];?>
									</a>
								</td>
								<?if($arResult["DISPLAY_PROPERTIES"]["GOALS_TEAM_".$i]["VALUE"]):?>
									<td class="text-nowrap">
										<?$count=0;
										foreach ($arResult["DISPLAY_PROPERTIES"]["GOALS_TEAM_".$i]["VALUE"] as $k => $v) {
											if($v == $key) $count++;
										}?>
										<?if ($count>0) :?>
											<img src="<?=SITE_TEMPLATE_PATH?>/image/goal-30x30.png" height="20" class="rounded">
											<?if ($count>1) {?>
												<small>x</small> <?=$count;?>
											<?}?>
										<?endif;?>
									</td>
								<?endif;?>
								<?if($arResult["DISPLAY_PROPERTIES"]["YELLOW_CARDS_TEAM_".$i]["VALUE"]):?>
									<td>
										<?if($arResult["DISPLAY_PROPERTIES"]["YELLOW_CARDS_TEAM_".$i]["LINK_ELEMENT_VALUE"][$key]):?>
											<img src="<?=SITE_TEMPLATE_PATH?>/image/yellowcard-15x30.png" height="20" class="rounded">
										<?endif;?>
									</td>
								<?endif;?>
								<?if($arResult["DISPLAY_PROPERTIES"]["TWO_YELLOW_CARDS_TEAM_".$i]["VALUE"]):?>
									<td>
										<?if($arResult["DISPLAY_PROPERTIES"]["TWO_YELLOW_CARDS_TEAM_".$i]["LINK_ELEMENT_VALUE"][$key]):?>
											<img src="<?=SITE_TEMPLATE_PATH?>/image/2yellowcards-25x30.png" height="20" class="rounded">
										<?endif;?>
									</td>
								<?endif;?>
								<?if($arResult["DISPLAY_PROPERTIES"]["RED_CARDS_TEAM_".$i]["VALUE"]):?>
									<td>
										<?if($arResult["DISPLAY_PROPERTIES"]["RED_CARDS_TEAM_".$i]["LINK_ELEMENT_VALUE"][$key]):?>
											<img src="<?=SITE_TEMPLATE_PATH?>/image/redcard-15x30.png" height="20" class="rounded">
										<?endif;?>
									</td>
								<?endif;?>
								<?if($arResult["DISPLAY_PROPERTIES"]["AUTO_GOALS_TEAM_".$i]["VALUE"]):?>
									<td class="text-nowrap">
										<?$count=0;
										foreach ($arResult["DISPLAY_PROPERTIES"]["AUTO_GOALS_TEAM_".$i]["VALUE"] as $k => $v) {
											if($v == $key) $count++;
										}?>
										<?if ($count>0) :?>
											<span class="text-danger">A</span>
											<?if ($count>1) {?>
												<small>x</small> <?=$count;?>
											<?}?>
										<?endif;?>
									</td>
								<?endif;?>
								<?if($arResult["DISPLAY_PROPERTIES"]["PENALTY_TEAM_".$i]["VALUE"]):?>
									<td class="text-nowrap">
										<?$count=0;
										foreach ($arResult["DISPLAY_PROPERTIES"]["PENALTY_TEAM_".$i]["VALUE"] as $k => $v) {
											if($v == $key) $count++;
										}?>
										<?if ($count>0) :?>
											<span class="text-danger">П</span>
											<?if ($count>1) {?>
												<small>x</small> <?=$count;?>
											<?}?>
										<?endif;?>
									</td>
								<?endif;?>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
		</div>
	<?endfor;?>
</div>


<?/*
<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<img
			class="detail_picture"
			border="0"
			src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
			width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
			height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
			alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
			title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
			/>
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3><?=$arResult["NAME"]?></h3>
	<?endif;?>
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif($arResult["DETAIL_TEXT"] <> ''):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):
		if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
		{
			?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?
			if (!empty($value) && is_array($value))
			{
				?><img border="0" src="<?=$value["SRC"]?>" width="<?=$value["WIDTH"]?>" height="<?=$value["HEIGHT"]?>"><?
			}
		}
		else
		{
			?><?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?><?
		}
		?><br />
	<?endforeach;
	foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;
	if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
	{
		?>
		<div class="news-detail-share">
			<noindex>
			<?
			$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
					"HANDLERS" => $arParams["SHARE_HANDLERS"],
					"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
					"PAGE_TITLE" => $arResult["~NAME"],
					"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
					"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
					"HIDE" => $arParams["SHARE_HIDE"],
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
			?>
			</noindex>
		</div>
		<?
	}
	?>
</div>
*/?>