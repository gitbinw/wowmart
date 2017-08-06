// JavaScript Document
(function($) {
	var thisObj   = null;
	var settings  = {};
	var options   = {};
	var originX   = 0;
	var originY   = 0;
	var moveStartX = 0;
	var moveStartY = 0;
	var moveOriginX = 0;
	var moveOriginY = 0;
	var moveOriginW = 0;
	var moveOriginH = 0;
	var mouseDown = false;
	var isMoving  = false;
	var isResize  = false;
	var popwin = null;
	var popwinInitLeft = 0;
	var popwinInitTop  = 200;
	var mainBorderWidthTop    = 0;
	var mainBorderWidthBottom = 0;
	var mainBorderWidthLeft   = 0;
	var mainBorderWidthRight  = 0;
	
	jQuery.fn.popwindow = function (inSettings, inOptions) {
		settings = jQuery.extend({}, jQuery.fn.popwindow.default_settings, inSettings);
		options  = jQuery.extend({}, jQuery.fn.popwindow.default_options, inOptions);
		
		originX  = jQuery(this).offset().left;
		originY  = jQuery(this).offset().top;
		jQuery(this).css({
			position: 'relative'
		});
		popwinInitLeft = (jQuery(this).width()  - settings.width ) / 2;
		if (settings.location.top) popwinInitTop = settings.location.top;
		if (settings.location.left) popwinInitLeft = settings.location.left;
		
		return this.each(function() {
			thisObj = this;
			if (jQuery("#" + settings.name).attr('id') == null) {
				initWindow();
				addResizeHook();
				adjustWindowForIE6();
				showEffects();
			} else {
				jQuery("#" + settings.name + " div.popwindow_title").html(settings.title);
				jQuery("#" + settings.name + " div.popwindow_main").html(settings.content);
				if (jQuery("#" + settings.name).css('display') == 'none') {
					jQuery("#" + settings.name).css({
						width: settings.width + 'px'
					});
					jQuery("#" + settings.name + " div.popwindow_main").css({
						width:  settings.width - mainBorderWidthLeft - mainBorderWidthRight + 'px'
					});
					jQuery("#" + settings.name + " div.popwindow_header_middle").css({
						width:  settings.width - settings.headerBorderWidth * 2 + 'px'
					});
					updateResizeHook();
					adjustWindowForIE6();
					showEffects();
				}
			}
		});
	};
	
	/*********************Public Access to Properties****************************/
	jQuery.fn.popwindow.default_settings = {
		content: '',
		resizeArea: 11,
		resizableParts: ["div.popwindow_header_middle"],
		headerBorderWidth: 3,
		headerHeight: 30,
		width: 500,
		height: 400,
		position: 'center',
		location: {},
		name: 'popwindow',
		title: 'Popup Window',
		zindex: 1000,
		effects: {show: 'flyIn', hide: 'flyOut', speed: 500},
		onLoaded: function() {},
		onClosed: function() {}
	};
	
	jQuery.fn.popwindow.default_options = {
		
	};
	
	/*********************Public Accessed Methods*****************************/
	jQuery.fn.popwindow.debug = function() {
	};
	
	jQuery.fn.popwindow.close = hideEffects;
	
	jQuery.fn.popwindow.stopPropagation = stopPropagation;
	
	/*********************End of Public Accessed Methods**********************/
	
	
	/*********************Private Methods Here*******************************/
	function stopPropagation() {
		jQuery("#" + settings.name).click(function(e) {
			e.stopPropagation();
		});
	}
	
	function adjustWindowForIE6() {
		if (jQuery.browser.msie && jQuery.browser.version == '6.0') {
			var w = jQuery.trim(jQuery("#" + settings.name + " div.popwindow_main").css('width'));
			w = parseInt(w.substr(0, w.length - 2)); 
			jQuery("#" + settings.name + " div.popwindow_main").css({
				width:  w - 2 + 'px',
				marginLeft: '1px'
			});
			var t = jQuery.trim(jQuery("#" + settings.name + " div.popwindow_resize").css('top'));
			t = parseInt(t.substr(0, t.length - 2)); 
			var l = jQuery.trim(jQuery("#" + settings.name + " div.popwindow_resize").css('left'));
			l = parseInt(l.substr(0, l.length - 2));
			jQuery("#" + settings.name + " div.popwindow_resize").css({
				top:  t - 2 + 'px',
				left: l - (isResize ? 2 : 1) + 'px'
			});
		}
	}
	
	function initWindow () {
		var winCont = "<div class='popwindow_body'>"
					+ "	<div class='popwindow_header'>" 
					+ "		<div class='popwindow_header_left'></div>"
					+ "		<div class='popwindow_header_middle'>"
					+ "			<div class='popwindow_close'>X</div>"
					+ "			<div class='popwindow_title'>" + settings.title + "</div>"
					+ "		</div>"
					+ "		<div class='popwindow_header_right'></div>"
					+ "	</div>"
					+ "	<div class='popwindow_main'>"
					+		settings.content 
					+ " </div>"
					+ "	<div class='popwindow_footer'>"
					+ " </div>"
					+ "	<div class='popwindow_side_btm'></div>" 
					+ " <div class='popwindow_side_rgt'></div>"
					+ " <div class='popwindow_top_lft'></div>"
					+ " <div class='popwindow_top_rgt'></div>"
					+ " <div class='popwindow_btm_lft'></div>"
					+ " <div class='popwindow_btm_rgt'></div>"
					+ "</div>";
			
		popwin = document.createElement('div');

		jQuery(popwin)
			.css({
				display: 'none',
				zIndex: settings.zindex,
				position: 'absolute',
				width: settings.width + 'px'
			})
			.attr({id:settings.name})
			.addClass('popwindow_default')
			.html(winCont);
		
		jQuery(thisObj).append(jQuery(popwin));
		
		var strBorderWidthTop    = jQuery.trim(jQuery("#" + settings.name + " div.popwindow_main").css('borderTopWidth'));
		if (strBorderWidthTop.indexOf('px') != -1)
			mainBorderWidthTop    = strBorderWidthTop.substr(0, strBorderWidthTop.length - 2);
		var strBorderWidthBottom = jQuery.trim(jQuery("#" + settings.name + " div.popwindow_main").css('borderBottomWidth'));
		if (strBorderWidthBottom.indexOf('px') != -1)
			mainBorderWidthBottom = strBorderWidthBottom.substr(0, strBorderWidthBottom.length - 2);
		var strBorderWidthLeft   = jQuery.trim(jQuery("#" + settings.name + " div.popwindow_main").css('borderLeftWidth'));
		if (strBorderWidthLeft.indexOf('px') != -1)
			mainBorderWidthLeft   = strBorderWidthLeft.substr(0, strBorderWidthLeft.length - 2);
		var strBorderWidthRight  = jQuery.trim(jQuery("#" + settings.name + " div.popwindow_main").css('borderRightWidth'));
		if (strBorderWidthRight.indexOf('px') != -1)
			mainBorderWidthRight  = strBorderWidthRight.substr(0, strBorderWidthRight.length - 2);
																
		jQuery("#" + settings.name + " div.popwindow_header_middle").css({width: settings.width - settings.headerBorderWidth * 2 + 'px'});
		jQuery("#" + settings.name + " div.popwindow_main").css({
			width:  settings.width - mainBorderWidthLeft - mainBorderWidthRight + 'px'
		});
		
		jQuery("div.popwindow_header")
			.mousedown(function(e) {
				popwin = jQuery(this).parent().parent();  //to fix moving delay
				disableTextSelect();
				mouseDown = true;
				isMoving = true;
				moveStartX = e.pageX;
				moveStartY = e.pageY;
				moveOriginX = jQuery(this).offset().left - originX;
				moveOriginY = jQuery(this).offset().top  - originY;
			})
			.mouseup(function() {
				mouseDown = false;
				isMoving  = false;
			});
			
		jQuery("div.popwindow_close")
			.click(function() {
				hideEffects();
			})
			.mouseover(function() {
				jQuery(this).addClass('popwindow_close_hover');
			})
			.mouseout(function() {
				jQuery(this).removeClass('popwindow_close_hover');
			});
		
		jQuery(thisObj)
			.mousemove(function(e) {
				if (mouseDown) {
					if (isResize) {
						//$("#splitpanel_left").html('left:' + jQuery(popwin).offset().left + "<Br>top:" +jQuery(popwin).offset().top + "<br>width:"+ jQuery(popwin).width()+"<br>height:"+jQuery(popwin).height()+"<br>originX:"+(e.pageX - moveStartX));
						var newW = moveOriginW + e.pageX - moveStartX;
						var newH = moveOriginH + e.pageY - moveStartY;
						jQuery(popwin)
							.css({
								width: newW + 'px',
								height: newH + 'px'
							});
						jQuery('#' + settings.name + ' div.popwindow_main').css({
								width: newW - mainBorderWidthLeft - mainBorderWidthRight + 'px',
								height: newH - settings.headerHeight - mainBorderWidthTop - mainBorderWidthBottom + 'px'
							});
						jQuery.each(settings.resizableParts, function(i, val) {
							jQuery(popwin).find(val).css({
								width: (newW - settings.headerBorderWidth * 2) + 'px'
							});
						});
						updateResizeHook();
						adjustWindowForIE6();
					} else if (isMoving) {
						var newLeft = moveOriginX + e.pageX - moveStartX;
						var newTop  = moveOriginY + e.pageY - moveStartY;
						jQuery(popwin).css({
							left: newLeft + 'px',
							top: newTop + 'px'
						});
					}
				}
			})
			.mouseup(function() {
				mouseDown = false;
				isMoving  = false;
				isResize  = false;
			});
		
		jQuery(document).mouseup(function(e) {
			mouseDown = false;
			isMoving  = false;
			isResize  = false;
			enableTextSelect();
		})
	}
	
	function showEffects() {
		if ($("body").children('.popwindow_mask').length == 0) {
			var mask = document.createElement('div');
			$(mask)
				.addClass('popwindow_mask')
				.css({
					width: document.body.clientWidth + 'px',
					height: $(document).height() + 'px',
					left: 0,
					top: 0,
					position: 'absolute',
					opacity: 0.5
				});
				$("body").append(mask);
				
				$(window).resize(function() {
				$(mask)
					.css({
						width: document.body.clientWidth + 'px',
						height: $(document).height() + 'px'
				});
			});
		} else {
			$('.popwindow_mask').show();
		}
		
		if (settings.effects.show == 'fadeIn') {
			jQuery("#" + settings.name)
				.css({
					left: popwinInitLeft + 'px',
					top:  popwinInitTop  + 'px'
				})
				.fadeIn();
		} else if (settings.effects.show == 'flyIn') {			
			jQuery("#" + settings.name)
				.css({
					opacity: 0,
					left: originX + 'px',
					top:  popwinInitTop  + 'px'
				})
				.show()
				.animate({
					left: '+=' + popwinInitLeft + 'px',
					opacity: 1
				},  settings.effects.speed, 'linear', function() {
					jQuery(this).css('opacity', 'none');
					settings.onLoaded();
				}
			);
		} else if (settings.effects.show == 'slideDown') {
			jQuery("#" + settings.name)
				.css({
					left: popwinInitLeft + 'px',
					top:  popwinInitTop  + 'px'
				})
				.slideDown('normal', function() {settings.onLoaded();});
		}
	}
	
	function hideEffects(callback) {
		$('.popwindow_mask').hide();
		
		if (settings.effects.hide == 'fadeOut') {
			jQuery("#" + settings.name)
				.fadeOut();
		} else if (settings.effects.hide == 'flyOut') {		
			jQuery("#" + settings.name)
				.animate({
						left: '-=' + popwinInitLeft + 'px',
						opacity: 0
					}, settings.effects.speed, 'linear', function() {
						jQuery(this).hide();
						
						if (callback != null) callback.apply(this);
						settings.onClosed();
					}
				);
		} else if (settings.effects.hide == 'slideUp') {
			jQuery("#" + settings.name)
				.slideUp('normal', function() {settings.onClosed();});
		}
	}
	
	function updateResizeHook() {
		var lft = jQuery(popwin).width()  - settings.resizeArea - 1;
		var top = jQuery(popwin).height() - settings.resizeArea - 1;
		jQuery("#" + settings.name + " div.popwindow_resize").css({
			left: lft + 'px',
			top: top + 'px'
		});
	}
	
	function addResizeHook() {
		var lft = jQuery(popwin).width()  - settings.resizeArea - 1;
		var top = jQuery(popwin).height() - settings.resizeArea - 1;
		var tmp = document.createElement('div');
		jQuery(tmp)
			.addClass('popwindow_resize')
			.css({
				position: 'absolute',
				background: 'transparent url(img/popwindow_resize.gif) no-repeat center center',
				width: settings.resizeArea + 'px',
				height: settings.resizeArea + 'px',
				left: lft + 'px',
				top: top + 'px'
			})
			.mouseover(function() {
				jQuery(this).css({cursor:'nw-resize'});
			})
			.mouseout(function() {
				jQuery(this).css({cursor:'default'});
			})
			.mousedown(function(e) {
				mouseDown = true;
				isResize = true;
				moveStartX = e.pageX;
				moveStartY = e.pageY;
				moveOriginW = jQuery(popwin).width();
				moveOriginH = jQuery(popwin).height();
			});
		jQuery(popwin).append(jQuery(tmp));
	}
	
	function removeResizeHook() {
		jQuery(popwin).find('div.popwindow_resize').remove();
	}
	
	function disableTextSelect() {
		jQuery(thisObj).css('MozUserSelect', 'none');// for Firefox
		jQuery(thisObj).bind('selectstart', function() { return false; }); //for IE, Safari
	}
	
	function enableTextSelect() {
		jQuery(thisObj).css('MozUserSelect', 'inherit');// for Firefox
		jQuery(thisObj).unbind('selectstart'); //for IE, Safari
	}
	
	/********************************End of Private Methods*************************************/
})(jQuery);