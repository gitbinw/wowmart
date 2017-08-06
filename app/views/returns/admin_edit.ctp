<?php
header('Cache-Control: no-cache, must-revalidate');
?>
<form id='form_detail' name='form_detail'>
<input id='txt_order_id' type='hidden' name='data[Order][id]' 
	   value='<?=@$thisItem['Order']['id'];?>' />
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Order Return Detail Form</td></tr>
<tr><td style='padding-top:5px;' align="center">
<table cellspacing='2' cellpadding='0' class="reg_table">

<tr>
	<td class="form_error" colspan="2">
		<?php if (isset($errors)) debug($errors);?>
	</td>
</tr>

<tr>
<td width="120px">Returned Order No.:</td>
<td>
	<select id="order_numbers" name="order_id" 
	 <?=isset($is_edit) && $is_edit==true ? 'disabled' : '';?>>
		<option value='0'></option>
		<?php foreach ($orderNumber as $key => $val) { ?>
		<option value='<?=$key;?>' <?=$key==@$thisItem['Order']['id'] ? 'selected' : '';?>>
			<?=$val;?>
		</option>
		<?php } ?>
	</select>
</td>
</tr>

<tr><td colspan="2"><br></td></tr>

<tr id="return_list">
	<td colspan="2">
		<table id="return_items">
			
		</table>
	</td>
</tr>

</table>
</td></tr>
</table>
</form>

<script language='javascript' type='text/javascript'>
	<?php 
		if (isset($thisItem['Order']['id'])) {
			$data = json_encode($thisItem);
	?>
			listReturnItems('<?=$data;?>', 'json');
	<?php
		}
	?>
	$("#order_numbers").change(function() {
		var pid = $(this).val();
		$('#txt_order_id').val(pid);
		var opts = {
			type : 'post',
			url : '/admin/returns/get/' + pid,
			dataType : 'json',
			beforeSend : function() {
				var loading = '<td colspan="2"><img src="/img/icons/icon_load.gif" /></td>';
				$("#return_items").html(loading);
			},
			success : function(data) {
				if(data.success == true) {
					listReturnItems(data.order);
				}
			}
		};
		
		if ($("#order_numbers").val() > 0) {
			$.ajax(opts);
		}
	});
</script>