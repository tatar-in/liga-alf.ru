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
	$arResult['RESULT']['ok'] = false;

	CModule::IncludeModule("iblock");

	switch($arParams['ACTION'])
	{
		case "player":
			$arOrder = Array("ID" => "ASC");
			$arSelect = Array("ID", "NAME", "DETAIL_PICTURE", "DETAIL_PAGE_URL");
			$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_PLAYER_ID'], "ACTIVE" => "Y", "?NAME" => $_GET["name"], "ID" => $_GET['id']);
			$res = CIBlockElement::GetList($arOrder, $arFilter, false, array("nPageSize" => 30, "bShowAll" => false,'iNumPage' => $_GET['page']), $arSelect);
			
			// проверяем, чтобы номер страницы не выходил за границы возможных значений
			if($_GET['page'] && $res->NavPageNomer != $_GET['page']) 
			{
				$arResult['RESULT']['message'] = "No players";
				break;
			}

			while($ob = $res->GetNext())
			{
				$arResult['RESULT']['result'][] = array(
					"id" => $ob["ID"],
					"name" => $ob["NAME"],
					"url" => "https://liga-alf.ru".$ob["DETAIL_PAGE_URL"],
					"picture" => ($ob["DETAIL_PICTURE"]) ? array(
						'origin' => "https://liga-alf.ru".CFile::GetPath($ob["DETAIL_PICTURE"]),
						'medium' => "https://liga-alf.ru".CFile::ResizeImageGet($ob["DETAIL_PICTURE"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true)['src'],
						'small' => "https://liga-alf.ru".CFile::ResizeImageGet($ob["DETAIL_PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true)['src']) : "",
					);
			}

			// проверяем, удалось ли сформировать результат в соответствии с параметрами
			if(!$arResult['RESULT']['result']) $arResult['RESULT']['message'] = "The player is not found";
			else $arResult['RESULT']['ok'] = true;

			break;
		case "team":
			$arOrder = Array("ID" => "ASC");
			$arSelect = Array("ID", "NAME", "DETAIL_PICTURE", "DETAIL_PAGE_URL");
			$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_TEAM_ID'], "ACTIVE" => "Y", "?NAME" => $_GET["name"], "ID" => $_GET['id']);
			$res = CIBlockElement::GetList($arOrder, $arFilter, false, array("nPageSize" => 30, "bShowAll" => false,'iNumPage' => $_GET['page']), $arSelect);
			
			// проверяем, чтобы номер страницы не выходил за границы возможных значений
			if($_GET['page'] && $res->NavPageNomer != $_GET['page']) 
			{
				$arResult['RESULT']['message'] = "No teams";
				break;
			}
			
			while($ob = $res->GetNext())
			{
				$arResult['RESULT']['result'][] = array(
					'id' => $ob['ID'],
					'name' => $ob['NAME'],
					'url' => 'https://liga-alf.ru'.$ob["DETAIL_PAGE_URL"],
					"picture" => ($ob["DETAIL_PICTURE"]) ? array(
						'origin' => "https://liga-alf.ru".CFile::GetPath($ob["DETAIL_PICTURE"]),
						'medium' => "https://liga-alf.ru".CFile::ResizeImageGet($ob["DETAIL_PICTURE"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true)['src'],
						'small' => "https://liga-alf.ru".CFile::ResizeImageGet($ob["DETAIL_PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true)['src']) : "",
					);
			}

			// проверяем, удалось ли сформировать результат в соответствии с параметрами
			if(!$arResult['RESULT']['result']) $arResult['RESULT']['message'] = "The team is not found";
			else $arResult['RESULT']['ok'] = true;

			break;
		default:
			$arResult['RESULT']['message'] = "Action undefined";
			break;
	}
    
	
	// saving template name to cache array
	$arResult["__TEMPLATE_FOLDER"] = $this->__folder;
	
	// writing new $arResult to cache file
	$obCache_trade->EndDataCache($arResult);

}

$this->__component->arResult = $arResult; 
?>