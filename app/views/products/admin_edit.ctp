<?php
header('Cache-Control: no-cache, must-revalidate');
$selectedCat = isset($parentItem) && !empty($parentItem) ? $parentItem : '';
$currFormType = isset($currentFormType) ? $currentFormType : '';

$arrMedias = isset($thisItem['Media']) ? $utility->formatMediaData($thisItem) : '';
$subCategory = isset($sub_category) ? $sub_category : '';
?>
<form id='form_detail' name='form_detail'>
<input type='hidden' id="current_form_type" name='form_type' value='<?=$currFormType;?>'>
<input type='hidden' id="product_id" name='data[Product][id]' value='<?=@$thisItem['Product']['id'];?>'>
<input type='hidden' name='data[Product][supplier_id]' value='<?=SUPPLIER_DEFAULT_ID;?>'>

<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Product Detail Form</td></tr>
<tr><td style='padding-top:5px;' align="center"><table cellspacing='2' cellpadding='0' class="reg_table">
<tr bgcolor="#CCCCCC">
<td colspan="2">
<input type='hidden' name='data[Product][is_shown]' value='1'>
<input type='checkbox' name='data[Product][is_shown]' value='0' <?=isset($thisItem['Product']['is_shown'])&&$thisItem['Product']['is_shown']==0?"CHECKED":"";?>>
Do not show this product
</td>
</tr>
<tr bgcolor="#CCCCCC">
<td colspan="2">
<input type='hidden' name='data[Product][on_homepage]' value='0'>
<input type='checkbox' name='data[Product][on_homepage]' value='1' <?=isset($thisItem['Product']['on_homepage'])&&$thisItem['Product']['on_homepage']==1?"CHECKED":"";?>>
Show this product on homepage.
</td>
</tr>
<!--<tr bgcolor="#CCCCCC">
<td colspan="2">
<input type='hidden' name='data[Product][for_delivery]' value='0'>
<input type='checkbox' name='data[Product][for_delivery]' value='1' <?=isset($thisItem['Product']['for_delivery'])&&$thisItem['Product']['for_delivery']==1?"CHECKED":"";?>>
Only for delivery business.
</td>
</tr>-->
<tr bgcolor="#FFD5FF">
<td colspan="2">
<?=$this->element('product_types');?>
</td>
</tr>
<?=$combobox->recursiveCategory('Categories', @$selected_categories,@$categories);?>
<tr>
<td>Product Name:</td>
<td><input type='text' id='product_name' name='data[Product][name]' value="<?=@$thisItem['Product']['name'];?>">
<span class="form_error">
	&nbsp;&nbsp;<?=isset($errors['name']) ? $errors['name'] : '';?>
</span>
</td>
</tr>
<tr>
<td>Product Alias (displays in URL):</td>
<td><input type='text' id='product_alias' name='data[Product][product_alias]' value="<?=@$thisItem['Product']['product_alias'];?>" />
<span class="form_error">
	&nbsp;&nbsp;<?=isset($errors['product_alias']) ? $errors['product_alias'] : '';?>
</span>
</td>
</tr>

<!--<tr>
<td>Serial Number:</td>
<td><input type='text' id="product_serial" name='data[Product][serial_no]' value="<?=@$thisItem['Product']['serial_no'];?>">
<span class="form_error">
	&nbsp;&nbsp;<?=isset($errors['serial_no']) ? $errors['serial_no'] : '';?>
</span>
</td>
</tr>-->

<tr>
<td valign="top">
Description:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','prod_long_desc');" onfocus='this.blur();'>Html Editor</a>
</td>
<td><textarea id="prod_long_desc" name="data[Product][long_desc]" cols="40" rows="2"><?=@$thisItem['Product']['long_desc'];?></textarea></td>
</tr>
<tr>
<td>Original Price:</td>
<td><input type='text' id='original_price' name='data[Product][price]' value="<?=@$thisItem['Product']['price'];?>">$</td>
</tr>
<tr>
<td>Now Price:</td>
<td><input type='text' id='deal_price' name='data[Product][deal_price]' value="<?=@$thisItem['Product']['deal_price'];?>">$</td>
</tr>

<!--<tr>
<td>Unit:</td>
<td><input type='text' name='data[Product][unit]' value="<?=@$thisItem['Product']['unit'];?>"></td>
</tr>-->

<tr>
<td>Weight (KG):</td>
<td><input type='text' name='data[Product][unit_weight]' value="<?=@$thisItem['Product']['unit_weight'];?>"></td>
</tr>

<!--<tr>
<td>Package Type:</td>
<td><input type="radio" name='data[Product][pack_type]' value=1 <?=!isset($thisItem['Product']['pack_type']) || $thisItem['Product']['pack_type']==1?"checked" : "";?>>Letters
<input type="radio" name='data[Product][pack_type]' value=2 <?=@$thisItem['Product']['pack_type']==2?"checked" : "";?>>Parcel
</td>
</tr>-->

<tr bgcolor="#ccc">
<td>Stock:</td>
<td><input type='text' name='data[Product][stock]' value="<?=!empty($thisItem['Product']['stock']) ? $thisItem['Product']['stock'] : 1;?>">
<span class="form_error">
	&nbsp;&nbsp;<?=isset($errors['stock']) ? $errors['stock'] : '';?>
</span>
</td>
</tr>
<tr bgcolor="#ccc">
<td>Shipping:</td>
<td>
<?=$dropdown->simple('data[Product][logistics_company_id]', 'LogisticsCompany',
				$logistics, @$thisItem['Product']['logistics_company_id'], 
				'', 'logi_company');?>
<span class="form_error"></span>
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Discounts:</td>
<td>
<?=$dropdown->simple('data[Product][discount_id]', 'Discount', 
				$discounts, @$thisItem['Product']['discount_id'], 
				'', 'dis_name');?>
<span class="form_error"></span>
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Rewards:</td>
<td>
<?=$dropdown->simple('data[Product][reward_id]', 'Reward', 
			 	$rewards, @$thisItem['Product']['reward_id'],
				'', 'rew_name');?>
<span class="form_error"></span>
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Vouchers:</td>
<td>
<?=$dropdown->simple('data[Product][voucher_id]', 'Voucher',
				$vouchers, @$thisItem['Product']['voucher_id'], 
				'', 'vou_name');?>
<span class="form_error"></span>
</td>
</tr>

<!--<tr>
<td>Earn Point:</td>
<td><input type='text' name='data[Product][points]' value="<?=@$thisItem['Product']['points'];?>"></td>
</tr>-->

<!--<tr bgcolor="#CF9FFF">
<td>Enter Limited Locations: <br />
(format: 2000,2212)<br />

</td>
<td>
<input type="text" id="prod_locations" name="data[Product][locations]" value='<?=@$thisItem['Product']['locations'];?>' />
<input type="button" value="Get Supplier Locations" id="get_sup_loc" />
<input type="hidden" id="default_locations" value="<?=@$thisItem['Supplier']['locations'];?>" />
</td>
</tr>-->

<tr bgcolor="#5EA226">
<td colspan="2" class="image_uploader_trigger">
	<label for="upload_image_on">Please tick this when you want to update the image for this product</label>
    <input type="checkbox" name="upload_image_on" value="1" id="upload_image_on" />
</td>
</tr>
<?=$utility->addImageListUploader('btn_image_add', 'image_list', 
				array(
					'title' => 'Product Image', 
					'media_id_name' => 'data[Media][Media]',
					'only_one_image' => false,
					'medias' => $arrMedias
				)
			);?>

</table>
</td></tr>
</table>
</form>

<script language="javascript" type="text/javascript">
setupAutoFillup('product_alias', 'product_name');

$("#get_sup_loc").click(function(e) {
	$("#prod_locations").val($("#default_locations").val());
});
</script>