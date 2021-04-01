<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


// получаем принадлежность матчей к разделам
foreach ($arResult["ITEMS"] as $key => $value) {
	$rsSection = CIBlockElement::GetElementGroups($value["ID"], true);
	while($arSection = $rsSection->Fetch()){
		$arResult["ITEMS"][$key]["SECTION"][] = $arSection["ID"];
	}
}

// получаем все разделы турниров
$arOrder = array("DEPTH_LEVEL" => "ASC", "SORT" => "ASC", "NAME" => "ASC");
$arFilter = array('IBLOCK_ID' =>3, "GLOBAL_ACTIVE" => "Y", "ACTIVE" => "Y", "ELEMENT_SUBSECTIONS" => "N"); 
$arSelect = array("ID", "NAME");
$rsSect = CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect);
while ($arSect = $rsSect->GetNext())
{
	$arResult["SECTIONS"][$arSect["ID"]] = $arSect;
}

 // echo '<pre>'; print_r($arResult); echo '</pre>';



?>