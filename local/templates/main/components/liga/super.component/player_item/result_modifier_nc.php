<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// result modifier nocache


    // Добавляем хлебные крошки
	foreach ($arResult["PARENTS"] as $value) {
		$APPLICATION->AddChainItem($value["NAME"], "/tournament/calendar/?SECTION_ID=".$value["ID"]);
	}

	// для отладки
	// if($USER -> IsAdmin()){
	// 	echo '$arResult';
	// 	echo '<pre>'; print_r($arResult); echo '</pre>';
	// 	echo '$arParams';
	// 	echo '<pre>'; print_r($arParams); echo '</pre>';
	// }


/*
$APPLICATION->SetTitle($arResult["NAME"]);


if($GLOBALS["APPLICATION"]->GetShowIncludeAreas())
{
	if (CModule::IncludeModule("iblock"))
	{
		$this->AddIncludeAreaIcons(
			CIBlock::ShowPanel($arResult["IBLOCK_ID"], 
			$arResult["ID"], 
			$arResult["IBLOCK_SECTION_ID"], 
			$arParams["IBLOCK_TYPE"], true)
		);
	}
}
	
return $arResult["ID"];
*/


?>