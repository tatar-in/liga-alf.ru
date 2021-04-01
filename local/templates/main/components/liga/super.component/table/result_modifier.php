<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$obCache_trade = new CPHPCache();
$CACHE_ID = 'CACHE_ID'.implode("", $arParams);
if ( $obCache_trade->InitCache($arParams['CACHE_TIME'], $CACHE_ID, '/') )
{
	$arResult = $obCache_trade->GetVars();
}
else
{
	
	$obCache_trade->StartDataCache();
	
	// component text here
	$arResult = array();
	

	CModule::IncludeModule("iblock");

	// Получаем все секции
	$arOrder = array("SORT" => "ASC", "DEPTH_LEVEL" => "ASC", "NAME" => "ASC");
	$arFilter = array('IBLOCK_ID' =>$arParams["IBLOCK_ID"], "GLOBAL_ACTIVE" => "Y", "ACTIVE" => "Y", "ELEMENT_SUBSECTIONS" => "N"); 
	$arSelect = array("ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "NAME", "SORT", "UF_TYPE", "UF_STATISTICS");
	$rsSect = CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect);
	while ($arSect = $rsSect->GetNext())
	{
		$arR[$arSect["ID"]] = $arSect;
	}


	// Выбираем только дочерние секции
	$parent[]=$arParams["SECTION_ID"];
	while(true){
		// Было так, работало, но был неверный порядок категории. Из-за этого сначала шли категории одного уровня вложенности, потом другого (напр, 1 2 2 3 3 3 3)
		// $par=array();
		// foreach ($arR as $value) {
		// 	if(in_array($value["IBLOCK_SECTION_ID"], $parent) && !in_array($value["ID"], $parent)){
		// 		$par[]= $value[ID];
		// 	}
		// }
		// if(!empty($par)){
		// 	$parent=array_merge($parent, $par);
		// }
		// else{
		// 	break;
		// }

		// Переписал чуть-чуть код выше, чтобы в порядке учитывалась и вложенность категорий (напр, 1 2 3 3 2 3 3) 
		$par=array();
		foreach ($arR as $value) {
			if(in_array($value["IBLOCK_SECTION_ID"], $parent) && !in_array($value["ID"], $parent)){
				$par[]= $value[ID];
				$parent=array_merge($parent, $par);
			}
		}
		$parent = array_unique($parent);
		if(empty($par)) break;
	}
	

	// Заполняем секциями $arResult
	foreach ($parent as $value) {
		$arResult["SECTION"][$value]["ID"]=$value;
		$arResult["SECTION"][$value]["NAME"]=$arR[$value]["NAME"];
		$arResult["SECTION"][$value]["IBLOCK_SECTION_ID"]=$arR[$value]["IBLOCK_SECTION_ID"];
		$arResult["SECTION"][$value]["DEPTH_LEVEL"]=$arR[$value]["DEPTH_LEVEL"];
		$arResult["SECTION"][$value]["SORT"]=$arR[$value]["SORT"];
		$arResult["SECTION"][$value]["ELEMENT_CNT"]=$arR[$value]["ELEMENT_CNT"];
		$arResult["SECTION"][$value]["TYPE"]=$arR[$value]["UF_TYPE"];
		$arResult["SECTION"][$value]["STATISTICS"]=$arR[$value]["UF_STATISTICS"];
	}

	// Проходим по элементам (матчам)
	$arOrder = array('ID' => 'ASC');
	$arFilter = array('IBLOCK_ID' =>$arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $arParams["SECTION_ID"], "INCLUDE_SUBSECTIONS" => "Y"); 
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_STAGE",
		"PROPERTY_TEAM_1", "PROPERTY_TEAM_1.NAME", "PROPERTY_TEAM_1.PREVIEW_PICTURE", "PROPERTY_GOALS_TEAM_1", "PROPERTY_AUTO_GOALS_TEAM_1", "PROPERTY_STRUCTURE_TEAM_1", "PROPERTY_TEAM_1.DETAIL_PAGE_URL",
		"PROPERTY_TEAM_2", "PROPERTY_TEAM_2.NAME", "PROPERTY_TEAM_2.PREVIEW_PICTURE", "PROPERTY_GOALS_TEAM_2", "PROPERTY_AUTO_GOALS_TEAM_2", "PROPERTY_STRUCTURE_TEAM_2", "PROPERTY_TEAM_2.DETAIL_PAGE_URL",);
	$rsEl = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	while ($arEl = $rsEl->GetNext())
	{
		// Формируем массив матчей
		$arResult["MATCHES"][$arEl["ID"]]["NAME"] = $arEl["NAME"];
		$arResult["MATCHES"][$arEl["ID"]]["URL"] = $arEl["DETAIL_PAGE_URL"];
		$arResult["MATCHES"][$arEl["ID"]]["STAGE"] = $arEl["PROPERTY_STAGE_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["TEAM_1"] = $arEl["PROPERTY_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["TEAM_2"] = $arEl["PROPERTY_TEAM_2_VALUE"];
		if(!empty($arEl["PROPERTY_STRUCTURE_TEAM_1_VALUE"]) && !empty($arEl["PROPERTY_STRUCTURE_TEAM_2_VALUE"])) {
			$arResult["MATCHES"][$arEl["ID"]]["GOALS_1"] = count($arEl["PROPERTY_GOALS_TEAM_1_VALUE"]) + count($arEl["PROPERTY_AUTO_GOALS_TEAM_2_VALUE"]);
			$arResult["MATCHES"][$arEl["ID"]]["GOALS_2"] = count($arEl["PROPERTY_GOALS_TEAM_2_VALUE"]) + count($arEl["PROPERTY_AUTO_GOALS_TEAM_1_VALUE"]);
		}
		$rsSection = CIBlockElement::GetElementGroups($arEl["ID"], true);
		while($arSection = $rsSection->Fetch()){
			$arResult["MATCHES"][$arEl["ID"]]["SECTION"][] = $arSection["ID"];
		}

		// Формируем массив команд
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_1_VALUE"]]["ID"] = $arEl["PROPERTY_TEAM_1_VALUE"];
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_2_VALUE"]]["ID"] = $arEl["PROPERTY_TEAM_2_VALUE"];
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_1_VALUE"]]["NAME"] = $arEl["PROPERTY_TEAM_1_NAME"];
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_2_VALUE"]]["NAME"] = $arEl["PROPERTY_TEAM_2_NAME"];
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_1_VALUE"]]["URL"] = $arEl["PROPERTY_TEAM_1_DETAIL_PAGE_URL"];
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_2_VALUE"]]["URL"] = $arEl["PROPERTY_TEAM_2_DETAIL_PAGE_URL"];
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_1_VALUE"]]["PICTURE"] = CFile::GetFileArray($arEl["PROPERTY_TEAM_1_PREVIEW_PICTURE"]);
		$arResult["TEAMS"][$arEl["PROPERTY_TEAM_2_VALUE"]]["PICTURE"] = CFile::GetFileArray($arEl["PROPERTY_TEAM_2_PREVIEW_PICTURE"]);
	}



	// Производим рассчет таблиц команд (таблица, кубок, пустой)	
	foreach ($arResult["MATCHES"] as $key => $value) {
		foreach ($value["SECTION"] as $v){
			if($arResult["SECTION"][$v]["TYPE"] == 3){ //выходим из расчета, если тип раздела = пустой
				break;
			}
			elseif($arResult["SECTION"][$v]["TYPE"] == 2){ // просто выбираем и добавляем массив игр в раздел
				$arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["NAME"]=$value["STAGE"];// нужно для сортировки
				if (is_array($arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["GAMES"][$value["TEAM_1"]])){
					$arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["GAMES"][$value["TEAM_1"]][] = $key;
				}
				elseif (is_array($arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["GAMES"][$value["TEAM_2"]])) {
					$arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["GAMES"][$value["TEAM_2"]][] = $key;
				}
				else{
					$arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["GAMES"][$value["TEAM_1"]]["TEAM_1"] = $value["TEAM_1"];
					$arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["GAMES"][$value["TEAM_1"]]["TEAM_2"] = $value["TEAM_2"];
					$arResult["SECTION"][$v]["CUP"][$value["STAGE"]]["GAMES"][$value["TEAM_1"]][] = $key;
				}
				
			}
			elseif($arResult["SECTION"][$v]["TYPE"] == 1){ // производим расчет для команд их игр, очков, побед, ничьи, поражения и пр., если тип раздела = таблица
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["ID"]=$value["TEAM_1"];
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["ID"]=$value["TEAM_2"];
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["GOALS_SCORED"]+=$value["GOALS_1"];
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["GOALS_SCORED"]+=$value["GOALS_2"];
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["GOALS_LOSED"]+=$value["GOALS_2"];
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["GOALS_LOSED"]+=$value["GOALS_1"];
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["GAMES"] += 0; 
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["GAMES"] += 0; 
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["SCORES"] += 0;
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["SCORES"] += 0;
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["WINS"] += 0;
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["WINS"] += 0;
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["LOSSES"] += 0;
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["LOSSES"] += 0;
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["DRAWS"] += 0;
				$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["DRAWS"] += 0;
				if(!is_null($value["GOALS_1"]) && !is_null($value["GOALS_2"])){
					$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["GAMES"] += 1; 
					$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["GAMES"] += 1; 
					if($value["GOALS_1"] > $value["GOALS_2"]){
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["SCORES"] += 3;
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["WINS"] += 1;
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["LOSSES"] += 1;
					}
					elseif ($value["GOALS_1"] < $value["GOALS_2"]) {
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["SCORES"] += 3;
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["WINS"] += 1;
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["LOSSES"] += 1;
					}
					else{
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["SCORES"] += 1;
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["SCORES"] += 1;
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_1"]]["DRAWS"] += 1;
						$arResult["SECTION"][$v]["TEAMS"][$value["TEAM_2"]]["DRAWS"] += 1;
					}
				}
			}
		}
	}


	//  Создаем функцию для сортироки команд по очкам
	function table_sort($a, $b){
		if($a['SCORES'] < $b['SCORES']){
			return true;
		}
		elseif ($a['SCORES'] == $b['SCORES']) {
			if($a["GOALS_SCORED"] - $a["GOALS_LOSED"] < $b["GOALS_SCORED"] - $b["GOALS_LOSED"]){
				return true;
			}
			elseif ($a["GOALS_SCORED"] - $a["GOALS_LOSED"] == $b["GOALS_SCORED"] - $b["GOALS_LOSED"]) {
				if ($a["GOALS_SCORED"] < $b["GOALS_SCORED"]) {
					return true;
				}
				elseif ($a["GOALS_SCORED"] == $b["GOALS_SCORED"]) {
					return false;
				}
				else{
					return false;
				}
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	
	//  Создаем функцию для сортироки этапов кубка
	function cup_sort($a, $b){
		$stage=["1/16 финала" => 0, 
				"1/8 финала" => 1, 
				"1/4 финала" => 2, 
				"1/2 финала" => 3, 
				"финал" => 4];
		return $stage[$a["NAME"]] > $stage[$b["NAME"]] ? true : false ;
	}

	//  Сортировка команд по очкам и этапу кубка
	foreach ($arResult["SECTION"] as $key => $value) {
		if($value["TYPE"] == 1){
			uasort($arResult["SECTION"][$key]["TEAMS"], 'table_sort');
		}
		elseif ($value["TYPE"] == 2) {
			uasort($arResult["SECTION"][$key]["CUP"], 'cup_sort');
		}
	}



	// if($USER->IsAdmin()){
	// 	echo "<pre>";print_r($arResult);echo "</pre>";
	// }



	
	// saving template name to cache array
	$arResult["__TEMPLATE_FOLDER"] = $this->__folder;
	
	// writing new $arResult to cache file
	$obCache_trade->EndDataCache($arResult);

}

$this->__component->arResult = $arResult; 
?>