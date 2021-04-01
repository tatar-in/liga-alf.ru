<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);

//echo '<pre>';print_r($arResult);echo '</pre>';



if (!empty($arResult["ERRORS"])):?>
	<div class="alert alert-danger" role="alert">
		<?foreach($arResult['ERRORS'] as $value) {
			echo $value;
			echo '<br>';
		}?>
	</div>
<?endif;
if ($arResult["MESSAGE"] <> ''):?>
	<div class="alert alert-success" role="alert">
		<?=$arResult["MESSAGE"]?>
	</div>
<?endif?>

<h3><?$APPLICATION->ShowTitle();?></h3> 
<h4>
	<?=$arResult["ELEMENT"]["NAME"];?>
	(<?=$arResult["ELEMENT_PROPERTIES"]["18"]["0"]["VALUE_ENUM"];?>,
	<?=str_replace(".","",$arResult["SECTION_LIST"][$arResult["ELEMENT"]["IBLOCK_SECTION"]["0"]["VALUE"]]["VALUE"]);?>)
</h4>
<form name="iblock_add" action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
	<?=bitrix_sessid_post()?>
	<?if ($arParams["MAX_FILE_SIZE"] > 0):?><input type="hidden" name="MAX_FILE_SIZE" value="<?=$arParams["MAX_FILE_SIZE"]?>" /><?endif?>
	
	

	<?if (is_array($arResult["PROPERTY_LIST"]) && !empty($arResult["PROPERTY_LIST"])):?>
	

		<?foreach ($arResult["PROPERTY_LIST"] as $propertyID):?>
			
				<div class="form-group row">
					<?//подсвечиваем название команд в форме /personal/player/protocol.php (ID 4 и 5, выводится до 6 и 7)
					if(($propertyID == "6" || $propertyID == "7") && dirname($_SERVER['REQUEST_URI'])=="/personal/tournament")
					{?>
								<div class="col-lg-12">
									<h5 class="my-3 py-1 bg-secondary text-white text-center">
										<?=$arResult['ELEMENT_PROPERTIES'][$propertyID-2]["0"]["NAME"];?>
									</h5>

								</div>
							</div>
						<div class="form-group row">				
					<?}?>
						<label class="col-lg-2 col-form-label">
							<?if (intval($propertyID) > 0):?>
								<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"]?>
							<?else:?>
								<?=!empty($arParams["CUSTOM_TITLE_".$propertyID]) ? $arParams["CUSTOM_TITLE_".$propertyID] : GetMessage("IBLOCK_FIELD_".$propertyID)?>
							<?endif?>
							<?if(in_array($propertyID, $arResult["PROPERTY_REQUIRED"])):?>
								<span>*</span>
							<?endif?>
						</label>
						<div class="col-lg-10">
							<?
							if (intval($propertyID) > 0)
							{
								if (
									$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "T"
									&&
									$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] == "1"
								)
									$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "S";
								elseif (
									(
										$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "S"
										||
										$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "N"
									)
									&&
									$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"] > "1"
								)
									$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "T";
							}
							elseif (($propertyID == "TAGS") && CModule::IncludeModule('search'))
								$arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] = "TAGS";

							if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y")
							{
								$inputNum = ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0) ? count($arResult["ELEMENT_PROPERTIES"][$propertyID]) : 0;
								$inputNum += $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE_CNT"];
							}
							else
							{
								$inputNum = 1;
							}

							if($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"])
								$INPUT_TYPE = "USER_TYPE";
							else
								$INPUT_TYPE = $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"];

							switch ($INPUT_TYPE):
								case "USER_TYPE":
									for ($i = 0; $i<$inputNum; $i++)
									{
										if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
										{
											$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["~VALUE"] : $arResult["ELEMENT"][$propertyID];
											$description = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["DESCRIPTION"] : "";
										}
										elseif ($i == 0)
										{
											$value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
											$description = "";
										}
										else
										{
											$value = "";
											$description = "";
										}
										echo call_user_func_array($arResult["PROPERTY_LIST_FULL"][$propertyID]["GetPublicEditHTML"],
											array(
												$arResult["PROPERTY_LIST_FULL"][$propertyID],
												array(
													"VALUE" => $value,
													"DESCRIPTION" => $description,
												),
												array(
													"VALUE" => "PROPERTY[".$propertyID."][".$i."][VALUE]",
													"DESCRIPTION" => "PROPERTY[".$propertyID."][".$i."][DESCRIPTION]",
													"FORM_NAME"=>"iblock_add",
												),
											));
									?><?
									}
								break;
								case "TAGS":
									$APPLICATION->IncludeComponent(
										"bitrix:search.tags.input",
										"",
										array(
											"VALUE" => $arResult["ELEMENT"][$propertyID],
											"NAME" => "PROPERTY[".$propertyID."][0]",
											"TEXT" => 'size="'.$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"].'"',
										), null, array("HIDE_ICONS"=>"Y")
									);
								break;
								case "HTML":
									$LHE = new CHTMLEditor;
									$LHE->Show(array(
										'name' => "PROPERTY[".$propertyID."][0]",
										'id' => preg_replace("/[^a-z0-9]/i", '', "PROPERTY[".$propertyID."][0]"),
										'inputName' => "PROPERTY[".$propertyID."][0]",
										'content' => $arResult["ELEMENT"][$propertyID],
										'width' => '100%',
										'minBodyWidth' => 350,
										'normalBodyWidth' => 555,
										'height' => '200',
										'bAllowPhp' => false,
										'limitPhpAccess' => false,
										'autoResize' => true,
										'autoResizeOffset' => 40,
										'useFileDialogs' => false,
										'saveOnBlur' => true,
										'showTaskbars' => false,
										'showNodeNavi' => false,
										'askBeforeUnloadPage' => true,
										'bbCode' => false,
										'siteId' => SITE_ID,
										'controlsMap' => array(
											array('id' => 'Bold', 'compact' => true, 'sort' => 80),
											array('id' => 'Italic', 'compact' => true, 'sort' => 90),
											array('id' => 'Underline', 'compact' => true, 'sort' => 100),
											array('id' => 'Strikeout', 'compact' => true, 'sort' => 110),
											array('id' => 'RemoveFormat', 'compact' => true, 'sort' => 120),
											array('id' => 'Color', 'compact' => true, 'sort' => 130),
											array('id' => 'FontSelector', 'compact' => false, 'sort' => 135),
											array('id' => 'FontSize', 'compact' => false, 'sort' => 140),
											array('separator' => true, 'compact' => false, 'sort' => 145),
											array('id' => 'OrderedList', 'compact' => true, 'sort' => 150),
											array('id' => 'UnorderedList', 'compact' => true, 'sort' => 160),
											array('id' => 'AlignList', 'compact' => false, 'sort' => 190),
											array('separator' => true, 'compact' => false, 'sort' => 200),
											array('id' => 'InsertLink', 'compact' => true, 'sort' => 210),
											array('id' => 'InsertImage', 'compact' => false, 'sort' => 220),
											array('id' => 'InsertVideo', 'compact' => true, 'sort' => 230),
											array('id' => 'InsertTable', 'compact' => false, 'sort' => 250),
											array('separator' => true, 'compact' => false, 'sort' => 290),
											array('id' => 'Fullscreen', 'compact' => false, 'sort' => 310),
											array('id' => 'More', 'compact' => true, 'sort' => 400)
										),
									));
								break;
								case "T":
									for ($i = 0; $i<$inputNum; $i++)
									{

										if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
										{
											$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
										}
										elseif ($i == 0)
										{
											$value = intval($propertyID) > 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];
										}
										else
										{
											$value = "";
										}
									?>
									<textarea class="form-control" cols="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]?>" rows="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"]?>" name="PROPERTY[<?=$propertyID?>][<?=$i?>]"><?=$value?></textarea>
									<?
									}
								break;

								case "S":
								case "N":

									if($propertyID == 8 || $propertyID == 9){?>
										<?/*
										<select class="form-control case-E " name="PROPERTY[<?=$propertyID?>][]" <?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y"?"multiple":""?>>
											
											<?if($arResult["PROPERTY_LIST_FULL"][$propertyID]["LINK_IBLOCK_ID"] == 1){
												if($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "N"){
													echo "<option></option>";
												}
												$fgc=file_get_contents("https://liga-alf.ru/api/players.php");	
												foreach($arResult['ELEMENT_PROPERTIES'][$propertyID] as $key){
													$value = $key["VALUE"];
													$fgc=str_replace('"'.$value.'"','"'.$value.'" selected ',$fgc);
												}
												echo $fgc;		
											}?>

										</select>
										*/?>

										<?
										$fgc=file_get_contents("https://liga-alf.ru/api/players.php");	
										foreach($arResult['ELEMENT_PROPERTIES'][$propertyID] as $key)
										{?>
											<select class="form-control case-E " name="PROPERTY[<?=$propertyID?>][]">
												<option></option>
												<?if($key["VALUE"] != "")
												{
													$value = $key["VALUE"];
													echo str_replace('"'.$value.'"','"'.$value.'" selected ',$fgc);
												}
												else
												{
													echo $fgc;
												}
												?>
											</select>
										<?}
										if($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y"){?>		
											<a href="javascript:" onclick="add_more('select',<?=$propertyID?>,this);">Добавить еще</a>
											<div style="display:none;">
												<select class="form-control case-E " name="PROPERTY[<?=$propertyID?>][]">
													<option></option>
													<?=$fgc;?>
												</select>
											</div>
											<script>
												function add_more(tp,prop,_this){
													
													//$(_this).next().clone().show().insertBefore(_this).find('input[type=file]').attr('name','PROPERTY_FILE_'+prop+'_'+idn).prev().attr('name','PROPERTY['+prop+']['+idn+']');
													
													$(_this).next().clone().show().insertBefore(_this).select2();
												}
												$(function() { setTimeout(function(){$("html, body").scrollTop(0);}, 1000);});
											</script>
										<?}
									}
									else{
										for ($i = 0; $i<$inputNum; $i++)
										{
											if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
											{
												$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
											}
											elseif ($i == 0)
											{
												$value = intval($propertyID) <= 0 ? "" : $arResult["PROPERTY_LIST_FULL"][$propertyID]["DEFAULT_VALUE"];

											}
											else
											{
												$value = "";
											}
											?>
											
											
											<input type="text" class="form-control" id="<?=$propertyID?>_<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["CODE"];?>" name="PROPERTY[<?=$propertyID?>][<?=$i?>]" size="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]; ?>" value="<?=$value?>"/>
											


											<?
											if($arResult["PROPERTY_LIST_FULL"][$propertyID]["USER_TYPE"] == "DateTime"):?><?
												$APPLICATION->IncludeComponent(
													'bitrix:main.calendar',
													'',
													array(
														'FORM_NAME' => 'iblock_add',
														'INPUT_NAME' => "PROPERTY[".$propertyID."][".$i."]",
														'INPUT_VALUE' => $value,

														'HIDE_TIMEBAR' => 'N',
														'SHOW_INPUT' => 'N',
														'SHOW_TIME' => 'Y',
													),
													null,
													array('HIDE_ICONS' => 'Y')
												);
												?><small><?=GetMessage("IBLOCK_FORM_DATE_FORMAT")?><?=FORMAT_DATETIME?></small><?
											endif
											?><?
										}
									}
								break;

								case "F":
									for ($i = 0; $i<$inputNum; $i++)
									{
										$value = intval($propertyID) > 0 ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE"] : $arResult["ELEMENT"][$propertyID];
										?>
										<input type="hidden" name="PROPERTY[<?=$propertyID?>][<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] : $i?>]" value="<?=$value?>" />
										<input class="form-control-file" type="file" size="<?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]?>"  name="PROPERTY_FILE_<?=$propertyID?>_<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] : $i?>" />
										<?

										if (!empty($value) && is_array($arResult["ELEMENT_FILES"][$value]))
										{
											?>
											<div class="custom-control custom-checkbox">
												<input class="custom-control-input" type="checkbox" name="DELETE_FILE[<?=$propertyID?>][<?=$arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] ? $arResult["ELEMENT_PROPERTIES"][$propertyID][$i]["VALUE_ID"] : $i?>]" id="file_delete_<?=$propertyID?>_<?=$i?>" value="Y" />
												<label class="custom-control-label" for="file_delete_<?=$propertyID?>_<?=$i?>">
													<?=GetMessage("IBLOCK_FORM_FILE_DELETE")?>
												</label>
											</div>
											<?

											if ($arResult["ELEMENT_FILES"][$value]["IS_IMAGE"])
											{
												?>
												<img class="img-thumbnail" src="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>" height="<?/*=$arResult["ELEMENT_FILES"][$value]["HEIGHT"]*/?>200" width="<?/*=$arResult["ELEMENT_FILES"][$value]["WIDTH"]*/?>200" border="0" />
												<?
											}
											else
											{
												?>
												<?=GetMessage("IBLOCK_FORM_FILE_NAME")?>: <?=$arResult["ELEMENT_FILES"][$value]["ORIGINAL_NAME"]?>
												<?=GetMessage("IBLOCK_FORM_FILE_SIZE")?>: <?=$arResult["ELEMENT_FILES"][$value]["FILE_SIZE"]?> 
												[<a href="<?=$arResult["ELEMENT_FILES"][$value]["SRC"]?>"><?=GetMessage("IBLOCK_FORM_FILE_DOWNLOAD")?></a>]
												<?
											}
											
										}
									}

								break;
								case "E":
									?>
										

										<select class="form-control case-E" name="PROPERTY[<?=$propertyID?>][]" <?=$arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y"?"multiple":""?>>
											
											<?if($arResult["PROPERTY_LIST_FULL"][$propertyID]["LINK_IBLOCK_ID"] == 1){
												if($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "N"){
													echo "<option></option>";
												}
												$fgc=file_get_contents("https://liga-alf.ru/api/players.php");	
												foreach($arResult['ELEMENT_PROPERTIES'][$propertyID] as $key){
													$value = $key["VALUE"];
													$fgc=str_replace('"'.$value.'"','"'.$value.'" selected ',$fgc);
												}
												echo $fgc;		
											}
											elseif($arResult["PROPERTY_LIST_FULL"][$propertyID]["LINK_IBLOCK_ID"] == 2){
												if($arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "N"){
													echo "<option></option>";
												}
												$fgc=file_get_contents("https://liga-alf.ru/api/teams.php");
												foreach($arResult['ELEMENT_PROPERTIES'][$propertyID] as $key){
													$value = $key["VALUE"];
													$fgc=str_replace('"'.$value.'"','"'.$value.'" selected ',$fgc);
												}
												echo $fgc;				
											}?>

										</select>


										
													
										
									<?
								break;
								case "L":

									if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["LIST_TYPE"] == "C")
										$type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "checkbox" : "radio";
									else
										$type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "multiselect" : "dropdown";

									switch ($type):
										case "checkbox":
										case "radio":
											foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum)
											{
												$checked = false;
												if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
												{
													if (is_array($arResult["ELEMENT_PROPERTIES"][$propertyID]))
													{
														foreach ($arResult["ELEMENT_PROPERTIES"][$propertyID] as $arElEnum)
														{
															if ($arElEnum["VALUE"] == $key)
															{
																$checked = true;
																break;
															}
														}
													}
												}
												else
												{
													if ($arEnum["DEF"] == "Y") $checked = true;
												}

												?>
												<input type="<?=$type?>" name="PROPERTY[<?=$propertyID?>]<?=$type == "checkbox" ? "[".$key."]" : ""?>" value="<?=$key?>" id="property_<?=$key?>"<?=$checked ? " checked=\"checked\"" : ""?> /><label for="property_<?=$key?>"><?=$arEnum["VALUE"]?></label>
												<?
											}
										break;

										case "dropdown":
										case "multiselect":
											?>

											<select  class="form-control case-E" name="PROPERTY[<?=$propertyID?>]<?=$type=="multiselect" ? "[]\" size=\"".$arResult["PROPERTY_LIST_FULL"][$propertyID]["ROW_COUNT"]."\" multiple=\"multiple" : ""?>">
											<option value=""><?echo GetMessage("CT_BIEAF_PROPERTY_VALUE_NA")?></option>
											<?
												if (intval($propertyID) > 0) $sKey = "ELEMENT_PROPERTIES";
												else $sKey = "ELEMENT";

												foreach ($arResult["PROPERTY_LIST_FULL"][$propertyID]["ENUM"] as $key => $arEnum)
												{
													$checked = false;
													if ($arParams["ID"] > 0 || count($arResult["ERRORS"]) > 0)
													{
														foreach ($arResult[$sKey][$propertyID] as $elKey => $arElEnum)
														{
															if ($key == $arElEnum["VALUE"])
															{
																$checked = true;
																break;
															}
														}
													}
													else
													{
														if ($arEnum["DEF"] == "Y") $checked = true;
													}
													?>
													<option value="<?=$key?>" <?= ($checked || ($propertyID=='IBLOCK_SECTION' && $_GET['SECTION_ID']==$key)) ? " selected=\"selected\"" : ""?>><?=$arEnum["VALUE"]?></option>
													<?
												}		
											?>
											</select>
											<?
										break;

									endswitch;
								break;
							endswitch;?>
						</div>

				</div>
		<?endforeach;?>

		<?if($arParams["USE_CAPTCHA"] == "Y" && $arParams["ID"] <= 0):?>
				<div class="form-group">
					<?=GetMessage("IBLOCK_FORM_CAPTCHA_TITLE")?>
					
						<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
				
					<?=GetMessage("IBLOCK_FORM_CAPTCHA_PROMPT")?><span class="starrequired">*</span>:
					<input type="text" name="captcha_word" maxlength="50" value="">
				</div>
		<?endif?>
		
	
	<?endif?>
		<div class="form-group">
			<input class="btn btn-success" type="submit" name="iblock_submit" value="<?=GetMessage("IBLOCK_FORM_SUBMIT")?>" />
			<?if ($arParams["LIST_URL"] <> ''):?>
			<?/*<input type="submit" name="iblock_apply" value="<?=GetMessage("IBLOCK_FORM_APPLY")?>" />*/?>
				<input class="btn btn-outline-secondary"
					type="button"
					name="iblock_cancel"
					value="<? echo GetMessage('IBLOCK_FORM_CANCEL'); ?>"
					onclick="location.href='<? echo CUtil::JSEscape($arParams["LIST_URL"])?>';"
				>
			<?endif?>
		</div>
</form>
<script>

//	$(document).ready(function(){
		// initiate select2
 //$('.select2').select2(); 
 // delegate a click event on the input box
 // $('.select2-input').on('click',function()
 // {
 //   // remove select2-disabled class from all li under the dropdown
 //   $('.select2-drop .select2-results li').removeClass('select2-disabled');
 //    // add select2-result-selectable class to all li which are missing the respective class
 //   $('.select2-drop .select2-results li').each(function()
 //   {
 //     if(!$(this).hasClass('select2-result-selectable'))
 //       $(this).addClass('select2-result-selectable');
 //   });   
 // });
 
 //   // had to include the following code as a hack since the click event required double click on 'select2-input' to invoke the event
 // $('.select2-container-multi').on('mouseover',function()
 // {
 //   $('.select2-input').click();
 // });
// });


	$(document).ready(function(){
		$(".case-E").select2({
			placeholder: "Начни вводить",
			minimumInputLength: 0,
			//allowClear: true,
			language: {
				"searching": function(){
					return "Поиск...";
				},
				"noResults": function(){
					return "Нет результатов удовлетворяющих критериям поиска";
				},
				"inputTooShort": function(){
					return "Для поиска введите 1 или более символов";
				},
			},
		});
		//  $('.select2-input').on('click',function()
 	// 	{
		// 	// remove select2-disabled class from all li under the dropdown
		// 	$('.select2-drop .select2-results li').removeClass('select2-disabled');
		// 	// add select2-result-selectable class to all li which are missing the respective class
		// 	$('.select2-drop .select2-results li').each(function()
		// 	{
		// 		if(!$(this).hasClass('select2-result-selectable'))
		// 		$(this).addClass('select2-result-selectable');
		// 	});   
		// });

		// // had to include the following code as a hack since the click event required double click on 'select2-input' to invoke the event
		// $('.select2-container-multi').on('mouseover',function()
		// {
		// 	$('.select2-input').click();
		// });
	});


	//var id=0;
	// function add_more(tp,prop,_this){
	// 	if(tp=='file'){
	//		idn=id++; 
	//		$(_this).next().clone().show().insertBefore(_this).find('input[type=file]').attr('name','PROPERTY_FILE_'+prop+'_'+idn).prev().attr('name','PROPERTY['+prop+']['+idn+']');
	// 	} else {
	// 		$(_this).next().clone().show().insertBefore(_this);
			
	// 	}
	// }
	// $(function() { setTimeout(function(){$("html, body").scrollTop(0);}, 1000);});
</script>