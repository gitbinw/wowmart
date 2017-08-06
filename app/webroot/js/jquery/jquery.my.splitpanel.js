// JavaScript Document
(function($) {
	var settings = {};
	var options  = {};
	var windowWidth  = 0;
	var panelHeight  = 0;
	var panelWidth   = 0;
	var leftPanelW   = 0;
	var rightPanelW  = 0;
	var fakeSplitL   = 0;
	var isLeftHide   = false;
	var mouseDown    = false;
	var isFullScreen = false;
	
	$.fn.splitpanel = function (inSettings, inOptions) {
		windowWidth = $(window).width();
		panelHeight = $(this).height();
		panelWidth  = $(this).width();
		settings = $.extend({}, $.fn.splitpanel.default_settings, inSettings);
		options  = $.extend({}, $.fn.splitpanel.default_options, inOptions);
		leftPanelW  = $("#" + settings.leftPanelID).width();
		rightPanelW = $("#" + settings.rightPanelID).width();
		
		/*****Create a mask panel*****/
		$(this).css('overflow', 'hidden');
		var maskPanelW = $(this).width() + leftPanelW;
		var newHtml = "<div id='" + options.splitPanelMaskID + "' style='height:100%;width:" + maskPanelW + "px'>"
					+ $(this).html()
					+ "</div>";
		$(this).html(newHtml);
		
		return this.each(function() {
			loadSplitter(this);
			if (settings.needFullScreen == true) setupFullScreen(this);
		});
	};
	
	/*********************Public Access to Properties****************************/
	$.fn.splitpanel.default_settings = {
		leftPanelID: "splitpanel_left",
		rightPanelID: "splitpanel_right",
		fullScreenButton: "splitpanel_fullscreen",
		needFullScreen: true,
		fullScreenID: [],
		needHideBar: true,
		fixHideBar: false,
		hideBarSpeed: 500,
		slidableBar: true
	};
	
	$.fn.splitpanel.default_options = {
		splitPanelBarID         : 'splitpanel_bar',
		splitPanelBarClass      : 'splitpanel_bar',
		splitPanelKnobID        : 'splitpanel_bar_knob',
		splitPanelKnobClass     : 'splitpanel_bar_knob',
		splitPanelKnobHideClass : 'splitpanel_bar_knob_hide',
		splitPanelKnobTitle     : 'Hide Left',
		splitPanelKnobHideTitle : 'Show Left',
		splitPanelMaskID        : 'splitpanel_mask',
		splitPanelFakeBarID     : 'splitpanel_fake',
		splitPanelFakeBarClass  : 'splitpanel_fake',
		splitPanelFakeMaxRight  : 50,
		splitPanelFakeMinLeft   : 50,
		splitPanelFullScreen    : 'splitpanel_fullscreen',
		splitPanelNormalScreen  : 'splitpanel_normalscreen',
		splitPanelFullTitle     : 'Full Screen',
		splitPanelNormalTitle   : 'Normal Screen'
	};
	
	/*********************Public Accessed Methods*****************************/
	$.fn.splitpanel.debug = function() {
	};
	
	$.fn.splitpanel.refreshHeight = refreshHeight;
	
	/*********************End of Public Accessed Methods**********************/
	
	/*********************Private Methods Here*******************************/
	function refreshHeight(minHeight) {
		var lftH = $("#" + settings.leftPanelID).height();
		var rgtH = $("#" + settings.rightPanelID).height();
		
		if (rgtH > minHeight) {
			newHeight = rgtH;
		} else {
			newHeight = minHeight;
		}
		
		$("#" + settings.leftPanelID).css({
			height: newHeight + 'px'
		});
		$("#" + options.splitPanelBarID).css({
			height: newHeight + 'px'
		});

		var kh = parseInt($("#" + options.splitPanelBarID).height() / 2)
				- parseInt($("#" + options.splitPanelKnobID).height() / 2);
		$("#" + options.splitPanelKnobID).css({
			top: kh + 'px'
		});
	}
	
	function loadSplitter (thisObj) {
		var splitterH = panelHeight;
		var leftPanel = $("#" + settings.leftPanelID);
		if (splitterH == 0) splitterH = "500";
		var splitter = "<div id='" + options.splitPanelBarID + "' class='" + options.splitPanelBarClass + "' style='height:" + splitterH + "px;'>";
					 + "</div>";
		leftPanel.after(splitter);
		if (settings.needHideBar == true) {
			$("#" + options.splitPanelBarID).append("<div id='" + options.splitPanelKnobID + "' class='" + options.splitPanelKnobClass + "' title='"
													 + options.splitPanelKnobTitle + "'></div>");
			knobH = parseInt(splitterH / 2) - parseInt($("#" + options.splitPanelKnobID).height() / 2);
			if (knobH < 0) knobH = 0;
			$("#" + options.splitPanelKnobID)
				.css({'top' : knobH})
				.click(function() {
					togglePanel();
				});
		}
		if (settings.slidableBar == true) {
			$("#" + options.splitPanelBarID).css('cursor', 'e-resize');
			var fakebar = "<div id='" + options.splitPanelFakeBarID + "' class='" + options.splitPanelFakeBarClass + "' style='height:" + splitterH 
			            + "px;'></div>";
			$("#" + options.splitPanelBarID).after(fakebar);
			$("#" + options.splitPanelBarID).mousedown( function(e) {
				if (e.target.id == options.splitPanelBarID) {
					fakeSplitL = $("#" + options.splitPanelBarID).offset().left - $(thisObj).offset().left;
					mouseDown = true;
					$("#" + options.splitPanelFakeBarID)
						.css('left', fakeSplitL + 'px')
						.show();
				}
			});
			$(thisObj).mousemove(function(e) {
					var ml = e.pageX - $(thisObj).offset().left;
					var mr = $(thisObj).width() + $(thisObj).offset().left - e.pageX;

					if (mouseDown == true) {
						disableTextSelect(thisObj);
						if (mr > options.splitPanelFakeMaxRight && 
							((isLeftHide == false && ml > options.splitPanelFakeMinLeft) || isLeftHide == true)
						) {
							var dw   = e.pageX - $("#" + options.splitPanelBarID).offset().left;
							var curL = $("#" + options.splitPanelFakeBarID).css('left');
							var newL = fakeSplitL + parseInt(dw);
							$("#" + options.splitPanelFakeBarID).css('left', newL + 'px');	
						}
					}
				})
			$(document).mouseup(function(e) {
					enableTextSelect(thisObj);
					if (mouseDown == true) {
						mouseDown = false;
						
						var ml = e.pageX - $(thisObj).offset().left;
						if (isLeftHide == true && ml <= options.splitPanelFakeMinLeft) {
							$("#" + options.splitPanelFakeBarID).hide();
							return;
						}
						
						var newW = $("#" + options.splitPanelFakeBarID).offset().left - $(thisObj).offset().left;
						var drw  = newW - leftPanel.width();
						leftPanelW  = newW;
						rightPanelW = $("#" + settings.rightPanelID).width() - drw;
						if (leftPanelW <= 0 ) {
							setLeftPanelStatus('hide');
						} else {
							setLeftPanelStatus('show');
						}
						leftPanel.css('width', newW + 'px');
						$("#" + settings.rightPanelID).css('width', rightPanelW + 'px');
						
						$("#" + options.splitPanelFakeBarID).hide();
					}
				});				
		}
	}
	
	function setupFullScreen(thisObj) {
		$("#" + settings.fullScreenButton).click(function() {
			var newW = windowWidth;
			
			if (isFullScreen == false) {
				var dw = newW - panelWidth;
				resetPanels(thisObj, newW, dw);
				
				isFullScreen = true;
				$(this)
					.removeClass(options.splitPanelFullScreen)
					.addClass(options.splitPanelNormalScreen)
					.attr('title', options.splitPanelNormalTitle);
				
			} else {
				var dw = panelWidth - newW;
				resetPanels(thisObj, panelWidth, dw);
				
				isFullScreen = false;
				$(this)
					.removeClass(options.splitPanelNormalScreen)
					.addClass(options.splitPanelFullScreen)
					.attr('title', options.splitPanelFullTitle);
			}
		});
		
		$(window).resize(function() {
			var ww = $(window).width();
			var dww = ww - windowWidth;
			windowWidth = ww;
			if (isFullScreen == true) {	
				resetPanels(thisObj, ww, dww);
			}
		});
	}
	
	function resetPanels (thisObj, newWidth, dw) {
		rightPanelW = $("#" + settings.rightPanelID).width() + dw;
		var maskPanelW  = $("#" + options.splitPanelMaskID).width() + dw;
		$.each(settings.fullScreenID, function(i, val) {
			var nw = $("#" + val).width() + dw;
			$("#" + val).css('width', nw + 'px');
		});
		$("#" + options.splitPanelMaskID).css('width', maskPanelW + 'px');
		$("#" + settings.rightPanelID).css('width', rightPanelW + 'px');
		$(thisObj).css('width', newWidth + 'px');
		
		var maxLeft = $(thisObj).width() - options.splitPanelFakeMaxRight;
		if ( $("#" + settings.leftPanelID).width() > maxLeft) {
			leftPanelW  = maxLeft;
			rightPanelW = options.splitPanelFakeMaxRight;
			$("#" + settings.rightPanelID).css('width', rightPanelW + 'px');
			$("#" + settings.leftPanelID).css('width', leftPanelW + 'px');
		}
	}
	
	function togglePanel() {
		if (isLeftHide == true) {
			showPanel();
		} else {
			hidePanel();
		}
	}
	
	function setLeftPanelStatus (st) {
		switch (st) {
			case 'show' :
				isLeftHide = false;
				$("#" + options.splitPanelKnobID)
					.removeClass(options.splitPanelKnobHideClass)
					.addClass(options.splitPanelKnobClass)
					.attr('title', options.splitPanelKnobTitle);
				break;
			case 'hide' :
				isLeftHide = true;
				$("#" + options.splitPanelKnobID)
					.removeClass(options.splitPanelKnobClass)
					.addClass(options.splitPanelKnobHideClass)
					.attr('title', options.splitPanelKnobHideTitle);
				break;
		}
	}
	
	function hidePanel() {
		$("#" + settings.rightPanelID).css('width', leftPanelW + rightPanelW + 'px');
		$("#" + settings.leftPanelID).animate(
			{
				'width' : '-=' + leftPanelW + 'px'
			},
			{
				'duration' : settings.hideBarSpeed,
			 	'complete' : function () {
					$(this).hide();
					setLeftPanelStatus('hide');
				}
			}
		);
	};
	
	function showPanel() {
		$("#" + settings.leftPanelID)
			.show()
			.animate(
			{
				'width' : '+=' + leftPanelW + 'px'
			},
			{
				'duration' : settings.hideBarSpeed,
				'complete' : function() {
					setLeftPanelStatus('show');
					rightPanelW = $("#" + settings.rightPanelID).width() - leftPanelW;
					$("#" + settings.rightPanelID).css('width', rightPanelW + 'px');
				}
			}
		);
	};

	function disableTextSelect(thisObj) {
		$(thisObj).css('MozUserSelect', 'none');// for Firefox
		$(thisObj).bind('selectstart', function() { return false; }); //for IE, Safari
	}
	
	function enableTextSelect(thisObj) {
		$(thisObj).css('MozUserSelect', 'inherit');// for Firefox
		$(thisObj).unbind('selectstart'); //for IE, Safari
	}
	/********************************End of Private Methods*************************************/
})(jQuery);