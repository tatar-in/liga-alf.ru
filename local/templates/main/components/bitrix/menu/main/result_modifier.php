<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


//echo '<pre>';print_r($arResult);echo '</pre>';


$arPropItems = [];
if(!empty($arResult)){
	foreach ($arResult as $key => $item) {
		if($item["DEPTH_LEVEL"] == 1){
			$arPropItems[] = $item;
		}
		else{
			$arPropItems[end(array_keys($arPropItems))]["SUBITEMS"][] = $item;
		}
	}
}

// echo '<pre>';print_r($arPropItems);echo '</pre>';

$arResult=$arPropItems;



?>