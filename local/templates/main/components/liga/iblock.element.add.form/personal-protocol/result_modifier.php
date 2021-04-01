<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


CModule::IncludeModule("iblock");

$res = CIBlockElement::GetByID($arResult['ELEMENT_PROPERTIES'][4]["0"]["VALUE"]);
if($ar_res = $res->GetNext())
{
	$arResult['ELEMENT_PROPERTIES'][4]["0"]["NAME"] = $ar_res['NAME'];
}

$res = CIBlockElement::GetByID($arResult['ELEMENT_PROPERTIES'][5]["0"]["VALUE"]);
if($ar_res = $res->GetNext())
{
	$arResult['ELEMENT_PROPERTIES'][5]["0"]["NAME"] = $ar_res['NAME'];
}

// $arSelect = Array("ID", "NAME");
// $arFilter = Array("IBLOCK_ID"=>"1");
// $res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, false, $arSelect);
// while($ob = $res->GetNext())
// {
// 	//$arR[$ob['ID']]["VALUE_NAME"] =  $ob['NAME'];
// 	$arR[$ob['ID']]["VALUE_SRC"] =  CFile::GetPath($ob['PREVIEW_PICTURE']);
// }

//echo '<pre>';print_r($arResult);echo '</pre>';

?>
