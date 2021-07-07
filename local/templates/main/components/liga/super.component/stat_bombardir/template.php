<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
// echo '<pre>'; print_r($arResult); echo '</pre>';
?>


<div class="table-responsive">
	<table class="table table-sm table-hover">
		<thead>
		    <tr>
		      <th scope="col">Игрок</th>
		      <th scope="col">Голы</th>
		      <th scope="col">Игры</th>
		    </tr>
  		</thead>
  		<tbody>
			<?foreach($arResult as $value):?>
				<?if($value["GOALS"]>0):?>
					<?$file = CFile::ResizeImageGet($value["PREVIEW_PICTURE"], array('width'=>30, 'height'=>30), BX_RESIZE_IMAGE_EXACT, true);?>
					<tr>
						<td>
							<a href="<?=$value["URL"]."?TOURNAMENT=".$arParams["SECTION_ID"]?>" class="text-reset">
								<img class="rounded float-left mr-2" src="<?=$file["src"]?>" />
								<?echo $value["NAME"]?>
							</a>
							
						</td>
						<td>
							<?=$value["GOALS"]?>
						</td>
						<td>
							<?=$value["GAMES"]?>
						</td>
					</tr>
				<?endif?>
			<?endforeach;?>
		</tbody>
	</table>

</div>
