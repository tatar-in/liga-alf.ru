<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<style>
	.nav-tabs .mynav-link {
		border:3px solid transparent;
	}
	.nav-tabs .mynav-link:focus,
	.nav-tabs .mynav-link:hover {
		border-color:#fff #fff #0056b3;
	}
	.nav-tabs .nav-item.show .mynav-link,
	.nav-tabs .mynav-link.active {
		border-color:#fff #fff #495057;
	}
</style>

<ul class="nav nav-tabs mb-3" id="teamTab" role="tablist">
	<li class="nav-item" role="presentation">
		<a class="nav-link mynav-link active" id="structure-tab" data-toggle="tab" href="#structure" role="tab" aria-controls="structure" aria-selected="true">
			Состав
		</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link mynav-link" id="matches-tab" data-toggle="tab" href="#matches" role="tab" aria-controls="matches" aria-selected="false">
			<?if(!empty($arParams["SECTION_ID"])){?>Матчи<?}else{?>Турниры<?}?>
		</a>
	</li>
	<li class="nav-item" role="presentation">
		<a class="nav-link mynav-link" id="statistic-tab" data-toggle="tab" href="#statistic" role="tab" aria-controls="statistic" aria-selected="false">
			Статистика
		</a>
	</li>
</ul>
<div class="tab-content" id="teamTabContent">
	<div class="tab-pane fade show active" id="structure" role="tabpanel" aria-labelledby="structure-tab">
		<div class="table-responsive">
			<table class="table table-hover table-sm ">
				<thead>
					<tr>
						<th scope="col">Игрок</th>
						<th scope="col">Матчи</th>
						<th scope="col">Голы</th>
						<th scope="col">ЖК</th>
						<th scope="col">2ЖК</th>
						<th scope="col">КК</th>
						<th scope="col">АГ</th>
						<th scope="col" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Голы в серии послематчевых пенальти">Пен.</th>
					</tr>
				</thead>
		  		<tbody>
	  				<?foreach($arResult["PLAYERS"] as $key => $value){?>
	  					<tr>
		  					<td>
								<?$file = CFile::ResizeImageGet($value["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
								<a href="<?=$value["URL"]?><?if($arParams["SECTION_ID"]) echo "?TOURNAMENT=".$arParams["SECTION_ID"];?>" class="text-reset">
									<img class="rounded float-left mr-2" src="<?=$file["src"]?>" />
									<?=$value["NAME"]?>
								</a>
							</td>
		  					<td><?=$value["MATCHES"]?></td>
		  					<td><?=$value["GOALS"]?></td>
		  					<td><?=$value["YCARDS"]?></td>
		  					<td><?=$value["2YCARDS"]?></td>
		  					<td><?=$value["RCARDS"]?></td>
		  					<td><?=$value["AUTOGOALS"]?></td>
		  					<td><?=$value["PENALTY"]?></td>
		  				</tr>
					<?}?>
		  		</tbody>
		  	</table>
		</div>
	</div>
	<div class="tab-pane fade" id="matches" role="tabpanel" aria-labelledby="matches-tab">
		<?if($arParams["SECTION_ID"]){?>
			<table class="table table-hover table-sm ">
				<thead>
					<tr>
						<th scope="col">Турнир</th>
						<th scope="col">Тур</th>
						<th scope="col">Дата</th>
						<th scope="col">Время</th>
						<th scope="col">Матч</th>
						<th scope="col">Счет</th>
						<th scope="col">ЖК</th>
						<th scope="col">2ЖК</th>
						<th scope="col">КК</th>
						<th scope="col">АГ</th>
					</tr>
				</thead>
		  		<tbody>
		  			<?foreach($arResult["MATCHES"] as $key => $value){?>
						<tr>
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
								<?=date("H:i", strtotime($value["DATE"]))?>
							</td>
							<td>
								<a href="/tournament/calendar<?=$value['URL']?>&TOURNAMENT=<?=$value['SECTION'][0]?>">
									<?=$value["NAME"]?>
								</a>
							</td>
							<td>
								<?if (!empty($value['STRUCTURE_1']) && !empty($value['STRUCTURE_2'])) {
									$score1 = count($value['GOALS_1']) + count($value['AUTOGOALS_2']) + count($value['PENALTY_1']);
									$score2 = count($value['GOALS_2']) + count($value['AUTOGOALS_1']) + count($value['PENALTY_2']);?>
									<span style="font-weight:500;" class="<?if($score1 == $score2){echo 'text-dark';} 
									elseif(($arParams['TEAM'] == $value['TEAM_1'] && $score1 > $score2) || ($arParams['TEAM'] == $value['TEAM_2'] && $score2 > $score1)){echo 'text-success';} 
									else{echo 'text-danger';}?>">
										<?echo (count($value["GOALS_1"])+count($value["AUTOGOALS_2"])).":".(count($value["GOALS_2"])+count($value["AUTOGOALS_1"]));?>
										<?if(count($value['PENALTY_1'])>0 || count($value['PENALTY_2'])>0) {
											echo " <small>(".count($value['PENALTY_1']).":".count($value['PENALTY_2']).")</small>";
										}?>
									</span>
								<?}
								elseif(($value["TECHNICAL_DEFEAT_1"]=="0" || $value["TECHNICAL_DEFEAT_1"]>0) && ($value["TECHNICAL_DEFEAT_2"]=="0" || $value["TECHNICAL_DEFEAT_2"]>0)){?>
									<span style="font-weight:500;" class="<?if($arParams['TEAM'] == $value['TEAM_1'] && $value["TECHNICAL_DEFEAT_1"] > $value["TECHNICAL_DEFEAT_1"]){echo 'text-success';}else{echo 'text-danger';}?>"> 
										<?echo $value["TECHNICAL_DEFEAT_1"].":".$value["TECHNICAL_DEFEAT_2"]." <small>(т.п.)</small>";?>
									</span>
								<?}?>
							</td>
							<td>
								<?if($arParams["TEAM"] == $value["TEAM_1"] && !empty($value["YCARDS_1"])) echo count($value["YCARDS_1"]); 
								elseif($arParams["TEAM"] == $value["TEAM_2"] && !empty($value["YCARDS_2"])) echo count($value["YCARDS_2"]); ?>
							</td>
							<td>
								<?if($arParams["TEAM"] == $value["TEAM_1"] && !empty($value["2YCARDS_1"])) echo count($value["2YCARDS_1"]); 
								elseif($arParams["TEAM"] == $value["TEAM_2"] && !empty($value["2YCARDS_2"])) echo count($value["2YCARDS_2"]); ?>
							</td>
							<td>
								<?if($arParams["TEAM"] == $value["TEAM_1"] && !empty($value["RCARDS_1"])) echo count($value["RCARDS_1"]); 
								elseif($arParams["TEAM"] == $value["TEAM_2"] && !empty($value["RCARDS_2"])) echo count($value["RCARDS_2"]); ?>
							</td>
							<td>
								<?if($arParams["TEAM"] == $value["TEAM_1"] && !empty($value["AUTOGOALS_1"])) echo count($value["AUTOGOALS_1"]); 
								elseif($arParams["TEAM"] == $value["TEAM_2"] && !empty($value["AUTOGOALS_2"])) echo count($value["AUTOGOALS_2"]); ?>
							</td>
						</tr>
		  			<?}?>
				</tbody>
			</table>
		<?}else{?>
			<table class="table table-hover table-sm ">
				<thead>
					<tr>
						<th scope="col">Турнир</th>
						<th scope="col">Матчи</th>
						<th scope="col">Голы</th>
						<th scope="col">ЖК</th>
						<th scope="col">2ЖК</th>
						<th scope="col">КК</th>
						<th scope="col">АГ</th>
						<th scope="col" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Голы в серии послематчевых пенальти">Пен.</th>
					</tr>
				</thead>
				<tbody>
		  			<?foreach($arResult["SECTIONS"] as $key => $value){?>
						<tr>
							<td>
								<?$sec = array();
								foreach($value["PARENTS"] as $k => $v){
									$sec[$v["ID"]] = $v["NAME"];
									if($arResult["ALL_SECTIONS"][$v["ID"]]["UF_STATISTICS"] == 1) { $id = $v["ID"]; } 
								}?>
								<a href="?TOURNAMENT=<?=$id?>">
									<?echo implode(" &rarr; ", $sec);?>
								</a>
							</td>
							<td>
								<?=$value["MATCHES"]?>
							</td>
							<td>
								<?echo ($value["GOALS_SCORED"] != 0) ? $value["GOALS_SCORED"] : "";?>
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
	<div class="tab-pane fade" id="statistic" role="tabpanel" aria-labelledby="statistic-tab">
		<table class="table table-hover table-sm ">
			<thead>
				<tr>
					<th scope="col"></th>
					<th scope="col">Сумма</th>
					<th scope="col">Среднее</th>
				</tr>
			</thead>
			<tbody>
				<?foreach($arResult["SECTIONS"] as $key => $value){
					$all_matches += $value["MATCHES"];
					$matches += $value["PLAYED_MATCHES"];
					$win += $value["WIN"];
					$deadheat += $value["DEADHEAT"];
					$lose += $value["LOSE"];
					$score += $value["SCORE"];
					$goals_scored += $value["GOALS_SCORED"];
					$goals_missed += $value["GOALS_MISSED"];
					$ycards += $value["YCARDS"];
					$two_ycards += $value["2YCARDS"];
					$rcards += $value["RCARDS"];
					$autogoals += $value["AUTOGOALS"];
					$penalty += $value["PENALTY"];
				}?>
				<tr>
					<td>
						Сыгранные матчи
					</td>
					<td>
						<?echo $matches;?>						
					</td>
					<td>
						<?echo round(($matches / $all_matches ) * 100, 0)."%";?>
					</td>
				</tr>
				<tr>
					<td>
						Победы
					</td>
					<td>
						<?echo $win;?>
					</td>
					<td>
						<?echo round(($win / $matches) * 100, 0)."%";?>
					</td>
				</tr>
				<tr>
					<td>
						Ничьи
					</td>
					<td>
						<?echo $deadheat;?>
					</td>
					<td>
						<?echo round(($deadheat / $matches) * 100, 0)."%";?>
					</td>
				</tr>
				<tr>
					<td>
						Поражения
					</td>
					<td>
						<?echo $lose;?>
					</td>
					<td>
						<?echo round(($lose / $matches) * 100, 0)."%";?>
					</td>
				</tr>
				<tr>
					<td>
						Набранные очки
					</td>
					<td>
						<?echo $score;?>
					</td>
					<td>
						<?echo round(($score / ($matches * 3)) * 100, 0)."%";?>
					</td>
				</tr>
				<tr>
					<td>
						Забитые мячи
					</td>
					<td>
						<?echo $goals_scored;?>
					</td>
					<td>
						<?echo round($goals_scored / $matches, 2);?>
					</td>
				</tr>
				<tr>
					<td>
						Пропущенные мячи
					</td>
					<td>
						<?echo $goals_missed;?>
					</td>
					<td>
						<?echo round($goals_missed / $matches, 2);?>
					</td>
				</tr>
				<tr>
					<td>
						Разность мячей
					</td>
					<td>
						<?echo $goals_scored - $goals_missed;?>
					</td>
					<td>
						<?echo round(($goals_scored - $goals_missed) / $matches, 2);?>
					</td>
				</tr>
				<tr>
					<td>
						Желтые карточки
					</td>
					<td>
						<?echo $ycards;?>
					</td>
					<td>
						<?echo round($ycards / $matches, 2);?>
					</td>
				</tr>
				<tr>
					<td>
						Вторые желтые карточки
					</td>
					<td>
						<?echo $two_ycards;?>
					</td>
					<td>
						<?echo round($two_ycards / $matches, 2);?>
					</td>
				</tr>
				<tr>
					<td>
						Красные карточки
					</td>
					<td>
						<?echo $rcards;?>
					</td>
					<td>
						<?echo round($rcards / $matches, 2);?>
					</td>
				</tr>
				<tr>
					<td>
						Автоголы
					</td>
					<td>
						<?echo $autogoals;?>
					</td>
					<td>
						<?echo round($autogoals / $matches, 2);?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>




	
<script type="text/javascript">
	// активация всплывающих подсказок
	$(function () {
	  	$('[data-toggle="tooltip"]').tooltip()
	})
</script>