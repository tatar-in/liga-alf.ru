<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<div class="bg-light shadow px-3 py-3 mb-5">
	<div class="row">

		<div class="col-4" style="max-width: 180px;">
			<? $file = CFile::ResizeImageGet($arResult["SECTIONS"][$arResult["PARENTS"]["0"]["ID"]]["PICTURE"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true); ?>
			<?if(is_array($file)):?>
				<img src="<?=$file["src"]?>" class="w-100" />
			<?endif?>
		</div>
		
		<div class="col-8">
			<h3><?=$arResult["SECTIONS"][$arResult["PARENTS"]["0"]["ID"]]["NAME"]?></h3>
			
			<?if($arResult["SECTIONS"][$arResult["PARENTS"]["0"]["ID"]]["UF_STATISTICS"] != 1){?>
				<?$b=true;
				$max_level=0;
				foreach ($arResult["SECTIONS"] as $value) {
					if($max_level<$value["DEPTH_LEVEL"]) $max_level=$value["DEPTH_LEVEL"];
				}
				$level=1;
				$parent[]=$arResult["PARENTS"]["0"]["ID"];
				while($b){?>
					<select class="custom-select custom-select-sm" style="max-width: 180px;" onchange="window.location.href = this.options[this.selectedIndex].value">
						<option value="<?=$arParams["DIR"]."/?TOURNAMENT=".$arResult["PARENTS"][$level-1]["ID"]?>">Выберите...</option>
						<?$par=array();
						foreach ($arResult["SECTIONS"] as $key => $value) {
							if(in_array($value["IBLOCK_SECTION_ID"], $parent)){
								if($value["UF_STATISTICS"]==1) $b=false;
								$par[]=$value["ID"];?>
								<option value="<?=$arParams["DIR"].$value["SECTION_PAGE_URL"]?>" <?if($key==$arResult["PARENTS"][$level]["ID"]) echo "selected";?> >
									<?=$value["NAME"]?>
								</option>
							<?}

						}?>
					</select>
					<?$parent=$par;
					$level++;
					if($level>=$max_level) $b=false;
				}
			}?>

		</div>
	</div>
</div>

<nav class="nav nav-pills flex-column flex-sm-row w-100 my-5 ">
	<a id="calendar" class="flex-sm-fill text-sm-center nav-link border border-primary mx-1 <?if($arParams["DIR"]=="/tournament/calendar") echo 'active';?>" href="/tournament/calendar/?TOURNAMENT=<?=$arParams["SECTION_ID"]?>">Календарь</a>
	<a id="table" class="flex-sm-fill text-sm-center nav-link border border-primary mx-1 <?if($arParams["DIR"]=="/tournament/table") echo 'active';?>" href="/tournament/table/?TOURNAMENT=<?=$arParams["SECTION_ID"]?>">Таблица</a>
	<a id="bombardir" class="flex-sm-fill text-sm-center nav-link border border-primary mx-1 <?if($arParams["DIR"]=="/tournament/bombardir") echo 'active';?>" href="/tournament/bombardir/?TOURNAMENT=<?=$arParams["SECTION_ID"]?>">Бомбардиры</a>
	<a id="warning" class="flex-sm-fill text-sm-center nav-link border border-primary mx-1 <?if($arParams["DIR"]=="/tournament/warning") echo 'active';?>" href="/tournament/warning/?TOURNAMENT=<?=$arParams["SECTION_ID"]?>">Карточки</a>
	<a id="autogoals" class="flex-sm-fill text-sm-center nav-link border border-primary mx-1 <?if($arParams["DIR"]=="/tournament/autogoals") echo 'active';?>" href="/tournament/autogoals/?TOURNAMENT=<?=$arParams["SECTION_ID"]?>">Автоголы</a>
	<a id="statistic" class="flex-sm-fill text-sm-center nav-link border border-primary mx-1 <?if($arParams["DIR"]=="/tournament/statistic") echo 'active';?>" href="/tournament/statistic/?TOURNAMENT=<?=$arParams["SECTION_ID"]?>">Статистика</a>
</nav>


