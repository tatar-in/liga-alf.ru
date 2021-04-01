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

	$arOrder = array('UF_ARCHIVE' => 'ASC', "SORT" => "ASC", "NAME" => "ASC");
	$arFilter = array('IBLOCK_ID' =>$arParams["IBLOCK_ID"], "GLOBAL_ACTIVE" => "Y", "ACTIVE" => "Y", "ELEMENT_SUBSECTIONS" => "N"); 
	$arSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "NAME", "SORT", "ACTIVE", "GLOBAL_ACTIVE", "PICTURE", "SECTION_PAGE_URL", "UF_*");
	$rsSect = CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect);
	while ($arSect = $rsSect->GetNext())
	{
		$arResult["SECTIONS"][$arSect["ID"]] = $arSect;
		$arResult["SECTIONS"][$arSect["ID"]]["PICTURE"] = CFile::GetFileArray($arSect["PICTURE"]);
	}




	// Получаем родительские категории 
	$nav = CIBlockSection::GetNavChain(false, $arParams["SECTION_ID"]);
    while($arItem = $nav->Fetch()){
        $arResult["PARENTS"][] = $arItem;
    }

	// echo "<pre>";print_r($arResult);echo "</pre>";
    
	
	// saving template name to cache array
	$arResult["__TEMPLATE_FOLDER"] = $this->__folder;
	
	// writing new $arResult to cache file
	$obCache_trade->EndDataCache($arResult);

}

$this->__component->arResult = $arResult; 
?>