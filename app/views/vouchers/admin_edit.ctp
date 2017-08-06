<?php 
header('Cache-Control: no-cache, must-revalidate');

$voucherOn = "";
if (isset($thisItem['Voucher']['vou_on']) && $thisItem['Voucher']['vou_on'] == 1) $voucherOn = "checked";

$systemLabelValue = "";
if (isset($thisItem['Voucher']['system_label_value']) && !empty($thisItem['Voucher']['system_label_value'])) {
	$systemLabelValue = $thisItem['Voucher']['system_label_value'];
}

$labelValueShow = 'hidden';
$systemLabelOptions = '<option value="">Leave blank</option>';
if (isset($SYSTEM_MODULE_LABELS)) {
	foreach($SYSTEM_MODULE_LABELS as $label) {
		$checked = isset($thisItem['Voucher']['system_label']) && $thisItem['Voucher']['system_label'] == $label['id'] ? 
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
<input type='hidden' name='data[Voucher][id]' value='<?=@$thisItem['Voucher']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Voucher Detail Form</td></tr>
<tr><td align='center'>

<table cellspacing='2' cellpadding='0' class="reg_table">

<tr bgcolor="#CCCCCC">
<td colspan="3" align="left">
	<input type='hidden' name='data[Voucher][vou_on]' value='0'>
	<input id="chk_voucher_on" type='checkbox' name='data[Voucher][vou_on]' value='1' <?=$voucherOn;?>>
	<label for="chk_voucher_on">Check this box to turn the voucher on</label>
</td>
</tr>

<tr>
<td>Voucher Name:</td>
<td><input type='text' name='data[Voucher][vou_name]' value='<?=@$thisItem['Voucher']['vou_name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['vou_name']) ? $errors['vou_name'] : '';?>
</td>
</tr>
<tr>
<td>Voucher Value:</td>
<td><input type='text' name='data[Voucher][vou_value]' value='<?=@$thisItem['Voucher']['vou_value'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['vou_value']) ? $errors['vou_value'] : '';?>
</td>
</tr>
<tr>
<td>Number of Voucher Codes:</td>
<td><input type='text' name='data[Voucher][vou_number]' value='<?=@$thisItem['Voucher']['vou_number'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['vou_number']) ? $errors['vou_number'] : '';?>
</td>
</tr>
<tr>
<td>Voucher Expire Date:</td>
<td><input type='text' class="datetimepicker" name='data[Voucher][vou_expiry]' 
		value='<?=isset($thisItem['Voucher']['vou_expiry']) ? substr($thisItem['Voucher']['vou_expiry'], 0, 16) : '';?>'>
</td>
<td class="form_error">
	<?=isset($errors['vou_expiry']) ? $errors['vou_expiry'] : '';?>
</td>
</tr>

<tr>
<td valign="top">Comments:</td>
<td><textarea name="data[Voucher][vou_comments]" cols="40" rows="5"><?=@$thisItem['Voucher']['vou_comments'];?></textarea></td>
<td class="form_error">
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>System Voucher Label:</td>
<td>
<select id="system_label_option" name="data[Voucher][system_label]"><?=$systemLabelOptions;?></select>
</td>
<td class="form_error">
	<?=isset($errors['system_label']) ? $errors['system_label'] : '';?>
</td>
</tr>

<tr id="system_label_value" bgcolor="#FFD5FF" class="<?=$labelValueShow;?>">
<td>System Voucher Label Value:</td>
<td>
<input type="text" name="data[Voucher][system_label_value]" value='<?=$systemLabelValue;?>' />
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