<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>

<?
// $res = CIBlockSection::GetByID($_GET["SECTION_ID"]);
// if($ar_res = $res->GetNext())
//   echo $ar_res['NAME'];
?>
<?

CModule::IncludeModule("iblock");
$IBLOCK_ID    = 3;
$arFilter    = Array(
      'IBLOCK_ID'=>$IBLOCK_ID, 
      'GLOBAL_ACTIVE'=>'Y');
$obSection    = CIBlockSection::GetTreeList($arFilter);

while($arResult = $obSection->GetNext()){
   for($i=0;$i<=($arResult['DEPTH_LEVEL']-2);$i++)
    echo "..";
     echo $arResult['NAME'].'<br>';
}
?>  

<div class="form-group row">
	<label class="col-sm-2 col-form-label">

	</label>
	<div class="col-sm-10">

	</div>
</div>

<?

// $bs = new CIBlockSection;

// $arPICTURE = $_FILES["PICTURE"];
// $arPICTURE["MODULE_ID"] = "iblock";

// $arFields = Array(
//   "ACTIVE" => $ACTIVE,
//   "IBLOCK_SECTION_ID" => $IBLOCK_SECTION_ID,
//   "IBLOCK_ID" => $IBLOCK_ID,
//   "NAME" => $NAME,
//   "SORT" => $SORT,
//   "PICTURE" => $arPICTURE,
//   "DESCRIPTION" => $DESCRIPTION,
//   "DESCRIPTION_TYPE" => $DESCRIPTION_TYPE
//   );

// if($ID > 0)
// {
//   $res = $bs->Update($ID, $arFields);
// }
// else
// {
//   $ID = $bs->Add($arFields);
//   $res = ($ID>0);
// }
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>