<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Протокол");

$APPLICATION->AddChainItem($APPLICATION->GetTitle() );

//Подключаем модуль инфоблоков
CModule::IncludeModule('iblock');

//Получаем базу игроков
    $arSelect = Array("ID", "NAME",'PREVIEW_PICTURE');
    $arFilter = Array("IBLOCK_ID"=>"1");
    $res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext())
    {
    	$arResult["PLAYERS"][$ob['ID']]["ID"] = $ob['ID'];
    	$arResult["PLAYERS"][$ob['ID']]["NAME"] = $ob['NAME'];
    	$arResult["PLAYERS"][$ob['ID']]["IMG"] = CFile::GetPath($ob['PREVIEW_PICTURE']);
    }

//Получаем базу команд 
    $arSelect = Array("ID", "NAME",'PREVIEW_PICTURE');
    $arFilter = Array("IBLOCK_ID"=>"2");
    $res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext())
    {
    	$arResult["TEAMS"][$ob['ID']]["ID"] = $ob['ID'];
    	$arResult["TEAMS"][$ob['ID']]["NAME"] = $ob['NAME'];
    	$arResult["TEAMS"][$ob['ID']]["IMG"] = CFile::GetPath($ob['PREVIEW_PICTURE']);
    }

//Получаем получаем полную информацию по элементу
    $arSelect = Array("ID", "IBLOCK_ID", "NAME",'ACTIVE_FROM', "PROPERTY_*");
    $arFilter = Array("IBLOCK_ID"=>"3", "ID" => $_REQUEST["CODE"]);
    $res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement())
    {
    	$arResult["ELEMENT"]["FIELDS"] = $ob->GetFields();
    	$arResult["ELEMENT"]["PROPERTIES"] = $ob->GetProperties();
    }


//echo '<pre>';print_r($arResult);echo '</pre>';

?>
 

<div class="accordion mb-3" id="accordion">
    <div class="card">
        <div class="card-header py-0" id="headingOne">
            <h5 class="mb-0">
                &#9888; 
                <button class="btn btn-link collapsed text-reset" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    Справка по заполнению 
                </button>
            </h5>
        </div>

        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <p>При заполнении следует вносить сразу составы обеих команд. Иначе не будут сохранены данные.</p>
                <p><b>ЖК</b> - игроком получена 1 желтая карточка за матч<br>
                <b>2ЖК</b> - игроком получена 2 желтые карточки за матч<br>
                <b>КК</b> - игроком получена прямая красная карточка. Не следует ставить эту галочку, если игроком сначала получена 2 желтые карточки, а потом дана красная карточка<br>
                <b>Пенальти</b> - подразумевается серия послематчевых пенальти. Голы, забитые в серии пенальти, не учитывается в расчете бомбардиров</p> 
            </div>
        </div>
    </div>
</div>




<?if(CSite::InGroup( array(1,7) )){?>
	<h3><?$APPLICATION->ShowTitle();?></h3> 
<h4>
	<?=$arResult["ELEMENT"]["FIELDS"]["NAME"];?>
	(<?=$arResult["ELEMENT"]["PROPERTIES"]["STAGE"]["VALUE"];?>)
</h4>
    <form name="protocol" action="/personal/tournament/protocol_result.php" method="POST" enctype="multipart/form-data">
       
        <input type="hidden" name="section_id" value="<?=$_GET["SECTION_ID"];?>" />
        <input type="hidden" name="game_id" value="<?=$arResult['ELEMENT']["FIELDS"]["ID"];?>" />
        <input type="hidden" name="stage" value="<?=$arResult['ELEMENT']["PROPERTIES"]["STAGE"]["VALUE_ENUM_ID"];?>" />
        <input type="hidden" name="team_1" value="<?=$arResult['ELEMENT']["PROPERTIES"]["TEAM_1"]["VALUE"];?>" />
        <input type="hidden" name="team_2" value="<?=$arResult['ELEMENT']["PROPERTIES"]["TEAM_2"]["VALUE"];?>" />

        <?for ($j=1; $j <= 2; $j++) { ?>
            
            <div class="col-lg-12 px-0">
                <h5 class="my-3 py-1 bg-secondary text-white text-center">
                    <?=$arResult["TEAMS"][$arResult['ELEMENT']["PROPERTIES"]["TEAM_$j"]["VALUE"]]["NAME"];?>
                </h5>
            </div>
        	<div class="table-responsive">
        		<table class="table table-hover table-sm ">
        			<thead class="thead-light">
        				<tr>
                            <th scope="col">
                                #
                            </th>
        					<th scope="col">
                                Состав
        					</th>
                            <th scope="col">
                                Голы
                            </th>
                            <th scope="col"  data-toggle="tooltip" data-placement="bottom" title="Желтая карточка за матч">
                                ЖК
                            </th>
                            <th scope="col"  data-toggle="tooltip" data-placement="bottom" title="Две желтые карточки за матч">
                                2ЖК
                            </th>
                            <th scope="col"  data-toggle="tooltip" data-placement="bottom" title="Прямая красная карточка">
                                КК
                            </th>
                            <th scope="col">
                                Автоголы
                            </th>
                            <th scope="col"  data-toggle="tooltip" data-placement="bottom" title="Серия послематчевых пенальти">
                                Пенальти
                            </th>
        				</tr>
        			</thead>
          			<tbody>
                        <? $c = count($arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"])< 10 ? 10 : count($arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"]) + 1 ;
                        for ($i=0; $i <= $c; $i++) {?> 
                            <tr <?if($i==$c) echo 'class="d-none team_'.$j.'"';?>>
                                <td>
                                    <?=$i+1;?>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm <?if($i!=$c) echo 'select2';?>"
                                    		name="team_<?=$j;?>_player[]"
                                    		id="team_<?=$j;?>_player_<?=$i;?>"
                                    		onchange="change_select(this.id,this.value)" 
                                    		style="width:100%;"  >
                                        <option></option>
                                        <?foreach ($arResult["PLAYERS"] as $key => $value) 
	                                        {?>
	                                            <option value="<?=$key;?>" 
	                                            		<?if($arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]==$key) echo "selected";?> >
	                                                <?=$value["NAME"];?>
	                                            </option>';
	                                        <?}?>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" 
                                    		type="number"
                                    		name=""
                                    		id="team_<?=$j;?>_goals_<?=$i;?>"  
                                    		max="50" 
                                    		min="0" 
                                    		data-value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>"
                                    		onchange="change_input(this.id)" 
                                    		value="<?if(count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["GOALS_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]))>0)
	                                    		{
	                                    			echo count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["GOALS_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]));
	                                    		}?>"  
                                    		<?if(!$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]) 
	                                    		{
	                                    			echo "disabled";
	                                    		}?>  />
                                    <div class="d-none" id="team_<?=$j;?>_goals_<?=$i;?>_hidden"  >
	                                    <?for ($n=0; $n < count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["GOALS_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i])); $n++) 
		                                    { ?>
		                                        <input type="hidden" name="team_<?=$j;?>_goals[]" value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>" />
		                                    <?}?>
	                                </div>

                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" 
                                        		type="checkbox" 
                                        		name="team_<?=$j;?>_yellow_card[]" 
                                        		id="team_<?=$j;?>_yellow_card_<?=$i;?>" 
                                        		value ="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>" 
                                        		<?if(in_array($arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i], $arResult["ELEMENT"]["PROPERTIES"]["YELLOW_CARDS_TEAM_$j"]["VALUE"])) 
	                                        		{
	                                        			echo 'checked' ;
	                                        		}?>  
                                        		<?if(!$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]) 
	                                        		{
	                                        			echo "disabled";
	                                        		}?> />
                                        <label class="custom-control-label" for="team_<?=$j;?>_yellow_card_<?=$i;?>">
                                            
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" 
                                        		type="checkbox" 
                                        		name="team_<?=$j;?>_two_yellow_card[]" 
                                        		id="team_<?=$j;?>_two_yellow_card_<?=$i;?>" 
												value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>"
                                        		<?if(in_array($arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i], $arResult["ELEMENT"]["PROPERTIES"]["TWO_YELLOW_CARDS_TEAM_$j"]["VALUE"])) 
	                                        		{
	                                        			echo 'checked' ;
	                                        		}?>  
                                        		<?if(!$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]) 
	                                        		{
	                                        			echo "disabled";
	                                        		}?> />
                                        <label class="custom-control-label" for="team_<?=$j;?>_two_yellow_card_<?=$i;?>">
                                             
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" 
                                        		type="checkbox" 
                                        		name="team_<?=$j;?>_red_card[]" 
                                        		id="team_<?=$j;?>_red_card_<?=$i;?>" 
                                        		value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>"
                                        		<?if(in_array($arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i], $arResult["ELEMENT"]["PROPERTIES"]["RED_CARDS_TEAM_$j"]["VALUE"])) 
		                                			{
		                                    			echo 'checked' ;
		                                			}?> 
                                    			<?if(!$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]) 
	                                    			{
	                                    				echo "disabled";
	                                    			}?> />
                                        <label class="custom-control-label" for="team_<?=$j;?>_red_card_<?=$i;?>">
                                                           
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" 
                                    		type="number"
                                    		name=""
                                    		id="team_<?=$j;?>_autogoals_<?=$i;?>"  
                                    		max="50" 
                                    		min="0" 
                                    		data-value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>"
                                    		onchange="change_input(this.id)" 
                                    		value="<?if(count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["AUTO_GOALS_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]))>0)
	                                    		{
	                                    			echo count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["AUTO_GOALS_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]));
	                                    		}?>"  
                                    		<?if(!$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]) 
	                                    		{
	                                    			echo "disabled";
	                                    		}?>  />
                                    <div class="d-none" id="team_<?=$j;?>_autogoals_<?=$i;?>_hidden"  >
	                                    <?for ($n=0; $n < count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["AUTO_GOALS_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i])); $n++) 
		                                    { ?>
		                                        <input type="hidden" name="team_<?=$j;?>_autogoals[]" value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>" />
		                                    <?}?>
	                                </div>

                                </td>
                                <td>
                                    <input class="form-control form-control-sm" 
                                    		type="number"
                                    		name=""
                                    		id="team_<?=$j;?>_penalty_<?=$i;?>"  
                                    		max="50" 
                                    		min="0" 
                                    		data-value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>"
                                    		onchange="change_input(this.id)" 
                                    		value="<?if(count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["PENALTY_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]))>0)
	                                    		{
	                                    			echo count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["PENALTY_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]));
	                                    		}?>"  
                                    		<?if(!$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i]) 
	                                    		{
	                                    			echo "disabled";
	                                    		}?>  />
                                    <div class="d-none" id="team_<?=$j;?>_penalty_<?=$i;?>_hidden"  >
	                                    <?for ($n=0; $n < count(array_keys($arResult["ELEMENT"]["PROPERTIES"]["PENALTY_TEAM_$j"]["VALUE"], $arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i])); $n++) 
		                                    { ?>
		                                        <input type="hidden" name="team_<?=$j;?>_penalty[]" value="<?=$arResult["ELEMENT"]["PROPERTIES"]["STRUCTURE_TEAM_$j"]["VALUE"][$i];?>" />
		                                    <?}?>
	                                </div>

                                </td>
                            </tr>
                                
                        <?}?>
            				
          			</tbody>
          		</table>
                <div class="mb-3">
	                <a href="javascript:" onclick="add_more('team_<?=$j;?>')">Добавить строку</a>
	            </div>
          	</div>

        <?}?>
    	<div class="form-group">
			<input class="btn btn-success" type="submit" name="protocol_submit" value="Сохранить" />
			<input class="btn btn-outline-secondary" type="button" name="protocol_cancel" value="Отмена" onclick="location.href= '<?echo CUtil::JSEscape("/personal/tournament/?SECTION_ID=".$_GET['SECTION_ID'])?>' "	>
		</div>
    </form>


    <script>
        //при выборе игрока в select проставляем id игрока в value элементов и разрешаем изменение элементов
        function change_select(id, value){
        	// меняем в id слова и ищем этот id в коде, проставляем у него data-value
            $("#"+id.replace("player",'goals')).attr('data-value', value);
            // убираем абритут не активности элемента (т.к. выбран игрок)
            $("#"+id.replace("player",'goals')).removeAttr("disabled");
            // проставляем в input элемента value (значение)
            $("#"+id.replace("player",'goals')).attr("value", "");
            // удаляем скрытые input c голами для передачи post (на случай если игрок был изменен)
            $("#"+id.replace("player",'goals')+"_hidden").empty();

            $("#"+id.replace("player",'autogoals')).attr('data-value', value);
            $("#"+id.replace("player",'autogoals')).removeAttr("disabled");
            $("#"+id.replace("player",'autogoals')).attr("value", "");
            $("#"+id.replace("player",'autogoals')+"_hidden").empty();

            $("#"+id.replace("player",'penalty')).attr('data-value', value);
            $("#"+id.replace("player",'penalty')).removeAttr("disabled");
            $("#"+id.replace("player",'penalty')).attr("value", "");
            $("#"+id.replace("player",'penalty')+"_hidden").empty();
            
            $("#"+id.replace("player",'yellow_card')).val(value);
            $("#"+id.replace("player",'yellow_card')).removeAttr("disabled");
            
            $("#"+id.replace("player",'two_yellow_card')).val(value);
            $("#"+id.replace("player",'two_yellow_card')).removeAttr("disabled");
            
            $("#"+id.replace("player",'red_card')).val(value);
            $("#"+id.replace("player",'red_card')).removeAttr("disabled");
        }

        //при вводе голов добавляем скрытые input с голами для передачи post
        function change_input(id){
        	// обнуляем скрытый div со скрытыми input
        	$("#"+id+"_hidden").empty();
        	// проверяем наличие data-value (что выбран игрок)
        	if ($("#"+id).attr("data-value")>0) {
        		// создаем скрытый input для post, обрезаем name и вставляем нужное value
    			var str = '<input type="hidden" name="'+id.substr(0,id.lastIndexOf("_"))+'[]" value="'+$("#"+id).attr("data-value")+'" />';
				// вставляем в скрытый div , дублируя необходимое количество раз
				$("#"+id+"_hidden").append(str.repeat($("#"+id).val()));
        	}
        }


        //добавляем строки в таблицу
        function add_more(team){
            
            //вставляем копию скрытой строки
            $('.'+team).clone().show().removeAttr("class").insertBefore('.'+team);
            
            //меняем атрибуты у скрытой строки
            var num = Number.parseInt($('.'+team).find("td:first").text());
            
            $('.'+team).find("td:first").text((num+1).toString());
            
            $('.'+team).find("select").attr('id',team+"_player_"+num).trigger("change");

            $('.'+team).find("#"+team+"_goals_"+(num-1)).attr('id',team+"_goals_"+num);
            $('.'+team).find("#"+team+"_goals_"+(num-1)+"_hidden").attr('id',team+"_goals_"+num+"_hidden");

            $('.'+team).find("#"+team+"_yellow_card_"+(num-1)).attr('id',team+"_yellow_card_"+num);
            $('.'+team).find("label[for="+team+"_yellow_card_"+(num-1)+"]").attr('for',team+"_yellow_card_"+num);
            
            $('.'+team).find("#"+team+"_two_yellow_card_"+(num-1)).attr('id',team+"_two_yellow_card_"+num);
            $('.'+team).find("label[for="+team+"_two_yellow_card_"+(num-1)+"]").attr('for',team+"_two_yellow_card_"+num);
            
            $('.'+team).find("#"+team+"_red_card_"+(num-1)).attr('id',team+"_red_card_"+num);
            $('.'+team).find("label[for="+team+"_red_card_"+(num-1)+"]").attr('for',team+"_red_card_"+num);

            $('.'+team).find("#"+team+"_autogoals_"+(num-1)).attr('id',team+"_autogoals_"+num);
            $('.'+team).find("#"+team+"_autogoals_"+(num-1)+"_hidden").attr('id',team+"_autogoals_"+num+"_hidden");

            $('.'+team).find("#"+team+"_penalty_"+(num-1)).attr('id',team+"_penalty_"+num);
            $('.'+team).find("#"+team+"_penalty_"+(num-1)+"_hidden").attr('id',team+"_penalty_"+num+"_hidden");
			
			//Добавляем класс и иницируем select2
            $("#"+team+"_player_"+(num-1)).addClass("select2").select2(
            {
                placeholder: "Начни вводить",
                minimumInputLength: 1,
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
        }   


        $(document).ready(function(){
            $(".select2").select2({
                placeholder: "Начни вводить",
                minimumInputLength: 1,
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
        });


        // активация всплывающих подсказок
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>