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

	$arOrder = array("ID" => "DESC", 'left_margin' => 'asc');
	$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ELEMENT_SUBSECTIONS" => "N"); 
	$arSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "NAME", "SORT", "ACTIVE", "GLOBAL_ACTIVE", "PICTURE", "SECTION_PAGE_URL", "UF_*");
	$rsSect = CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect);
	while ($arSect = $rsSect->GetNext())
	{
		if($arSect["DEPTH_LEVEL"]==1){
			if($arSect["UF_ARCHIVE"]==1){
				$arResult["SECTIONS"]["ARCHIVE"][$arSect["ID"]] = $arSect;
				$arResult["SECTIONS"]["ARCHIVE"][$arSect["ID"]]["PICTURE"] = CFile::GetFileArray($arSect["PICTURE"]);
			}
			else{
				$arResult["SECTIONS"]["ACTIVE"][$arSect["ID"]] = $arSect;
				$arResult["SECTIONS"]["ACTIVE"][$arSect["ID"]]["PICTURE"] = CFile::GetFileArray($arSect["PICTURE"]);
				
			}
		}
	}


	// echo "<pre>";print_r($arResult);echo "</pre>";

	
	// saving template name to cache array
	$arResult["__TEMPLATE_FOLDER"] = $this->__folder;
	
	// writing new $arResult to cache file
	$obCache_trade->EndDataCache($arResult);

}

$this->__component->arResult = $arResult; 
?>