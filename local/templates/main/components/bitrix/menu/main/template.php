<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
// echo "<pre>";print_r($arResult);echo "</pre>";
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark px-0">
    <a class="navbar-brand" href="/">
    	<img src="<?=SITE_TEMPLATE_PATH?>/image/logo-30.png" width="30" height="30" class="d-inline-block align-top">
		Liga ALF
	</a>

 
	<?if (!empty($arResult)):?>
	 	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topMenu" aria-controls="topMenu" aria-expanded="false" aria-label="Toggle navigation">
	    <span class="navbar-toggler-icon"></span>
	  	</button>
		<div class="navbar-collapse collapse" id="topMenu" style="">
			<ul class="navbar-nav mr-auto">

				<?
				foreach($arResult as $arItem):
					if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
						continue;
				?>

					<li class="nav-item <?if($arItem["SELECTED"]) echo 'active ';?> <?if($arItem["IS_PARENT"]) echo 'dropdown';?>">
						<a class="nav-link <?if($arItem["IS_PARENT"]) echo 'dropdown-toggle';?>" 
							href="<?=$arItem["LINK"]?>" 
							<?if($arItem["IS_PARENT"]){?>
								id="navbarDropdown" 
								role="button" 
								data-toggle="dropdown" 
								aria-haspopup="true" 
								aria-expanded="false"
							<?}?>
							>
							<?=$arItem["TEXT"]?>
						</a>
						<?if($arItem["IS_PARENT"]){?>
							<div class="dropdown-menu my-dropdown-menu" aria-labelledby="navbarDropdown">
								<?foreach($arItem["SUBITEMS"] as $item){?>
									<a class="dropdown-item my-dropdown-item" href="<?=$item["LINK"]?>" ><?=$item["TEXT"]?></a>
								<?}?>
							</div>
						<?}?>
					</li>
				<?endforeach?>
			</ul>
			<a class="btn btn-dark" href="/personal/">
				<?if($USER->IsAuthorized()):?>
					<?if($USER->GetFullName()==""){
						echo $USER->GetLogin();
					}
					else{
						echo $USER->GetFirstName()." ".substr($USER->GetLastName(),0,1).".";
					}?>
					</a>	
					<a class="btn btn-dark" href="/?logout=yes">
						Выйти
				<?else:?>
					Авторизация
				<?endif?>
			</a>
		
		</div>
	<?endif?>

</nav>