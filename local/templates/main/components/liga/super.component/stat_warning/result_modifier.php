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
	$arSelect = Array("ID", "NAME", "PROPERTY_STRUCTURE_TEAM_1", "PROPERTY_STRUCTURE_TEAM_2", "PROPERTY_YELLOW_CARDS_TEAM_1", "PROPERTY_YELLOW_CARDS_TEAM_2", "PROPERTY_TWO_YELLOW_CARDS_TEAM_1", "PROPERTY_TWO_YELLOW_CARDS_TEAM_2", "PROPERTY_RED_CARDS_TEAM_1", "PROPERTY_RED_CARDS_TEAM_2", );
	$arFilter = Array("IBLOCK_ID"=> "3", "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "SECTION_ID" => $arParams["SECTION_ID"], "INCLUDE_SUBSECTIONS" => "Y", "SECTION_ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y");
	$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	while($ob = $res->GetNext())
	{
		$arR["STRUCTURE"] = array_merge($ob["PROPERTY_STRUCTURE_TEAM_1_VALUE"], $ob["PROPERTY_STRUCTURE_TEAM_2_VALUE"]);
		$arR["YELLOW_CARDS"] = array_merge($ob["PROPERTY_YELLOW_CARDS_TEAM_1_VALUE"], $ob["PROPERTY_YELLOW_CARDS_TEAM_2_VALUE"]);
		$arR["TWO_YELLOW_CARDS"] = array_merge($ob["PROPERTY_TWO_YELLOW_CARDS_TEAM_1_VALUE"], $ob["PROPERTY_TWO_YELLOW_CARDS_TEAM_2_VALUE"]);
		$arR["RED_CARDS"] = array_merge($ob["PROPERTY_RED_CARDS_TEAM_1_VALUE"], $ob["PROPERTY_RED_CARDS_TEAM_2_VALUE"]);

		foreach ($arR["STRUCTURE"] as $value) {
			$arResult[$value]["GAMES"] ++;
			$arResult[$value]["YELLOW_CARDS"] += 0; 
			$arResult[$value]["TWO_YELLOW_CARDS"] += 0; 
			$arResult[$value]["RED_CARDS"] += 0; 
			if(in_array($value, $arR["YELLOW_CARDS"])) {
				$arResult[$value]["YELLOW_CARDS"] += 1; 
				$arResult[$value]["RATE"] += 1;
			}
			if(in_array($value, $arR["TWO_YELLOW_CARDS"])) {
				$arResult[$value]["TWO_YELLOW_CARDS"] += 1; 
				$arResult[$value]["RATE"] += 2;
			}
			if(in_array($value, $arR["RED_CARDS"])) {
				$arResult[$value]["RED_CARDS"] += 1; 
				$arResult[$value]["RATE"] += 3;
			}
		}
		
	}
	function cmp_sort1($a, $b){
		if($a['RATE'] < $b['RATE']){
			return true;
		}
		elseif($a['RATE'] == $b['RATE']){
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
	 
	uasort($arResult, 'cmp_sort1');


	// echo '<pre>'; print_r($arR); echo '</pre>';

	
	
	// saving template name to cache array
	$arResult["__TEMPLATE_FOLDER"] = $this->__folder;
	
	// writing new $arResult to cache file
	$obCache_trade->EndDataCache($arResult);

}

$this->__component->arResult = $arResult; 
?>