function mover (obj) {
	if (obj.className == "li_pnode") $(obj).removeClass('li_pnode').addClass('li_pnode_over');
	else $(obj).removeClass('li_node').addClass('li_node_over');
	$(obj).children('a').addClass('hover');
	var clds = obj.childNodes;
	for(i in clds) {
		if (clds[i].tagName == "UL" ) {
			clds[i].style.display = "block";
		}
	}
}

function mout (obj) {
	if (obj.className == "li_pnode_over") $(obj).removeClass('li_pnode_over').addClass('li_pnode');
	else $(obj).removeClass('li_node_over').addClass('li_node');
	$(obj).children('a').removeClass('hover');
	var clds = obj.childNodes;
	for(i in clds) {
		if (clds[i].tagName == "UL" ) {
			clds[i].style.display = "none";
		}
	}
}

function loadProdImages() {
	$("#prod_slider").slideviewer({
		isAjax : false,
		width: 66,
		height: 51,
		display_counts: 6,
		slide_counts: 6,
		useScrollButton: true,
		hideButtons: true,
		effects : 'click'
	});
		
	$("#prod_slider .slider_item").click(function() {
		var curl = $(this).find('.prod_img').children('img').attr('src');
		cext = curl.substring(curl.indexOf('a4') + 2);
		curl = curl.substring(0, curl.indexOf('a4')) + 'a2' + cext;
		$("#prod_main_img").children('img').attr('src', curl);
		$(this).siblings('.current_img').removeClass('current_img');
		$(this).addClass('current_img');
	});
}

function loadTabs(objId) {
	var tabId = $("#" + objId + " .tab_current").attr('id') + '_cnt';
	$("#" + tabId).show();
		
	$("#" + objId + " .tab_element").click(function() {
		var tabId = $(this).attr('id') + '_cnt';
		$("#" + objId + " .tab_current").removeClass('tab_current');
		$(this).addClass('tab_current');
		$("#" + tabId).siblings(".tab_cnt").hide();
		$("#" + tabId).show();
	});
}

function switchForm(thisObj) {
	if (thisObj.id == 'form_type_login') {
		newId = 'form_login';
		oldId = 'form_register';
		$(thisObj).children('input').attr('checked', true);
		$("#form_type_register").children('input').attr('checked', false);
	} else {
		newId = 'form_register';
		oldId = 'form_login';
		$(thisObj).children('input').attr('checked', true);
		$("#form_type_login").children('input').attr('checked', false);
	}
	$("#" + newId).slideDown();
	$("#" + oldId).slideUp();
}

function forgot() {
	var cnt = "<div id='wrap_address'></div>";
	$("body").popwindow({
		content: cnt,
		title: 'Retrieve Lost Password',
		width: 280,
		height: 180,
		location: {top: 100 + $(document).scrollTop()},
		effects: {show: 'slideDown', hide: 'slideUp'},
		onLoaded: function() {
			var opts1 = {
				type : 'POST',
				url  : '/forgot',
				beforeSend : function() {
					$("#wrap_address").html("<div class='icon_loading_small'>Loading ...</div>");
				},
				success : function(data) {
					$("#wrap_address").html(data);
						
					$("#wrap_address #getpassword").click(function(e) {
						var opts2 = {
							type : 'POST',
							url  : '/forgot',
							data : $('#form_forgotpass').serialize(),
							dataType : 'json',
							beforeSend : loadIcon,
							success : function(data) {
								if (data.success == true) {
									var msg = '<div class="message">A new password has been sent to your email.<br>'  + 
											  'You can login and change the password.<br><br>' + 
											  '<button type="button" class="btn_normal" id="closewin">Close</button></div>';
									$("#wrap_address").html(msg);
									$("#closewin").click(function(){$("body").popwindow.close();});
								} else {
									var errs = '<li class="title">Errors:</li>';
									for(var i in data.errors) {
										errs += '<li>' + data.errors[i] + '</li>';
										break;
									}
									$("#wrap_address #form_errors").html(errs);
								}
							}
						};
						$.ajax(opts2);
					});
				}
			};
			$.ajax(opts1);
		}
	});
	return false;
}

function docPrint(areaId) { 
	var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
    disp_setting+="scrollbars=yes,width=760,height=600,left=100,top=25,resizable=1"; 
  	var content_vlue = document.getElementById(areaId).innerHTML; 
  
  	var docprint=window.open("","",disp_setting); 
   	docprint.document.open(); 
   	docprint.document.write('<html><head><title>Order Sheet - FRESHLA</title>');
   	docprint.document.write("<link rel='stylesheet' type='text/css' href='css/cms.css' />");
   	docprint.document.write('</head><body style="background:#ffffff;margin-left:2px;" onLoad="self.print()"><center>');          
   	docprint.document.write(content_vlue);          
   	docprint.document.write('</center></body></html>'); 
   	docprint.document.close(); 
   	docprint.focus(); 
}

function runForm(act,contentId,txtId,tg) {
	var txtContent = document.getElementById(contentId).innerHTML;
	document.getElementById(txtId).innerHTML = "test";
	document.form_email.action = act;
	document.form_email.target = tg;
	document.form_email.submit();
	document.getElementById(txtId).innerHTML = '';
}

function beforeSubmit(btnId) {
	$("#" + btnId).attr('disabled', true);
	return true;
}

function loadUserEditor(tabId) {
	$("#" + tabId + " a.btn_edit").click(function(e) {
		e.preventDefault();
		
		var cnt = "";
		var returnValue = "";
		var fieldId = $(this).attr('id');
		switch(fieldId) {
			case 'email' :
				cnt = '<label class="first">Your Account Password:</label>' +
					  '<input type="password" name="data[User][old_password]" />' +
				 	  '<label>Your New Email Address:</label>' +
				 	  '<input type="text" name="data[User][email]" />' + 
					  '<label>Confirm New Email Address:</label>' + 
					  '<input type="text" name="data[User][confirm_email]" />';
				returnValue = "data.account.email";
				break;
			case 'password' :
				cnt = '<label class="first">Your Current Password:</label>' +
					  '<input type="password" name="data[User][old_password]" />' +
				 	  '<label>Your New Password:</label>' +
				 	  '<input type="password" name="data[User][password]" />' + 
					  '<label>Confirm New Password:</label>' + 
					  '<input type="password" name="data[User][confirm_password]" />';
				break;
			
			default :
				var lbl = $(this).attr('name');
				var val = $("#" + fieldId + '_text').text();
				cnt = '<div class="info">Your Current ' + lbl + ': ' + val + '</div>' + 
					  '<label>Your New ' + lbl + ':</label>' +
				 	  '<input type="text" name="data[UserProfile][' + fieldId + ']" />';
				returnValue = "data.account." + fieldId;
				break;
		}
		
		cnt = '<form name="form_account" id="form_account">' +
			  '<div class="form_main" id="basic_info">' +
			  '<ul class="form_errors" id="form_errors"></ul>' + 
				cnt +
			  '<div id="btn_update" class="button">Submit</div>' + 
			  '</div></form>'; 
		$("body").popwindow({
			content: cnt,
			title: 'Update Your Account',
			width: 300,
			height: 250,
			location: {top: 100 + $(document).scrollTop()},
			effects: {show: 'slideDown', hide: 'slideUp'},
			onLoaded: function() {
				$("#form_account")[0].elements[0].focus();
				$("#btn_update").click(function(e) {
					var opts = {
						type : 'POST',
						url  : '/account/update',
						data : $('#form_account').serialize(),
						dataType : 'json',
						beforeSend : loadIcon,
						success : function(data) {
							if (data.success == true) {
								$("#" + fieldId + '_text').text(eval(returnValue));
								$("body").popwindow.close();
							} else {
								var errs = '<li class="title">Errors:</li>';
								for(var i in data.errors) {
									errs += '<li>' + data.errors[i] + '</li>';
									break;
								}
								$("#form_errors").html(errs);
							}
						}
					}
					$.ajax(opts);
				});
				
			},
			onClosed: function() {
			}
		});
	});
}

function loadAddressEditor(actionId) {
	var cnt = "<div id='wrap_address'></div>";
	var addressId = "";
	var params = "";
	if (actionId == 'edit') {
		if (global_contact_id == null || global_contact_id == "") {
			alert('Please select an address from you address book.');
			return false;
		}
		addressId = global_contact_id.substring(12);
		params = 'data[Contact][id]=' + addressId;
	}
		
	$("body").popwindow({
		content: cnt,
		title: 'Update Your Address',
		width: 430,
		height: 450,
		location: {top: 100 + $(document).scrollTop()},
		effects: {show: 'slideDown', hide: 'slideUp'},
		onLoaded: function() {
			var opts = {
				type : 'POST',
				url  : '/contacts/view',
				data : params,
				beforeSend : function() {
					$("#wrap_address").html("<div class='icon_loading'>Loading ...</div>");
				},
				success : function(data) {
					$("#wrap_address").html(data);
						
					$("#wrap_address #btn_update").click(function(e) {
						var opts1 = {
							type : 'POST',
							url  : '/contacts/save',
							data : $("#form_address").serialize(),
							dataType : 'json',
							beforeSend : loadIcon,
							success : function(data) {
								if (data.success == true) {
					 				var defaultType = "&nbsp;";
									if (data.returnValue.is_billing == 1) {
										defaultType += "<span class='default_billing'>Billing</span>";
									}
									if (data.returnValue.is_shipping == 1) {
										if (defaultType != "&nbsp;") {
											defaultType += "<span class='separator'>" +
														   "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" +
														   "&amp;<br></span>";
										}
										defaultType += "<span class='default_shipping'>Shipping</span>";
									}
					 				var prefix = "address_row_";
									var output ="<td valign='top'>" + data.returnValue.alias + "</td>" + 
												"<td valign='top'>" + data.returnValue.firstname + "&nbsp;" +
												data.returnValue.lastname + "<br>" + 
												data.returnValue.address1 + "&nbsp;" + 
												data.returnValue.address2 + "<br>" +
												data.returnValue.suburb + "&nbsp;" + 
												data.returnValue.state + "&nbsp;" + 
												data.returnValue.postcode + "<br>" + 
												data.returnValue.country + 
												(data.returnValue.phone != "" ? "<br>Phone: " + 
												data.returnValue.phone : "") +
												(data.returnValue.mobile != "" ? "<br>Mobile: " +
												data.returnValue.mobile : "") + 
												"</td>" +
												"<td valign='top' class='default_type'>" + defaultType + "</td>" +
												"<td valign='top'>" + 
												"<input type='radio' name='radSelect' class='radSelect' checked />" + 
												"</td>";
										
									if (addressId != "" && addressId == data.returnValue.id) {			 
										$("#" + prefix + addressId).html(output);
									} else if(data.returnValue.id != "") {
										output = "<tr class='row' id='address_row_" + 
								   					data.returnValue.id + "'>" +
													output + "</tr>";
										$("#tb_address_book #noaddress").remove();			
										$("#tb_address_book").append(output);
										
										if (global_contact_id != null && global_contact_id != "") {
											$("#" + global_contact_id).removeClass('row_select');
											$("#" + global_contact_id + " input.radSelect").attr('checked', false);
										}
										$("#address_row_" + data.returnValue.id).addClass('row_select');
										global_contact_id = "address_row_" + data.returnValue.id;
									}
									$("body").popwindow.close();
								} else {
									var errs = '<li class="title">Errors:</li>';
									for(var i in data.errors) {
										errs += '<li>' + data.errors[i] + '</li>';
										break;
									}
									$("#form_errors").html(errs);
								}
							}
						};
						$.ajax(opts1);
					});
					$("#wrap_address #btn_cancel").click(function(e) {
						$("body").popwindow.close();
					});
				}
			};
			$.ajax(opts);
		},
		onClosed: function() {
		}
	});
}

function loadAddressRemove() {
	$("#address_del").click(function(e) {
		e.preventDefault();
		if (global_contact_id == null || global_contact_id == "") {
			alert('Please select an address from you address book.');
			return false;
		} 
		var addressId = global_contact_id.substring(12);
		if (confirm('Are you sure to remove this address from your address book?')) {
			var opts = {
				type : 'POST',
				url  : '/contacts/remove',
				data : 'data[Contact][id]=' + addressId,
				dataType : 'json',
				beforeSend : function() {$("#icon_process").show();},
				success : function(data) {
					if (data.success == true) {
						$("#" + global_contact_id).remove();
						global_contact_id = "";
					}
					$("#icon_process").hide();
				}
			};
			$.ajax(opts);
		}
	});
}

function loadAddressDefault(actionType) {
	var params = "";
	params = "&data[type]=" + actionType;
	if (global_contact_id == null || global_contact_id == "") {
		alert('Please select an address from you address book.');
		return false;
	} 
	var addressId = global_contact_id.substring(12);
	var opts = {
		type : 'POST',
		url  : '/contacts/setdefault',
		data : 'data[Contact][id]=' + addressId + params,
		dataType : 'json',
		beforeSend : function() {$("#icon_process").show();},
		success : function(data) {
			if (data.success == true) {
				var defaultType = "&nbsp;";
				if (data.returnValue.is_billing == 1) {
					defaultType += "<span class='default_billing'>Billing</span>";
				}
				if (data.returnValue.is_shipping == 1) {
					if (defaultType != "&nbsp;") {
						defaultType += "<span class='separator'><br>" +
									   "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&amp;<br></span>";
					}
					defaultType += "<span class='default_shipping'>Shipping</span>";
				}
				$("#tb_book_cnt span.default_" + actionType).remove();
				$("#tb_book_cnt span.separator").remove();
				$("#" + global_contact_id + " td.default_type").html(defaultType);
			}
			$("#icon_process").hide();
		}
	};
	$.ajax(opts);
}

function loadAddressRow() {
	$("#tb_book_cnt tr.row")
		.live('mouseover', function(e) {
			$(this).addClass('row_hover');
		})
		.live('mouseout', function(e) {
			$(this).removeClass('row_hover');
		})
		.live('click', function(e) {
			if (global_contact_id != null && global_contact_id != "") {
				$("#" + global_contact_id).removeClass('row_select');
			}
			$(this).addClass('row_select');
			$("#" + $(this).attr('id') + " .radSelect").attr('checked', true);
			global_contact_id = $(this).attr('id');
		});
}

var global_contact_id = "";
function setupAddressBook() {
	loadAddressRow();
		
	$("#address_add").click(function(e) {
		e.preventDefault();
		loadAddressEditor();
	});
	$("#address_edit").click(function(e) {
		e.preventDefault();
		loadAddressEditor('edit');
	});
	$("#address_billing").click(function(e) {
		e.preventDefault();
		loadAddressDefault('billing');
	});
	$("#address_shipping").click(function(e) {
		e.preventDefault();
		loadAddressDefault('shipping');
	});
	
	loadAddressRemove();
}

function loadOrderRow() {
	$("#tb_order_cnt tr.row")
		.live('mouseover', function(e) {
			$(this).addClass('row_hover');
		})
		.live('mouseout', function(e) {
			$(this).removeClass('row_hover');
		})
		.live('click', function(e) {
			$("#tb_order_cnt tr.row_select").removeClass('row_select');
			$(this).addClass('row_select');
			$("#" + this.id + " .radSelect").attr('checked', true);
		});
}

function setupOrderList() {
	loadOrderRow();
	
	$("#order_view_track, #order_view_history").click(function(e) {
		e.preventDefault();
		loadOrderDetail(this);
	});
	
	$("#order_continue").click(function(e) {
		e.preventDefault();
		
		var objTr = $(this).parent().next().find('tr.row_select');
		if (objTr.length <= 0 || objTr[0].id == "") {
			alert('Please select an order from you order list.');
			return false;
		}
		orderId = objTr[0].id.substring(10);
		document.location.href = '/orders/view/' + orderId;
	});
	
	$("#order_del").click(function(e) {
		e.preventDefault();
		
		var objTr = $(this).parent().next().find('tr.row_select');
		if (objTr.length <= 0 || objTr[0].id == "") {
			alert('Please select an order from you order list.');
			return false;
		}
		orderId = objTr[0].id.substring(10);
		if (confirm('Are you sure to remove this order from your order list?')) {
			var opts = {
				type : 'POST',
				url  : '/orders/remove',
				data : 'data[Order][id]=' + orderId,
				dataType : 'json',
				beforeSend : function() {$("#icon_order_del").show();},
				success : function(data) {
					if (data.success == true) {
						objTr.remove();
					}
					$("#icon_order_del").hide();
				}
			};
			$.ajax(opts);
		}
	});
}

function loadOrderDetail(thisObj) {
	var cnt = "<div id='wrap_address'></div>";
	var orderId = "";
	var params = "";
	
	var objTr = $(thisObj).parent().next().find('tr.row_select');
	if (objTr.length <= 0 || objTr[0].id == "") {
		alert('Please select an order from you order list.');
		return false;
	}
	orderId = objTr[0].id.substring(10);
	params = 'data[Order][id]=' + orderId;
		
	$("body").popwindow({
		content: cnt,
		title: 'Order Details',
		width: 600,
		height: 400,
		location: {top: 100 + $(document).scrollTop()},
		effects: {show: 'slideDown', hide: 'slideUp'},
		onLoaded: function() {
			var opts = {
				type : 'POST',
				url  : '/orders/view',
				data : params,
				beforeSend : function() {
					$("#wrap_address").html("<div class='icon_loading'>Loading ...</div>");
				},
				success : function(data) {
					$("#wrap_address").html(data);
					
					var hgt = $("#wrap_address").height() + 50;
					$("#popwindow .popwindow_main")
						.css({
							height : hgt + 'px'
						});
					$("#popwindow")
						.css({
							height : hgt +  + $("#popwindow .popwindow_header").height() + 'px'
						});
				}
			};
			$.ajax(opts);
		}
	});
}

function getMonthOptions() {
	var month = "<select name='data[month]'><option value=''></option>";
	for (var i=1; i<13; i++) {
		var j=i;
		if (i<10) j= '0' + i; 
		month += "<option value='" + j + "'>" + j + "</option>";
	}
	month += "</select>";
	
	return month;
}

function getDayOptions() {
	var day = "<select name='data[day]'><option value=''></option>";
	for (var i=1; i<32; i++) {
		var j=i;
		if (i<10) j= '0' + i; 
		day += "<option value='" + j + "'>" + j + "</option>";
	}
	day += "</select>";
	
	return day;
}

function loadForm(action) {
	var cnt = "<form id='ajax_form'><div id='wrap_form' class='form_main'>" +
			  "<ul class='form_errors' id='form_errors'></ul><div id='form_content'>";
			  
	var ajaxUrl = winTitle = msg = '';
	var winH = 270;
	var winW = 300;
	switch (action) {
		case 'btn_subscribe' :
		case 'link_subscribe' :
			cnt += "<label>Your Email: (required)</label><input type='text' name='data[Subscription][email]' class='form_email' />" +
				   "<label>Your Name: (required)</label><input type='text' name='data[Subscription][fullname]' />" +
				   "<label>Your Date of Birth: (optional)</label><div>Day:" + getDayOptions() + 
				   "&nbsp;&nbsp;Month:" + getMonthOptions() + "</div>";
			ajaxUrl = "/forms/subscribe";
			winTitle = "Subscribe Newsletter";
			msg = "<h4>Your email has been successfully subscribed!<br><br> Thank you for your subscribe.</h4>";
			break;
		case 'btn_competition':
			cnt += "<label>Title: (required)</label>" +
				   "<select name='data[Competition][title]'>" + 
				   "<option value=''>Select your title</option>" + 
				   "<option value='Mrs'>Mrs</option>" + 
				   "<option value='Ms'>Ms</option>" + 
				   "<option value='Mr'>Mr</option>" + 
				   "<option value='Dr'>Dr</option>" +
				   "</select>" +
				   "<label>First Name: (required)</label><input type='text' name='data[Competition][firstname]' />" +
				   "<label>Last Name: (required)</label><input type='text' name='data[Competition][lastname]' />" +
				   "<label>Email: (required)</label><input type='text' name='data[Competition][email]' class='form_email' /></" +
				   "<label>Phone: (required)</label><input type='text' name='data[Competition][phone]' />" +
				   "<label>Friend Emails: (optional)</label>" + 
				   "<input type='text' name='data[Competition][friend][]' class='form_email' />" + 
				   "<input type='text' name='data[Competition][friend][]' class='form_email' />" + 
				   "<input type='text' name='data[Competition][friend][]' class='form_email' />" + 
				   "<input type='text' name='data[Competition][friend][]' class='form_email' />" + 
				   "<input type='text' name='data[Competition][friend][]' class='form_email' />";
			ajaxUrl = "/forms/competition";
			winH = 500;
			winW = 400;
			winTitle = "Subscribe Competition";
			msg = "<h4>You have been successfully entered into our competition!<br><br> Thank you for your subscribe.</h4>";
			break;
		case 'btn_recipe' :
			cnt += "<label>Your Email: (required)</label><input type='text' name='data[Recipe][email]' class='form_email' />" +
				   "<label>Your Recipe: (required)</label><textarea name='data[Recipe][recipe]'></textarea>";
			ajaxUrl = "/forms/recipe";
			winH = 320;
			winTitle = "Submit Recipe";
			msg = "<h4>Your recipe has been successfully submited!<br><br> Thank you for your submission.</h4>";
			break;
	} 
	cnt +=  "</div><div class='btn_buttons'><div id='btn_save' class='button'>Submit</div>" + 
			"<div id='btn_cancel' class='button'>Cancel</div></div>" +
			"</div></form>";
		
	$("body").popwindow({
		content: cnt,
		title: winTitle,
		width: winW,
		height: winH,
		location: {top: 100 + $(document).scrollTop()},
		effects: {show: 'slideDown', hide: 'slideUp'},
		onLoaded: function() {
			$("#wrap_form .form_email").focus();
			
			$("#wrap_form #btn_cancel").click(function(e) {
				$("body").popwindow.close();
			});
			$("#wrap_form #btn_save").click(function(e) {
				var opts = {
					type : 'POST',
					url  : ajaxUrl,
					data : $("#ajax_form").serialize(),
					dataType : 'json',
					beforeSend : loadIcon,
					success : function(data) {
						$("#form_errors").empty();
						if (data.success == true) {
							$("#wrap_form #btn_cancel").text('Close');
							$("#wrap_form #btn_save").remove();
							$("#form_content").html(msg);
						} else {
							var errs = '<li class="title">Errors:</li>';
							for(var i in data.errors) {
								errs += '<li>' + data.errors[i] + '</li>';
								break;
							}
							$("#form_errors").html(errs);
						}
					}
				};
				$.ajax(opts);
			});
		}
	});
}

function loadIcon() {
	$("#form_errors").html("<li class='loading' style='height:22px;'>Processing ...</li>");
}
		
function switchCategory(e) {
	e.stopPropagation();
			
	var $tg = $(e.target);
	var $cat = $("#left_side_page");
	var $btn = $("#category_trigger");
	var $catW = $cat.width();
	var $tgId = $tg.attr('id');
			
	if ($tgId == 'category_trigger') {
		$(document).unbind('mouseover.category');
		showCategory($btn, $cat, $catW);
	} else if ($cat.css('display') == 'block' && $tgId != 'left_side_page' 
			&& $tg.parents('#left_side_page').length <= 0 && $tgId != 'category_trigger') {
		$(document).unbind('mouseover.category');
		hideCategory($btn, $cat, $catW);
	}
}
function showCategory($btn, $cat, $catW) {
	$cat
		.css ({left: '-' + $catW + 'px', display: 'block'})
		.animate({left : "+=" + $catW}, 300, function(){
			$(document).bind('mouseover.category', switchCategory);
		});
}
function hideCategory($btn, $cat, $catW) {
	$cat.animate({left : "-=" + $catW}, 300, function(){
		$cat.hide();
		$(document).bind('mouseover.category', switchCategory);		
	});
}
function toggleBlock() {
	$(".page_block").find("a.block_open").click(function() {
		$(this).toggleClass('block_closed');
		$(this).parent().siblings('.block_content').slideToggle('fast');
	});
}

function loadInstagram() {
	 $("#social-instagram").instagram({
			   userId: '19585963',
			   clientId: '54a563468ef84c1aac393dfe0559c757',
			   image_size:'thumbnail',
		show:'6',
		onComplete: function(data, res) {
			$("#social-instagram").css('background', 'none');
			//$("#social-instagram").jScrollPane({showArrows: false});
		}
			 
	});
}

function beforeSubscribeSubmit() {
	var $form = $("#styledby-form-subscribe");
	
	$("#styledby-btn-subscribe", $form).attr('disabled', true);		
	$('#styledby-subscribe-info').html('<div class="styledby-form-loading">Processing ... Please wait.</div>');
}
function afterSubscribeSubmit() {
	var $form = $("#styledby-form-subscribe");
	
	$("#styledby-btn-subscribe", $form).attr('disabled', false);		
	$('#styledby-subscribe-info').empty();
}
function resetSubscribeForm() {
	$('#styledby-subscribe-info').empty();
	$('#styledby-subscribe-error').empty();
	
	$('#styledby-form-subscribe #styledby-btn-subscribe').attr('disabled', false);
	$('#styledby-form-subscribe').get(0).reset();
}
function validateSubscribeForm() {
	var $form = $("#styledby-form-subscribe");

	/*validate form*/
	var txtEmail = $.trim($("#styledby_subscribe_email", $form).val()),
		regDigit = /^[0-9\s]+$/,
		regEmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
		error = '';
	
	if (!txtEmail) {
		error = 'Please enter your email address';
		$("#styledby_subscribe_email", $form).focus();
	} else if (!regEmail.test(txtEmail)) {
		error = 'Your email address is not valid';
		$("#styledby_subscribe_email", $form).focus().select();
	}
	
	return error;
}	

$(document).ready(function() {
	if ($("#social-instagram").length) loadInstagram();
	
	$("#topmenu_bar").find('a').hover(
		function() {
			$(this).addClass('current');
		},
		function() {
			$(this).removeClass('current');
		}
	);
	
	$("#btn_subscribe, #link_subscribe, #btn_competition").click(function() {
		loadForm(this.id);
	});
	
	$("#keywords")
		.focus(function() {
			var $val = $(this).val();
			if ($val == 'Enter keywords for search') {
				$(this)
					.css({color:'#000000'})
					.val('');
			}
		})
		.blur(function() {
			var $val = $.trim($(this).val());
			if ($val == '') {
				$(this)
					.css({color:'#666666'})
					.val('Enter keywords for search');
			}
		});
		
	$("#styledby-popup-subscribe").dialog({
			autoOpen: false,
			//show: {effect: "scale"},
			//hide: {effect: "scale"},
			height: 260,
			width: 400,
			dialogClass: 'styledby-popup',
			resizable: false,
			modal: true,
			title: 'Subscribe Form',
			close: function() {
				resetSubscribeForm();
			}
		});
	
	$('#styledby-btn-subscribe').unbind('click').click(function(e) {
		var $form = $("#styledby-form-subscribe"),
			ajaxUrl = '/subscriptions/subscribe';
		
		var ajaxOpt = {
			url: ajaxUrl,
			type: 'POST',
			data: $form.serialize(),
			dataType: 'JSON',
			beforeSend: function() {
				beforeSubscribeSubmit();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				afterSubscribeSubmit();
			},
			success: function(jData) {
				afterSubscribeSubmit();
				
				if (jData.status === 1) {
					$form.hide();
					$('#styledby-subscribe-success').html('You have successfully subscribed. <br>Thank you!');
				} else {
					var err = jData.errMsg ? jData.errMsg : 'System errors!';
					
					$('#styledby-subscribe-error').html('Errors: ' + err);
				}
			}
		};
	
		if (error = validateSubscribeForm()) {
			$('#styledby-subscribe-error').html('Errors: ' + error);
		} else {
			$.ajax(ajaxOpt);
		}
	});
	$('#link_subscribe').unbind('click').click(function(e) {
		e.preventDefault();
		
		resetSubscribeForm();
			
		$("#styledby-popup-subscribe").dialog('open');
		
		return false;
	});
	
});