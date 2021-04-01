<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


CModule::IncludeModule("iblock");

$arSelect = Array("ID", "NAME", 'PREVIEW_PICTURE', /*"DETAIL_PAGE_URL"*/);
$arFilter = Array("IBLOCK_ID"=>"2");
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, false, $arSelect);
while($ob = $res->GetNext())
{
	// $arR[$ob['ID']]["URL"] =  $ob['DETAIL_PAGE_URL'];
	$arR[$ob['ID']]["PREVIEW_PICTURE"] =  CFile::GetFileArray($ob['PREVIEW_PICTURE']);
}

$arSelect = Array("ID", "NAME", 'PREVIEW_PICTURE');
$arFilter = Array("IBLOCK_ID"=>"1");
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, false, $arSelect);
while($ob = $res->GetNext())
{
	//$arR[$ob['ID']]["VALUE_NAME"] =  $ob['NAME'];
	$arR[$ob['ID']]["PREVIEW_PICTURE"] =  CFile::GetFileArray($ob['PREVIEW_PICTURE']);
}

// echo '<pre>';print_r($arR);echo '</pre>';

foreach ($arResult["DISPLAY_PROPERTIES"] as $key => $value) {
	foreach ($value["LINK_ELEMENT_VALUE"] as $k => $v) {
		$arResult["DISPLAY_PROPERTIES"][$key]["LINK_ELEMENT_VALUE"][$k]["PREVIEW_PICTURE"] = $arR[$k]["PREVIEW_PICTURE"];
	}
}





// echo '<pre>';print_r($arResult);echo '</pre>';

?>
