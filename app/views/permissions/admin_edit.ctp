<?php header('Cache-Control: no-cache, must-revalidate');?>
<form id='form_detail'>
<input type='hidden' name='data[Permission][id]' value='<?=@$thisItem['Permission']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Permission Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<tr>
<td>Permission Name:</td>
<td><input type='text' name='data[Permission][name]' value='<?=@$thisItem['Permission']['name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['name']) ? $errors['name'] : '';?>
</td>
</tr>
<tr>
<td valign="top">Comment:</td>
<td><textarea name="data[Permission][description]" cols="40" rows="5"><?=@$thisItem['Permission']['description'];?></textarea></td>
<td class="form_error">
</td>
</tr>
</table>
</td></tr>
</table>
</form>

