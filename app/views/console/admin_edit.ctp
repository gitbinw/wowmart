<?php header('Cache-Control: no-cache, must-revalidate');?>
<form id='form_detail' onsubmit='return false'>
<input type='hidden' name='data[AdminMenu][id]' value='<?=@$thisItem['AdminMenu']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Admin Menu Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<tr>
<td>Admin Name:</td>
<td><input type='text' name='data[AdminMenu][name]' value='<?=@$thisItem['AdminMenu']['name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['name']) ? $errors['name'] : '';?>
</td>
</tr>
<tr>
<td>Controller:</td>
<td><input type='text' name='data[AdminMenu][ctrl]' value='<?=@$thisItem['AdminMenu']['ctrl'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['ctrl']) ? $errors['ctrl'] : '';?>
</td>
</tr>
<tr>
<td>Controller Params:</td>
<td><input type='text' name='data[AdminMenu][params]' value='<?=@$thisItem['AdminMenu']['params'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['params']) ? $errors['params'] : '';?>
</td>
</tr>

<tr>
<td valign="top">Priority:</td>
<td><input name="data[AdminMenu][priority]" value='<?=@$thisItem['AdminMenu']['priority'];?>' /><td>
<td class="form_error">
	<?=isset($errors['priority']) ? $errors['priority'] : '';?>
</td>
</tr>

<tr>
<td valign="top">Show Children:</td>
<td>
<select name="data[AdminMenu][showChildren]">
	<option value="0" <?php echo @$thisItem['AdminMenu']['priority'] != 1 ? 'selected' : '';?>>No</option>
    <option value="1" <?php echo @$thisItem['AdminMenu']['priority'] == 1 ? 'selected' : '';?>>Yes</option>
</select>	
</td>
<td class="form_error">
</td>
</tr>

</table>
</td></tr>
</table>
</form>