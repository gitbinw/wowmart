// JavaScript Document
(function($) {
	var settings = {};
	var options  = {};
	var thisObj  = null;
	var timer    = null;
	var icon_id  = '';
	var picker_id = '';
	var picker    = null;
	var picker_icon = null;
	var picker_focus = false;
	
	$.fn.autopicker = function (inSettings, inOptions) {
		settings = $.extend({}, $.fn.autopicker.default_settings, inSettings);
		options  = $.extend({}, $.fn.autopicker.default_options, inOptions);
		thisObj  = this;
		$(thisObj).attr('autoComplete', 'off');
		
		return this.each(function() {
				var pid = $(this).attr('id');
				var lft = $(this).offset().left - $(this).parent().offset().left;
				var top = $(this).offset().top - $(this).parent().offset().top;
				var wid = $(this).outerWidth();
				var pwid = wid - (wid - $(this).width()) / 2;
				var hgt = $(this).outerHeight();

				icon_id = pid + '_autopicker_loading';
				picker_id = pid + '_autopicker';
				
				removeAutopicker();
				$(this).after('<div id="' + icon_id + '" class="autopicker_loading"></div>');
				$(this).after('<div id="' + picker_id + '" class="autopicker"><ul></ul></div>');
				
				picker_parent = $(this).siblings('div#' + picker_id)[0];
				picker = $(picker_parent).children('ul')[0];
				picker_icon = $(this).siblings('div#' + icon_id)[0];
				picker_icon_padd = (hgt - $(picker_icon).height()) / 2;

				$(picker_icon)
					.css({
							display: 'none',
							position: 'absolute',
							left: (pwid - $(picker_icon).width()) + lft + 'px',
							top: top + picker_icon_padd + 'px'
					});
				$(picker_parent)
					.css({
						 	display: 'none',
							position: 'absolute',
							width: $(this).innerWidth() + 'px',
							left: lft + 'px',
							top: top + hgt + 'px',
							border: '1px solid #cccccc'
					})
					.parent().css({position: 'relative'});
				
				$(this)
					.keyup(function(e){
						if (e.keyCode == 40) {
								if ($(picker_parent).css('display') == 'none' && $(picker).html() != null && $.trim($(picker).html()) != "") {
									showPicker(true);
								} else {
									var pre = $(picker).children('li.autopicker_selected');
									pre.removeClass('autopicker_selected');
									if(pre.next().html() == null) {
										$(picker).children('li:first').addClass('autopicker_selected');
										$(picker_parent).scrollTop(0);
									} else {
										pre.next().addClass('autopicker_selected');
									}
									
									scrollDown();
								}
						} else if (e.keyCode == 38) {
								if ($(picker_parent).css('display') == 'none' && $(picker).html() != null && $.trim($(picker).html()) != "") {
									showPicker(true);
								} else {
									var pre = $(picker).children('li.autopicker_selected');
									pre.removeClass('autopicker_selected');
									if(pre.prev().html() == null) {
										$(picker).children('li:last').addClass('autopicker_selected');
										scrollDown();
									} else {
										pre.prev().addClass('autopicker_selected');
									}
									
									scrollUp();
								}
						} else if (e.keyCode == 13 || e.keyCode == 9 || e.keyCode == 27) {
							return false;
						} else {
    						var val = $(this).val();
    						if (val.length > 2) {
    								clearTimer();
    								timer = setTimeout(ajaxCall, 500);
    						} else {
    								clearTimer();
    								$(picker).empty();
    								setReturnValue('');
									$(picker_parent).hide();
    						}
    					}
				})
				.keypress(function(e) {
					if (e.keyCode == 13) { //RETURN
						if ($(picker_parent).css('display') == 'block') {
							selectItem();
							return false;
						}
					}
				})
				.keydown(function(e) {
					if (e.keyCode == 9) {			//TAB
						if ($(picker_parent).css('display') == 'block') {
							selectItem();
							return false;
						}
					} else if (e.keyCode == 27) {	//ESC
						$(this).val($(this).val());
						$(picker_parent).hide();
					}
				})
				.click(function(e) {
					e.stopPropagation();
					if ($(picker_parent).css('display') == 'none' && $(picker).html() != null && $.trim($(picker).html()) != "") {
						showPicker(true);
					}
				});
				
				bindFocusout(thisObj);
				
				$(picker_parent)
					.mousedown(function(e) {
						e.stopPropagation();
						$(thisObj).focus();
						suspendFocusout(thisObj);
					})
					.mouseleave(function(e) {
						e.stopPropagation();
						$(thisObj).focus();
						resumeFocusout(thisObj);
					});
		});
	};
	
	/*********************Public Access to Properties****************************/
	$.fn.autopicker.default_settings = {
			url: "",
			returnField: "",
			otherFields: []
	};
	
	$.fn.autopicker.default_options = {};
	
	/*********************Public Accessed Methods*****************************/
	$.fn.autopicker.remove = function() {
		
	};
	
	/*********************End of Public Accessed Methods**********************/
	function bindFocusout(thisObj) {
		if ($.browser['msie']) {
			$(thisObj).bind('blur', function(e) {
				hidePicker();
			});
		} else { //Fix the problem: combobox not blur after clicking another combobox on Safari, Chrome, Firefox
			$(thisObj).bind('focusout', function(e) {
				hidePicker();
			});
		} 
	}
	
	function suspendFocusout(thisObj) {
		$(thisObj).freezeEvents('blur');
		$(thisObj).freezeEvents('focusout');
	}
	
	function resumeFocusout(thisObj) {
		$(thisObj).unFreezeEvents('blur');
		$(thisObj).unFreezeEvents('focusout');
	}
	
	function unbindFocusout(thisObj) {
		$(thisObj).unbind('blur');
		$(thisObj).unbind('focusout');
	}
	
	function removeAutopicker() {
		$(thisObj).siblings('div#' + picker_id).remove();
		$(thisObj).siblings('div#' + icon_id).remove();
	}
	
	function disableEnterKey(e) {
		var key;
		if(window.event)
			key = window.event.keyCode;     //IE
		else
			key = e.which;     //firefox
		
		if(key == 13)
			return false;
		else
			return true;
	}

	function hidePicker() {
		$(picker_parent).hide();
	}
	
	function showPicker(defaultOption) {
		var nosel = false;
		$(picker).children('li.autopicker_selected').removeClass('autopicker_selected');
		$(picker_parent).show();
		if (defaultOption == true) {
			$(picker).children('li').each(function(i, val) {
				if ($(thisObj).val() == $(val).text()) {
					$(val).addClass('autopicker_selected');
					nosel = true;
				}
			});
			if (!nosel) {
				$(picker).children('li:first').addClass('autopicker_selected');
			}
			$(picker_parent).scrollTop(0);
			scrollDown();
		} else {
			$(picker).children('li:first').addClass('autopicker_selected');
			$(picker_parent).scrollTop(0);
		}
	}
	
	function scrollDown() {
		var nextli = $('li.autopicker_selected').next();
		var phgt = $(picker_parent).height();
		var itop = 0;
		if (nextli.html() != null) itop = nextli.offset().top - $(picker_parent).offset().top; 
		else {
			itop = $(picker).offset().top + $(picker_parent).height() + $(picker).height();
		}
		
		if (itop >= phgt) {
			var sctop = $(picker_parent).scrollTop();
			var dvtop = itop - phgt;
			$(picker_parent).scrollTop(sctop + dvtop);
		}
	}
	
	function scrollUp() {
		var itop = $('li.autopicker_selected').offset().top - $(picker_parent).offset().top;
		var phgt = $(picker_parent).height(); 
		if (itop <= phgt) {
			var sctop = $(picker_parent).scrollTop();
			var dvtop = $('li.autopicker_selected').height() - itop;
			$(picker_parent).scrollTop(sctop - dvtop);
		}
	}
	
	function pickItem() {
		$(picker).children('li')
			.mousedown(function(e) {
				e.stopPropagation();
				$(thisObj).focus();
				suspendFocusout(thisObj);
			})
			.mouseup(function(e) {
				e.stopPropagation();
				selectItem();
				resumeFocusout(thisObj);
			})
			.mouseover(function() {
				$(this).siblings('li.autopicker_selected').removeClass('autopicker_selected');
				$(this).addClass('autopicker_selected');
			});
	}
	
	function selectItem() {
			var value = $(picker).children('li.autopicker_selected').html();
			var id    = $(picker).children('li.autopicker_selected').attr('id');
			if (value == null) return;
			$(thisObj).val(value);
			setReturnValue(id);
			$(picker_parent).hide();
			$(thisObj).focus();
	}
	
	function setReturnValue(value) {
			if (settings.returnField) {
					$("#" + settings.returnField).val(value);
			}
	}
	
	function ajaxCall() {
			var params = $(thisObj).attr('name') + '=' + $(thisObj).val();
			$.each(settings.otherFields, function(i, val) {
				var pm = $('#' + val).attr('name') + '=' + $('#' + val).val();
				params += '&' + pm;
			});
			params += '&' + settings.data;
								
			var ajaxOptions = {
					url  : settings.url,
					type : 'post',
					dataType : 'json',
					data : params,
					beforeSend : function () {
							$(picker_icon).show();
					},
					success : function(data) {
							var items = '';
							if (data.success == true) {
									$.each(data.results, function(i, val) {
										items += '<li id="' + i + '">' + val + '</li>'; 
									});
							}
							$(picker).html(items);
							if (items == '') $(picker_parent).hide();
							else {
								pickItem();
								showPicker();
							}
							$(picker_icon).hide();
					}
			};
			$.ajax(ajaxOptions);
			
			clearTimer();
			setReturnValue('');
	}
	
	function clearTimer() {
		if (timer != null) {
				clearTimeout(timer);
				timer = null;
		}
	}
	
})(jQuery);