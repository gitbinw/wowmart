$(function() {
	
	template_editor = $.namespace ({
		
		initHtml: '',
		popup_main_id: 'template-popup-main',
		popup_wrapper_id: 'template-popup-box',
		slides_wrapper_id: 'template-slides',
		afterSave: null,
		onTemplateClick: null,
		
		init: function(options) {
			if (options.init_html) this.initHtml = options.init_html;
			if (options.afterSave) this.afterSave = options.afterSave;
			if (options.wrapper_id) this.slides_wrapper_id = options.wrapper_id;
			if (options.onTemplateClick) this.onTemplateClick = options.onTemplateClick;
			
			this.initTemplatePopup();
			this.templateSlides(options);
			//this.removeLinkFromTemplate();
		},
		
		templateSlides: function(options) {
			var viewCount = 3,
				jData = options.data,
				$slideBox = $("#" + options.wrapper_id),
				$next = $slideBox.children('.slider-next'),
				$prev = $slideBox.children('.slider-prev'),
				$nav = $slideBox.children('.slider-nav'),
				$slideBody = $('.slider_body', $slideBox),
				htmlContent = '';

			var mod = jData.length % viewCount,
				offsetNum = mod > 0 ? (viewCount - mod) : 0;
					
			$slideBody.removeClass('load_icon');	
			htmlContent = '<div>';
			$.each(jData, function(i, val) {
				if (i % viewCount == 0 && i != 0) {
					htmlContent += '</div><div>';
				}
				htmlContent += '<a class="template-one" id="' + val.alias + '">' + 
							   '	<img src="/img/templates/' + val.alias + '.jpg" border="0" width=220 />' +
							   '	<div class="template-name">' + val.name + '</div>' +
							   '</a>';
			});
				
			for(var j=0; j<offsetNum; j++) {
				htmlContent += '<a class="template-one template-fake"></a>';
			}
					
			htmlContent += '</div>';
			
			/*calculate selected template*/
			var slideId = 0;
			if (options.selectedTemplate) {
				var arrId = options.selectedTemplate.split('-');
				
				slideId = parseInt(arrId[arrId.length - 1] / viewCount);
				if (arrId[arrId.length - 1] % viewCount == 0) slideId--;
			}
			/*****************************/	
			$slideBody
				.html(htmlContent)
				.cycle({ 
					fx:     'scrollHorz', 
					next:   $next,
					prev: 	$prev,
					pager:  $nav,
					before: function(curr, next, opts) { opts.animOut.opacity = 0; }, 
					timeout: 0,
					startingSlide: slideId
				});/*.touchwipe({
					wipeLeft: function() { $boxBody.cycle("next"); },
					wipeRight: function() { $boxBody.cycle("prev");  },
					wipeUp: function() { },
					wipeDown: function() { },
					min_move_x: 20,
					min_move_y: 20,
					preventDefaultEvents: true
				});*/
				
			this.addTemplateEvents($slideBody);
		},
		initTemplatePopup: function() {
			var $popup = $('#' + this.popup_wrapper_id);
			if (!$popup.length) {
				var htmlPopup = '<div id="' + this.popup_wrapper_id + '" class="hidden">' + 
								'	<div id="' + this.popup_main_id + '" class="template-edit-mode">' + 
								'	</div>' +
								'	<div class="template-editor-buttons">' + 
								'		<button id="template-btn-save">Save</button>' + 
								'	</div>' +
								'	<div class="template-loading-area"></div>' +
								'</div>';
				
				$('body').append(htmlPopup);
				
				$popup = $('#' + this.popup_wrapper_id);
			}
			
			$popup.dialog({
				autoOpen: false,
				modal: true,
				width: 980,
				open: function(e, ui) {
					if (template_editor.initHtml) {
						$('#' + template_editor.popup_main_id, $popup).html(template_editor.initHtml);
					}
				},
				close: function(e, ui) {
					template_editor.initHtml = '';
				}
			});
			
			//this.addBackButton();
		},
		loadTemplate: function(tplContent) {
			var $popup = $('#' + this.popup_wrapper_id);
			$('#' + this.popup_main_id, $popup).html(tplContent).fadeIn();
		},
		loadTextEditor: function(initText, $section, txtAreaId) {
			var posLeft = $section.position().left + 3,
				posTop = $section.position().top + 3,
				w = $section.width() - 7,
				h = $section.height() - 4,
				htmlEditor = '<div class="template-editor-box hidden" style="' + 
							 '	width:' + w + 'px;height:' + h +'px;left:' + posLeft + 'px;top:' + posTop + 'px;">' + 
							 '  <a class="template-htmleditor" href="javascript:showEditor(\'htmleditor/cmsedit.php\',\'' + txtAreaId + '\');"><b>Html Editor</b></a>' +
							 '	<textarea class="template-editor-textarea" id="' + txtAreaId + '" placeholder="Please enter text content">' + (initText ? initText : '') + '</textarea>' +  
							 '</div>'; 
			
			return htmlEditor;				 
		},
		loadImageEditor: function($section) {
			var posLeft = $section.position().left,
				posTop = $section.position().top,
				w = $section.width(),
				h = $section.height(),
				htmlEditor = '<div class="template-editor-box hidden" style="' + 
							 '	width:' + w + 'px;height:' + h +'px;left:' + posLeft + 'px;top:' + posTop + 'px;">' + 
							 '	<div class="template-image-section"></div>' + 
							// '	<div class="template-link-section">' + 
							// '		<label>Enter Link:</label>' + 
							// '		<input type="text" name="link" placeholder="Enter Link URL" />' + 
							// '	</div>' + 
							 '</div>';
			
			return htmlEditor;
		},
		addBackButton: function() {
			var btn = '<a id="template-edit-back" class="hidden">Back</a>';
			
			if (!$('#template-edit-back').length) {
				$('#' + this.popup_wrapper_id).prev('.ui-dialog-titlebar').append( btn );
				
				
			}
		},
		
		addTemplateEvents: function($slideWrapper) {
			$slideWrapper.off('click').on('click', '.template-one', function(e) {
				var tplId = this.id,
					$this = $(this),
					tplName = $this.children('.template-name').text();
				
				$slideWrapper.find('.template-selected').removeClass('template-selected');
				$this.addClass('template-selected');
				
				if (template_editor.onTemplateClick && $.isFunction(template_editor.onTemplateClick)) {
					template_editor.onTemplateClick(tplId);
				}
				
				template_editor.loadTemplate(template_center.get(tplId));
				$('#' + template_editor.popup_wrapper_id).dialog("option", "title", tplName);
				$('#' + template_editor.popup_wrapper_id).dialog('open');
			});
			
			$('#' + this.popup_main_id).off('click').on('click', '.template-section', function(e) {
				var $this = $(this);
				
				if ($this.hasClass('template-text')) {
					var htmlContent = $this.html(),
						$editor = $this.next('.template-editor-box');
					
					if (!$editor.length) {
						var txtAreaId = 'template-editor-text-' + (new Date()).getTime(),
							htmlEditor = template_editor.loadTextEditor(htmlContent, $this, txtAreaId);
						$this.after(htmlEditor);
						$editor = $this.next('.template-editor-box');
						template_editor.addTextEditorEvents($editor, txtAreaId);
					} else {
						var txtAreaId = $editor.children('textarea:first').attr('id');
						template_editor.addTextEditorEvents($editor, txtAreaId);
					}
					
					$editor.siblings('.template-editor-box').hide();
					$editor.show();
					$('textarea', $editor).focus();
					
				} else if ($this.hasClass('template-image')) {
					var $img = $this.find('img:first'),
						imgSrc = $img.length ? $img.attr('src') + '?uq=' + (new Date()).getTime() : '',
						$editor = $this.next('.template-editor-box');
					
					if (!$editor.length) {
						var htmlEditor = template_editor.loadImageEditor($this);
						var w = $this.width(),
							h = $this.height();
				
						$this.after(htmlEditor);
						$editor = $this.next('.template-editor-box');
						setXhrUploadPhoto({
							photo_parent: $editor.find('.template-image-section:first'),
							wrapper: 'media-upload-wrapper-' + (new Date()).getTime(),
							upload_info: 'Please upload ' + w + ' X ' + h + 'px image.',
							width: w,
							height: h,
							url: imgSrc,
							media_center: true,
							callback: function(data) {
								$('input#media_name').val(data.name);
								$('input#file_name').val(data.name);
								
								template_editor.saveImage(data, $this);
							}
						});
						
						//template_editor.addImageLinkEvents($editor);
					}
					
					$editor.siblings('.template-editor-box').hide();
					$editor.show();
					$('textarea', $editor).focus();
				}
			});
			
			$('#template-edit-back').unbind('click').click(function(e) {
				var $popup = $('#' + template_editor.popup_wrapper_id);
				
				$(this).hide();
				
				$('.template-editor-box:visible', $popup).fadeOut('fast', function() {
					
					$('#' + template_editor.popup_main_id).fadeIn();
				});
			});
			
			$('#template-btn-save').unbind('click').click(function(e) {
				template_editor.setupTextareaContent();
				
				$('#' + template_editor.popup_main_id).find('.template-editor-box').remove();
				
				var $dialog = $('#' + template_editor.popup_wrapper_id),
					content = $('#' + template_editor.popup_main_id).html(),
					title = $dialog.dialog("option", "title"),
					$currTpl = $('#' + template_editor.slides_wrapper_id).find('.template-selected:first'),
					currTplId = $currTpl.attr('id');
				
				$('#' + template_editor.slides_wrapper_id).find('.template-current').removeClass('template-current');
				$currTpl.addClass('template-current');
				
				if (template_editor.afterSave && $.isFunction(template_editor.afterSave)) {
					template_editor.afterSave({content: content, template_name: title, template_id: currTplId});
				}
				$dialog.dialog('close');
			});
		},
		setupTextareaContent: function() {
			$('#' + template_editor.popup_main_id + ' textarea.template-editor-textarea').each(function(i, val) {
				var $txt = $(val),
					$sec = $txt.parent().prev('.template-section:first');
				
				$sec.html($txt.val());
			});
		},
		saveImage: function(data, $this) {
			var params = data.media_id ? {media_id: data.media_id} : {
				file_name: data.name, 
				media_name: data.name, 
				file_src: data.file
			},
			site_root = window.g_site_root ? g_site_root : '';
			
			var opts = {
				url: site_root + '/admin/medias/saveMedia',
				type: 'POST',
				data: params,
				dataType: 'JSON',
				beforeSend: function() {},
				success: function(jData) {
					if (jData.status == 1) {
						$this.html('<img src="' + site_root + jData.data.url + '?uq=' + (new Date()).getTime() + '" />');
						$this.next('.template-editor-box').fadeOut();
					}
				}
			};
			
			$.ajax(opts);
		},
		addTextEditorEvents: function($editor, txtAreaId) {
			/*$('textarea', $editor).unbind('blur').blur(function(e) {
				var txt = $(this).val(),
					$section = $editor.prev('.template-section:first');
				
				$editor.hide();
				$section.addClass('template-typed').html(txt);
				$section.show();
			});*/
			$(document).unbind('click.styledby').bind('click.styledby', function(e) {
				var $target = $(e.target),
					$txtArea = $target.next('.template-editor-box').children('textarea:first');

				if (!$target.hasClass('template-text') 
						&& !$target.hasClass('template-htmleditor')
						&& !$target.parent().hasClass('template-htmleditor')
						&& !$target.hasClass('template-editor-textarea')
					) {
			
					var $txt = $('#' + txtAreaId),
						txt = $txt.val(),
						$section = $editor.prev('.template-section:first');

					$editor.hide();
					$section.addClass('template-typed').html(txt);
					$section.show();	
					
					$txt.removeClass('template-textarea-current');
				}
				
				if ($target.hasClass('template-editor-textarea')) {
					var $currTxt = $('#' + template_editor.popup_main_id + ' .template-textarea-current');
					if ($currTxt.length) {
						var txt = $currTxt.val();

						$currTxt.parent().prev('.template-section:first').html(txt);
							
						$currTxt.removeClass('template-textarea-current');
					}
					$target.addClass('template-textarea-current');
				}
				if ($target.hasClass('template-text')) {
					var $currTxt = $('#' + template_editor.popup_main_id + ' .template-textarea-current'),
						$tgTxtArea = $target.next('.template-editor-box').children('.template-editor-textarea');
					if ($currTxt.length) {
						var txt = $currTxt.val();

						$currTxt.parent().prev('.template-section:first').html(txt);
							
						$currTxt.removeClass('template-textarea-current');
					}
					$tgTxtArea.addClass('template-textarea-current');
				}
				
			});
		},
		addImageLinkEvents: function($editor) {
			$('input', $editor).unbind('blur').blur(function(e) {
				var lnkUrl = $(this).val(),
					$section = $editor.prev('.template-section:first');
				
				$section.find('img').wrap('<a href="' + lnkUrl + '"></a>');
				
				$section.find('a').unbind('click').click(function(e) {
					e.preventDefault();
				});
			});
		},
		removeLinkFromTemplate: function() {
			$('#' + this.popup_main_id).find('.template-image a').unbind('click').click(function(e) {
				e.preventDefault();
			});
		}
		
	});
	
});