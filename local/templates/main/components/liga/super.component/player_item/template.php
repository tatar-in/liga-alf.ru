<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<div class="row">
	<div class="col-sm">
		<table class="table table-sm table-hover">
			<tbody>
				<tr>
					<td>
						Матчи
					</td>
					<td>
						<div class="float-right">
							<?=$arResult["STAT"]["MATCHES"]?>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						Голы
					</td>
					<td>
						<div class="float-right">
							<?=$arResult["STAT"]["GOALS"]?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-sm">
		<table class="table table-sm table-hover">
			<tbody>
				<tr>
					<td>
						Желтые карточки
					</td>
					<td>
						<div class="float-right">
							<?=$arResult["STAT"]["YCARDS"]?>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						Вторые желтые карточки
					</td>
					<td>
						<div class="float-right">
							<?=$arResult["STAT"]["2YCARDS"]?>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						Красные карточки
					</td>
					<td>
						<div class="float-right">
							<?=$arResult["STAT"]["RCARDS"]?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-sm">
		<table class="table table-sm table-hover">
			<tbody>
				<tr>
					<td>
						Автоголы
					</td>
					<td>
						<div class="float-right">
							<?=$arResult["STAT"]["AUTOGOALS"]?>
						</div>
					</td>
				</tr>
				<tr>
					<td data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Голы в серии послематчевых пенальти">
						Пенальти
					</td>
					<td>
						<div class="float-right">
							<?=$arResult["STAT"]["PENALTY"]?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="table-responsive">
	<?if(!empty($arParams["SECTION_ID"])){?>
		<h5 class="my-3">Матчи</h5>
		<table class="table table-hover table-sm ">
			<thead>
				<tr>
					<th scope="col"></th>
					<th scope="col">Турнир</th>
					<th scope="col">Тур</th>
					<th scope="col">Дата</th>
					<th scope="col">Матч</th>
					<th scope="col">Счет</th>
					<th scope="col">Гол</th>
					<th scope="col">ЖК</th>
					<th scope="col">2ЖК</th>
					<th scope="col">КК</th>
					<th scope="col">АГ</th>
					<th scope="col" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Голы в серии послематчевых пенальти">Пен</th>
				</tr>
			</thead>
	  		<tbody>
	  			<?foreach($arResult["MATCHES"] as $key => $value){?>
					<tr>
						<td>
							<?if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
								$file = CFile::ResizeImageGet($arResult["TEAMS"][$value["TEAM_1"]]["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);
							}
							elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
								$file = CFile::ResizeImageGet($arResult["TEAMS"][$value["TEAM_2"]]["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);
							}?>
							<img class="rounded float-left mr-2" src="<?=$file['src']?>">
						</td>
						<td>
							<?foreach($value["SECTION"] as $k => $v){
								$sec = array();
								foreach($arResult["SECTIONS"][$v]["PARENTS"] as $k1 => $v1){
									$sec[$v1["ID"]] = $v1["NAME"];
									if($arResult["ALL_SECTIONS"][$v1["ID"]]["UF_STATISTICS"] == 1) { $id = $v1["ID"]; } 
								}?>
								<div data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?echo implode(' &rarr; ', $sec);?>">
									<?echo $arResult["SECTIONS"][$v]["NAME"];?>
								</div>
							<?}?>
						</td>
						<td>
							<?=$value["STAGE"]?>
						</td>	
						<td>
							<?=date("d.m.Y", strtotime($value["DATE"]))?>
						</td>
						<td>
							<a href="/tournament/calendar<?=$value['URL']?>&SECTION_ID=<?=$value['SECTION'][0]?>">
								<?=$value["NAME"]?>
							</a>
						</td>
						<td>
							<span style="font-weight:500;" class="<?if(count($value['GOALS_1']) == count($value['GOALS_2'])){echo 'text-dark';} 
							elseif((in_array($arParams['PLAYER'], $value['STRUCTURE_1']) && count($value['GOALS_1']) > count($value['GOALS_2'])) || (in_array($arParams['PLAYER'], $value['STRUCTURE_2']) && count($value['GOALS_2']) > count($value['GOALS_1']))){echo 'text-success';} 
							else{echo 'text-danger';}?>">
								<?echo count($value["GOALS_1"]).":".count($value["GOALS_2"]);?>
							</span>
						</td>
						<td>
							<?if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
								echo array_count_values($value["GOALS_1"])[$arParams["PLAYER"]]; 
							}
							elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
								echo array_count_values($value["GOALS_2"])[$arParams["PLAYER"]]; 
							}?>
						</td>
						<td>
							<?if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
								echo array_count_values($value["YCARDS_1"])[$arParams["PLAYER"]]; 
							}
							elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
								echo array_count_values($value["YCARDS_2"])[$arParams["PLAYER"]]; 
							}?>
						</td>
						<td>
							<?if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
								echo array_count_values($value["2YCARDS_1"])[$arParams["PLAYER"]]; 
							}
							elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
								echo array_count_values($value["2YCARDS_2"])[$arParams["PLAYER"]]; 
							}?>
						</td>
						<td>
							<?if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
								echo array_count_values($value["RCARDS_1"])[$arParams["PLAYER"]]; 
							}
							elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
								echo array_count_values($value["RCARDS_2"])[$arParams["PLAYER"]]; 
							}?>
						</td>
						<td>
							<?if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
								echo array_count_values($value["AUTOGOALS_1"])[$arParams["PLAYER"]]; 
							}
							elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
								echo array_count_values($value["AUTOGOALS_2"])[$arParams["PLAYER"]]; 
							}?>
						</td>
						<td>
							<?if(in_array($arParams["PLAYER"], $value["STRUCTURE_1"])){
								echo array_count_values($value["PENALTY_1"])[$arParams["PLAYER"]]; 
							}
							elseif(in_array($arParams["PLAYER"], $value["STRUCTURE_2"])){
								echo array_count_values($value["PENALTY_2"])[$arParams["PLAYER"]];
							}?>
						</td>
					</tr>
	  			<?}?>
			</tbody>
		</table>
	<?}else{?>
		<h5 class="my-3">Турниры</h5>
		<table class="table table-hover table-sm ">
			<thead>
				<tr>
					<th scope="col"></th>
					<th scope="col">Турнир</th>
					<th scope="col">Матчи</th>
					<th scope="col">Голы</th>
					<th scope="col">ЖК</th>
					<th scope="col">2ЖК</th>
					<th scope="col">КК</th>
					<th scope="col">АГ</th>
					<th scope="col" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Голы в серии послематчевых пенальти">Пен</th>
				</tr>
			</thead>
			<tbody>
	  			<?foreach($arResult["SECTIONS"] as $key => $value){?>
					<tr>
						<td>
							<?foreach ($value["TEAM"] as $k => $v) {
								$file = CFile::ResizeImageGet($arResult["TEAMS"][$k]["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
								<img class="rounded float-left mr-2" src="<?=$file['src']?>">
							<?}?>
						</td>
						<td>
							<?$sec = array();
							foreach($value["PARENTS"] as $k => $v){
								$sec[$v["ID"]] = $v["NAME"];
								if($arResult["ALL_SECTIONS"][$v["ID"]]["UF_STATISTICS"] == 1) { $id = $v["ID"]; } 
							}?>
							<a href="/player/?ID=<?=$arParams["PLAYER"]?>&TOURNAMENT=<?=$id?>">
								<?echo implode(" &rarr; ", $sec);?>
							</a>
						</td>
						<td>
							<?=$value["MATCHES"]?>
						</td>
						<td>
							<?echo ($value["GOALS"] != 0) ? $value["GOALS"] : "";?>
						</td>
						<td>
							<?echo ($value["YCARDS"] != 0) ? $value["YCARDS"] : "";?>
						</td>
						<td>
							<?echo ($value["2YCARDS"] != 0) ? $value["2YCARDS"] : "";?>
						</td>
						<td>
							<?echo ($value["RCARDS"] != 0) ? $value["RCARDS"] : "";?>
						</td>
						<td>
							<?echo ($value["AUTOGOALS"] != 0) ? $value["AUTOGOALS"] : "";?>
						</td>
						<td>
							<?echo ($value["PENALTY"] != 0) ? $value["PENALTY"] : "";?>
						</td>
					</tr>
	  			<?}?>
			</tbody>
		</table>
	<?}?>
</div>
<script type="text/javascript">
	// активация всплывающих подсказок
	$(function () {
	  	$('[data-toggle="tooltip"]').tooltip()
	})
</script>