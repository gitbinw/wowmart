// JavaScript Document
(function($) {
	var settings = {};
	var options  = {};
	
	$.fn.inlineedit = function (inSettings, inOptions) {
		settings = $.extend({}, $.fn.inlineedit.default_settings, inSettings);
		options  = $.extend({}, $.fn.inlineedit.default_options, inOptions);
		var thisObj  = this;
		var fieldId = settings.fieldId;
		return this.each(function() {
			$(this).children('.edit').click(function() {
				if ($(this).hasClass('update')) {
					var fieldName  = inSettings.fieldName ? inSettings.fieldName : fieldId;
					var fieldValue = $(this).siblings('.field').find('#' + fieldName).val();
	
					var params = 'field=' + fieldName + '&value=' + fieldValue;
					var thisField = $(this)[0];
					var options = {
						type: 'POST',
						dataType: 'json',
						data: params,
						url: inSettings.updateUrl + '?uq=' + (new Date()).getTime(),
						beforeSend: function() {},
						success: function(data) {
							if (data.success == false) {
								displayError(thisField, data.errors);
							} else {
								$(thisField).siblings('div.form_error').remove();
								if (inSettings.type != 'password') {
									$(thisObj).children('.text').children('span').html(
										$(thisObj).children('.field').find('#' + fieldId).val()
									);
								}
								showNormal(thisObj);
							}
						}
					}
		
					$.ajax(options);
				} else {
					$(this).siblings('.text').hide();
					$(this).siblings('.cancel').show();
					$(this).toggleClass('update');
					$(this).html('Update');
					
					$(this).siblings('.field').show();
					if (inSettings.type == 'autopicker_location') {
			   			$(this).siblings('.field').find('#' + fieldId).autopicker({
							url : '/locations/ajaxSearch', 
							returnField : inSettings.fieldName, 
							otherFields : inSettings.otherFields
						});
						$(this).siblings('.field').find('select').combobox();
					}
					var content = $(this).siblings('.text').children('span').text();
					if (inSettings.type == 'password') content ='';
					
					$(this).siblings('.field').find('#' + fieldId)
						.focus()
						.val(content)
						.select();
						
					$(document).bind('click', function(e) {
						e.stopPropagation();
						//If click somewhere else in the page, it will hide the fields.
						if ($(e.target).parents('.field:first').html() == null && e.target != $(thisObj).children('.edit')[0]) {
							showNormal(thisObj);
						}
					});
				}
				
				$(this).siblings('.cancel').click(function() {
					showNormal(thisObj);
				});
			});
		});
	};
	
	/*********************Public Access to Properties****************************/
	$.fn.inlineedit.default_settings = {
			updateUrl: "",
			cancel: true,
			fieldId: ''
	};
	
	$.fn.inlineedit.default_options = {};
	
	/*********************Public Accessed Methods*****************************/
	$.fn.inlineedit.debug = function() {
	};
	
	/*********************End of Public Accessed Methods**********************/
	
	function showNormal(thisObj) {
		$(thisObj).children('.field').hide();
		$(thisObj).children('.text').show();
		$(thisObj).children('.edit')[0].className = 'edit';
		$(thisObj).children('.edit').html('Edit');
		$(thisObj).children('.cancel').hide();
		clearErrors(thisObj);
		$(document).unbind('click');
	}
	
	function displayError(fieldId, errorMsg) {
		var err = $(fieldId).siblings('div.form_error').html();
		if (err == null) {
			var msg = "<div class='form_error'>" + errorMsg + "</div>";
			$(fieldId).after(msg);
		} else {
			$(fieldId).siblings('div.form_error').html(errorMsg);
		}
	}
	
	function clearErrors(thisObj) {
		$(thisObj).children("div.form_error").empty();
	}
	
})(jQuery);