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
	
	CModule::IncludeModule('iblock');

	$arOrder = Array("NAME" => "ASC");
	$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
	$arFilter = Array("IBLOCK_ID"=> "1", "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	while($ob = $res->GetNext())
	{
		$arResult[$ob["ID"]] = $ob;
		$arResult[$ob["ID"]]["URL"] = $ob["DETAIL_PAGE_URL"];
		$arResult[$ob["ID"]]["PREVIEW_PICTURE"] = CFile::GetFileArray($ob["PREVIEW_PICTURE"]);
	}

	$arOrder = Array("NAME" => "ASC");
	$arSelect = Array("ID", "NAME", "PROPERTY_STRUCTURE_TEAM_1", "PROPERTY_STRUCTURE_TEAM_2", "PROPERTY_GOALS_TEAM_1", "PROPERTY_GOALS_TEAM_2");
	$arFilter = Array("IBLOCK_ID"=> "3", "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "SECTION_ID" => $arParams["SECTION_ID"], "INCLUDE_SUBSECTIONS" => "Y", "SECTION_ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y");
	$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	while($ob = $res->GetNext())
	{
		// было
		// $arR = array_count_values(array_merge($ob["PROPERTY_GOALS_TEAM_1_VALUE"], $ob["PROPERTY_GOALS_TEAM_2_VALUE"]));
		// foreach ($arR as $key => $value) {
		// 	$arResult[$key]["GOALS"] += $value;
		// 	$arResult[$key]["GAMES"] ++;
		// }

		// стало
		$arR["STRUCTURE"] = array_merge($ob["PROPERTY_STRUCTURE_TEAM_1_VALUE"], $ob["PROPERTY_STRUCTURE_TEAM_2_VALUE"]);
		$arR["GOALS"] = array_count_values(array_merge($ob["PROPERTY_GOALS_TEAM_1_VALUE"], $ob["PROPERTY_GOALS_TEAM_2_VALUE"]));
		foreach ($arR["STRUCTURE"] as $value) {
			$arResult[$value]["GAMES"] ++;
			$arResult[$value]["GOALS"] += $arR["GOALS"][$value];
		}
	}
	function cmp_sort($a, $b){
		if($a['GOALS'] < $b['GOALS']){
			return true;
		}
		elseif($a['GOALS'] == $b['GOALS']){
			if ($a["GAMES"] > $b["GAMES"]) {
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	 
	uasort($arResult, 'cmp_sort');

	
	
	// saving template name to cache array
	$arResult["__TEMPLATE_FOLDER"] = $this->__folder;
	
	// writing new $arResult to cache file
	$obCache_trade->EndDataCache($arResult);

}

$this->__component->arResult = $arResult; 
?>