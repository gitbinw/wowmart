<?php
if (isset($thisItem[$modelName]['id']) && !empty($thisItem[$modelName]['id'])) {
	$modelId   = $thisItem[$modelName]['id'];
	$modelName = strtolower($modelName);
?>

<tr class="upload_image_strip" style="background-color:#FFD5FF;">
<td class="image_list_label">
	<div class="left_label"><b>Documents:</b></div>
	<div class='add_images' id='add_files'></div>
</td>
<td class="image_list"><div id="prod_files_viewer"></div></td>
</tr>
<script language="javascript" type="text/javascript">

function updateFileId() {
	var fileCount = 0;
	$("#form_upload input.upload_image_input").each(function(i, val) {
		if ($(val).attr('disabled') == true) {
			$(val).siblings('div.upload_image_msg').attr('id', '');
		} else {
			fileCount ++;
			$(val).siblings('div.upload_image_msg').attr('id', 'upload_image_' + fileCount);
		}
	});
}
function loadFiles() {
	var ajaxOption = {
		type: 'post',
		url: '/documents/get/<?=$modelName;?>/<?=@$modelId;?>',
		dataType: 'json',
		beforeSend: function() {
			$("#prod_files_viewer").html('<div class="slider_loading"></div>');
		},
		success: function(data) {
			var output = '<ul class="document_list">';
			var fileRoot = '/files/<?=$modelName;?>/<?=@$modelId;?>/';
			$.each(data, function(i, val) {
				var docUrl = fileRoot + val['Document']['file_name'];
				var docIcon = 'document_icon_' + val['Document']['extension'].substring(1);
				output += '<li id="' + val['Document']['id'] + '">' + 
									'	<a href="' + docUrl + '" target="doc_view">' + 
									'	<div class="document_item_url ' + docIcon + '">' + docUrl + '</div></a>' +
									' <div class="document_item_size">' + val['Document']['file_size'] + ' KB' + '</div>' +
									'	<div class="document_del" title="Detlete" alt="Delete"></div>' + 
									'</li>';
			});
			output += '</ul>';
			$("#prod_files_viewer").html(output);
			deleteDocument();
		}
	};
	$.ajax(ajaxOption);
}

function deleteDocument() {
	$("div.document_del").click(function() {
		if ( confirm('Are your sure to delete the selected file?') ) {
			var thisUrl = '/admin/documents/delete/<?=$modelName;?>/<?=@$modelId;?>/' + 
											$(this).parent().attr('id');
			var ajaxOptions = {
				type: 'POST',
				url: thisUrl,
				dataType: 'json',
				beforeSend: function() {
					$("#prod_files_viewer").html('<div class="slider_loading"></div>');
				},
				success: function(data) {
					if (data.success == true) {
						loadFiles();
					}
				}
			};
			
			$.ajax(ajaxOptions);
		}
	});
}

function submitUploadFileForm() {
	var params = $("#form_upload").serialize();
	var options = {
		type: 'POST',
		url: '/admin/documents/upload/<?=$modelName;?>/<?=@$modelId;?>',
		dataType: 'json',
		data: params,
		beforeSend: function() {
			$("div#upload_prog_loading").show();
			$("div#upload_form_errors").empty();
			$("#form_upload div.upload_image_ok").siblings('input').attr('disabled', true);
			$("#form_upload div.upload_image_fail")
				.removeClass('upload_image_fail')
				.hide();
				
			updateFileId();
		},
		success: function(data) {
			$.each(data.uploaded, function(i, val) {
				var fileId = parseInt(val) + 1;
				$("#upload_image_" + fileId)
					.addClass('upload_image_ok')
					.html('Uploaded')
					.show();
			});
			if (data.success == true) {
				$("#form_upload")[0].reset();
				$("body").popwindow.close(loadFiles);
			} else {
				var err = "<ul class='error_msg'><li>Errors:</li>";
				var errMsg = "";
				$.each(data.message, function(i, val) {
					if (i != 'global') {
						var fileId = parseInt(i) + 1;
						errMsg += "<li>No." + fileId + " file - " + val + "</li>";
						$("#upload_image_" + fileId)
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
			
			updateFileId();
		}
	};
	$("#form_upload").upload(options);
}
$("#add_files").click(function() {
	var cnt = '<form id="form_upload" name="form_upload" enctype="multipart/form-data" method="POST">' +
			  '<div style="padding: 5px 10px;">' +
			  '<div id="upload_form_errors"></div>';
	for(var i=0; i<5; i++) {
		cnt += '<div class="form_upload_field">' + 
			   '	<input class="upload_image_input" type="file" name="upload_file[]" size="29" />' + 
			   '	<div id="upload_image_' + (i+1) + '" class="upload_image_msg"></div>' +
			   '</div>';
	}
	cnt += '</div>' + 
		   '<div style="padding: 0px 10px;">' +
		   '	<input type="button" name="btn_upload" id="btn_upload" value="Upload" />' +
		   '	<div id="upload_prog_loading">Uploading ... Please wait.</div>' + 
		   '</div>' +
		   '<div style="padding: 5px 10px;font-weight:bold;">' +
		   '	Note: acceptable file(.pdf, .doc, .xsl, .ppt, .txt); less than 5MB.' +
		   '</div>' +
		   '</form>';
						
	$("body").popwindow({
		content: cnt,
		title: 'Add Documents',
		width: 375,
		height: 300,
		onLoaded: function() {
			$("#form_upload input#btn_upload").click(function() {
				submitUploadFileForm();
			});
			$("#form_upload input.upload_image_input").change(function() {
				$(this).siblings("div.upload_image_msg")
					.removeClass('upload_image_ok upload_image_fail')
					.hide();
			});
		},
		onClosed: function() {
			loadFiles();
		}
	});
});
loadFiles();

</script>

<?php } else { ?>

<tr bgcolor="#FFD5FF">
	<td class="image_list_label" colspan="2" style="height: 25px;width:100%;">
		<b>To add documents, You have to save this <?=$modelName;?> firstly.</b>
	</td>
</tr>

<?php } ?>