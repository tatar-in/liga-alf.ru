<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

//ShowMessage($arParams["~AUTH_RESULT"]);
//echo '<pre>';print_r($arParams);echo '</pre>';
?>

<div class="bx-authform">
	<?if ($arParams["~AUTH_RESULT"]['TYPE']=='OK'):?>
		<div class="alert alert-success" role="alert">
			<?= $arParams["~AUTH_RESULT"]['MESSAGE'];?>
		</div>
	<?else:?>
		<?if($arParams["~AUTH_RESULT"]['TYPE']=='ERROR'):?>
			<div class="alert alert-danger" role="alert">
				<?= $arParams["~AUTH_RESULT"]['MESSAGE'];?>
			</div>
		<?endif;?>
		<h3>Восстановление пароля</h3>
		<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
			<?if ($arResult["BACKURL"] <> '')
			{?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?}?>
			
			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="SEND_PWD">

			<?/*<p><?echo GetMessage("sys_forgot_pass_label")?></p>*/?>

			<div class="form-group">
				 <label for="InputEmail">
				 	<?=GetMessage("sys_forgot_pass_login1")?>
			 	</label>
			
				<input type="text" class="form-control" id="InputEmail" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>" />
				<input type="hidden" class="form-control" name="USER_EMAIL" aria-describedby="emailHelp"/>
				
				<small id="emailHelp" class="form-text text-muted"><?echo GetMessage("sys_forgot_pass_note_email")?></small>
			</div>

			<?if($arResult["PHONE_REGISTRATION"]):?>
				<div class="form-group">
					<label for="USER_PHONE_NUMBER">
						<?=GetMessage("sys_forgot_pass_phone")?>
					</label>
					<input type="text" class="form-control" name="USER_PHONE_NUMBER" id="USER_PHONE_NUMBER" value="<?=$arResult["USER_PHONE_NUMBER"]?>" aria-describedby="numberHelp" />
					<small id="numberHelp" class="form-text text-muted">
						<?echo GetMessage("sys_forgot_pass_note_phone")?>
					</small>
				</div>
			<?endif;?>

			<?if($arResult["USE_CAPTCHA"]):?>
				<div class="form-group">
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
				</div>
				<div class="form-group">
					<label for="captcha_word">
						<?echo GetMessage("system_auth_captcha")?>
					</label>
					<input type="text" class="form-control" name="captcha_word" id="captcha_word" maxlength="50" value="" />
				</div>
			<?endif?>

			<div class="form-group">
				<input type="submit" class="btn btn-primary btn-lg" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
			</div>
		</form>
		<div class="form-group">
			<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=GetMessage("AUTH_AUTH")?></a>
		</div>

		<script type="text/javascript">
			document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
			document.bform.USER_LOGIN.focus();
		</script>


	<?endif;?>
</div>