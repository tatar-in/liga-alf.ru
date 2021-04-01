<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["PHONE_REGISTRATION"])
{
	CJSCore::Init('phone_auth');
}
?>

<div class="bx-authform">
	<?if ($arParams["~AUTH_RESULT"]):?>
		<div class="alert alert-danger" role="alert">
			<?foreach($arParams["~AUTH_RESULT"] as $key=>$value){
				if($key=='MESSAGE') echo $value;
			}?>
		</div>
	<?endif?>


	<?if($arResult["SHOW_FORM"]):?>

		<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform">
			<?if ($arResult["BACKURL"] <> ''): ?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<? endif ?>
			
			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="CHANGE_PWD">
								
			<h3><?=GetMessage("AUTH_CHANGE_PASSWORD")?></h3>
			<?if($arResult["PHONE_REGISTRATION"]):?>
				<div class="form-group ">
					<label for="phone" class="col-form-label">
						<?echo GetMessage("sys_auth_chpass_phone_number")?>
					</label>
					<input type="text" id="phone" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" class="form-control" disabled="disabled" />
					<input type="hidden" name="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" />
				</div>

				<div class="form-group ">
					<label for="USER_CHECKWORD" class="col-form-label">
						<?echo GetMessage("sys_auth_chpass_code")?> *
					</label>
					<input type="text" name="USER_CHECKWORD" id="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="form-control" autocomplete="off" />
				</div>
			<?else:?>
				<div class="form-group">
					<label for="USER_LOGIN" class="col-form-label">
						<?=GetMessage("AUTH_LOGIN")?> *
					</label>
					<input type="text" name="USER_LOGIN" id="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="form-control" />
				</div>
				<?if($arResult["USE_PASSWORD"]):?>
					<div class="form-group">
						<label for="USER_CURRENT_PASSWORD" class="col-form-label">
							<?echo GetMessage("sys_auth_changr_pass_current_pass")?> *
						</label>
						<input type="password" name="USER_CURRENT_PASSWORD" id="USER_CURRENT_PASSWORD" maxlength="255" value="<?=$arResult["USER_CURRENT_PASSWORD"]?>" class="form-control" autocomplete="new-password" />
					</div>
				<?else:?>
					<div class="form-group">
						<label for="USER_CHECKWORD" class="col-form-label">
							<?=GetMessage("AUTH_CHECKWORD")?> *
						</label>
						<input type="text" name="USER_CHECKWORD" id="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="form-control" autocomplete="off" />
					</div>
				<?endif?>
			<?endif?>
			<div class="form-group">
				<label for="USER_PASSWORD" class="col-form-label">
					<?=GetMessage("AUTH_NEW_PASSWORD_REQ")?> *
				</label>
				<input type="password" name="USER_PASSWORD" id="USER_PASSWORD" maxlength="255" value="<?=$arResult["USER_PASSWORD"]?>" class="form-control" autocomplete="new-password" aria-describedby="help"/>
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
			<div class="form-group">
				<label for="USER_CONFIRM_PASSWORD" class="col-form-label">
					<?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?> *
				</label>
				<input type="password" name="USER_CONFIRM_PASSWORD" id="USER_CONFIRM_PASSWORD" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="form-control" autocomplete="new-password" />
			</div>
			<?if($arResult["USE_CAPTCHA"]):?>
				<div class="form-group">
					<label for="" class="col-form-label ">
					</label>
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
				</div>
				<div class="form-group">
					<label for="captcha_word" class="col-form-label ">
						<?echo GetMessage("system_auth_captcha")?> *
					</label>
					<input type="text" name="captcha_word" id="captcha_word" maxlength="50" value="" class="form-control" autocomplete="off" />
				</div>
			<?endif?>
		

			<div class="form-group">	
				<input type="submit" name="change_pwd" class="btn btn-primary btn-lg" value="<?=GetMessage("AUTH_CHANGE")?>" />
			</div>

		</form>

		<div class="form-group">
			<p class="text-muted">
				<?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
				<br>
				* <?=GetMessage("AUTH_REQ")?>
			</p>	
		</div>

		<?if($arResult["PHONE_REGISTRATION"]):?>

			<script type="text/javascript">
				new BX.PhoneAuth({
					containerId: 'bx_chpass_resend',
					errorContainerId: 'bx_chpass_error',
					interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
					data:
						<?=CUtil::PhpToJSObject([
							'signedData' => $arResult["SIGNED_DATA"]
						])?>,
					onError:
						function(response)
						{
							var errorDiv = BX('bx_chpass_error');
							var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
							errorNode.innerHTML = '';
							for(var i = 0; i < response.errors.length; i++)
							{
								errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
							}
							errorDiv.style.display = '';
						}
				});
			</script>

			<div class="alert alert-danger" role="alert" style="display:none">
				<?ShowError("error")?>
			</div>

			<div class="alert alert-warning" role="alert"></div>

		<?endif?>

	<?endif?>

	<div class="form-group ">
		<a href="<?=$arResult["AUTH_AUTH_URL"]?>">
			<?=GetMessage("AUTH_AUTH")?>
		</a>
	</div>

</div>