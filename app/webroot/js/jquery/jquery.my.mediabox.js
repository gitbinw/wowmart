// JavaScript Document
(function($) {
	$.fn.mediabox = function (inSettings) {	
		var settings = $.extend({}, $.fn.slideviewer.default_settings, inSettings);
		var thisObj = this;
        
        return this.each(function() {
        	var output = '';
        	if (settings.modelId == null || settings.modelName == null) {
        		output = '<td class="image_list_label" colspan="3" style="height: 25px;">' + 
						 '	<b>To add images, You have to save this ' + settings.modelName + ' firstly.</b>' + 
						 '</td>';
        	} else {
        		output = '<td class="image_list_label">' + 
						 '	<div class="left_label"><b>Images:</b></div>' + 
						 '	<div class="add_images" id="' + settings.buttonId + '"></div>' + 
						 '	<br><b>' + settings.note + '</b>' + 
						 '</td>' + 
						 '<td class="image_list" colspan="2"><div id="prod_image_viewer"></div></td>';
        	}
        	
        	$(thisObj).html(output);
        	loadMediaBox(settings);
        });
	}
        
    $.fn.mediabox.default_settings = {
        buttonId : 'add_images',
        width: 375,
        height: 300,
        fileInputSize: 29,
        linkModel: false
    };
    
    function updateImageId() {
        var imgCount = 0;
        $("#form_upload input.upload_image_input").each(function(i, val) {
            if ($(val).attr('disabled') == true) {
                $(val).parent().parent().find('div.upload_image_msg').attr('id', '');
            } else {
                imgCount ++;
                $(val).parent().parent().find('div.upload_image_msg').attr('id', 'upload_image_' + imgCount);
            }
        });
    }
    
    function loadImages(settings) {
        $("#prod_image_viewer").slideviewer({
                isAjax : true,
                ajaxUrl : '/images/get/' + settings.modelName + '/' + settings.modelId,
                params : '',
                imageRoot: '/img/images/' + settings.modelName + '/',
                ajaxUrlDel : '/admin/images/delete/' + settings.modelName + '/' + settings.modelId,
                ajaxUrlSetDefault : '/admin/images/set/' + settings.modelName + '/' + settings.modelId,
                width: 104,
                scroll_left_class : 'slider_scroll_left',
                scroll_right_class : 'slider_scroll_right',
                effects : 'mousedown'
        });
    }
    
    function submitUploadForm(settings) {
        var params = $("#form_upload").serialize();
        var options = {
            type: 'POST',
            url: '/admin/images/upload/' + settings.modelName + '/' + settings.modelId,
            dataType: 'json',
            data: params,
            beforeSend: function() {
                $("div#upload_prog_loading").show();
                $("div#upload_form_errors").empty();
                $("#form_upload div.upload_image_ok").parent().parent().find('input.upload_image_input').attr('disabled', true);
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
                    $("body").popwindow.close(loadImages(settings));
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
                $("#form_upload div.upload_image_ok").parent().parent().find('input.upload_image_input').attr('disabled', false);
                $("div#upload_prog_loading").hide();
                
                updateImageId();
            }
        };
        $("#form_upload").upload(options);
    }

    function loadMediaBox(settings) {
        $("#" + settings.buttonId).click(function() {
            var cnt = '<form id="form_upload" name="form_upload" enctype="multipart/form-data" method="POST">' +
                     '<div style="padding: 5px 10px; style="height:350px;width:400px;">' +
                     '	<div id="upload_form_errors">' + 
                     '		<span style="font-weight:bold;color:#4590ff;">' + 
			  						 '		Please select images and upload. You can upload 5 image at the same time.<br>' +
			  						 ' 		For the best display, please upload images with dimension ratio 1. E.g. 500 x 500 px.<br>' + 
			 							 '		The maximum size of image is 5MB. And only JPEG, PNG and GIF are allowed to upload.' + 
			 							 '</span>' +
                     '	</div>' +
                     '	<div id="upload_form_main"><ul>';
            for(var i=0; i<5; i++) {
                cnt += '<li class="form_upload_field">' +
                	   '	<div class="form_upload_input">' + 
                	   '		<label>Select Image:</label>' +  
                       '		<input class="upload_image_input" type="file" name="upload_image[]" size="' + settings.fileInputSize + '" />' + 
                       '	</div>';
                if(settings.linkModel == true) {
                	cnt += '<div class="form_upload_input">' +
                		   '	<label>Link Type:</label>' +
                		   '	<select name="data[extra][link_model][]">' + 
                		   '		<option value="products">Product</option>' + 
                		   '		<option value="categories">Category</option>' +
                		   '	</select>' +
                		   '</div>' +
                		   '<div class="form_upload_input">' +
                		   '	<label>Link ID:</label>' +
                		   '	<input class="short_field extra_mid" type="text" name="data[extra][link_model_id][]">' + 
                		   '</div>' + 
                		   '<div class="form_upload_input">' +
                		   '	<label>Link Description:</label>' +
                		   '	<input class="middle_field extra_alt" type="text" name="data[extra][alt_text][]">' + 
                		   '</div>';
                }
                cnt += '	<div class="form_upload_input">' +
                	   '		<div id="upload_image_' + (i+1) + '" class="upload_image_msg"></div>' +
                	   '	</div>' +
                       '</li>';
            }
            cnt += '	<li class="form_upload_field">' +
                   '		<input type="button" name="btn_upload" id="btn_upload" value="Upload" />' +
                   '		<div id="upload_prog_loading">Uploading ... Please wait.</div>' + 
                   '	</li>' +
                   '</ul></div>' +
                   '<div style="font-weight:bold;">' +
                   '	Note: acceptable image(.jpg, .png, .gif); less than 5MB.' +
                   '</div>' +
                   '</div></form>';
                            
            $("body").popwindow({
                content: cnt,
                title: 'Add Images',
                width: settings.width,
                height: settings.height,
                onLoaded: function() {
                    $("#form_upload input#btn_upload").click(function() {
                    		var errMsg = '';
                    		if (settings.linkModel == true) {
                    			$("#form_upload input.upload_image_input").each(function(i,val) {
                    				if ($(val).val() != '') {
                    					if ($(val).parent().parent().find('input.extra_mid')[0].value == '') {
                    						errMsg += '<li>No. ' + (i+1) + 
                    											' image link ID can not be empty.' +
                    											'	Please enter a valid ID.' + 
                    											'</li>';
                    					}
                    				}
                    			});
                    		}
                    		if (errMsg != '') {
                    			var errMsg = '<ul class="error_msg"><li>Errors:</li>' + errMsg + '</ul>';
                    			$("div#upload_form_errors").html(errMsg);
                    		} else {
                       		submitUploadForm(settings);
                       	}
                    });
                    $("#form_upload input.upload_image_input").change(function() {
                        $(this).parent().parent().find("div.upload_image_msg")
                            .removeClass('upload_image_ok upload_image_fail')
                            .hide();
                    });
                },
                onClosed: function() {
                    loadImages(settings);
                }
            });
        });
        loadImages(settings);
    }
})(jQuery);
