<?php header('Cache-Control: no-cache, must-revalidate');?>
<form id='form_detail'>
<input type='hidden' name='data[Store][id]' value='<?=@$thisItem['Store']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Store Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">

<tr>
<td>Store Name:</td>
<td><input type='text' name='data[Store][company]' value='<?=@$thisItem['Store']['company'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['company']) ? $errors['company'] : '';?>
</td>
</tr>

<tr>
<td>Alias:</td>
<td><input type='text' name='data[Store][alias]' value='<?=@$thisItem['Store']['alias'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['alias']) ? $errors['alias'] : '';?>
</td>
</tr>

<tr>
<td>First Name:</td>
<td><input type='text' name='data[Store][firstname]' value='<?=@$thisItem['Store']['firstname'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['firstname']) ? $errors['firstname'] : '';?>
</td>
</tr>

<tr>
<td>Last Name:</td>
<td><input type='text' name='data[Store][lastname]' value='<?=@$thisItem['Store']['lastname'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['lastname']) ? $errors['lastname'] : '';?>
</td>
</tr>

<tr>
<td>Address:</td>
<td><input type='text' name='data[Store][address]' value='<?=@$thisItem['Store']['address'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>Suburb:</td>
<td><input type='text' name='data[Store][suburb]' value='<?=@$thisItem['Store']['suburb'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>State:</td>
<td><input type='text' name='data[Store][state]' value='<?=@$thisItem['Store']['state'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>Postcode:</td>
<td><input type='text' name='data[Store][postcode]' value='<?=@$thisItem['Store']['postcode'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>Country:</td>
<td><input type='text' name='data[Store][country]' value='<?=@$thisItem['Store']['country'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>Phone:</td>
<td><input type='text' name='data[Store][phone]' value='<?=@$thisItem['Store']['phone'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>Fax:</td>
<td><input type='text' name='data[Store][fax]' value='<?=@$thisItem['Store']['fax'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>ABN:</td>
<td><input type='text' name='data[Store][abn]' value='<?=@$thisItem['Store']['abn'];?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>GST:</td>
<td><input type='text' name='data[Store][gst]' value='<?=!empty($thisItem['Store']['gst']) ? $thisItem['Store']['gst'] : 10;?>'>
</td>
<td class="form_error">
</td>
</tr>

<tr>
<td>Website:</td>
<td><input type='text' name='data[Store][web]' value='<?=@$thisItem['Store']['web'];?>'>
</td>
<td class="form_error">
</td>
</tr>

</table>
</td></tr>
</table>
</form>

