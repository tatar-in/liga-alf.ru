<?

// echo '<pre>';print_r($aMenuLinks);echo '</pre>';


CModule::IncludeModule("iblock");

$arOrder = array('UF_ARCHIVE' => 'ASC', "SORT" => "ASC", "NAME" => "ASC");
$arFilter = array('IBLOCK_ID' =>"3", "ACTIVE"=>"Y", "DEPTH_LEVEL" => "1"); 
$arSelect = array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "DEPTH_LEVEL", "NAME", "SORT", "ACTIVE", "GLOBAL_ACTIVE", "PICTURE", "SECTION_PAGE_URL", "UF_*");
$rsSect = CIBlockSection::GetList($arOrder, $arFilter, true, $arSelect);
while ($arSect = $rsSect->GetNext())
{
	$arFields[] = $arSect;
}


//echo '<pre>';print_r($arFields);echo '</pre>';


foreach ($arFields as $value) {
	$aMenuLinksExt[] = Array(
		$value['NAME'],
		"/tournament/calendar".$value['SECTION_PAGE_URL'],
		Array(),
		Array(
			"UF_ARCHIVE" => $value['UF_ARCHIVE'],
			"UF_STATISTICS" => $value['UF_STATISTICS'],
		),
		""
	);
}


// echo '<pre>';print_r($aMenuLinksExt);echo '</pre>';


$aMenuLinks=array_merge($aMenuLinksExt,$aMenuLinks);

?>