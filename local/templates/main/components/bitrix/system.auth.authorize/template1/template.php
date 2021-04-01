<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?//echo '$arResult<pre>';print_r($arResult);echo '</pre>';?>
<?//echo '$arParams<pre>';print_r($arParams);echo '</pre>';?>


<div class="bx-authform">
	<?if ($arParams["~AUTH_RESULT"] || $arResult['ERROR_MESSAGE']):?>
		<div class="alert alert-danger" role="alert">
			<?foreach($arResult['ERROR_MESSAGE'] as $key=>$value){
				if($key=='MESSAGE') echo $value;
			}
			foreach($arParams["~AUTH_RESULT"] as $key=>$value){
				if($key=='MESSAGE') echo $value;
			}?>
		</div>
	<?endif?>

	
	<?if($arResult["AUTH_SERVICES"]):?>
		<h3><?echo GetMessage("AUTH_TITLE")?></h3>
	<?endif?>
	<h3><?=GetMessage("AUTH_PLEASE_AUTH")?></h3>

	<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
		
		<?if ($arResult["BACKURL"] <> ''):?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<?endif?>
		
		<?foreach ($arResult["POST"] as $key => $value):?>
			<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?endforeach?>

		<div class="form-group">
			<label for="login" class="col-form-label">
				<?=GetMessage("AUTH_LOGIN")?>
			</label>
	    	<input type="text" name="USER_LOGIN" class="form-control" id="login" value="<?=$arResult["LAST_LOGIN"]?>" maxlength="255" />
		</div>
		<div class="form-group">
			<label for="password" class="col-form-label">
				<?=GetMessage("AUTH_PASSWORD")?>
			</label>
			<input type="password" name="USER_PASSWORD" class="form-control" id="password" maxlength="255" autocomplete="off" aria-describedby="help"/>
				
			<?if($arResult["SECURE_AUTH"]):?>
				<small id="help" class="form-text text-muted">
					<?echo GetMessage("AUTH_SECURE_NOTE")?>
					<noscript>
						<br><?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					</noscript>
				</small>
				<script type="text/javascript">
					document.getElementById('bx_auth_secure').style.display = 'inline-block';
				</script>
			<?endif?>
		</div>
		
		<?if($arResult["CAPTCHA_CODE"]):?>
			<div class="form-group">
				<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			</div>
			<div class="form-group">
				<label for="captcha_word" class="col-form-label">
					<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:
				</label>
				<input class="form-control" type="text" name="captcha_word" id="captcha_word" maxlength="50" value="" size="15" autocomplete="off" />
			</div>
		<?endif;?>
			
		<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
			<div class="form-group">
				<div class="custom-control custom-switch">
					<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" class="custom-control-input" value="Y" />
					<label for="USER_REMEMBER" class="custom-control-label">
						<?=GetMessage("AUTH_REMEMBER_ME")?>
					</label>
				</div>
			</div>
		<?endif?>
			
		<div class="form-group">	
				<input type="submit" class="btn btn-primary btn-lg" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />
		</div>
		

		<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
			<noindex>
				<div class="form-group">
					<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow">
						<?=GetMessage("AUTH_FORGOT_PASSWORD_2")?>
					</a>
				</div>
			</noindex>
		<?endif?>

		<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
			<noindex>
				<div class="form-group">
					<a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow">
						<?=GetMessage("AUTH_REGISTER")?>
					</a>
					<br />
					<?=GetMessage("AUTH_FIRST_ONE")?>
				</div>
			</noindex>
		<?endif?>

	</form>
	

	<script type="text/javascript">
		<?if ($arResult["LAST_LOGIN"] <> ''):?>
			try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
		<?else:?>
			try{document.form_auth.USER_LOGIN.focus();}catch(e){}
		<?endif?>
	</script>

	<?if($arResult["AUTH_SERVICES"]):?>
		<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
			array(
				"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
				"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
				"AUTH_URL" => $arResult["AUTH_URL"],
				"POST" => $arResult["POST"],
				"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
				"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
				"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
			),
			$component,
			array("HIDE_ICONS"=>"Y")
		);
		?>
	<?endif?>
</div>