<?php header('Cache-Control: no-cache, must-revalidate');?>
<form id='form_detail'>
<input type='hidden' name='data[Group][id]' value='<?=@$thisItem['Group']['id'];?>'>
<input type='hidden' name='data[GroupDetail][id]' value='<?=@$thisItem['GroupDetail']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Group Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<tr>
<td>Group Name:</td>
<td><input type='text' name='data[GroupDetail][name]' value='<?=@$thisItem['GroupDetail']['name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['GroupDetail']['name']) ? $errors['GroupDetail']['name'] : '';?>
</td>
</tr>

<tr>
<td valign="top">Permissions:</td>
<td>
<?=$this->element('admin_permissions');?>
<div class='msg_note'></div>
</td>
<td class="form_error" valign="top">
	<?=isset($errors['Permission']) ? $errors['Permission'] : '';?>
</td>
</tr>

<tr>
<td valign="top">Comment:</td>
<td><textarea name="data[GroupDetail][comment]" cols="40" rows="5"><?=@$thisItem['GroupDetail']['comment'];?></textarea></td>
<td class="form_error">
</td>
</tr>
</table>
</td></tr>
</table>
</form>