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
		case "getplayers":
			$arOrder = Array("ID" => "DESC");
			$arSelect = Array("ID", "NAME", "DETAIL_PICTURE", "DETAIL_PAGE_URL");
			$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_PLAYER_ID'], "ACTIVE" => "Y", "?NAME" => $_GET["name"], "ID" => $_GET['id']);
			$res = CIBlockElement::GetList($arOrder, $arFilter, false, array("nPageSize" => 20, "bShowAll" => false,'iNumPage' => $_GET['page']), $arSelect);
			
			// проверяем, чтобы номер страницы не выходил за границы возможных значений
			if($_GET['page'] && $res->NavPageNomer != $_GET['page']) 
			{
				$arResult['RESULT']['message'] = "No players";
				break;
			}

			$arResult['RESULT']['count'] = $res->NavRecordCount;
			$arResult['RESULT']['pages'] = $res->NavPageCount;

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
			if(!$arResult['RESULT']['result']) 
			{
				$arResult['RESULT']['message'] = "Players are not found";
				unset($arResult['RESULT']['count']);
				unset($arResult['RESULT']['pages']);
			}
			else $arResult['RESULT']['ok'] = true;

			break;
		case "getteams":
			$arOrder = Array("ID" => "DESC");
			$arSelect = Array("ID", "NAME", "DETAIL_PICTURE", "DETAIL_PAGE_URL");
			$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_TEAM_ID'], "ACTIVE" => "Y", "?NAME" => $_GET["name"], "ID" => $_GET['id']);
			$res = CIBlockElement::GetList($arOrder, $arFilter, false, array("nPageSize" => 20, "bShowAll" => false,'iNumPage' => $_GET['page']), $arSelect);
			
			// проверяем, чтобы номер страницы не выходил за границы возможных значений
			if($_GET['page'] && $res->NavPageNomer != $_GET['page']) 
			{
				$arResult['RESULT']['message'] = "No teams";
				break;
			}

			$arResult['RESULT']['count'] = $res->NavRecordCount;
			$arResult['RESULT']['pages'] = $res->NavPageCount;

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
			if(!$arResult['RESULT']['result']) 
			{
				$arResult['RESULT']['message'] = "Teams are not found";
				unset($arResult['RESULT']['count']);
				unset($arResult['RESULT']['pages']);
			}
			else $arResult['RESULT']['ok'] = true;

			break;
		case "gettournaments":
			$arOrder = array("ID" => "DESC");
			$arSelect = array("ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "NAME", "UF_STATISTICS", "UF_ARCHIVE", "PICTURE");
			$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_TOURNAMENT_ID"], "GLOBAL_ACTIVE" => "Y", "ACTIVE" => "Y", "ELEMENT_SUBSECTIONS" => "N",
				"ID" => $_GET['id']); 
			$res = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
			while ($ob = $res->GetNext())
			{
				$arResult['RESULT']['result'][$ob["ID"]] = array(
					'id' => $ob["ID"],
					'name' => $ob["NAME"],
					'picture' => ($ob["PICTURE"]) ? array(
						'origin' => "https://liga-alf.ru".CFile::GetPath($ob["PICTURE"]),
						'medium' => "https://liga-alf.ru".CFile::ResizeImageGet($ob["PICTURE"], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true)['src'],
						'small' => "https://liga-alf.ru".CFile::ResizeImageGet($ob["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true)['src']) : "",
					'parent' => $ob["IBLOCK_SECTION_ID"],
					'depth' => $ob['DEPTH_LEVEL'],
					'archive' => $ob['UF_ARCHIVE'],
					'statistic' => $ob['UF_STATISTICS']
					);
			}

			// проверяем, удалось ли сформировать результат в соответствии с параметрами
			if(!$arResult['RESULT']['result']) 
			{
				$arResult['RESULT']['message'] = "Tournaments are not found";
				break;
			}
			else $arResult['RESULT']['ok'] = true;

			// рассчитываем туры (этапы), которые были в турнире
			foreach ($arResult['RESULT']['result'] as $key => $value) 
			{
				if($value['statistic'] != 1) continue;
				if($value['parent']) $arResult['RESULT']['result'][$value['parent']]['sub'][] = $value['id'];
				$res = CIBlockElement::GetList(array("ID" => "ASC"), Array("IBLOCK_ID" => $arParams['IBLOCK_TOURNAMENT_ID'], "ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y", "SECTION_ID" =>  $value['id'], "INCLUDE_SUBSECTIONS" => "Y"), false, false, array("PROPERTY_STAGE"));
				while($ob = $res->GetNext())
				{
					$arResult['RESULT']['result'][$value['id']]['stage'][$ob['PROPERTY_STAGE_ENUM_ID']] = $ob['PROPERTY_STAGE_VALUE'];
				}
				ksort($arResult['RESULT']['result'][$value['id']]['stage']);
				$arResult['RESULT']['result'][$value['id']]['stage'] = array_values($arResult['RESULT']['result'][$value['id']]['stage']);
			}

			// убираем ненужные подкатегории (служебные)
			foreach ($arResult['RESULT']['result'] as $key => $value) 
			{
				if($value['statistic'] == 1 || $value['sub']) continue;
				unset($arResult['RESULT']['result'][$key]);
			}
			
			$arResult['RESULT']['result'] = array_values($arResult['RESULT']['result']);

			break;
		case "getmatches":
			// получаем игроков
			$arOrder = Array();
			$arSelect = Array("ID", "NAME");
			$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_PLAYER_ID'], "ACTIVE" => "Y");
			$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
			while($ob = $res->GetNext())
			{
				$players[$ob["ID"]] = $ob["NAME"];
			}

			// получаем турниры
			$arOrder = array();
			$arSelect = array("ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "NAME", "UF_TYPE", "UF_STATISTICS", "UF_ARCHIVE");
			$arFilter = array('IBLOCK_ID' => $arParams["IBLOCK_TOURNAMENT_ID"], "GLOBAL_ACTIVE" => "Y", "ACTIVE" => "Y"); 
			$res = CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect);
			while ($ob = $res->GetNext())
			{
				$tournaments[$ob["ID"]]["ID"] = $ob["ID"];
				$tournaments[$ob["ID"]]["IBLOCK_SECTION_ID"] = $ob["IBLOCK_SECTION_ID"];
				$tournaments[$ob["ID"]]["DEPTH_LEVEL"] = $ob["DEPTH_LEVEL"];
				$tournaments[$ob["ID"]]["NAME"] = $ob["NAME"];
				$tournaments[$ob["ID"]]["UF_TYPE"] = $ob["UF_TYPE"];
				$tournaments[$ob["ID"]]["UF_STATISTICS"] = $ob["UF_STATISTICS"];
				$tournaments[$ob["ID"]]["UF_ARCHIVE"] = $ob["UF_ARCHIVE"];
			}

			// получаем матчи
			$arOrder = Array("ID" => "DESC");
			$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "DETAIL_PAGE_URL", "PROPERTY_STAGE", 
				"PROPERTY_TEAM_1", "PROPERTY_TEAM_2", "PROPERTY_TEAM_1.NAME", "PROPERTY_TEAM_2.NAME",
				"PROPERTY_STRUCTURE_TEAM_1", "PROPERTY_STRUCTURE_TEAM_2", 
				"PROPERTY_GOALS_TEAM_1", "PROPERTY_GOALS_TEAM_2", 
				"PROPERTY_YELLOW_CARDS_TEAM_1", "PROPERTY_YELLOW_CARDS_TEAM_2", 
				"PROPERTY_TWO_YELLOW_CARDS_TEAM_1", "PROPERTY_TWO_YELLOW_CARDS_TEAM_2", 
				"PROPERTY_RED_CARDS_TEAM_1", "PROPERTY_RED_CARDS_TEAM_2", 
				"PROPERTY_AUTO_GOALS_TEAM_1", "PROPERTY_AUTO_GOALS_TEAM_2", 
				"PROPERTY_PENALTY_TEAM_1", "PROPERTY_PENALTY_TEAM_2");
			$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_TOURNAMENT_ID'], "ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y", 
				"SECTION_ID" =>  $_GET['tournament'], "INCLUDE_SUBSECTIONS" => "Y",
				"?NAME" => $_GET["name"], "ID" => $_GET['id'], "PROPERTY_STAGE_VALUE" => $_GET['stage'], 
				">=DATE_ACTIVE_FROM" => ($_GET['datefrom']) ? ConvertDateTime($_GET['datefrom'], "DD.MM.YYYY")." 00:00:00" : "", 
				"<=DATE_ACTIVE_FROM" => ($_GET['dateto']) ? ConvertDateTime($_GET['dateto'], "DD.MM.YYYY")." 23:59:59" : "");
			$res = CIBlockElement::GetList($arOrder, $arFilter, false, array("nPageSize" => 20, "bShowAll" => false,'iNumPage' => $_GET['page']), $arSelect);
			
			// проверяем, чтобы номер страницы не выходил за границы возможных значений
			if($_GET['page'] && $res->NavPageNomer != $_GET['page']) 
			{
				$arResult['RESULT']['message'] = "No matches";
				break;
			}
			
			$arResult['RESULT']['count'] = $res->NavRecordCount;
			$arResult['RESULT']['pages'] = $res->NavPageCount;

			while($ob = $res->GetNext())
			{
				$arR['id'] = $ob['ID'];
				$arR['stage'] = $ob["PROPERTY_STAGE_VALUE"];
				$arR['date'] = date("d.m.Y H:i", strtotime($ob['DATE_ACTIVE_FROM']));
				$arR['name'] = $ob['NAME'];

				// получаем турнир, к которому относится матч и относительно которого производится расчет статистики
				$section = CIBlockElement::GetElementGroups($ob["ID"], true);
				while($arS = $section->GetNext())
				{
					$tournament = $arS["ID"];
					while (true)
					{ 
						if($tournaments[$tournament]['UF_STATISTICS'] == 1) break;
						$tournament = $tournaments[$tournament]['IBLOCK_SECTION_ID'];
					}
					$tournament = array('id' => $tournament, 'name' => $tournaments[$tournament]["NAME"]);
				}

				$arR['tournament'] = $tournament;
				$arR['url'] = 'https://liga-alf.ru/tournament/calendar'.$ob["DETAIL_PAGE_URL"].'&TOURNAMENT='.$tournament['id'];
				$arR['team_1'] = array('id' => $ob['PROPERTY_TEAM_1_VALUE'], 'name' => $ob['PROPERTY_TEAM_1_NAME']);
				$arR['team_2'] = array('id' => $ob['PROPERTY_TEAM_2_VALUE'], 'name' => $ob['PROPERTY_TEAM_2_NAME']);
				if(!empty($ob["PROPERTY_STRUCTURE_TEAM_1_VALUE"]) && !empty($ob["PROPERTY_STRUCTURE_TEAM_2_VALUE"]))
				{
					// счет
					$arR['score_1'] = count($ob["PROPERTY_GOALS_TEAM_1_VALUE"]) + count($ob["PROPERTY_AUTO_GOALS_TEAM_2_VALUE"]);
					$arR['score_2'] = count($ob["PROPERTY_GOALS_TEAM_2_VALUE"]) + count($ob["PROPERTY_AUTO_GOALS_TEAM_1_VALUE"]);

					// состав
					foreach ($ob["PROPERTY_STRUCTURE_TEAM_1_VALUE"] as $key => $value) $arR['structure_1'][$value] = $players[$value];
					foreach ($ob["PROPERTY_STRUCTURE_TEAM_2_VALUE"] as $key => $value) $arR['structure_2'][$value] = $players[$value];

					// кто сколько забил
					if(!empty($ob["PROPERTY_GOALS_TEAM_1_VALUE"])) 
						foreach ($ob["PROPERTY_GOALS_TEAM_1_VALUE"] as $key => $value) $arR['goals_1'][$value] += 1;
					if(!empty($ob["PROPERTY_GOALS_TEAM_2_VALUE"])) 
						foreach ($ob["PROPERTY_GOALS_TEAM_2_VALUE"] as $key => $value) $arR['goals_2'][$value] += 1;
					
					// жк
					if(!empty($ob["PROPERTY_YELLOW_CARDS_TEAM_1_VALUE"])) 
						foreach ($ob["PROPERTY_YELLOW_CARDS_TEAM_1_VALUE"] as $key => $value) $arR['ycards_1'][] = $value;
					if(!empty($ob["PROPERTY_YELLOW_CARDS_TEAM_2_VALUE"]))
						foreach ($ob["PROPERTY_YELLOW_CARDS_TEAM_2_VALUE"] as $key => $value) $arR['ycards_2'][] = $value;

					// 2жк
					if(!empty($ob["PROPERTY_TWO_YELLOW_CARDS_TEAM_1_VALUE"])) 
						foreach ($ob["PROPERTY_TWO_YELLOW_CARDS_TEAM_1_VALUE"] as $key => $value) $arR['2ycards_1'][] = $value;
					if(!empty($ob["PROPERTY_TWO_YELLOW_CARDS_TEAM_2_VALUE"])) 
						foreach ($ob["PROPERTY_TWO_YELLOW_CARDS_TEAM_2_VALUE"] as $key => $value) $arR['2ycards_2'][] = $value;

					// красные карточки
					if(!empty($ob["PROPERTY_RED_CARDS_TEAM_1_VALUE"])) 
						foreach ($ob["PROPERTY_RED_CARDS_TEAM_1_VALUE"] as $key => $value) $arR['rcards_1'][] = $value;
					if(!empty($ob["PROPERTY_RED_CARDS_TEAM_2_VALUE"])) 
						foreach ($ob["PROPERTY_RED_CARDS_TEAM_2_VALUE"] as $key => $value) $arR['rcards_2'][] = $value;

					// автоголы
					if(!empty($ob["PROPERTY_AUTO_GOALS_TEAM_1_VALUE"])) 
						foreach ($ob["PROPERTY_AUTO_GOALS_TEAM_1_VALUE"] as $key => $value) $arR['autogoals_1'][$value] += 1;
					if(!empty($ob["PROPERTY_AUTO_GOALS_TEAM_2_VALUE"])) 
						foreach ($ob["PROPERTY_AUTO_GOALS_TEAM_2_VALUE"] as $key => $value) $arR['autogoals_2'][$value] += 1;

					// пенальти
					if(!empty($ob["PROPERTY_PENALTY_TEAM_1_VALUE"])) 
						foreach ($ob["PROPERTY_PENALTY_TEAM_1_VALUE"] as $key => $value) $arR['penalty_1'][$value] += 1;
					if(!empty($ob["PROPERTY_PENALTY_TEAM_2_VALUE"])) 
						foreach ($ob["PROPERTY_PENALTY_TEAM_2_VALUE"] as $key => $value) $arR['penalty_2'][$value] += 1;
				}
				$arResult['RESULT']['result'][] = $arR;
			}

			// проверяем, удалось ли сформировать результат в соответствии с параметрами
			if(!$arResult['RESULT']['result']) 
			{
				$arResult['RESULT']['message'] = "Matches are not found";
				unset($arResult['RESULT']['count']);
				unset($arResult['RESULT']['pages']);
			}
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