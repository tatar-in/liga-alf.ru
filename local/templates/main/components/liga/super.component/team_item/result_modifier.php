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

	// получаем элементы турниров
	$arOrder = array("DATE_ACTIVE_FROM" => "DESC", 'ID' => 'DESC');
	$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], "SECTION_GLOBAL_ACTIVE" => "Y", "SECTION_ACTIVE" => "Y", "ACTIVE" => "Y", "SECTION_ID" => $arParams["SECTION_ID"], "INCLUDE_SUBSECTIONS" => "Y", array("LOGIC" => "OR", "PROPERTY_TEAM_1" => $arParams["TEAM"], "PROPERTY_TEAM_2" => $arParams["TEAM"])); 
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_STAGE", "DATE_ACTIVE_FROM",
		"PROPERTY_TEAM_1", "PROPERTY_TEAM_1.NAME", "PROPERTY_TEAM_1.DETAIL_PAGE_URL", "PROPERTY_TEAM_1.PREVIEW_PICTURE", "PROPERTY_GOALS_TEAM_1", "PROPERTY_AUTO_GOALS_TEAM_1", "PROPERTY_STRUCTURE_TEAM_1", "PROPERTY_YELLOW_CARDS_TEAM_1", "PROPERTY_TWO_YELLOW_CARDS_TEAM_1", "PROPERTY_RED_CARDS_TEAM_1", "PROPERTY_PENALTY_TEAM_1", "PROPERTY_TECHNICAL_DEFEAT_TEAM_1",
		"PROPERTY_TEAM_2", "PROPERTY_TEAM_2.NAME", "PROPERTY_TEAM_2.DETAIL_PAGE_URL", "PROPERTY_TEAM_2.PREVIEW_PICTURE", "PROPERTY_GOALS_TEAM_2", "PROPERTY_AUTO_GOALS_TEAM_2", "PROPERTY_STRUCTURE_TEAM_2", "PROPERTY_YELLOW_CARDS_TEAM_2", "PROPERTY_TWO_YELLOW_CARDS_TEAM_2", "PROPERTY_RED_CARDS_TEAM_2", "PROPERTY_PENALTY_TEAM_2", "PROPERTY_TECHNICAL_DEFEAT_TEAM_2");
	$rsEl = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	while ($arEl = $rsEl->GetNext())
	{
		// Формируем массив матчей
		$arResult["MATCHES"][$arEl["ID"]]["NAME"] = $arEl["NAME"];
		$arResult["MATCHES"][$arEl["ID"]]["DATE"] = $arEl["DATE_ACTIVE_FROM"];
		$arResult["MATCHES"][$arEl["ID"]]["URL"] = $arEl["DETAIL_PAGE_URL"];
		$arResult["MATCHES"][$arEl["ID"]]["STAGE"] = $arEl["PROPERTY_STAGE_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["TEAM_1"] = $arEl["PROPERTY_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["TEAM_2"] = $arEl["PROPERTY_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["STRUCTURE_1"] = $arEl["PROPERTY_STRUCTURE_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["STRUCTURE_2"] = $arEl["PROPERTY_STRUCTURE_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["GOALS_1"] = $arEl["PROPERTY_GOALS_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["GOALS_2"] = $arEl["PROPERTY_GOALS_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["AUTOGOALS_1"] = $arEl["PROPERTY_AUTO_GOALS_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["AUTOGOALS_2"] = $arEl["PROPERTY_AUTO_GOALS_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["YCARDS_1"] = $arEl["PROPERTY_YELLOW_CARDS_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["YCARDS_2"] = $arEl["PROPERTY_YELLOW_CARDS_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["2YCARDS_1"] = $arEl["PROPERTY_TWO_YELLOW_CARDS_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["2YCARDS_2"] = $arEl["PROPERTY_TWO_YELLOW_CARDS_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["RCARDS_1"] = $arEl["PROPERTY_RED_CARDS_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["RCARDS_2"] = $arEl["PROPERTY_RED_CARDS_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["PENALTY_1"] = $arEl["PROPERTY_PENALTY_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["PENALTY_2"] = $arEl["PROPERTY_PENALTY_TEAM_2_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["TECHNICAL_DEFEAT_1"] = $arEl["PROPERTY_TECHNICAL_DEFEAT_TEAM_1_VALUE"];
		$arResult["MATCHES"][$arEl["ID"]]["TECHNICAL_DEFEAT_2"] = $arEl["PROPERTY_TECHNICAL_DEFEAT_TEAM_2_VALUE"];

		
		// принадлежность матча к турниру
		$rsSection = CIBlockElement::GetElementGroups($arEl["ID"], true);
		while($arSection = $rsSection->Fetch()){
			$arResult["MATCHES"][$arEl["ID"]]["SECTION"][] = $arSection["ID"];
		    // формируем массив турниров
		    $arResult["SECTIONS"][$arSection["ID"]]["ID"] = $arSection["ID"];
		    $arResult["SECTIONS"][$arSection["ID"]]["NAME"] = $arSection["NAME"];
		}

		// Формируем массив игроков
		if ($arParams["TEAM"] == $arResult["MATCHES"][$arEl["ID"]]["TEAM_1"]) $number_team = 1;
		elseif ($arParams["TEAM"] == $arResult["MATCHES"][$arEl["ID"]]["TEAM_2"]) $number_team = 2;
		foreach ($arResult["MATCHES"][$arEl["ID"]]["STRUCTURE_".$number_team] as $key => $value) {
			$arResult["PLAYERS"][$value]["ID"] = $value;
		}
	}

	// получаем дополнительную информацию по игрокам
	$arOrder = array('ID' => 'ASC');
	$arFilter = array('IBLOCK_ID' => 1, "ID" => array_keys($arResult["PLAYERS"])); 
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "DETAIL_PICTURE", "PROPERTY_SURNAME", "PROPERTY_NAME", "PROPERTY_PATRONYMIC");
	$rsEl = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	while ($arEl = $rsEl->GetNext())
	{
		$arResult["PLAYERS"][$arEl["ID"]]["NAME"] = $arEl["NAME"];
		$arResult["PLAYERS"][$arEl["ID"]]["URL"] = $arEl["DETAIL_PAGE_URL"];
		$arResult["PLAYERS"][$arEl["ID"]]["PICTURE"] = CFile::GetFileArray($arEl["DETAIL_PICTURE"]);
		$arResult["PLAYERS"][$arEl["ID"]]["SURNAME"] = $arEl["PROPERTY_SURNAME_VALUE"];
		$arResult["PLAYERS"][$arEl["ID"]]["NAME_"] = $arEl["PROPERTY_NAME_VALUE"];
		$arResult["PLAYERS"][$arEl["ID"]]["PATRONYMIC"] = $arEl["PROPERTY_PATRONYMIC_VALUE"];
	}

	// получаем все турниры, которые есть в базе (даже в которых игрок не участвовал)
	$arOrder = array('ID' => 'DESC');
	$arFilter = array('IBLOCK_ID' =>$arParams["IBLOCK_ID"]); 
	$arSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "UF_STATISTICS");
	$rsSect = CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect);
	while ($arSect = $rsSect->GetNext())
	{
		$arResult["ALL_SECTIONS"][$arSect["ID"]] = $arSect;
	}

	// добавляем в массив цепочку категорий 
	foreach ($arResult["SECTIONS"] as $key => $value) {
		$nav = CIBlockSection::GetNavChain(false, $value["ID"]);
		while($arItem = $nav->Fetch()){
		    $arResult["SECTIONS"][$value["ID"]]["PARENTS"][$arItem["ID"]]["ID"] = $arItem["ID"];
		    $arResult["SECTIONS"][$value["ID"]]["PARENTS"][$arItem["ID"]]["NAME"] = $arItem["NAME"];
		}
	}

	// рассчитываем сводную статистику
	$arResult["STATISTIC"]["GENERAL"] = count($arResult["MATCHES"]); 
	foreach ($arResult["MATCHES"] as $key => $value) {
		if ($arParams["TEAM"] == $value["TEAM_1"]) $number_team = 1;
		elseif ($arParams["TEAM"] == $value["TEAM_2"]) $number_team = 2;
		foreach ($value["STRUCTURE_".$number_team] as $k => $v) {
			$arResult["PLAYERS"][$v]["MATCHES"] += 1;
		}
		foreach ($value["GOALS_".$number_team] as $k => $v) {
			$arResult["PLAYERS"][$v]["GOALS"] += 1;
		}
		foreach ($value["AUTOGOALS_".$number_team] as $k => $v) {
			$arResult["PLAYERS"][$v]["AUTOGOALS"] += 1;
		}
		foreach ($value["YCARDS_".$number_team] as $k => $v) {
			$arResult["PLAYERS"][$v]["YCARDS"] += 1;
		}
		foreach ($value["2YCARDS_".$number_team] as $k => $v) {
			$arResult["PLAYERS"][$v]["2YCARDS"] += 1;
		}
		foreach ($value["RCARDS_".$number_team] as $k => $v) {
			$arResult["PLAYERS"][$v]["RCARDS"] += 1;
		}
		foreach ($value["PENALTY_".$number_team] as $k => $v) {
			$arResult["PLAYERS"][$v]["PENALTY"] += 1;
		}
		foreach ($value["SECTION"] as $k => $v) {
			$arResult["SECTIONS"][$v]["MATCHES"] += 1;
			if (!empty($value["STRUCTURE_1"]) && !empty($value["STRUCTURE_2"])) {
				$arResult["SECTIONS"][$v]["PLAYED_MATCHES"] += 1;
				$arResult["SECTIONS"][$v]["GOALS_SCORED"] += count($value["GOALS_".$number_team]);
				$arResult["SECTIONS"][$v]["GOALS_MISSED"] += count($value["GOALS_".(3 - $number_team)]);
				$arResult["SECTIONS"][$v]["AUTOGOALS"] += count($value["AUTOGOALS_".$number_team]);; 
				$arResult["SECTIONS"][$v]["YCARDS"] += count($value["YCARDS_".$number_team]); 
				$arResult["SECTIONS"][$v]["2YCARDS"] += count($value["2YCARDS_".$number_team]); 
				$arResult["SECTIONS"][$v]["RCARDS"] += count($value["RCARDS_".$number_team]); 
				$arResult["SECTIONS"][$v]["PENALTY"] += count($value["PENALTY_".$number_team]); 
				if (count($value["GOALS_".$number_team]) + count($value["AUTOGOALS_".(3 - $number_team)]) + count($value["PENALTY_".$number_team]) > count($value["GOALS_".(3 - $number_team)]) + count($value["AUTOGOALS_".$number_team]) + count($value["GOALS_".(3 - $number_team)])){
					$arResult["SECTIONS"][$v]["WIN"] += 1;
					$arResult["SECTIONS"][$v]["SCORE"] += 3;
				}
				elseif (count($value["GOALS_".$number_team]) + count($value["AUTOGOALS_".(3 - $number_team)]) + count($value["PENALTY_".$number_team]) == count($value["GOALS_".(3 - $number_team)]) + count($value["AUTOGOALS_".$number_team]) + count($value["GOALS_".(3 - $number_team)])){
					$arResult["SECTIONS"][$v]["DEADHEAT"] += 1;
					$arResult["SECTIONS"][$v]["SCORE"] += 1;
				}
				else{
					$arResult["SECTIONS"][$v]["LOSE"] += 1; 
				}
			}
		}
	}	

    
	
	// saving template name to cache array
	$arResult["__TEMPLATE_FOLDER"] = $this->__folder;
	
	// writing new $arResult to cache file
	$obCache_trade->EndDataCache($arResult);

}

$this->__component->arResult = $arResult; 
?>