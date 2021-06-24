<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
// if($USER->IsAdmin())
// {
// 	echo '<pre>'; print_r($arParams); echo '</pre>';
// 	echo '<pre>'; print_r($arResult); echo '</pre>';
// }
?>
	


<?foreach ($arResult["SECTION"] as $key => $secItem) :?>
	<?if($secItem["ELEMENT_CNT"]==0 || $secItem["TYPE"] == 0):?>
		<?if($secItem["ID"]!=$arParams["SECTION_ID"]):?>
			<h4><?=$secItem["NAME"]?></h4>
			<hr style="border-top: 1px solid #000;">
		<?endif;?>
	 	<?continue;?>
	<?elseif ($secItem["TYPE"] == 1):?>
		<?if($secItem["ID"]!=$arParams["SECTION_ID"]):?>
			<h5><?=$secItem["NAME"]?></h5>
		<?endif;?>
		<div class="my-3">
			<a href="javascript:" onclick="columns(<?=$key?>)" data-matches-show-<?=$key?>="0">Показать результаты матчей</a>
		</div>
		<div class="table-responsive mb-3">
			<table class="table table-sm table-hover">
				<thead>
					<tr class="text-muted">
						<th scope="col">№</th>
						<th scope="col">Команда</th>
							<?foreach ($arResult["SECTION"][$key]["TEAMS"] as $value):?>
								<?$file = CFile::ResizeImageGet($arResult["TEAMS"][$value["ID"]]["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
								<th scope="col" class="d-none" data-matches-<?=$key?>="column-hidden">
									<div class="text-center">
										<img class="rounded" src="<?=$file["src"]?>" />
									</div>
								</th>
							<?endforeach;?>
						<th scope="col">И</th>
						<th scope="col">В</th>
						<th scope="col">Н</th>
						<th scope="col">П</th>
						<th scope="col">Мячи</th>
						<th scope="col">О</th>
					</tr>
		  		</thead>
		  		<tbody>
					<?$n=0;
					foreach($arResult["SECTION"][$key]["TEAMS"] as $value):?>
						<?$file = CFile::ResizeImageGet($arResult["TEAMS"][$value["ID"]]["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
						<tr>
							<td class="text-muted">
								<?=++$n;?>
							</td>
							<td class="text-nowrap text-truncate">
								<a href="<?echo substr($arResult["TEAMS"][$value["ID"]]["URL"],1)?>" class="text-reset">
									<img class="rounded float-left mr-2" src="<?=$file["src"]?>" />
									<?echo $arResult["TEAMS"][$value["ID"]]["NAME"]?>
								</a>
							</td>	
							<?foreach ($arResult["SECTION"][$key]["TEAMS"] as $v) :?>
								<td class="text-center d-none" data-matches-<?=$key?>="column-hidden" <?if($v["ID"]==$value["ID"]) echo 'style="background-color:#ececec;" ';?> >
									<?foreach ($arResult["MATCHES"] as $v1){
										if ((($v1["TEAM_1"]==$value["ID"] && $v1["TEAM_2"]==$v["ID"]) || ($v1["TEAM_2"]==$value["ID"] && $v1["TEAM_1"]==$v["ID"])) && !$v1["GOALS_1"] && !$v1["GOALS_2"]  && in_array($key, $v1["SECTION"])) {?>	<?// добавил in_array($key, $v1["SECTION"]) для исправления бага, который отображал в таблицах результаты матчей из других категорий турнира?>
											<a href="/tournament/calendar<?=$v1["URL"]?>&SECTION_ID=<?=$arParams["SECTION_ID"]?>" class="text-reset">
												<?echo " -:- ";?>
											</a>
										<?}
										elseif ($v1["TEAM_1"]==$value["ID"] && $v1["TEAM_2"]==$v["ID"] && in_array($key, $v1["SECTION"])) {?>	<?// добавил in_array($key, $v1["SECTION"]) для исправления бага, который отображал в таблицах результаты матчей из других категорий турнира?>
											<a href="/tournament/calendar<?=$v1["URL"]?>&SECTION_ID=<?=$arParams["SECTION_ID"]?>" class="text-reset">
												<?echo " ".$v1["GOALS_1"].":".$v1["GOALS_2"]." ";?>
											</a>
										<?}
										elseif ($v1["TEAM_2"]==$value["ID"] && $v1["TEAM_1"]==$v["ID"] && in_array($key, $v1["SECTION"])) {?>	<?// добавил in_array($key, $v1["SECTION"]) для исправления бага, который отображал в таблицах результаты матчей из других категорий турнира?>
											<a href="/tournament/calendar<?=$v1["URL"]?>&SECTION_ID=<?=$arParams["SECTION_ID"]?>" class="text-reset">
												<?echo " ".$v1["GOALS_2"].":".$v1["GOALS_1"]." ";?>
											</a>
										<?}
									}?>
								</td>
							<?endforeach;?>
							<td class="font-weight-bolder">
								<?=$value["GAMES"]?>
							</td>
							<td>
								<?=$value["WINS"]?>
							</td>
							<td>
								<?=$value["DRAWS"]?>
							</td>
							<td>
								<?=$value["LOSSES"]?>
							</td>
							<td class="text-nowrap">
								<?=$value["GOALS_SCORED"]."-".$value["GOALS_LOSED"]?>
							</td>
							<td class="font-weight-bolder">
								<?=$value["SCORES"]?>
							</td>
						</tr>
					<?endforeach;?>
				</tbody>
			</table>
		</div>
	<?elseif ($secItem["TYPE"] == 2):?>
		<?//if ($USER->IsAdmin()):?>
			<?if($secItem["ID"]!=$arParams["SECTION_ID"]):?>
				<h5><?=$secItem["NAME"]?></h5>
			<?endif;?>
			<div class="cup mb-3">
				<div class="stage mb-3 font-weight-bolder text-muted">
					<?foreach ($secItem["CUP"] as $value) :?>
						<div class="levels py-3">
							<?=$value["NAME"]?>
						</div>
					<?endforeach;?>
				</div>
				<div class="table-cup mb-3">
					<?foreach ($secItem["CUP"] as $key => $value) :?>
						<div class="column">
							<?foreach($value["GAMES"] as $v):?>
								<div class="match">
			                            <div class="team">
											<div class="name">
												<a href="<?echo substr($arResult["TEAMS"][$v["TEAM_1"]]["URL"],1)?>" class="text-reset">
													<?$file = CFile::ResizeImageGet($arResult["TEAMS"][$v["TEAM_1"]]["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
													<img class="rounded float-left mr-2 ml-1" src="<?=$file["src"]?>" />
													<?=$arResult["TEAMS"][$v["TEAM_1"]]["NAME"]?>  
												</a>
											</div>
											<div class="score">
												<?if($v[0]):?>
													<a class="text-reset" href="/tournament/calendar<?=$arResult["MATCHES"][$v[0]]["URL"]?>&SECTION_ID=<?=$arParams["SECTION_ID"]?>">
														<?if(empty($arResult["MATCHES"][$v[0]]["GOALS_1"]) && empty($arResult["MATCHES"][$v[0]]["GOALS_2"])):?>
															<?= " -:- ";?>
														<?else:?>
															<?=$arResult["MATCHES"][$v[0]]["GOALS_1"].":".$arResult["MATCHES"][$v[0]]["GOALS_2"]?>
														<?endif;?>
													</a>
												<?endif;?>
											</div>
										</div>
										<div class="team">
											<div class="name">
												<a href="<?echo substr($arResult["TEAMS"][$v["TEAM_2"]]["URL"],1)?>" class="text-reset">
													<?$file = CFile::ResizeImageGet($arResult["TEAMS"][$v["TEAM_2"]]["PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
													<img class="rounded float-left mr-2 ml-1" src="<?=$file["src"]?>" />
													<?=$arResult["TEAMS"][$v["TEAM_2"]]["NAME"]?>  
					                            </a>
											</div>
											<div class="score">
												<?if($v[1]):?>
													<a class="text-reset" href="/tournament/calendar<?=$arResult["MATCHES"][$v[1]]["URL"]?>&SECTION_ID=<?=$arParams["SECTION_ID"]?>">
														<?if(empty($arResult["MATCHES"][$v[1]]["GOALS_1"]) && empty($arResult["MATCHES"][$v[1]]["GOALS_2"])):?>
															<?= " -:- ";?>
														<?elseif($v["TEAM_2"] == $arResult["MATCHES"][$v[1]]["TEAM_1"] && $v["TEAM_1"] == $arResult["MATCHES"][$v[1]]["TEAM_2"]):?>
															<?=$arResult["MATCHES"][$v[1]]["GOALS_1"].":".$arResult["MATCHES"][$v[1]]["GOALS_2"]?>
														<?else:?>
															<?=$arResult["MATCHES"][$v[1]]["GOALS_2"].":".$arResult["MATCHES"][$v[1]]["GOALS_1"]?>
														<?endif;?>
													</a>
												<?endif;?>
											</div>
										</div>
								</div>
							<?endforeach;?>
						</div>
					<?endforeach;?>
				</div>

			</div>
		<?//endif;?>
	<?endif;?>
<?endforeach;?>


<script>
	// Открываем/скрываем спрятанные столбцы таблицы
	function columns(sectionId){
		if($("[data-matches-show-"+sectionId+"]").attr("data-matches-show-"+sectionId)=="0"){
			$("[data-matches-"+sectionId+"='column-hidden']").attr("data-matches-"+sectionId, "column-open").removeClass("d-none");	    
			$("[data-matches-show-"+sectionId+"]").attr("data-matches-show-"+sectionId, "1").text("Скрыть результаты матчей");
		}
		else if ($("[data-matches-show-"+sectionId+"]").attr("data-matches-show-"+sectionId)=="1"){
			$("[data-matches-"+sectionId+"='column-open']").attr("data-matches-"+sectionId, "column-hidden").addClass("d-none");	
			$("[data-matches-show-"+sectionId+"]").attr("data-matches-show-"+sectionId, "0").text("Показать результаты матчей");
		}
	} 
</script>



