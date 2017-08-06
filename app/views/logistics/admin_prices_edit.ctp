<?php 
header('Cache-Control: no-cache, must-revalidate');

$logiCompany = isset($thisItem['LogisticsPrice']['logi_company']) ? $thisItem['LogisticsPrice']['logi_company'] : '';
if (empty($logiCompany) && isset($parentItem['LogisticsCompany']['logi_company'])) {
	$logiCompany = $parentItem['LogisticsCompany']['logi_company'];
}
?>
<form id='form_detail' onsubmit='return false'>
<input type='hidden' name='data[LogisticsPrice][id]' value='<?=@$thisItem['LogisticsPrice']['id'];?>'>
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Shipping Price Detail Form</td></tr>
<tr><td align='center'>

<table cellspacing='2' cellpadding='0' class="reg_table">

<tr>
<td>Company Name:</td>
<td><input type='text' name='data[LogisticsPrice][logi_company]' value='<?=@$logiCompany;?>' readonly>
<span class='msg_note'></span>
</td>
<td class="form_error"></td>
</tr>

<tr>
<td>Post Code:</td>
<td><input type='text' name='data[LogisticsPrice][logi_postcode]' value='<?=@$thisItem['LogisticsPrice']['logi_postcode'];?>'>
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['logi_postcode']) ? $errors['logi_postcode'] : '';?>
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td>Unit Price:</td>
<td><input type='text' name='data[LogisticsPrice][logi_price]' value='<?=@$thisItem['LogisticsPrice']['logi_price'];?>'>
<span class='msg_note'>$ required</span>
</td>
<td class="form_error">
	<?=isset($errors['logi_price']) ? $errors['logi_price'] : '';?>
</td>
</tr>
<tr bgcolor="#FFD5FF">
<td>Basic Price:</td>
<td><input type='text' name='data[LogisticsPrice][logi_basic_price]' value='<?=@$thisItem['LogisticsPrice']['logi_basic_price'];?>'>
<span class='msg_note'>$ required</span>
</td>
<td class="form_error">
	<?=isset($errors['logi_basic_price']) ? $errors['logi_basic_price'] : '';?>
</td>
</tr>

<tr bgcolor="#CCC">
<td>Zone:</td>
<td><input type='text' name='data[LogisticsPrice][logi_zone]' value='<?=@$thisItem['LogisticsPrice']['logi_zone'];?>'>
<span class='msg_note'></span>
</td>
<td class="form_error">
	<?=isset($errors['logi_zone']) ? $errors['logi_zone'] : '';?>
</td>
</tr>
<tr bgcolor="#CCC">
<td>State:</td>
<td><?=$dropdown->dropdown("data[LogisticsPrice][logi_state]", 
			$STATE_OPTIONS, @$thisItem['LogisticsPrice']['logi_state']);?> 
<span class='msg_note'></span>
</td>
<td class="form_error">
	<?=isset($errors['logi_state']) ? $errors['logi_state'] : '';?>
</td>
</tr>

<tr bgcolor="#FFD5FF">
<td><b>Bulk Upload Prices</b></td>
<td colspan="2">
	<input type="file" name="uploaded_file" id="upload_file_browse">
	<span id="file_upload_info" style="font-weight: bold;"></span>
</td>
</tr>

</table>
</td></tr>

</table>
</form>
<script>
	$('#upload_file_browse').fileupload({
		url: g_site_root + '/admin/logistics/prices_upload',
		dataType: 'json',
		autoUpload: true,
		acceptFileTypes: /(\.|\/)(csv)$/i,
		maxFileSize: 5000000, // 5 MB,
		previewCrop: false,
		messages: {  
			acceptFileTypes: 'File type must be CSV',
			maxFileSize: 'File is too large (must be no more than 5MB)',
			minFileSize: 'File is too small'
		}
	}).on('fileuploadadd', function (e, data) {
			   
	}).on('fileuploadprocessalways', function (e, data) {
		var index = data.index,
			file = data.files[index];
		
		if (file.error) {
			$('#file_upload_info').html('<span style="color:red;">' + file.error + '</span>');
		}
	}).on('fileuploadprogressall', function (e, data) {
		/*var progress = parseInt(data.loaded / data.total * 100, 10);
		
		$('#file_upload_info').html('Uploading ... <br>' + progress + '%');
		$progress.not(':visible').show();
		$('.fileinput-progress-bar', $progress).css(
			'width',
			progress + '%'
		);*/
	}).on('fileuploadstart', function (e) {
		
		$('#file_upload_info').html('Uploading ...');
		
	}).on('fileuploaddone', function (e, data) {
		var res = data.result;
		if(res.status == 1) {
			$('#file_upload_info').html('<span style="color:green;">Uploaded Susscessfully!</span>');
		} else {
			$('#file_upload_info').html('<span style="color:red;">' + res.errorMsg + '</span>');
		}
		
	}).on('fileuploadfail', function (e, data) {
		$('#file_upload_info').html('<span style="color:red;">Unknown Error!</span>');
	})
</script>