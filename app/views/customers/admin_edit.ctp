<?php header('Cache-Control: no-cache, must-revalidate');?>
<form id='form_detail'>
<input type='hidden' name='data[User][id]' value='<?=@$thisItem['User']['id'];?>'>
<input type='hidden' name='data[UserProfile][id]' value='<?=@$thisItem['UserProfile']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Customer Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<tr>
<td>Email:</td>
<td><input type='text' name='data[User][email]' value='<?=@$thisItem['User']['email'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['email']) ? $errors['email'] : '';?>
</td>
</tr>

<?php 
if (isset($thisItem['User']['id']) && !empty($thisItem['User']['id'])) {
?>
	<tr>
		<td colspan="3">
			<div id="lnk_showfield" class="lnk_showfield">
				Change Password <span id="lnk_expand">+</span>
			</div>
		</td>
	</tr>
	
	<tr class='hidden_fields'>
		<td>New Password:</td>
		<td><input type='password' name='data[User][password]' value='' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['password']) ? $errors['password'] : '';?>
		</td>
  </tr>

	<tr class='hidden_fields'>
		<td>Re-type New Password:</td>
		<td><input type='password' name='data[User][confirm_password]' value='' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['confirm_password']) ? $errors['confirm_password'] : '';?>
		</td>
	</tr>
	
	<script language="javascript">
		var initDisplay = <?=(isset($thisItem['change_psw']) && $thisItem['change_psw'] == 1 ? 'true' : 'false');?>;
		showFields('lnk_showfield', 'hidden_fields', initDisplay);
	</script>
<?php	
} else {
?>
	<tr>
		<td>Password:</td>
		<td><input type='password' name='data[User][password]' value='<?=@$thisItem['User']['password'];?>' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['password']) ? $errors['password'] : '';?>
		</td>
	</tr>

	<tr>
		<td>Re-type Password:</td>
		<td><input type='password' name='data[User][confirm_password]' value='<?=@$thisItem['User']['confirm_password'];?>' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['confirm_password']) ? $errors['confirm_password'] : '';?>
		</td>
	</tr>
<?php
}
?>

<tr>
<td>First Name:</td>
<td><input type='text' name='data[UserProfile][firstname]' value='<?=@$thisItem['UserProfile']['firstname'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['UserProfile']['firstname']) ? $errors['UserProfile']['firstname'] : '';?>
</td>
</tr>

<tr>
<td>Last Name:</td>
<td><input type='text' name='data[UserProfile][lastname]' value='<?=@$thisItem['UserProfile']['lastname'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['UserProfile']['lastname']) ? $errors['UserProfile']['lastname'] : '';?>
</td>
</tr>

<input type='hidden' name='data[User][active]' value=1 />
</table>
</td></tr>
</table>
</form>