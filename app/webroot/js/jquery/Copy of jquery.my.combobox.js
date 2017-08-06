// JavaScript Document
(function($) {
	var settings = {};
	var options  = {};
	var currentCmb = null;
	
	$.fn.combobox = function (inSettings, inOptions) {
		settings = $.extend({}, $.fn.combobox.default_settings, inSettings);
		options  = $.extend({}, $.fn.combobox.default_options, inOptions);
		var thisObj  = this;
			
		return this.each(function() {
			//build options
			var opts = $(thisObj).children('option');
			var items = "";
			var defaultOption = '';

			$(thisObj).children('option').each(function(i, val) {
				var styleClass = "";
				if ($(val).attr('selected') == true) {
					styleClass = "class='option_selected'";
					defaultOption = $(val).text();
				}
				items += "<li " + styleClass + ">" + $(val).text() + "</li>";
			});
			var newCmbItems = document.createElement('ul');
			$(newCmbItems)
				.append(items);
			var newCmbList = document.createElement('div');
			$(newCmbList).append(newCmbItems);
			
			//display the selected option
			var newCmbSel = document.createElement('div');
			$(newCmbSel)
				.addClass('selected')
				.css({
				})
				.append("<div class='value'>" + defaultOption + "</div><div class='arrow'></div>");
				
			var newCmb = document.createElement('div');
			$(newCmb)
				.addClass('combobox')
				.css({
						position: 'absolute',
						top: $(thisObj).offset().top + 'px'
				})
				.attr({tabIndex : 0})
				.append(newCmbSel)
				.append(newCmbList);
			
			var p = null;
			if ($(thisObj).parents('form').html() != null) {
				p = $(thisObj).parents('form').parent();
			} else {
				p = $(thisObj).parent();
			}
			p.css({position : 'relative'});
			p.append(newCmb);
			
			$(thisObj).hide();	
				
			var wid = $(newCmbSel).innerWidth();
			$(newCmbList)
				.addClass('combobox_list')
				.css({
					display: 'none',
					width: wid + 'px',
					background: '#ffffff',
					zIndex: '1',
					border: '1px solid #cccccc',
					borderTop: 'none',
					overflow: 'auto'
				});

			
			$(newCmb)
				.focus(function() {
					$(newCmbSel).addClass('selected_focus');
					$(newCmbSel).children('.arrow').addClass('arrow_focus');
				})
				.keyup(function(e){
					if (e.keyCode == 40) {	//Down
						var pre = $(newCmbItems).children('li.option_selected');
						pre.removeClass('option_selected');
						if(pre.next().html() == null) {
							$(newCmbItems).children('li:first').addClass('option_selected');
							$(newCmbList).scrollTop(0);
						} else {
							pre.next().addClass('option_selected');
						}
								
						scrollDown(newCmbItems, newCmbList);
						selectItem2(thisObj, newCmbSel, newCmbList, newCmbItems);
					} else if (e.keyCode == 38) {	//Up
						var pre = $(newCmbItems).children('li.option_selected');
						pre.removeClass('option_selected');
						if(pre.prev().html() == null) {
							$(newCmbItems).children('li:last').addClass('option_selected');
							scrollDown(newCmbItems, newCmbList);
						} else {
							pre.prev().addClass('option_selected');
						}
								
						scrollUp(newCmbList);
						selectItem2(thisObj, newCmbSel, newCmbList, newCmbItems);
					} else if (e.keyCode == 13 || e.keyCode == 9 || e.keyCode == 27) {
						return false;
					}
				})
				.keypress(function(e) {
					if (e.keyCode == 13) { //RETURN
						if ($(newCmbItems).css('display') == 'block') {
							selectItem(thisObj, newCmbSel, newCmbList, newCmbItems);
							hideOptions(newCmbSel, newCmbList);
							return false;
						}
					}
				})
				.keydown(function(e) {
					if (e.keyCode == 9) {			//TAB
						selectItem(thisObj, newCmbSel, newCmbList, newCmbItems);
						hideOptions(newCmbSel, newCmbList);
						return false;
					} else if (e.keyCode == 27) {	//ESC
						var value = $(newCmbSel).children('.value').text();
						hideOptions(newCmbSel, newCmbList);
						$(newCmbItems).children('li.option_selected').removeClass('option_selected');
						$(newCmbItems).children("li:contains('" + value + "')").addClass('option_selected');
					}
				});
			
			if (!$.browser['msie']) { //Fix the problem: combobox not blur after clicking another combobox on none-IE
				$(newCmb)
					.focusout(function(e) {
						$(newCmbSel).removeClass('selected_focus');
						$(newCmbSel).children('.arrow').removeClass('arrow_focus');
						hideOptions(newCmbSel, newCmbList);
					});
			}
			
			$(newCmbSel)
				.mousedown(function(e) {
					e.stopPropagation();
					$(newCmb).focus();
					$(newCmb).unbind('blur');
				})
				.mouseup(function(e) {
					e.stopPropagation();
					$(newCmb).focus();
					showOptions(newCmbList, newCmbItems, newCmbSel);
					$(newCmb).bind('blur', function(e) {
						$(newCmbSel).removeClass('selected_focus');
						$(newCmbSel).children('.arrow').removeClass('arrow_focus');
						hideOptions(newCmbSel, newCmbList);
					});
				});
				
			$(newCmbItems).children('li')
				.mousedown(function(e) {
					e.stopPropagation();
					$(newCmb).unbind('blur');
				})
				.mouseup(function(e) {
					e.stopPropagation();
					selectItem(thisObj, newCmbSel, newCmbList, newCmbItems);
					hideOptions(newCmbSel, newCmbList);
					$(newCmb).bind('blur', function(e) {
						$(newCmbSel).removeClass('selected_focus');
						$(newCmbSel).children('.arrow').removeClass('arrow_focus');
						hideOptions(newCmbSel, newCmbList);
					});
				})
				.mouseover(function() {
					$(this).siblings('li.option_selected').removeClass('option_selected');
					$(this).addClass('option_selected');
				});
		});
	};
	
	/*********************End of Public Accessed Methods**********************/
	function resumeScroll() {
		$(document).unbind('keydown');
	}
	
	function disableScroll() {
		$(document)
			.keydown(function(e) {
				//disable window scrolling when press DOWN and UP
				if (e.keyCode == 40 || e.keyCode == 38) {
					e.preventDefault();
               		return false;  
				}
			});
	}
	
	function hideOptions(newCmbSel, newCmbList) {
		$(newCmbList).slideUp('fast');
	}
	
	function showOptions(newCmbList, newCmbItems, newCmbSel) {
		disableScroll();
		
		$(newCmbItems).children('li.option_selected').removeClass('option_selected');
		$(newCmbItems).children('li').each(function(i, val) {
			if ($(newCmbSel).children('.value').text() == $(val).text()) {
				$(val).addClass('option_selected');
			}
		});

		$(newCmbList).slideToggle('fast');
		$(newCmbList).scrollTop(0);
	}
	
	function scrollDown(newCmbItems, newCmbList) {
		var nextli = $('li.option_selected').next();
		var phgt = $(newCmbList).height();
		var itop = 0;
		if (nextli.html() != null) itop = nextli.offset().top - $(newCmbList).offset().top; 
		else {
			itop = $(newCmbItems).offset().top + $(newCmbList).height() + $(newCmbItems).height();
		}
		
		if (itop >= phgt) {
			var sctop = $(newCmbList).scrollTop();
			var dvtop = itop - phgt;
			$(newCmbList).scrollTop(sctop + dvtop);
		}
	}
	
	function scrollUp(newCmbList) {
		var itop = $('li.option_selected').offset().top - $(newCmbList).offset().top;
		var phgt = $(newCmbList).height(); 
		if (itop <= phgt) {
			var sctop = $(newCmbList).scrollTop();
			var dvtop = $('li.option_selected').height() - itop;
			$(newCmbList).scrollTop(sctop - dvtop);
		}
	}

	function selectItem(thisObj, newCmbSel, newCmbList, newCmbItems) {
		var value = $(newCmbItems).children('li.option_selected').text();
		$(newCmbSel).children('.value').text(value);
		$(thisObj).children('option').each(function(i, val) {
			if ($(val).text() == value) {
				$(val).attr('selected', true);
			}
		});
	}
	
	function selectItem2(thisObj, newCmbSel, newCmbList, newCmbItems) {
		if ($(newCmbList).css('display') == 'none') {
			var value = $(newCmbItems).children('li.option_selected').text();
			$(newCmbSel).children('.value').text(value);
			$(thisObj).children('option').each(function(i, val) {
				if ($(val).text() == value) {
					$(val).attr('selected', true);
				}
			});
		}
	}
	
})(jQuery);