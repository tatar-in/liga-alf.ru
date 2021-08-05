<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//Подключаем модуль инфоблоков
CModule::IncludeModule('iblock');

//Получаем базу игроков
    $arSelect = Array("ID", "NAME");
    $arFilter = Array("IBLOCK_ID"=>"1");
    $res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext())
    {
    	$arResult["PLAYERS"][$ob['ID']]["ID"] = $ob['ID'];
    	$arResult["PLAYERS"][$ob['ID']]["NAME"] = $ob['NAME'];
    }

//Получаем базу команд 
    $arSelect = Array("ID", "NAME");
    $arFilter = Array("IBLOCK_ID"=>"2");
    $res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext())
    {
    	$arResult["TEAMS"][$ob['ID']]["ID"] = $ob['ID'];
    	$arResult["TEAMS"][$ob['ID']]["NAME"] = $ob['NAME'];
    }

?>