<?php 
	header('Cache-Control: no-cache, must-revalidate');
?>
<form id='form_detail'>
<input type='hidden' name='data[Category][id]' value='<?=@$thisItem['Category']['id'];?>'>
<input type='hidden' name='data[CategoryDetail][id]' value='<?=@$thisItem['CategoryDetail']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Category Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<tr bgcolor="#CCCCCC">
<td colspan="3">
<input type='hidden' name='data[CategoryDetail][is_node]' value='1'>
<input type='checkbox' name='data[CategoryDetail][is_node]' value='0' <?=isset($thisItem['CategoryDetail']['is_node'])&&$thisItem['CategoryDetail']['is_node']==0?"CHECKED":"";?>>
Do not show this category in category tree.
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td colspan="3">
<input type='hidden' name='data[CategoryDetail][on_home]' value='0'>
<input type='checkbox' name='data[CategoryDetail][on_home]' value='1' <?=isset($thisItem['CategoryDetail']['on_home'])&&$thisItem['CategoryDetail']['on_home']==1?"CHECKED":"";?>>
Show this category in the home page.
</td>
</tr>

<tr>
<td>Category Name:</td>
<td><input type='text' id='category_name' name='data[CategoryDetail][name]' value='<?=@$thisItem['CategoryDetail']['name'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['CategoryDetail']['name']) ? $errors['CategoryDetail']['name'] : '';?>
</td>
</tr>

<tr>
<td>Category Alias (displays in URL):</td>
<td><input type='text' id='category_alias' name='data[CategoryDetail][category_alias]' value="<?=@$thisItem['CategoryDetail']['category_alias'];?>" />
<span class="form_error">
	&nbsp;&nbsp;<?=isset($errors['category_alias']) ? $errors['category_alias'] : '';?>
</span>
</td>
</tr>

<?=isset($categories) ? $combobox->recursiveCategory('Categories', @$selected_categories,@$categories,'disabled') : '';?>

<tr>
<td valign="top">Comment:<br>
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','cat_comments');" onfocus='this.blur();'>Html Editor</a></td>
<td><textarea id="cat_comments" name="data[CategoryDetail][comment]" cols="40" rows="5"><?=@$thisItem['CategoryDetail']['comment'];?></textarea></td>
<td class="form_error">
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Discounts:</td>
<td>
<?=$dropdown->simple('data[CategoryDetail][discount_id]', 'Discount', 
				$discounts, @$thisItem['CategoryDetail']['discount_id'], 
				'', 'dis_name');?>
</td>
<td class="form_error"></td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Rewards:</td>
<td>
<?=$dropdown->simple('data[CategoryDetail][reward_id]', 'Reward', 
			 	$rewards, @$thisItem['CategoryDetail']['reward_id'],
				'', 'rew_name');?>
</td>
<td class="form_error"></td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Vouchers:</td>
<td>
<?=$dropdown->simple('data[CategoryDetail][voucher_id]', 'Voucher',
				$vouchers, @$thisItem['CategoryDetail']['voucher_id'], 
				'', 'vou_name');?>
</td>
<td class="form_error"></td>
</tr>

<tr bgcolor="#CCC">
<td>Status:</td>
<td><?=$dropdown->dropdown("data[CategoryDetail][category_status]", 
			$CATEGORY_PRODUCT_STATUSES, @$thisItem['CategoryDetail']['category_status']);?> 
<span class='msg_note'></span>
</td>
<td class="form_error">
	<?=isset($errors['logi_state']) ? $errors['logi_state'] : '';?>
</td>
</tr>

<!--<tr>
<td valign="top">Icon Name:</td>
<td><input type='text' name='data[CategoryDetail][icon_name]' value='<?=@$thisItem['CategoryDetail']['icon_name'];?>'></td>
<td class="form_error">
</td>
</tr>-->
</table>
</td></tr>
</table>
</form>
<script language="javascript" type="text/javascript">
	setupAutoFillup('category_alias', 'category_name');
</script>