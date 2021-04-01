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


if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
	<div class="row justify-content-between pt-3">

		<div class="col-auto text-muted ">
			<?=$arResult["NavTitle"]?> 
			<?=$arResult["NavFirstRecordShow"]?> <?=GetMessage("nav_to")?> <?=$arResult["NavLastRecordShow"]?> <?=GetMessage("nav_of")?> <?=$arResult["NavRecordCount"]?>
		</div>
		<div class="col-auto">
			<nav aria-label="Page navigation">
				<ul class="pagination pagination-sm">
					

					<?if($arResult["bDescPageNumbering"] === true):?>	

						<?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
							<?if($arResult["bSavePage"]):?>
								<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=GetMessage("nav_begin")?></a>
								</li>
								<?/*<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
								</li>*/?>
							<?else:?>
								<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_begin")?></a>
								</li>
								<?if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):?>
									<?/*<li class="page-item active">
										<a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a>
									</li>*/?>
								<?else:?>
									<?/*<li class="page-item">
										<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_prev")?></a>
									</li>*/?>
								<?endif?>
							<?endif?>
						<?else:?>
							<li class="page-item disabled">
			      				<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_begin")?></a>
			    			</li>
			    			<?/*<li class="page-item disabled">
			      				<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_prev")?></a>
			    			</li>*/?>
						<?endif?>

						<?while($arResult["nStartPage"] >= $arResult["nEndPage"]):?>
							<?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>

							<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
								<li class="page-item active">
									<span class="page-link"><?=$NavRecordGroupPrint?></span>
								</li>
							<?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
								<li class="page-item active">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
								</li>
							<?else:?>
								<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
								</li>
							<?endif?>

							<?$arResult["nStartPage"]--?>
						<?endwhile?>

						<?if ($arResult["NavPageNomer"] > 1):?>
							<?/*<li class="page-item">
								<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_next")?></a>
							</li>*/?>
							<li class="page-item">
								<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_end")?></a>
							</li>
						<?else:?>
							<?/*<li class="page-item disabled">
								<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_next")?></a>
							</li>*/?>							
							<li class="page-item disabled">
								<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_end")?></a>
							</li>
						<?endif?>

					<?else:?>

						<?if ($arResult["NavPageNomer"] > 1):?>

							<?if($arResult["bSavePage"]):?>
								<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_begin")?></a>
								</li>
								<?/*<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev")?></a>
								</li>*/?>
							<?else:?>
								<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_begin")?></a>
								</li>
								<?if ($arResult["NavPageNomer"] > 2):?>
									<?/*<li class="page-item">
										<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev")?></a>
									</li>*/?>
								<?else:?>
									<?/*<li class="page-item">
										<a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a>
									</li>*/?>
								<?endif?>
								
							<?endif?>

						<?else:?>
							<li class="page-item disabled">
								<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_begin")?></a>
							</li>
							<?/*<li class="page-item disabled">
								<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_prev")?></a>
							</li>*/?>
						<?endif?>

						<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

							<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
								<li class="page-item active">
									<a class="page-link" href="#"><?=$arResult["nStartPage"]?></a>
								</li>
							<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
								<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
								</li>
							<?else:?>
								<li class="page-item">
									<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
								</li>
							<?endif?>
							<?$arResult["nStartPage"]++?>
						<?endwhile?>
						

						<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
							<?/*<li class="page-item">
			      				<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_next")?></a>
			    			</li>*/?>
			    			<li class="page-item">
			      				<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=GetMessage("nav_end")?></a>
			    			</li>
						<?else:?>
							<?/*<li class="page-item disabled">
			      				<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_next")?></a>
			    			</li>*/?>
			    			<li class="page-item disabled">
			      				<a class="page-link" href="#" tabindex="-1"><?=GetMessage("nav_end")?></a>
			    			</li>
						<?endif?>

					<?endif?>

						<?if ($arResult["bShowAll"]):?>
							<noindex>
								<li class="page-item">
									<?if ($arResult["NavShowAll"]):?>
										<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0" rel="nofollow"><?=GetMessage("nav_paged")?></a>
									<?else:?>
										<a class="page-link" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1" rel="nofollow"><?=GetMessage("nav_all")?></a>
									<?endif?>
								</li>
							</noindex>
						<?endif?>
					
				</ul>
			</nav>

		</div>

	</div>
