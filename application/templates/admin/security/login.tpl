<div id="login_item" align="center">
	<img src="/media/images/eyes_owl_line_tr.png" border="0" />
	<div class="logo"><?=SettingsManager::get('company');?></div>
	<div id="frm" class="content" style="height:auto; min-height:40px; width:294px;">
	<form method="POST">
        <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>
	<br/>
	<p class="errormsg"><?=@$msg;?></p>
	<table id="loginForm" class="form">
		<tr>
			<td align="center">
				Введите эл. почту:
				<input type="text" name="login" value="<?=@$login;?>"/>
			</td>
		</tr>
		<tr>
			<td align="center">
				Введите пароль:
				<input type="password" name="password" value="<?=@$pass;?>"/>
			</td>
		</tr>
	</table>
	<input type="submit" value="Войти" id="signin"/>
	<br/>
	<br/>
	</form>
	</div>
</div>