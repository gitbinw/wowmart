<?php header('Cache-Control: no-cache, must-revalidate');?>
<form id='form_detail'>
<input type='hidden' name='data[User][id]' value='<?=@$thisItem['User']['id'];?>'>
<input type='hidden' name='data[Supplier][id]' value='<?=@$thisItem['Supplier']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Supplier Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">

<tr>
<td>Email:</td>
<td><input type='text' name='data[User][email]' value='<?=@$thisItem['User']['email'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['email']) ? $errors['email'] : '';?>
</td>
</tr>

<?php 
if (isset($thisItem['User']['id']) && !empty($thisItem['User']['id'])) {
?>
	<tr>
		<td colspan="3">
			<div id="lnk_showfield" class="lnk_showfield">
				Change Password <span id="lnk_expand">+</span>
			</div>
		</td>
	</tr>
	
	<tr class='hidden_fields'>
		<td>New Password:</td>
		<td><input type='password' name='data[User][password]' value='' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['password']) ? $errors['password'] : '';?>
		</td>
  </tr>

	<tr class='hidden_fields'>
		<td>Re-type New Password:</td>
		<td><input type='password' name='data[User][confirm_password]' value='' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['confirm_password']) ? $errors['confirm_password'] : '';?>
		</td>
	</tr>
	
	<script language="javascript">
		var initDisplay = <?=(isset($thisItem['change_psw']) && $thisItem['change_psw'] == 1 ? 'true' : 'false');?>;
		showFields('lnk_showfield', 'hidden_fields', initDisplay);
	</script>
<?php	
} else {
?>
	<tr>
		<td>Password:</td>
		<td><input type='password' name='data[User][password]' value='<?=@$thisItem['User']['password'];?>' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['password']) ? $errors['password'] : '';?>
		</td>
	</tr>

	<tr>
		<td>Re-type Password:</td>
		<td><input type='password' name='data[User][confirm_password]' value='<?=@$thisItem['User']['confirm_password'];?>' />
		<span class='msg_note'>required</span>
		</td>
		<td class="form_error">
			<?=isset($errors['confirm_password']) ? $errors['confirm_password'] : '';?>
		</td>
	</tr>
<?php
}
?>

<tr>
<td>Business Name:</td>
<td><input type='text' name='data[Supplier][biz_name]' value='<?=@$thisItem['Supplier']['biz_name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['biz_name']) ? $errors['Supplier']['biz_name'] : '';?>
</td>
</tr>

<tr>
<td>Phone:</td>
<td><input type='text' name='data[Supplier][phone]' value='<?=@$thisItem['Supplier']['phone'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['phone']) ? $errors['Supplier']['phone'] : '';?>
</td>
</tr>

<tr>
<td>Sub Domain:</td>
<td><input type='text' name='data[Supplier][subdomain]' value='<?=@$thisItem['Supplier']['subdomain'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['subdomain']) ? $errors['Supplier']['subdomain'] : '';?>
</td>
</tr>

<tr>
<td>Website:</td>
<td><input type='text' name='data[Supplier][website]' value='<?=@$thisItem['Supplier']['website'];?>'>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Tell us a bit about your passion for your products and yourself:</td>
<td><textarea name='data[Supplier][aboutus]'><?=@$thisItem['Supplier']['aboutus'];?></textarea>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['aboutus']) ? $errors['Supplier']['aboutus'] : '';?>
</td>
</tr>

<tr>
<td>Contact Name:</td>
<td><input type='text' name='data[Supplier][contact_name]' value='<?=@$thisItem['Supplier']['contact_name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['contact_name']) ? $errors['Supplier']['contact_name'] : '';?>
</td>
</tr>

<tr>
<td>Shipping Return Address:</td>
<td><input type='text' name='data[Supplier][return_address1]' value="<?=@$thisItem['Supplier']['return_address1'];?>">
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['return_address1']) ? $errors['Supplier']['return_address1'] : '';?>
</td>
</tr>

<tr>
<td>Suburb:</td>
<td><input type='text' name='data[Supplier][return_suburb]' value='<?=@$thisItem['Supplier']['return_suburb'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['return_suburb']) ? $errors['Supplier']['return_suburb'] : '';?>
</td>
</tr>

<tr>
<td>Postcode:</td>
<td><input type='text' name='data[Supplier][return_postcode]' value='<?=@$thisItem['Supplier']['return_postcode'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['return_postcode']) ? $errors['Supplier']['return_postcode'] : '';?>
</td>
</tr>

<tr>
<td>State:</td>
<td>
<?=$this->element('states', array(
							'stateName'  => 'data[Supplier][return_state]',
							'stateId'    => 'state', 
							'stateValue' => @$thisItem['Supplier']['return_state'])
						);?>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['Supplier']['return_state']) ? $errors['Supplier']['return_state'] : '';?>
</td>
</tr>

<tr><td colspan="3"><br></td></tr>
<tr bgcolor="#cccccc">
<td colspan="3" align="center"><b>Supplier Store Page Settings</b></td>
</tr>
<tr><td colspan="3"><br></td></tr>

<tr>
<td>Verified:</td>
<td>
	<?=$this->element('user_status', array(
							'statusName'  => 'data[User][active]',
							'statusId'    => 'status', 
							'statusValue' => @$thisItem['User']['active'])
						);?>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Serial Number:</td>
<td>
<input type="text" id="supp_serial" name="data[Supplier][identifier]" value="<?=@$thisItem['Supplier']['identifier'];?>" />
<span class='msg_note'>required</span>
</td>
<td class="form_error">
<?=isset($errors['Supplier']['identifier']) ? $errors['Supplier']['identifier'] : '';?>
</td>
</tr>

<tr>
<td>Background Color:</td>
<td><input type="text" name="data[Supplier][bgcolor]" value='<?=@$thisItem['Supplier']['bgcolor'];?>'>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td colspan="3"><table width='100%' cellspacing='0' cellpadding='0'>
<?=$this->element('media_uploader', array(
																			'modelName' => 'Supplier', 
																			'imgLimits' => 1, 
																			'imgType' => IMAGE_SUPPLIER_LOGO)
);?>
</table></td>
</tr>

<tr>
<td>Store Story:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','sup_story');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id='sup_story' name='data[Supplier][story]' cols="40"><?=@$thisItem['Supplier']['story'];?></textarea>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Product Long Description:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','sup_long_desc');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id='sup_long_desc' name='data[Supplier][long_desc]' cols="40"><?=@$thisItem['Supplier']['long_desc'];?></textarea>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Product Short Description:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','sup_short_desc');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id='sup_short_desc' name='data[Supplier][short_desc]' cols="40"><?=@$thisItem['Supplier']['short_desc'];?></textarea>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Store Aphorism:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','sup_motto');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id='sup_motto' name='data[Supplier][motto]' cols="40"><?=@$thisItem['Supplier']['motto'];?></textarea>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Return Policy:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','sup_return_policy');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id='sup_return_policy' name='data[Supplier][return_policy]' cols="40"><?=@$thisItem['Supplier']['return_policy'];?></textarea>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Shipping Info.:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','sup_ship_info');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id='sup_ship_info' name='data[Supplier][shipping_info]' cols="40"><?=@$thisItem['Supplier']['shipping_info'];?></textarea>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Shipping &amp; Packaging Policy:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','sup_ship_policy');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id='sup_ship_policy' name='data[Supplier][freight_policy]' cols="40"><?=@$thisItem['Supplier']['freight_policy'];?></textarea>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr bgcolor="#CF9FFF">
<td>Enter Limited Locations: <br />
(format: 2000,2212)
</td>
<td><input type="text" name="data[Supplier][locations]" value='<?=@$thisItem['Supplier']['locations'];?>'>
</td>
<td class="form_error"></td>
</tr>

</table>
</td></tr>
</table>
</form>

<?php
if (!isset($thisItem['Supplier']['identifier']) || empty($thisItem['Supplier']['identifier'])) {
?>
<script language='javascript' type='text/javascript'>
$("#status").change(function() {
	if ($(this).val() == 1) {
		$opts = {
			type: 'POST',
			url: '/admin/suppliers/serial',
			success: function(data) {
				$("input#supp_serial").val(data);
			}
		};
		$.ajax($opts);
	}
});
</script>
<?php
}
?>
