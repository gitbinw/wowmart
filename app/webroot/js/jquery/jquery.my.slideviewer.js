// JavaScript Document
(function($) {
	var thisSlide = null;
	var thisSetting = {};
	$.fn.slideviewer = function (inSettings, inOptions) {	
		var settings = $.extend({}, $.fn.slideviewer.default_settings, inSettings);
		var options  = $.extend({}, $.fn.slideviewer.default_options, inOptions);
		var params   = settings.params != null ? settings.params : '';
		var thisObj = this;
		thisObj.sliding = false;
		thisSlide = this;
		
		settings.pause = false;
		settings.arrowDown = false;
		settings.decelerate = false;
		settings.direct = null;
		settings.velocity  = 0;
		settings.timeDuration = 0;
		settings.timeDivision = 1;
		settings.timer = null;
		settings.globalAcc = 0.2;
		settings.globalAccDec = 0.08;
		settings.maxV = 10;
		
		thisSetting = settings;
		
		return this.each(function() {
			if (settings.isAjax == true) {
				var ajaxOptions = {
					type: 'POST',
					url: settings.ajaxUrl,
					data: params,
					dataType: 'json',
					beforeSend: function() {
						thisObj.html('<div class="slider_loading"></div>');
					},
					success: function(data) {
						settings.totalItemCount = data.length;
						var output = '';
						var j = 1;
						settings.slideTotalCountNumber = Math.round(data.length / settings.slide_counts);
						
						if (settings.effects == 'noanimation') {
							output += '<div class="slider_item" id="' + thisObj.attr('id') + '_item_0" style="display:block;">'; //default one;
							$.each(data, function(i, val) {
								val['slide_text'] = "";
								var defString = '<div class="slide_button_1">Set Default</div>';
								var defImgClass = '';
								if (val['Image']['is_default'] == 1) {
									defString = '<div class="slide_button_txt">Default</div>';
									defImgClass = 'slide_img_default';
								}
								var itemDesc = '';
								var itemDesc = '<div class="slide_desc">' + 
											    		 '	<div>' + val['slide_text'] + 
											    		 '	</div>' +
											    		 '	<div class="slide_button_bar" id="' + val['Image']['id'] +'">' +
											    		 	defString +
											    		 '		<div class="slide_button_2">Delete</div>' +
											    		 '	</div>' +
											  		 	 '</div>';
								
								var alink = '';
								if (settings.clickLink == true) alink = '/' + val['Image']['link_model'] + '/view/' + val['Image']['link_model_id'];

								var itemImage = '<div class="slide_img ' + defImgClass + '">' +
												'	<a href="' + alink + '">';
								if (settings.useImageAsBackground) {
									itemImage += '<div class="slide_img_cnt" style="background:#ffffff url(' + settings.imageRoot + val['Image']['id'] + 
																	'/' + val['Image']['id'] + 'a' + settings.image_type + val['Image']['extension'] + ') no-repeat center center;"></div>';
								} else {
									var newSize = getImageSize(settings.image_width, settings.image_height, val['Image']['width'], val['Image']['height']);
									itemImage += '<div class="slide_img_cnt"><img src="' + settings.imageRoot + val['Image']['id'] + 
																	'/' + val['Image']['id'] + 'a' + settings.image_type + 
																	val['Image']['extension'] + '" width="' + newSize.width + 
																	'" height="' + newSize.height + '" border="0" /></div>';
								}
								itemImage += '</a>' + 
												'</div>';
								
								if (i % settings.slide_counts == 0 && i != 0) {
									var slider_item_id = thisObj.attr('id') + '_item_' + j;
									output += '</div><div class="slider_item" id="' + slider_item_id + '" style="display:none;">';
									j++;
								}
								output += '<div class="one_item" style="float: left;">';
										  
								if (settings.hideButtons == true) {
									output += itemImage;
								} else if (settings.imageOnTop == true) {
									output += itemImage + itemDesc;
								} else {
									output += itemDesc + itemImage;
								}
								
								output += '</div>';
							});
						} else {
							$.each(data, function(i, val) {
								val['slide_text'] = "";
								var defString = '<div class="slide_button_1">Set Default</div>';
								var defImgClass = '';
								if (val['Image']['is_default'] == 1) {
									defString = '<div class="slide_button_txt">Default</div>';
									defImgClass = 'slide_img_default';
								}
								var itemDesc = '<div class="slide_desc">' + 
											    		 '	<div>' + val['slide_text'] + 
											    		 '	</div>' +
											    		 '	<div class="slide_button_bar" id="' + val['Image']['id'] +'">' +
											    		 	defString +
											    		 '		<div class="slide_button_2">Delete</div>' +
											    		 '	</div>' +
											  		 	 '</div>';
								
								var alink = '';
								if (settings.clickLink == true) alink = '/' + val['Image']['link_model'] + '/view/' + val['Image']['link_model_id'];
								
								var itemImage = '<div class="slide_img ' + defImgClass + '">' +
												'	<a href="' + alink + '">';
								if (settings.useImageAsBackground) {
									itemImage += '<div class="slide_img_cnt" style="background:#ffffff url(' + settings.imageRoot + val['Image']['id'] + 
																	'/' + val['Image']['id'] + 'a' + settings.image_type + val['Image']['extension'] + ') no-repeat center center;"></div>';
								} else {
									var newSize = getImageSize(settings.image_width, settings.image_height, val['Image']['width'], val['Image']['height']);
									itemImage += '<div class="slide_img_cnt"><img src="' + settings.imageRoot + val['Image']['id'] + 
																	'/' + val['Image']['id'] + 'a' + settings.image_type + 
																	val['Image']['extension'] + '" width="' + newSize.width + 
																	'" height="' + newSize.height + '" border="0" /></div>';
								}
								itemImage += '</a>' +  
												'</div>';
												
								output += '<div class="slider_item">' + 
										  		'		<div class="one_item">';
								
								if (settings.hideButtons == true) {
									output += itemImage;
								} else if (settings.imageOnTop == true) {
									output += itemImage + itemDesc;
								} else {
									output += itemDesc + itemImage;
								}
								
								output += '</div></div>';
							});
						}
						
						thisObj.html(output);
					  if (settings.totalItemCount > 0) {					
							initSlider(thisObj, settings);
						}
						
						if (settings.afterLoaded) settings.afterLoaded.apply(this, [data]);
					}
				};
			
				$.ajax(ajaxOptions);
			
			} else {
				initSlider(thisObj, settings);
			}
		});
		
	};
	
	/*********************Public Access to Properties****************************/
	$.fn.slideviewer.default_settings = {
		isAjax : false,
		ajaxUrl: '',
		clickLink: false, //This is for click through to view related model(e.g. product).
		viewLargeImage: false,
		beforeLoading: function() {},
		afterLoaded: function(data) {},
		imageRoot: '/img/images/products/',
		useImageAsBackground: true,
		image_width : 100,
		image_height: 100,
		height: 130,
		width: 110,
		display_counts: 5, //how many items will be displayed.
		slide_counts: 5, //how many items will be moved at once. No more than display_counts.
		effects: 'mousedown',
		useScrollButton: true,
		image_type : 4, //this type is for different dimensions. 4 is for CMS.
		hideButtons : false, //set true to hide delete, set default links.
		scroll_right_id : '',
		scroll_left_id : '',
		
		scroll_left_class: '',
		scroll_right_class: '',
		scroll_left_class_over: '',
		scroll_right_class_over: '',
		scroll_left_class_disable: '',
		scroll_right_class_disable: '',
		
		imageOnTop: true,
		
		beforeChange: function(data) {},
		afterChange: function() {},
		
		interval: 1500,
		speed: 1500,
		adjust_speed: 1000,
		adjust: 20
	};
	
	$.fn.slideviewer.default_options = {
		mouse_tracing: false,
		scrollbar:  true
	};
	
	/*********************Public Accessed Methods*****************************/
	$.fn.slideviewer.startSlide = startSlide;
	
	/*********************End of Public Accessed Methods**********************/
	
	
	/*********************Private Methods Here*******************************/
	function getImageSize(new_w, new_h, org_w, org_h) {
		var rateOrig = org_w / org_h;
		var rateNew  = new_w / new_h;
									
		if (org_w < new_w && org_h < new_h) {
			new_w = org_w;
			new_h = org_h;
		} else if (rateNew > rateOrig) {
			new_w = org_w * new_h / org_h;
		} else if (rateNew < rateOrig) {
			new_h = org_h * new_w / org_w;
		}
		
		var newSize = {width : new_w, height : new_h};
		return newSize;
	}
	
	function initSlider(thisObj, settings) {
		var item_counts = thisObj.children('.slider_item').length;
		var barWidth = item_counts * settings.width;
		var displayWidth = settings.display_counts * settings.width;
		var maxLeft = barWidth - displayWidth;
		var minLeft = 0;
		
		if (settings.effects == 'noanimation') {
			thisObj.children('.slider_item').each(function(i, val) {
				$(val).css({
					width: settings.width * settings.slide_counts + 'px',
					height: settings.height + 'px'
				});
			});
		} else {
			thisObj.children('.slider_item').each(function(i, val) {
				$(val).css({
					width: settings.width + 'px',
					height: settings.height + 'px',
					float: 'left'
				});
			});
		}
		
		var htmlOutput = '<div class="slider_view" style="overflow:hidden;position:relative;float:left;width:' + displayWidth + 'px;">' +
						 '	<div class="slider_bar" style="width:' + barWidth + 'px;position:relative;">' + 
							thisObj.html() + 
						 '	</div>' +
						 '</div>';
		if (settings.useScrollButton == true) {
			htmlOutput = '<div class="slider_scroll_left ' + settings.scroll_left_class + '"></div>' + 
							htmlOutput + 
						 '<div class="slider_scroll_right ' + settings.scroll_right_class + '"></div>';
		}
		thisObj.html(htmlOutput);
	
		var objRight = thisObj.children('.slider_scroll_right');
		var objLeft = thisObj.children('.slider_scroll_left');
		if (settings.scroll_right_id != '') objRight = $("#" + settings.scroll_right_id);
		if (settings.scroll_left_id != '') objLeft = $("#" + settings.scroll_left_id);
		switchScrollBar(objRight, objLeft, thisObj.find('.slider_bar'));
		handleEvents(thisObj, settings, maxLeft, minLeft);
	}
	
	function handleEvents(thisObj, settings, maxLeft, minLeft) {
		thisObj.find('div.slide_button_1').click(function() {//set default image
			var thisUrl = settings.ajaxUrlSetDefault + '/' + $(this).parent().attr('id');
			var ajaxOptions = {
				type: 'POST',
				url: thisUrl,
				dataType: 'json',
				beforeSend: function() {
					thisObj.html('<div class="slider_loading"></div>');
				},
				success: function(data) {
					if (data.success == true) {
						thisObj.slideviewer(settings);
					}
				}
			};
			
			$.ajax(ajaxOptions);
		});
		
		thisObj.find('div.slide_button_2').click(function() {//delete image
			if ( confirm('Are your sure to delete this image?') ) {
				var thisUrl = settings.ajaxUrlDel + '/' + $(this).parent().attr('id');
				var ajaxOptions = {
					type: 'POST',
					url: thisUrl,
					dataType: 'json',
					beforeSend: function() {
						thisObj.html('<div class="slider_loading"></div>');
					},
					success: function(data) {
						if (data.success == true) {
							thisObj.slideviewer(settings);
						}
					}
				};
			
				$.ajax(ajaxOptions);
			}
		});
		
		if (settings.effects == 'auto') {
			thisObj.maxLeft = maxLeft; 
			thisObj.minLeft = minLeft;
			thisObj
				.mouseover(function(e) {
					e.stopPropagation();
					settings.pause = true;
					clearTimeout(thisObj.timer);
					thisObj.timer = null;
				})
				.mouseout(function(e) {
					e.stopPropagation();
					settings.pause = false;
					thisObj.timer = setTimeout(function(){slideRight(thisObj, settings);}, settings.interval);
				});
			thisObj.timer = setTimeout(function(){slideRight(thisObj, settings);}, settings.interval);
		} else if (settings.effects == 'click') {
			var objRight = thisObj.children('.slider_scroll_right');
			var objLeft = thisObj.children('.slider_scroll_left');
			if (settings.scroll_right_id != '') objRight = $("#" + settings.scroll_right_id);
			if (settings.scroll_left_id != '') objLeft = $("#" + settings.scroll_left_id);
			objRight
				.click(function(e) {
					if (thisObj.sliding == false) {
						e.stopPropagation();
						
						var sview = thisObj.children('.slider_view');
						var sbar  = sview.children('.slider_bar');
						var barMotion = settings.slide_counts * settings.width + settings.adjust;
						var currLeft  = Math.abs(sbar.offset().left - sview.offset().left);
				
						if ((currLeft + 1) < maxLeft) { //here plus 1, is for IE7, IE6(don't know why 1px difference)
							if (settings.beforeChange) settings.beforeChange.apply(this, [e]);
						
							thisObj.sliding = true;
							sbar
								.animate({"left" : "-=" + barMotion + "px"}, {"duration" : settings.speed}, function(){})
								.animate({"left" : "+=" + settings.adjust + "px"}, settings.adjust_speed, function() {
									thisObj.sliding = false;
									
									switchScrollBar(objRight, objLeft, sbar);
								});
						}
					}
				})
				.mouseover(function(e) {
					$(this).addClass(settings.scroll_right_class_over);
				})
				.mouseout(function(e) {
					$(this).removeClass(settings.scroll_right_class_over);

				});
				
			objLeft
				.click(function(e) {
					if (thisObj.sliding == false) {
						e.stopPropagation();
				
						var sview = thisObj.children('.slider_view');
						var sbar  = sview.children('.slider_bar');
						var barMotion = settings.slide_counts * settings.width + settings.adjust;
						var currLeft  = Math.abs(sbar.offset().left - sview.offset().left);
					
						if (currLeft > minLeft) {
							if (settings.beforeChange) settings.beforeChange.apply(this, [e]);
							
							thisObj.sliding = true;
							sbar
								.animate({"left" : "+=" + barMotion + "px"}, {"duration" : settings.speed}, function(){})
								.animate({"left" : "-=" + settings.adjust + "px"}, settings.adjust_speed, function() {
									thisObj.sliding = false;
									
									switchScrollBar(objRight, objLeft, sbar);
								});
						}
					}
				})
				.mouseover(function(e) {
					$(this).addClass(settings.scroll_left_class_over);
				})
				.mouseout(function(e) {
					$(this).removeClass(settings.scroll_left_class_over);
				});
				
		} else if (settings.effects == 'mousedown') {
			var objRight = thisObj.children('.slider_scroll_right');
			var objLeft = thisObj.children('.slider_scroll_left');
			if (settings.scroll_right_id != '') objRight = $("#" + settings.scroll_right_id);
			if (settings.scroll_left_id != '') objLeft = $("#" + settings.scroll_left_id);
			objRight
				.mousedown(function(e) {
					$(this).addClass(settings.scroll_right_class_over);
					settings.decelerate = false;
					settings.arrowDown = true;
					settings.preDirect = (settings.direct != null) ? settings.direct : 'pos';
					settings.direct = 'pos';
					if (settings.timer != null) {
						clearTimeout(settings.timer);
						settings.timer = null;	
					}
					scrollHR(settings, thisObj.children('.slider_view')[0]);
				})
				.mouseup(function(e) {
					$(this).removeClass(settings.scroll_right_class_over);
					settings.decelerate = true;
					settings.preDirect = 'pos';
					switchScrollBar(objRight, objLeft, thisObj.children('.slider_view').children('.slider_bar'));
				});
			objLeft
				.mousedown(function(e) {
					$(this).addClass(settings.scroll_left_class_over);
					settings.decelerate = false;
					settings.arrowDown = true;
					settings.preDirect = (settings.direct != null) ? settings.direct : 'neg';
					settings.direct = 'neg';
					if (settings.timer != null) {
						clearTimeout(settings.timer);
						settings.timer = null;
					}
					scrollHR(settings, thisObj.children('.slider_view')[0]);
				})
				.mouseup(function(e) {
					$(this).removeClass(settings.scroll_left_class_over);
					settings.decelerate = true;
					settings.preDirect = 'neg';
					switchScrollBar(objRight, objLeft, thisObj.children('.slider_view').children('.slider_bar'));
				});
		} else if (settings.effects == 'noanimation') {
			settings.slideCountNumber = 0;
			thisObj.children('.slider_scroll_right')
				.click(function(e) {
					disableTextSelect(thisObj);
					if (settings.slideCountNumber < settings.slideTotalCountNumber - 1) {
						var prev_slide_id = thisObj.attr('id') + '_item_' + settings.slideCountNumber;
						settings.slideCountNumber++;
						var next_slide_id = thisObj.attr('id') + '_item_' + settings.slideCountNumber;
						thisObj.find("#" + prev_slide_id).hide();
						thisObj.find("#" + next_slide_id).show();
					}
				})
				.mouseover(function(e) {
					$(this).addClass(settings.scroll_right_class_over);
				})
				.mouseout(function(e) {
					$(this).removeClass(settings.scroll_right_class_over);
				});
				
			thisObj.children('.slider_scroll_left')
				.click(function(e) {
					disableTextSelect(thisObj);
					if (settings.slideCountNumber > 0) {
						var prev_slide_id = thisObj.attr('id') + '_item_' + settings.slideCountNumber;
						settings.slideCountNumber--;
						var next_slide_id = thisObj.attr('id') + '_item_' + settings.slideCountNumber;
						thisObj.find("#" + prev_slide_id).hide();
						thisObj.find("#" + next_slide_id).show();
					}
				})
				.mouseover(function(e) {
					$(this).addClass(settings.scroll_left_class_over);
				})
				.mouseout(function(e) {
					$(this).removeClass(settings.scroll_left_class_over);
				});
		}
	}
	
	function startSlide(slideCount) {
		var item_counts = thisSlide.find('.slider_item').length;
		var barWidth = item_counts * thisSetting.width;
		var displayWidth = thisSetting.display_counts * thisSetting.width;
		var maxLeft = barWidth - displayWidth;
		var minLeft = 0;
		
		var objRight = thisSlide.find('.slider_scroll_right');
		var objLeft = thisSlide.find('.slider_scroll_left');
		if (thisSetting.scroll_right_id != '') objRight = $("#" + thisSetting.scroll_right_id);
		if (thisSetting.scroll_left_id != '') objLeft = $("#" + thisSetting.scroll_left_id);
			
		var sview = thisSlide.find('.slider_view');
		var sbar  = sview.children('.slider_bar');
		var currLeft  = Math.abs(sbar.offset().left - sview.offset().left);
		var barMotion = Math.abs(slideCount) * thisSetting.width + thisSetting.adjust;
		var comp1 = currLeft + 1;//here plus 1, is for IE7, IE6(don't know why 1px difference)
		var comp2 = maxLeft;
		var sign1 = '-='; 
		var sign2 = '+=';
		if (slideCount < 0) {
			comp1 = minLeft;
			comp2 = currLeft;
			sign1 = '+=';
			sign2 = '-=';
		}

		if (comp1 < comp2) { 
			//if (thisSetting.beforeChange) thisSetting.beforeChange.apply(this, [e]);
					
			thisSlide.sliding = true;
				sbar
					.animate({"left" : sign1 + barMotion + "px"}, {"duration" : thisSetting.speed}, function(){})
					.animate({"left" : sign2 + thisSetting.adjust + "px"}, thisSetting.adjust_speed, function() {
						thisSlide.sliding = false;
								
						switchScrollBar(objRight, objLeft, sbar);
					});
		}
		
	}
	
	function slideRight(thisObj, settings) {
		var sview = thisObj.children('.slider_view');
		var sbar  = sview.children('.slider_bar');
		var barMotion = settings.slide_counts * settings.width + settings.adjust;
		var currLeft  = Math.abs(sbar.offset().left - sview.offset().left);
		
		if (currLeft < thisObj.maxLeft) {
			thisObj.sliding = true;
			sbar
				.animate({"left" : "-=" + barMotion + "px"}, {"duration" : settings.speed}, function(){})
				.animate({"left" : "+=" + settings.adjust + "px"}, settings.adjust_speed, function() {
					thisObj.sliding = false;
					sbar.children('.slider_item:first').each(function(i, val) {
						var dtleft = $(val).offset().left - sview.offset().left;
						if (dtleft < 0) {
							sbar
								.append(val) //move the first item to the end.
								.css({left: '0'});
						}
					});
					if (!settings.pause) {
						thisObj.timer = setTimeout(function(){slideRight(thisObj, settings);}, settings.interval);
					}
				});
		}	
	}
	
	function scrollHorizontal(settings, sview, direct) {
		if (settings.arrowDown == true) {
			if (settings.decelerate == true) {
				settings.velocity -= settings.globalAccDec * settings.timeDivision;
			} else {
				settings.velocity += settings.globalAcc * settings.timeDivision; //v = t * a
			}
			
			if (settings.velocity < 0) {
				clearSlide(settings);
			}
			
			if (settings.velocity > settings.maxV) settings.velocity = settings.maxV;
			if (direct == 'neg') {
				$(sview)[0].scrollLeft -= settings.velocity * settings.timeDivision; //s = v * t
			} else {
				$(sview)[0].scrollLeft += settings.velocity * settings.timeDivision; //s = v * t
			}
			
			if (settings.velocity > 0) {
				settings.timer = setTimeout(function(){scrollHorizontal(settings, sview, direct);}, settings.timeDivision);
			} else {
				clearSlide(settings);
			}
		}
	}
	
	function scrollHR(settings, sview) {
		if (settings.arrowDown == true) {
			var acc = 0;
			if (settings.direct == 'neg') {
				acc = -settings.globalAcc;
				if (settings.decelerate == true) {
					acc = settings.globalAccDec;
				}
			} else {
				acc = settings.globalAcc;
				if (settings.decelerate == true) {
					acc = -settings.globalAccDec;
				}
			}
			
			settings.velocity += acc * settings.timeDivision;
			if ((settings.direct == 'pos' && settings.preDirect == settings.direct && settings.velocity < 0) || (settings.direct == 'neg' && settings.preDirect == settings.direct && settings.velocity > 0)) {
				clearSlide(settings);
			}
			
			if (Math.abs(settings.velocity) > settings.maxV) {
				if (settings.direct == 'pos') settings.velocity = settings.maxV;
				else settings.velocity = -settings.maxV;
			}

			$(sview)[0].scrollLeft += settings.velocity * settings.timeDivision; //s = v * t
			
			if (Math.abs(settings.velocity) > 0) {
				settings.timer = setTimeout(function(){scrollHR(settings, sview);}, settings.timeDivision);
			} else {
				clearSlide(settings);
			}
		}
	}
	
	function clearSlide(settings) {
		settings.arrowDown = false;
		settings.decelerate = false;
		settings.direct = null;
		if (settings.timer != null) clearTimeout(settings.timer);
		settings.timer = null;
		settings.timeDuration = 0;
		settings.velocity  = 0;
	}
	
	function switchScrollBar(objRight, objLeft, sbar) {
		var wd = Math.abs(sbar.offset().left - sbar.parent().offset().left) + sbar.parent().width();
		if (Math.ceil(wd) >= sbar.width()) {
			objRight.addClass('slider_scroll_right_disable');
		} else {
			objRight.removeClass('slider_scroll_right_disable');
		}
		
		if (sbar.offset().left - sbar.parent().offset().left >= 0) {
			objLeft.addClass('slider_scroll_left_disable');
		} else {
			objLeft.removeClass('slider_scroll_left_disable');
		}
	}
	
	function disableTextSelect(thisObj) {
		thisObj.css('MozUserSelect', 'none');// for Firefox
		thisObj.bind('selectstart', function() { return false; }); //for IE, Safari
	}
	
	function enableTextSelect(thisObj) {
		thisObj.css('MozUserSelect', 'inherit');// for Firefox
		thisObj.unbind('selectstart'); //for IE, Safari
	}
	/********************************End of Private Methods*************************************/
})(jQuery);