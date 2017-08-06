<?php
if (isset($thisItem[$modelName]['id']) && !empty($thisItem[$modelName]['id'])) {
	$modelId   = $thisItem[$modelName]['id'];
	$modelName = strtolower($modelName);
?>

<tr class="upload_image_strip">
<td class="image_list_label">
	<div style="height:25px;"><div class="left_label"><b>Images:</b></div></div>
	<div style="height:25px;">Add Logo<div class='add_images' id='add_logo'></div></div>
	<div>Add Photo<div class='add_images' id='add_photo'></div></div>
</td>
<td class="image_list"><div id="prod_image_viewer"></div></td>
</tr>
<script language="javascript" type="text/javascript">

function updateImageId() {
	var imgCount = 0;
	$("#form_upload input.upload_image_input").each(function(i, val) {
		if ($(val).attr('disabled') == true) {
			$(val).siblings('div.upload_image_msg').attr('id', '');
		} else {
			imgCount ++;
			$(val).siblings('div.upload_image_msg').attr('id', 'upload_image_' + imgCount);
		}
	});
}
function loadImages() {
	$("#prod_image_viewer").slideviewer({
			isAjax : true,
			ajaxUrl : '/images/get/<?=$modelName;?>/<?=@$modelId;?>',
			params : '',
			imageRoot: '/img/images/<?=$modelName;?>/',
			ajaxUrlDel : '/admin/images/delete/<?=$modelName;?>/<?=@$modelId;?>',
			ajaxUrlSetDefault : '/admin/images/set/<?=$modelName;?>/<?=@$modelId;?>',
			width: 104,
			effects : 'mousedown'
	});
}

function submitUploadForm() {
	var params = $("#form_upload").serialize();
	var options = {
		type: 'POST',
		url: '/admin/images/upload/<?=$modelName;?>/<?=@$modelId;?>',
		dataType: 'json',
		data: params,
		beforeSend: function() {
			$("div#upload_prog_loading").show();
			$("div#upload_form_errors").empty();
			$("#form_upload div.upload_image_ok").siblings('input').attr('disabled', true);
			$("#form_upload div.upload_image_fail")
				.removeClass('upload_image_fail')
				.hide();
				
			updateImageId();
		},
		success: function(data) {
			$.each(data.uploaded, function(i, val) {
				var imgId = parseInt(val) + 1;
				$("#upload_image_" + imgId)
					.addClass('upload_image_ok')
					.html('Uploaded')
					.show();
			});
			if (data.success == true) {
				$("#form_upload")[0].reset();
				$("body").popwindow.close(loadImages);
			} else {
				var err = "<ul class='error_msg'><li>Errors:</li>";
				var errMsg = "";
				$.each(data.message, function(i, val) {
					if (i != 'global') {
						var imgId = parseInt(i) + 1;
						errMsg += "<li>No." + imgId + " image - " + val + "</li>";
						$("#upload_image_" + imgId)
							.removeClass('upload_image_ok')
							.addClass('upload_image_fail')
							.html('Failed')
							.show();
					}
				});
				if (errMsg == '') { 
					errMsg += "<li>" + data.message['global'] + "</li>";
					$("#form_upload div.upload_image_msg")
						.removeClass('upload_image_ok')
						.addClass('upload_image_fail')
						.show();
				}
				err += errMsg;
				err += "</ul>";
				$("div#upload_form_errors").html(err);
			}
			$("#form_upload div.upload_image_ok").siblings('input').attr('disabled', false);
			$("div#upload_prog_loading").hide();
			
			updateImageId();
		}
	};
	$("#form_upload").upload(options);
}
$("#add_logo, #add_photo").click(function() {
	var cnt = '<form id="form_upload" name="form_upload" enctype="multipart/form-data" method="POST">' +
			  '<div style="padding: 5px 10px;">' +
			  '<div id="upload_form_errors">' + 
			  '	<span style="font-weight:bold;color:#4590ff;">' + 
			  '		Please select an image and upload for your logo.' +
			  ' 	For the best display, please upload images with dimension ratio 1. E.g. 167 x 167 px.' + 
			  '		The maximum size of image is 5MB. And only JPEG, PNG and GIF are allowed to upload.' + 
			  '</span>' +
			  '</div>';
			  
	var img_limits = 1;
	var img_type_logo = <?=IMAGE_SUPPLIER_LOGO;?>;
	var img_type_photo = <?=IMAGE_SUPPLIER_PHOTO;?>;
	for(var i=0; i<img_limits; i++) {
		cnt += '<div class="form_upload_field">' + 
			   '	<input class="upload_image_input" type="file" name="upload_image[]" size="29" />' + 
			   '	<div id="upload_image_' + (i+1) + '" class="upload_image_msg upload_error"></div>' +
			   '</div>';
		if (this.id == 'add_logo') {
			cnt += '<input type="hidden" name="data[extra][image_type][]" value="' + img_type_logo + '" />';
		} else if (this.id == 'add_photo') {
			cnt += '<input type="hidden" name="data[extra][image_type][]" value="' + img_type_photo + '" />';
		}
	}
	cnt += '</div>' + 
		   '<div style="padding: 0px 10px;position: relative;">' +
		   '	<input type="button" name="btn_upload" id="btn_upload" value="Upload" />' +
		   '	<div id="upload_prog_loading">Uploading ... Please wait.</div>' + 
		   '</div>' +
		   '<div style="padding: 5px 10px;font-weight:bold;">' +
		   '	Note: acceptable image(.jpg, .png, .gif); less than 5MB.' +
		   '</div>' +
		   '</form>';
						
	$("body").popwindow({
		content: cnt,
		title: 'Add Images',
		width: 375,
		height: 300,
		onLoaded: function() {
			$("#form_upload input#btn_upload").click(function() {
				submitUploadForm();
			});
			$("#form_upload input.upload_image_input").change(function() {
				$(this).siblings("div.upload_image_msg")
					.removeClass('upload_image_ok upload_image_fail')
					.hide();
			});
		},
		onClosed: function() {
			loadImages();
		}
	});
});
loadImages();

</script>

<?php } else { ?>

<tr bgcolor="#F2C977">
	<td class="image_list_label" colspan="2" style="height: 25px;width:100%;">
		<b>To add images, You have to save this <?=$modelName;?> firstly.</b>
	</td>
</tr>

<?php } ?>