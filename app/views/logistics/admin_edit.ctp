<?php 
header('Cache-Control: no-cache, must-revalidate');

$logiTypeOptions = '';
if (isset($SHIPPING_TYPES)) {
	foreach($SHIPPING_TYPES as $type) {
		$checked = isset($thisItem['LogisticsCompany']['logi_type']) && $thisItem['LogisticsCompany']['logi_type'] == $type['id'] ? 
					'selected' : '';

		$logiTypeOptions .= '<option value="' . $type['id'] . '" ' . $checked . '>' . 
								$type['name'] . '</option>';
	}
}

$logiUnitOptions = '<option value="">Leave blank</option>';
if (isset($SHIPPING_UNITS)) {
	foreach($SHIPPING_UNITS as $unit) {
		$checked = isset($thisItem['LogisticsCompany']['logi_unit']) && $thisItem['LogisticsCompany']['logi_unit'] == $unit['id'] ? 
					'selected' : '';

		$logiUnitOptions .= '<option value="' . $unit['id'] . '" ' . $checked . '>' . 
								$unit['name'] . '</option>';
	}
}
?>
<form id='form_detail' onsubmit='return false'>
<input type='hidden' name='data[LogisticsCompany][id]' value='<?=@$thisItem['LogisticsCompany']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Shipping Company Detail Form</td></tr>
<tr><td align='center'>

<table cellspacing='2' cellpadding='0' class="reg_table">

<tr>
<td>Company Name:</td>
<td><input type='text' name='data[LogisticsCompany][logi_company]' value='<?=@$thisItem['LogisticsCompany']['logi_company'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['logi_company']) ? $errors['logi_company'] : '';?>
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Shipping Type:</td>
<td>
<select name="data[LogisticsCompany][logi_type]"><?=$logiTypeOptions;?></select>
</td>
<td class="form_error">
	<?=isset($errors['logi_type']) ? $errors['logi_type'] : '';?>
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Shipping Unit:</td>
<td>
<select name="data[LogisticsCompany][logi_unit]"><?=$logiUnitOptions;?></select>
</td>
<td class="form_error">
	<?=isset($errors['logi_unit']) ? $errors['logi_unit'] : '';?>
</td>
</tr>

<tr>
<td>GST:</td>
<td><input type='text' name='data[LogisticsCompany][logi_gst]' value='<?=@$thisItem['LogisticsCompany']['logi_gst'];?>'>
<span class='msg_note'>%</span>
</td>
<td class="form_error">
	<?=isset($errors['logi_gst']) ? $errors['logi_gst'] : '';?>
</td>
</tr>

<tr>
<td>Fuel Fee:</td>
<td><input type='text' name='data[LogisticsCompany][logi_fuel]' value='<?=@$thisItem['LogisticsCompany']['logi_fuel'];?>'>
<span class='msg_note'>%</span>
</td>
<td class="form_error">
	<?=isset($errors['logi_fuel']) ? $errors['logi_fuel'] : '';?>
</td>
</tr>

</table>
</td></tr>

</table>
</form>