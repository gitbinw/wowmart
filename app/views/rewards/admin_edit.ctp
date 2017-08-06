<?php 
header('Cache-Control: no-cache, must-revalidate');

$rewardOn = "";
if (isset($thisItem['Reward']['rew_on']) && $thisItem['Reward']['rew_on'] == 1) $rewardOn = "checked";

$systemLabelValue = "";
if (isset($thisItem['Reward']['system_label_value']) && !empty($thisItem['Reward']['system_label_value'])) {
	$systemLabelValue = $thisItem['Reward']['system_label_value'];
}

$labelValueShow = 'hidden';
$systemLabelOptions = '<option value="">Leave blank</option>';
if (isset($SYSTEM_MODULE_LABELS)) {
	foreach($SYSTEM_MODULE_LABELS as $label) {
		$checked = isset($thisItem['Reward']['system_label']) && $thisItem['Reward']['system_label'] == $label['id'] ? 
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
<input type='hidden' name='data[Reward][id]' value='<?=@$thisItem['Reward']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Reward Detail Form</td></tr>
<tr><td align='center'>

<table cellspacing='2' cellpadding='0' class="reg_table">

<tr bgcolor="#CCCCCC">
<td colspan="3" align="left">
	<input type='hidden' name='data[Reward][rew_on]' value='0'>
	<input id="chk_reward_on" type='checkbox' name='data[Reward][rew_on]' value='1' <?=$rewardOn;?>>
	<label for="chk_reward_on">Check this box to turn the Reward on</label>
</td>
</tr>

<tr>
<td>Reward Name:</td>
<td><input type='text' name='data[Reward][rew_name]' value='<?=@$thisItem['Reward']['rew_name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['rew_name']) ? $errors['rew_name'] : '';?>
</td>
</tr>
<tr>
<td>Reward Value:</td>
<td><input type='text' name='data[Reward][rew_value]' value='<?=@$thisItem['Reward']['rew_value'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['rew_value']) ? $errors['rew_value'] : '';?>
</td>
</tr>
<tr>
<td>Reward Expire Date:</td>
<td><input type='text' class="datetimepicker" name='data[Reward][rew_expiry]' 
		value='<?=isset($thisItem['Reward']['rew_expiry']) ? substr($thisItem['Reward']['rew_expiry'], 0, 16) : '';?>'>
</td>
<td class="form_error">
	<?=isset($errors['rew_expiry']) ? $errors['rew_expiry'] : '';?>
</td>
</tr>

<tr>
<td valign="top">Comments:</td>
<td><textarea name="data[Reward][rew_comments]" cols="40" rows="5"><?=@$thisItem['Reward']['rew_comments'];?></textarea></td>
<td class="form_error">
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>System Reward Label:</td>
<td>
<select id="system_label_option" name="data[Reward][system_label]"><?=$systemLabelOptions;?></select>
</td>
<td class="form_error">
	<?=isset($errors['system_label']) ? $errors['system_label'] : '';?>
</td>
</tr>

<tr id="system_label_value" bgcolor="#FFD5FF" class="<?=$labelValueShow;?>">
<td>System Reward Label Value:</td>
<td>
<input type="text" name="data[Reward][system_label_value]" value='<?=$systemLabelValue;?>' />
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