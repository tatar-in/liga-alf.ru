<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Протокол");

$APPLICATION->AddChainItem($APPLICATION->GetTitle() );

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

//Получаем полную информацию по элементу
    $arSelect = Array("ID", "IBLOCK_ID", "NAME",'ACTIVE_FROM', "PROPERTY_STAGE", 
        "PROPERTY_TEAM_1", "PROPERTY_TEAM_2", "PROPERTY_TEAM_1.NAME", "PROPERTY_TEAM_2.NAME", 
        "PROPERTY_STRUCTURE_TEAM_1", "PROPERTY_STRUCTURE_TEAM_2", 
        "PROPERTY_GOALS_TEAM_1", "PROPERTY_GOALS_TEAM_2", 
        "PROPERTY_YELLOW_CARDS_TEAM_1", "PROPERTY_YELLOW_CARDS_TEAM_2", 
        "PROPERTY_TWO_YELLOW_CARDS_TEAM_1", "PROPERTY_TWO_YELLOW_CARDS_TEAM_2", 
        "PROPERTY_RED_CARDS_TEAM_1", "PROPERTY_RED_CARDS_TEAM_2", 
        "PROPERTY_AUTO_GOALS_TEAM_1", "PROPERTY_AUTO_GOALS_TEAM_2", 
        "PROPERTY_PENALTY_TEAM_1", "PROPERTY_PENALTY_TEAM_2", 
        "PROPERTY_TECHNICAL_DEFEAT_TEAM_1", "PROPERTY_TECHNICAL_DEFEAT_TEAM_2");
    $arFilter = Array("IBLOCK_ID"=>"3", "ID" => $_REQUEST["CODE"]);
    $res = CIBlockElement::GetList(Array("ID"=>"ASC"), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext())
    {
        $arResult["ELEMENT"]["ID"] = $ob['ID'];
        $arResult["ELEMENT"]["NAME"] = $ob['NAME'];
        $arResult["ELEMENT"]["DATE"] = $ob['ACTIVE_FROM'];
        $arResult["ELEMENT"]["STAGE"] = array('ID' => $ob['PROPERTY_STAGE_ENUM_ID'], 'NAME' => $ob['PROPERTY_STAGE_VALUE']);
        $arResult["ELEMENT"]["TEAM_1"] = array('ID' => $ob['PROPERTY_TEAM_1_VALUE'], "NAME" => $ob['PROPERTY_TEAM_1_NAME']);
        $arResult["ELEMENT"]["TEAM_2"] = array('ID' => $ob['PROPERTY_TEAM_2_VALUE'], "NAME" => $ob['PROPERTY_TEAM_2_NAME']);
        $arResult["ELEMENT"]["STRUCTURE_1"] = $ob['PROPERTY_STRUCTURE_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["STRUCTURE_2"] = $ob['PROPERTY_STRUCTURE_TEAM_2_VALUE'];
        $arResult["ELEMENT"]["GOALS_1"] = $ob['PROPERTY_GOALS_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["GOALS_2"] = $ob['PROPERTY_GOALS_TEAM_2_VALUE'];
        $arResult["ELEMENT"]["YCARDS_1"] = $ob['PROPERTY_YELLOW_CARDS_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["YCARDS_2"] = $ob['PROPERTY_YELLOW_CARDS_TEAM_2_VALUE'];
        $arResult["ELEMENT"]["2YCARDS_1"] = $ob['PROPERTY_TWO_YELLOW_CARDS_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["2YCARDS_2"] = $ob['PROPERTY_TWO_YELLOW_CARDS_TEAM_2_VALUE'];
        $arResult["ELEMENT"]["RCARDS_1"] = $ob['PROPERTY_RED_CARDS_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["RCARDS_2"] = $ob['PROPERTY_RED_CARDS_TEAM_2_VALUE'];
        $arResult["ELEMENT"]["AUTOGOALS_1"] = $ob['PROPERTY_AUTO_GOALS_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["AUTOGOALS_2"] = $ob['PROPERTY_AUTO_GOALS_TEAM_2_VALUE'];
        $arResult["ELEMENT"]["PENALTY_1"] = $ob['PROPERTY_PENALTY_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["PENALTY_2"] = $ob['PROPERTY_PENALTY_TEAM_2_VALUE'];
        $arResult["ELEMENT"]["TECHNICAL_DEFEAT_1"] = $ob['PROPERTY_TECHNICAL_DEFEAT_TEAM_1_VALUE'];
        $arResult["ELEMENT"]["TECHNICAL_DEFEAT_2"] = $ob['PROPERTY_TECHNICAL_DEFEAT_TEAM_2_VALUE'];
    }



// echo '<pre>';print_r($arResult);echo '</pre>';

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
                <br>
                <p>В случае если по игре назначено техническое поражение, то следует включить соответствующий режим, потянув ползунок под заголовком матча. После этого нужно внести счет у каждой команды.</p>
            </div>
        </div>
    </div>
</div>




<?if(CSite::InGroup( array(1,7) )){?>
	<h3><?$APPLICATION->ShowTitle();?></h3> 
    <h4>
    	<?=$arResult["ELEMENT"]["NAME"];?>
    	(<?=$arResult["ELEMENT"]["STAGE"]["NAME"];?>)
    </h4>
    <form name="protocol" action="/personal/tournament/protocol_result.php" method="POST" enctype="multipart/form-data">
       
        <input type="hidden" name="section_id" value="<?=$_GET["SECTION_ID"];?>" />
        <input type="hidden" name="game_id" value="<?=$arResult['ELEMENT']["ID"];?>" />
        <input type="hidden" name="stage" value="<?=$arResult['ELEMENT']["STAGE"]["ID"];?>" />
        <input type="hidden" name="team_1" value="<?=$arResult['ELEMENT']["TEAM_1"]["ID"];?>" />
        <input type="hidden" name="team_2" value="<?=$arResult['ELEMENT']["TEAM_2"]["ID"];?>" />

        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="technicalDefeat" <?if(isset($arResult["ELEMENT"]["TECHNICAL_DEFEAT_1"]) && isset($arResult["ELEMENT"]["TECHNICAL_DEFEAT_2"])) echo 'value="Y" checked'; else echo 'value="N"';?> onchange="technical_Defeat(this.value)">
            <label class="custom-control-label" for="technicalDefeat">Техническое поражение</label>
        </div>

        <?for ($j=1; $j <= 2; $j++) { ?>
            
            <div class="col-lg-12 px-0">
                <h5 class="my-3 py-1 bg-secondary text-white text-center">
                    <?=$arResult['ELEMENT']["TEAM_$j"]["NAME"];?>
                </h5>
            </div>

            <div class="form-group row <?if(!isset($arResult["ELEMENT"]["TECHNICAL_DEFEAT_1"]) && !isset($arResult["ELEMENT"]["TECHNICAL_DEFEAT_2"])) echo 'd-none';?>" data-defeat="technicalDefeat">
                <label for="technicalDefeat_<?=$j;?>" class="col-auto col-form-label">Голы</label>
                <div class="col-auto">
                    <input id="technicalDefeat_<?=$j;?>" class="form-control" type="number" name="technicalDefeat_<?=$j;?>" max="10" min="0" value="<?=$arResult['ELEMENT']["TECHNICAL_DEFEAT_".$j];?>" placeholder="Введи счет"> 
                </div>
            </div>

        	<div class="table-responsive <?if(isset($arResult["ELEMENT"]["TECHNICAL_DEFEAT_1"]) && isset($arResult["ELEMENT"]["TECHNICAL_DEFEAT_2"])) echo 'd-none';?>">
        		<table class="table table-hover table-sm ">
        			<thead class="thead-light">
        				<tr>
                            <th scope="col" style="min-width:30px">
                                #
                            </th>
        					<th scope="col" style="min-width:300px" width="50%">
                                Состав
        					</th>
                            <th scope="col" style="min-width:100px">
                                Голы
                            </th>
                            <th scope="col" style="min-width:50px" data-toggle="tooltip" data-placement="bottom" title="Желтая карточка за матч">
                                ЖК
                            </th>
                            <th scope="col" style="min-width:50px" data-toggle="tooltip" data-placement="bottom" title="Две желтые карточки за матч">
                                2ЖК
                            </th>
                            <th scope="col" style="min-width:50px" data-toggle="tooltip" data-placement="bottom" title="Прямая красная карточка">
                                КК
                            </th>
                            <th scope="col" style="min-width:100px">
                                Автоголы
                            </th>
                            <th scope="col" style="min-width:100px" data-toggle="tooltip" data-placement="bottom" title="Серия послематчевых пенальти">
                                Пен.
                            </th>
        				</tr>
        			</thead>
          			<tbody>
                        <? $c = count($arResult["ELEMENT"]["STRUCTURE_$j"])< 10 ? 10 : count($arResult["ELEMENT"]["STRUCTURE_$j"]) + 1 ;
                        for ($i=0; $i < 30; $i++) {?> 
                            <tr <?if($i>=$c) echo 'class="d-none team_'.$j.'"';?>>
                                <td>
                                    <?=$i+1;?>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm select2"
                                    		name="team_<?=$j;?>_player[]"
                                    		id="team_<?=$j;?>_player_<?=$i;?>"
                                    		onchange="change_select(this.id,this.value)" 
                                    		style="width:90%;"  >
                                        <option></option>
                                        <option value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i]?>" selected="selected"><?=$arResult["PLAYERS"][$arResult["ELEMENT"]["STRUCTURE_$j"][$i]]["NAME"]?></option>
                                    </select>
                                    <a href="javascript:" id="team_<?=$j;?>_player_<?=$i;?>_clear" onclick="clear_select(this.id)" title="Удалить" class="text-danger">x</a>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" 
                                    		type="number"
                                    		name=""
                                    		id="team_<?=$j;?>_goals_<?=$i;?>"  
                                    		max="50" 
                                    		min="0" 
                                    		data-value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>"
                                    		onchange="change_input(this.id)" 
                                    		value="<?if(count(array_keys($arResult["ELEMENT"]["GOALS_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i]))>0)
	                                    		{
	                                    			echo count(array_keys($arResult["ELEMENT"]["GOALS_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i]));
	                                    		}?>" />
                                    <div class="d-none" id="team_<?=$j;?>_goals_<?=$i;?>_hidden"  >
	                                    <?for ($n=0; $n < count(array_keys($arResult["ELEMENT"]["GOALS_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i])); $n++) 
		                                    { ?>
		                                        <input type="hidden" name="team_<?=$j;?>_goals[]" value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>" />
		                                    <?}?>
	                                </div>

                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" 
                                        		type="checkbox" 
                                        		name="team_<?=$j;?>_yellow_card[]" 
                                        		id="team_<?=$j;?>_yellow_card_<?=$i;?>" 
                                        		value ="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>" 
                                        		<?if(in_array($arResult["ELEMENT"]["STRUCTURE_$j"][$i], $arResult["ELEMENT"]["YCARDS_$j"])) 
	                                        		{
	                                        			echo 'checked' ;
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
												value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>"
                                        		<?if(in_array($arResult["ELEMENT"]["STRUCTURE_$j"][$i], $arResult["ELEMENT"]["2YCARDS_$j"]))
	                                        		{
	                                        			echo 'checked' ;
	                                        		}?>  />
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
                                        		value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>"
                                        		<?if(in_array($arResult["ELEMENT"]["STRUCTURE_$j"][$i], $arResult["ELEMENT"]["RCARDS_$j"])) 
		                                			{
		                                    			echo 'checked' ;
		                                			}?>  />
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
                                    		data-value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>"
                                    		onchange="change_input(this.id)" 
                                    		value="<?if(count(array_keys($arResult["ELEMENT"]["AUTOGOALS_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i]))>0)
	                                    		{
	                                    			echo count(array_keys($arResult["ELEMENT"]["AUTOGOALS_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i]));
	                                    		}?>" />
                                    <div class="d-none" id="team_<?=$j;?>_autogoals_<?=$i;?>_hidden"  >
	                                    <?for ($n=0; $n < count(array_keys($arResult["ELEMENT"]["AUTOGOALS_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i])); $n++) 
		                                    { ?>
		                                        <input type="hidden" name="team_<?=$j;?>_autogoals[]" value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>" />
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
                                    		data-value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>"
                                    		onchange="change_input(this.id)" 
                                    		value="<?if(count(array_keys($arResult["ELEMENT"]["PENALTY_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i]))>0)
	                                    		{
	                                    			echo count(array_keys($arResult["ELEMENT"]["PENALTY_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i]));
	                                    		}?>" />
                                    <div class="d-none" id="team_<?=$j;?>_penalty_<?=$i;?>_hidden"  >
	                                    <?for ($n=0; $n < count(array_keys($arResult["ELEMENT"]["PENALTY_$j"], $arResult["ELEMENT"]["STRUCTURE_$j"][$i])); $n++) 
		                                    { ?>
		                                        <input type="hidden" name="team_<?=$j;?>_penalty[]" value="<?=$arResult["ELEMENT"]["STRUCTURE_$j"][$i];?>" />
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
            // проставляем в input элемента value (значение)
            $("#"+id.replace("player",'goals')).attr("value", "");
            // удаляем скрытые input c голами для передачи post (на случай если игрок был изменен)
            $("#"+id.replace("player",'goals')+"_hidden").empty();

            $("#"+id.replace("player",'autogoals')).attr('data-value', value);
            $("#"+id.replace("player",'autogoals')).attr("value", "");
            $("#"+id.replace("player",'autogoals')+"_hidden").empty();

            $("#"+id.replace("player",'penalty')).attr('data-value', value);
            $("#"+id.replace("player",'penalty')).attr("value", "");
            $("#"+id.replace("player",'penalty')+"_hidden").empty();
            
            $("#"+id.replace("player",'yellow_card')).val(value);
            
            $("#"+id.replace("player",'two_yellow_card')).val(value);
            
            $("#"+id.replace("player",'red_card')).val(value);
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

        //открываем скрытую строку в таблице
        function add_more(team){
            $('tr.'+team).first().removeAttr("class");
        }   

        // обнуляем выбор игрока
        function clear_select(id){
            var text = id.replace("_clear", "");
            $('#'+text).val(null).trigger("change");
            $("#"+text.replace("player",'yellow_card')).removeAttr("checked");
            $("#"+text.replace("player",'two_yellow_card')).removeAttr("checked");
            $("#"+text.replace("player",'red_card')).removeAttr("checked");
        }

        // настраиваем select2 для выбора игроков
        $(".select2").select2({
            ajax: {
                url: "https://liga-alf.ru/api/?action=getplayers",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    if(!isNaN(params.term)){
                        var query = {
                            id: params.term,
                            page: params.page
                        }    
                    }
                    else{
                        var query = {
                            name: params.term,
                            page: params.page
                        }
                    }
                    return query;
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.result,
                        pagination: {
                            more: (params.page * 20) < data.count
                        }
                    };
                },
                cache: true
            },
            language: {
                    "searching": function(){
                        return "Поиск...";
                    },
                    "noResults": function(){
                        return "Нет результатов, удовлетворяющих критериям поиска";
                    },
                    "inputTooShort": function(){
                        return "Для поиска введи 1 или более символов";
                    },
                    loadingMore: function (){
                        return 'Загружаю ещё...';
                    },
                },
            escapeMarkup: function(markup){return markup;}, // пользовательское форматирование
            minimumInputLength: 1,
            templateResult: formatResult,
            templateSelection: formatSelectedResult
        });

        // форматирование найденных элементов в выпадающем списке select2
        function formatResult (result) {
            if (result.loading) {
                return result.name;
            }

            var $container = $(
                "<div class='container'>" +
                    "<div class='row'>" +
                        "<div class='col'>" +
                            "<img src='" + result.picture.medium + "' class='rounded float-left mr-3' />" +
                            result.name +  
                        "</div" +
                    "</div>" +
                "</div>"
            );

            if($container.find("img").attr("src") != "undefined") $container.find("img").attr("width", "100px");

          return $container;
        }

        // форматирование выбранного результата select2
        function formatSelectedResult (result) {
            if (result.id === '') return '<span class="text-black-50">Начни вводить</span>';
            else if(result.selected) return result.text;
            else return result.name || result.id;
        }

        // активация всплывающих подсказок
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        // включает поля технического поражения, отключает другие поля и наоборот
        function technical_Defeat(value){
            if(value=="N"){
                $("input#technicalDefeat").attr("value", "Y").attr("checked", "");
                $("div.table-responsive").addClass("d-none");
                $("div[data-defeat=technicalDefeat]").removeClass("d-none");
            }
            else if(value=="Y"){
                $("input#technicalDefeat").attr("value", "N").removeAttr("checked");
                $("div.table-responsive").removeClass("d-none");
                $("div[data-defeat=technicalDefeat]").addClass("d-none");
            }
        }
    </script>

<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>