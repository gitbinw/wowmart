$(function() {
	
	dishmenu = $.namespace({
		config: {
			optNum: 0,  //count the number of all options of the menu
			smallid: '#menulist',
			optional: 'ul.dropdown',
			eventid: '.button a',
			large_sidedish: ['Chips (Large)', 'Roasted Potatoes (Large)', 'Mashed Potatoes (Large)'],
			large_salad: ['Coleslaw (Large)', 
						  'Pasta Salad (Large)', 
						  'Potato Salad (Large)', 
						  'Garden Salad - Lettuce, tomato & cucumber (Large)'],
			large_drink: ['1.25 lt Coke', '1.25 lt Diet Coke', '1.25 lt Sprite', '1.25 lt Fanta']
		},
		counts: ['first', 'second', 'third', 'fouth', 'fifth'],
		base: $('.menu_desc'),
		current: {},
		
		init: function() {
			//dishmenu.deleteCookie();
			
			var $row = $(dishmenu.config.optional, dishmenu.base);
			if ($row.length > 0) {
				$row.each(function(key, val) {
					var rowid = val.id.split('_');
					var opt = '';
					for(var i=0; i<rowid[2]; i++) {
						dishmenu.config.optNum ++;
						opt += '<ul><li class="option">' + 
							   '	your ' + 
									dishmenu.counts[i] + ' option' + 
							   '</li>' +
							   '<li class="button">' + 
							   '	<a id="opt_' + dishmenu.config.optNum + 
							   '" class="' + rowid[0] + '_' + rowid[1] + '">select</a>' + 
							   '</li></ul>';
					}
					$(val).after(opt);
				});
				
				$("#btn_add2cart").live('click', dishmenu.formSubmit);
				$("#btn_add2cart_extra").live('click', dishmenu.extraFormSubmit);
			}
			
			dishmenu.smallmenu();
			dishmenu.addEvents();
		},
		
		deleteCookie: function() {
			var $del = $("#removed_items");
			if ($del.length > 0) {
				var delItems = $.parseJSON($del.val());
				for(var i=0,j=delItems.length; i<j; i++) {
					if ($.cookie('cookie_' + delItems[i])) {
						$.cookie('cookie_' + delItems[i], null);
					}
				}
			}
		},
		
		getFromCookie: function() {
			var cookieId = $("#product_main_id").val();
			if (cookieId) {
				return $.cookie('cookie_' + cookieId);
			} else {
				return '';
			}
		},
		
		saveToCookie: function($mnu) {
			var cookieId = $("#product_main_id").val();
			if (cookieId) {
				$.cookie('cookie_' + cookieId, $mnu);
			}
		},
		
		smallmenu: function() {
			var $cookieCnt = dishmenu.getFromCookie(); 
			if (!$cookieCnt) {
			  $("<ul>")
				  .addClass('menu_desc')
				  .attr({id: 'menu_small'})
				  .html(dishmenu.base.html())
				  .appendTo($(dishmenu.config.smallid));
			} else {
				$("#btn_add2cart").text('Update to Cart');
				$(dishmenu.config.smallid).empty().append($cookieCnt);
			}
		},
		
		addEvents: function() {
			$(dishmenu.config.eventid, dishmenu.base).live('click', dishmenu.optionEvent);
			$('.opt_list li')
				.live('click', dishmenu.optSelectEvent)
				.live('mouseover', dishmenu.optHoverInEvent)
				.live('mouseout', dishmenu.optHoverOutEvent);
			$(document).click(function(e) {
				if (dishmenu.current.curbtn != null &&
					dishmenu.current.curopt != null &&
					e.target !== dishmenu.current.curbtn[0] && 
					e.target !== dishmenu.current.curopt[0]) {
					dishmenu.hideOptions();
				}
			});
		},
		
		optionEvent: function(e) {
			e.stopPropagation();
			if ($(this).hasClass('active')) return false;
			
			var optkey = $(this).attr('class');
			var opts = eval('dishmenu.config.' + optkey);
			var lst = '';
			for(var i=0, j=opts.length; i<j; i++) {
				lst += '<li>' + opts[i] + '</li>';
			}
			
			var $parent = $('body').css({position: 'relative'});
			var $optlst = $('.opt_list', $parent);
			var lft = $(this).offset().left - $parent.offset().left;
			var top = $(this).offset().top - $parent.offset().top;

			if ($optlst.length > 0) $optlst.html(lst);
			else $optlst = $("<ul>")
								.html(lst)
								.addClass('opt_list')
								.appendTo($parent);
								
			lft = lft - $optlst.width() - 2;
			$optlst.css({
				left: lft + 'px',
				top: top + 'px'
			});
			if (dishmenu.current.curbtn != null) dishmenu.current.curbtn.removeClass('active');
			dishmenu.current.curbtn = $(this).addClass('active');
			if (dishmenu.current.curopt != null) dishmenu.current.curopt.hide(); 
			dishmenu.current.curopt = $optlst.fadeIn();//animate({right: '+=10', opacity: 0.25}, 5000);
		},
		
		optHoverInEvent: function(e) {
			$(this).addClass('hover');
		},
		
		optHoverOutEvent: function(e) {
			$(this).removeClass('hover');	
		},
		
		optSelectEvent: function(e) {
			e.stopPropagation();
			var value = $(this).text();
			dishmenu.current.curbtn
				.removeClass('active')
				.parent()
				.siblings('.option')
				.addClass('selected');
			
			/*set all options text value in two part*/
			var nth = dishmenu.current.curbtn.attr('id').substring(4) - 1;
			$("#menu_big li.option:eq(" + nth + ")").text(value);
			$("#menu_small li.option:eq(" + nth + ")")
				.addClass('selected')
				.text(value);
			dishmenu.current.curopt.hide();
		},
		
		hideOptions: function() {
			if (dishmenu.current.curbtn != null) dishmenu.current.curbtn.removeClass('active');
			if (dishmenu.current.curopt != null) dishmenu.current.curopt.fadeOut(); 
		},
		
		formSubmit: function() {
			var $mnu = $("#menu_small");
			if ($(".selected", $mnu).length < dishmenu.config.optNum) {
				alert('Please select options for your side dishes, salad or drinks.');
				return false;
			} else {
				dishmenu.saveToCookie($mnu.parent().html());
				
				var strMenu = '';
				$("ul li:first-child", $mnu).each(function(i, val) {
					var $val = $(val);
					if ($val.hasClass('option')) {
						strMenu += "&nbsp;&nbsp;--&nbsp;";
					} 
					strMenu += $val.text() + "<br>";
				});
				
				var $inp = $("#inp_prod_desc");
				if($inp.length <= 0) {
					$inp = $("<input>")
								.attr({
									id: 'inp_prod_desc',
									name: 'prod_desc',
									type: 'hidden'
								});
					$("#btn_add2cart").before($inp);
				}
				$inp.val(strMenu);
			}
		},
		
		extraFormSubmit: function() {
			var ext = $("#extraitems_list").val();
			if (!ext) {
				alert('Please select an extra side dishes, salad or drinks.');
				return false;
			}
		}
	});
	
	dishmenu.init();
});