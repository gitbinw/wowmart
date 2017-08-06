<?php header('Cache-Control: no-cache, must-revalidate');?>

<form id='form_detail' name='form_detail'>
<input type='hidden' name='data[Media][id]' value='<?=@$thisItem['Media']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Template Detail Form</td></tr>
<tr><td style='padding-top:5px;' align="center"><table cellspacing='2' cellpadding='0' class="reg_table">
<tr bgcolor="#CCCCCC">
<td valign="top" style="padding-top:5px;">Upload Image:</td>
<td>
	<div id="image-section"></div>
    <input type='hidden' id='file_name' name='data[Media][file_name]' 
		value="<?=@$thisItem['Media']['file_name'];?>" />
    <span class="form_error" style="padding-bottom:20px;display: inline-block;">
	&nbsp;&nbsp;<?=isset($errors['file_name']) ? $errors['file_name'] : '';?>
	</span>
</td>

</td>
</tr>

<tr>
<td width="120px">Media Name *: </td>
<td><input type='text' id='media_name' name='data[Media][media_name]' placeholder='Enter media name' 
		value="<?=@$thisItem['Media']['media_name'];?>" style="width: 300px;" />
<span class="form_error">
	&nbsp;&nbsp;<?=isset($errors['media_name']) ? $errors['media_name'] : '';?>
</span>
</td>
</tr>

<!--<tr bgcolor="#FFDCB9">
<td width="120px">Media External URL: </td>
<td><input type='text' id='media_external' name='data[Media][external_url]' placeholder='Enter external url' 
		value="<?=@$thisItem['Media']['external_url'];?>" style="width: 300px;" />
<span class="form_error">
	&nbsp;&nbsp;<?=isset($errors['external_url']) ? $errors['external_url'] : '';?>
</span>
</td>
</tr>-->

</table>
</td></tr>
</table>
</form>

<?php
	$imageUrl = '';
	if (isset($thisItem['Media']['dir']) && !empty($thisItem['Media']['dir'])
		&& isset($thisItem['Media']['file_name']) && !empty($thisItem['Media']['file_name']) ) {
			
		$img = str_replace("\\", '/', $thisItem['Media']['dir']);
		$imageUrl = SITE_URL_ROOT . IMAGE_URL_ROOT . $img . '/' . $thisItem['Media']['file_name'];
	}
?>
<script>
	setXhrUploadPhoto({
		photo_parent: 'image-section',
		wrapper: 'media-upload-wrapper',
		width: 250,
		height: 250,
		url: '<?php echo $imageUrl;?>?uq=' + (new Date()).getTime(),
		callback: function(data) {
			$('input#media_name').val(data.name);
			$('input#file_name').val(data.name);
		}
	});
</script>
