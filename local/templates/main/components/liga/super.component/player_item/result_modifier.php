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
	$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], "SECTION_GLOBAL_ACTIVE" => "Y", "SECTION_ACTIVE" => "Y", "ACTIVE" => "Y", "SECTION_ID" => $arParams["SECTION_ID"], "INCLUDE_SUBSECTIONS" => "Y", array("LOGIC" => "OR", "PROPERTY_STRUCTURE_TEAM_1" => $arParams["PLAYER"], "PROPERTY_STRUCTURE_TEAM_2" => $arParams["PLAYER"])); 
	$arSelect = array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_STAGE", "DATE_ACTIVE_FROM",
		"PROPERTY_TEAM_1", "PROPERTY_TEAM_1.NAME", "PROPERTY_TEAM_1.DETAIL_PAGE_URL", "PROPERTY_TEAM_1.PREVIEW_PICTURE", "PROPERTY_GOALS_TEAM_1", "PROPERTY_AUTO_GOALS_TEAM_1", "PROPERTY_STRUCTURE_TEAM_1", "PROPERTY_YELLOW_CARDS_TEAM_1", "PROPERTY_TWO_YELLOW_CARDS_TEAM_1", "PROPERTY_RED_CARDS_TEAM_1", "PROPERTY_PENALTY_TEAM_1",
		"PROPERTY_TEAM_2", "PROPERTY_TEAM_2.NAME", "PROPERTY_TEAM_2.DETAIL_PAGE_URL", "PROPERTY_TEAM_2.PREVIEW_PICTURE", "PROPERTY_GOALS_TEAM_2", "PROPERTY_AUTO_GOALS_TEAM_2", "PROPERTY_STRUCTURE_TEAM_2", "PROPERTY_YELLOW_CARDS_TEAM_2", "PROPERTY_TWO_YELLOW_CARDS_TEAM_2", "PROPERTY_RED_CARDS_TEAM_2", "PROPERTY_PENALTY_TEAM_2");
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
		
		// принадлежность матча к турниру
		$rsSection = CIBlockElement::GetElementGroups($arEl["ID"], true);
		while($arSection = $rsSection->Fetch()){
			$arResult["MATCHES"][$arEl["ID"]]["SECTION"][] = $arSection["ID"];
		    // формируем массив турниров
		    $arResult["SECTIONS"][$arSection["ID"]]["ID"] = $arSection["ID"];
		    $arResult["SECTIONS"][$arSection["ID"]]["NAME"] = $arSection["NAME"];
		    if(in_array($arParams["PLAYER"], $arResult["MATCHES"][$arEl["ID"]]["STRUCTURE_1"])) $arResult["SECTIONS"][$arSection["ID"]]["TEAM"][$arResult["MATCHES"][$arEl["ID"]]["TEAM_1"]] = $arResult["MATCHES"][$arEl["ID"]]["TEAM_1"];
		    if(in_array($arParams["PLAYER"], $arResult["MATCHES"][$arEl["ID"]]["STRUCTURE_2"])) $arResult["SECTIONS"][$arSection["ID"]]["TEAM"][$arResult["MATCHES"][$arEl["ID"]]["TEAM_2"]] = $arResult["MATCHES"][$arEl["ID"]]["TEAM_2"];
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
	$arResult["STAT"]["MATCHES"] = count($arResult["MATCHES"]); 
	foreach ($arResult["MATCHES"] as $key => $value) {
		if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
			$arResult["STAT"]["GOALS"] += array_count_values($value["GOALS_1"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["AUTOGOALS"] += array_count_values($value["AUTOGOALS_1"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["YCARDS"] += array_count_values($value["YCARDS_1"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["2YCARDS"] += array_count_values($value["2YCARDS_1"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["RCARDS"] += array_count_values($value["RCARDS_1"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["PENALTY"] += array_count_values($value["PENALTY_1"])[$arParams["PLAYER"]]; 
		}
		elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
			$arResult["STAT"]["GOALS"] += array_count_values($value["GOALS_2"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["AUTOGOALS"] += array_count_values($value["AUTOGOALS_2"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["YCARDS"] += array_count_values($value["YCARDS_2"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["2YCARDS"] += array_count_values($value["2YCARDS_2"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["RCARDS"] += array_count_values($value["RCARDS_2"])[$arParams["PLAYER"]]; 
			$arResult["STAT"]["PENALTY"] += array_count_values($value["PENALTY_2"])[$arParams["PLAYER"]];
		}
		foreach ($value["SECTION"] as $k => $v) {
			$arResult["SECTIONS"][$v]["MATCHES"] += 1;
			if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
				$arResult["SECTIONS"][$v]["GOALS"] += array_count_values($value["GOALS_1"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["AUTOGOALS"] += array_count_values($value["AUTOGOALS_1"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["YCARDS"] += array_count_values($value["YCARDS_1"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["2YCARDS"] += array_count_values($value["2YCARDS_1"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["RCARDS"] += array_count_values($value["RCARDS_1"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["PENALTY"] += array_count_values($value["PENALTY_1"])[$arParams["PLAYER"]]; 
			}
			elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
				$arResult["SECTIONS"][$v]["GOALS"] += array_count_values($value["GOALS_2"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["AUTOGOALS"] += array_count_values($value["AUTOGOALS_2"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["YCARDS"] += array_count_values($value["YCARDS_2"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["2YCARDS"] += array_count_values($value["2YCARDS_2"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["RCARDS"] += array_count_values($value["RCARDS_2"])[$arParams["PLAYER"]]; 
				$arResult["SECTIONS"][$v]["PENALTY"] += array_count_values($value["PENALTY_2"])[$arParams["PLAYER"]];
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