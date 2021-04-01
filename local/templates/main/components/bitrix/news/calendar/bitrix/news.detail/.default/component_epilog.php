<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


 // echo '<pre>'; print_r($arResult); echo '</pre>';

$APPLICATION->AddChainItem($arResult["NAME"], "/tournament/calendar/?ID=".$arResult["ID"]."&SECTION_ID=".$_GET["SECTION_ID"]);

?>