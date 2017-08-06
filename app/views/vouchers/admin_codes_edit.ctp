<?php 
header('Cache-Control: no-cache, must-revalidate');
?>

<input type="hidden" id="action_button_status" value="disable_new" />

<form id='form_detail' onsubmit='return false'>
<input type='hidden' name='data[VoucherCode][id]' value='<?=@$thisItem['VoucherCode']['id'];?>'>
<input type='hidden' name='data[VoucherCode][voucher_id]' value='<?=@$thisItem['VoucherCode']['voucher_id'];?>'>
<input type='hidden' name='data[VoucherCode][vou_code]' value='<?=@$thisItem['VoucherCode']['vou_code'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Voucher Code Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<tr>
<td>Voucher Name:</td>
<td bgcolor="#CCCCCC"><?=@$thisItem['Voucher']['vou_name'];?></td>
<td class="form_error"></td>
</tr>
<tr>
<td>Voucher Code:</td>
<td bgcolor="#CCCCCC"><?=@$thisItem['VoucherCode']['vou_code'];?></td>
<td class="form_error"></td>
</tr>
<tr>
<td>Voucher Value:</td>
<td bgcolor="#CCCCCC"><?='$' . @$thisItem['Voucher']['vou_value'];?></td>
<td class="form_error"></td>
</tr>
<tr>
<td>Voucher Comments:</td>
<td bgcolor="#CCCCCC"><?=@$thisItem['Voucher']['vou_comments'];?></td>
<td class="form_error">
</td>
</tr>

<tr>
<td valign="top">Voucher Code Comments:</td>
<td><textarea name="data[VoucherCode][vou_comments]" cols="40" rows="5"><?=@$thisItem['VoucherCode']['vou_comments'];?></textarea></td>
<td class="form_error">
</td>
</tr>
</table>
</td></tr>
</table>
</form>