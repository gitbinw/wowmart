<?php 
header('Cache-Control: no-cache, must-revalidate');

$discountOn = "";
if (isset($thisItem['Discount']['dis_on']) && $thisItem['Discount']['dis_on'] == 1) $discountOn = "checked";

$systemLabelValue = "";
if (isset($thisItem['Discount']['system_label_value']) && !empty($thisItem['Discount']['system_label_value'])) {
	$systemLabelValue = $thisItem['Discount']['system_label_value'];
}

$labelValueShow = 'hidden';
$systemLabelOptions = '<option value="">Leave blank</option>';
if (isset($SYSTEM_MODULE_LABELS)) {
	foreach($SYSTEM_MODULE_LABELS as $label) {
		$checked = isset($thisItem['Discount']['system_label']) && $thisItem['Discount']['system_label'] == $label['id'] ? 
					'selected' : '';

		$class = '';
		if (isset($label['need_value']) && $label['need_value'] == true) {
			$class = 'need_value';
			if ($checked == 'selected') $labelValueShow = '';
		}
		$systemLabelOptions .= '<option class="' . $class . '" value="' . $label['id'] . '" ' . $checked . '>' . 
								$label['name'] . '</option>';
	}
}

?>
<form id='form_detail' onsubmit='return false'>
<input type='hidden' name='data[Discount][id]' value='<?=@$thisItem['Discount']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Discount Detail Form</td></tr>
<tr><td align='center'>

<table cellspacing='2' cellpadding='0' class="reg_table">

<tr bgcolor="#CCCCCC">
<td colspan="3" align="left">
	<input type='hidden' name='data[Discount][dis_on]' value='0'>
	<input id="chk_discount_on" type='checkbox' name='data[Discount][dis_on]' value='1' <?=$discountOn;?>>
	<label for="chk_discount_on">Check this box to turn the Discount on</label>
</td>
</tr>

<tr>
<td>Discount Name:</td>
<td><input type='text' name='data[Discount][dis_name]' value='<?=@$thisItem['Discount']['dis_name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['dis_name']) ? $errors['dis_name'] : '';?>
</td>
</tr>
<tr>
<td>Discount Percentage:</td>
<td><input type='text' name='data[Discount][dis_percent]' value='<?=@$thisItem['Discount']['dis_percent'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['dis_percent']) ? $errors['dis_percent'] : '';?>
</td>
</tr>
<tr>
<td>Discount Expire Date:</td>
<td><input type='text' class="datetimepicker" name='data[Discount][dis_expiry]' 
		value='<?=isset($thisItem['Discount']['dis_expiry']) ? substr($thisItem['Discount']['dis_expiry'], 0, 16) : '';?>'>
</td>
<td class="form_error">
	<?=isset($errors['dis_expiry']) ? $errors['dis_expiry'] : '';?>
</td>
</tr>

<tr>
<td valign="top">Comments:</td>
<td><textarea name="data[Discount][dis_comments]" cols="40" rows="5"><?=@$thisItem['Discount']['dis_comments'];?></textarea></td>
<td class="form_error">
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>System Discount Label:</td>
<td>
<select id="system_label_option" name="data[Discount][system_label]"><?=$systemLabelOptions;?></select>
</td>
<td class="form_error">
	<?=isset($errors['system_label']) ? $errors['system_label'] : '';?>
</td>
</tr>

<tr id="system_label_value" bgcolor="#FFD5FF" class="<?=$labelValueShow;?>">
<td>System Discount Label Value:</td>
<td>
<input type="text" name="data[Discount][system_label_value]" value='<?=$systemLabelValue;?>' />
</td>
<td class="form_error">
	<?=isset($errors['system_label_value']) ? $errors['system_label_value'] : '';?>
</td>
</tr>

</table>
</td></tr>

</table>
</form>
<script>
	$(function() {
		$('.datetimepicker').datetimepicker({
			dateFormat: "yy-mm-dd"
		});
		
		$('#system_label_option').unbind('change').change(function(e) {
			var $selOpt = $(this).find('option:selected');
			if ($selOpt.hasClass('need_value')) {
				$('#system_label_value input').attr('disabled', false)
				$('#system_label_value').show();
			} else {
				$('#system_label_value').hide();
				$('#system_label_value input').attr('disabled', true);
			}
		});
  	});
</script>