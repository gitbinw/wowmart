// JavaScript Document
var global_state_data = {
	loginData: {
	     redirection : '1',
	     profileUrl : '/account',
	     autoShowUp : 'no',
	     controllerUrl : '/login'
	}
};

jQuery(window).load(function() {
	var searchForm = new Varien.searchForm('search_mini_form', 'search', 'Search entire store here...');
    searchForm.initAutocomplete('index.php/catalogsearch/ajax/suggest/', 'search_autocomplete');
	
	var dataForm = new VarienForm('login-form', true);
	
	var newsletterSubscriberFormDetail = new VarienForm('newsletter-validate-detail');
	
	jQuery(function(){
		jQuery('#camera_wrap').camera({
			alignmen: 'topCenter',
			height: '41.60%',
			minHeight: '100px',
			loader : false,
			//loaderOpacity: 0.5,
			navigation: true,
			fx: 'simpleFade',
			navigationHover:false,       
			thumbnails: false,
			playPause: false,
			pauseOnClick: false,
			pagination:false,
			transPeriod: 1000,
			time: 3000
		});
	});
	
	jQuery().youamaAjaxLogin(global_state_data.loginData);
	
});

/*product page*/
if (jQuery('#product_addtocart_form').length > 0) {
	var productAddToCartForm = new VarienForm('product_addtocart_form');
	productAddToCartForm.setDataToChild = function(data) {
		alert(33);
	}
	jQuery('#btn_test').click(function(e) {
		productAddToCartForm.submit(document.product_addtocart_form)
	});
}
/*end of product page*/

/*category page*/
if (jQuery("select.sort_opts").length > 0) {
	jQuery("select.sort_opts").change(function() {document.location.href=jQuery(this).val();});
}
/*end of category page*/

function calculatePath(pathRoot, imgId, deep) {
	var ttlNumber = deep ? deep : 6,
		strId = '' . imgId,
		count = ('' + imgId).length,
		path = '',
		offset = ttlNumber - count;
		
	for(var i=0; i<offset; i++) path += '0';

	path += imgId;

	var folderCount = ttlNumber / 2,
		folder = pathRoot;
	for(var j=0; j<folderCount; j++) {
		start = j * 2;
		folder += '/' + path.substr(start, 2);
	}

	return folder;
}
function getProductImageUrl(pathRoot, imgId, imgPrefix, imgSize, key, ext, pathDeep) {
	var imgType = imgSize ? imgSize :  'small',
		index = key ? key : 0,
		extension = ext ? ext : 'jpg',
		deep = pathDeep ? pathDeep : 6;
	path = calculatePath(pathRoot, imgId, deep);
	url = path + '/' + imgPrefix + '_' + index + '_' + imgType + "." + extension;
	
	return url;
}
function setEmptyPopupCart() {
	var htmlCart = '<div class="block block-cart ajaxcartbl">' + 
				   '	<div class="block-title">' + 
				   '		<strong><span>My Cart</span></strong>' + 
				   '	</div>' + 
				   '	<div class="block-content">' + 
				   '		<p class="empty">You have no items in your shopping cart.</p>' + 
				   '	</div>' + 
				   '</div>';
	
	return htmlCart;
}
function renderCheckoutItems(cartData) {
	var htmlCart = '<ol class="mini-products-list checkout_items_list">';
	
	jQuery.each(cartData.items, function(i, prod) {
		var p = prod.CartItem,
			imgSrc = getProductImageUrl('/medias/products', p.product_id, p.product_alias, 'xsmall');

		htmlCart += '<li class="item" id="key_prod_' + p.serial_no + '">' + 
					'	<a href="/product/" title="' + p.name + '" class="product-image">' + 
					'		<img src="' + imgSrc + '" alt="' + p.name + '" width="65">' + 
					'	</a>' + 
					'	<div class="product-details">' + 
					'		<p class="product-name"><a href="">' + p.name + '</a></p>' + 
					'		<div>' + 
					'			<div class="qtyinput">' + 
					'				<span class="sqty">' + p.qty + ' x </span>' + 
					'				<span class="price">$' + p.price + '</span>' +  
					'			</div>' + 
					'		</div>' + 
					'	</div>' + 
					'</li>';
	});

	htmlCart += '</ol>' +
				 '<p class="subtotal">' + 
				 '	<span class="label">Cart Subtotal:</span>' +  
				 '	<span class="price">$' + cartData.total + '</span>' + 		
				 '</p>';
	
	return htmlCart;
}
function renderPopupCart(cartData) {
	var htmlCart = '<div class="block block-cart ajaxcartbl">' + 
				   '	<div class="block-title">' + 
				   '		<strong><span>My Cart</span></strong>' + 
				   '	</div>' + 
				   '	<div class="block-content">' + 
				   '		<div class="summary">' + 
				   '			<p class="amount">There are <a id="total_cart_qty" href="/cart">' + cartData.totalCount + '</a> in your cart.</p>' +    
				   '		</div>' + 
				   ' 		<ol id="cart-sidebar" class="mini-products-list">';
	
	jQuery.each(cartData.items, function(i, prod) {
		var p = prod.CartItem,
			imgSrc = getProductImageUrl('/medias/products', p.product_id, p.product_alias, 'xsmall');
		htmlCart += '<li class="item" id="key_prod_' + p.serial_no + '">' + 
					'	<a href="/product/" title="' + p.name + '" class="product-image">' + 
					'		<img src="' + imgSrc + '" alt="' + p.name + '" width="65">' + 
					'	</a>' + 
					'	<div class="product-details">' + 
					'		<a title="Remove This Item" class="btn-remove ajrmbt">Remove This Item</a>' + 
					'		<p class="product-name"><a href="">' + p.name + '</a></p>' + 
					'		<div>' + 
					'			<div class="qtyinput">' + 
					'				<span class="sqty">' + p.qty + ' x </span>' + 
					'				<span class="price">$' + p.price + '</span>' +  
					'			</div>' + 
					'			<div class="add-to-cart">' + 
					'				<label>Qty:</label>' + 
					'				<input class="input-text qty ajaxqty" value="' + p.qty + '" maxlength="12" name="cart[913][qty]" type="text">' + 
					'				<button class="button btn-cart btajaxqty" title="Update Cart" type="button"><span><span>Update</span></span></button>' +  
					'			</div>' + 
					'		</div>' + 
					'	</div>' + 
					'</li>';
	});
	
	htmlCart += '		</ol>' + 
				'		<p class="subtotal">' + 
				'			<span class="label">Cart Subtotal:</span> <span class="price">$' + cartData.total + '</span>' + 
				'		</p>' +  
				'		<div class="actions">' + 
				'			<button type="button" title="Checkout" class="button ajcheckout" onclick="checkout()"><span><span>Checkout</span></span></button>' + 
				'			<button type="button" title="My Cart" onclick="setLocation(\'/carts/view\');" class="button"><span><span>My Cart</span></span></button>' + 
				'		</div>' + 
				'	</div>' + 
				'</div>';
	
	return htmlCart;
}

function renderCart(jData) {
	var ajopacity = '<div id="ajopacity"></div>';
	
	if(jData.ajaxCountItem != null) jQuery('div#ajaxscicon > span').html(jData.ajaxCountItem);
	if(jData.ajaxCartData) {
		jQuery("#ajaxcart").html(ajopacity + renderPopupCart(jData.ajaxCartData) );

		//update checkout items if it's in checkout page
		jQuery("#checkout-items-list").html(renderCheckoutItems(jData.ajaxCartData));
		jQuery("#payment-items-qty").html(jData.ajaxCountItem + ' Items');
		jQuery("#payment-items-total").html('$' + jData.ajaxCartData.total);
		jQuery("#payment-total").html('$' + jData.ajaxCartData.total);

		decorateList('cart-sidebar', 'none-recursive');
	}
	
	if(jData.ajaxAddedItem) afterAdd2Cart(jData.ajaxAddedItem);
}

function loadCart() {
	jQuery.getJSON('/carts/ajaxloadcart', function(jData) {
		if (jData && jData.status == 1) {
			renderCart(jData.data);
		}
	});
}

//auto set middle position for html object 
function autoct(o){	 
	o.css('top', (jQuery(window).height() - o.height()) / 2 + "px");
	o.css('left', (jQuery(window).width() - o.width()) / 2 + "px");
}
function afterAdd2Cart(prod) {
	var prodUrl = '/product/' + prod.product_alias,
		htmlInfo = '<div><ul class="messages ajaxcart-messages">' + 
				   '	<li class="success-msg"><ul>' + 
				   '		<li><span><a title="' + prod.name + '" href="' + prodUrl + '">' + prod.name + '</a>' + 
				   ' has been added to cart.</span></li>' + 
				   '		<li><button onclick="checkout()" class="button btn-continue" title="Checkout">' + 
				   '			<span><span>Checkout</span></span></button></li></ul>' + 
				   '	</li>' + 
				   '</ul></div>';
	
	var ajaxcartmsg = jQuery("#ajaxcartmsg"),
		ajaxallct = jQuery("#ajaxallct"),
		ajmsgc = jQuery('#ajaxcartmsgc');
		
	ajmsgc.html(htmlInfo);
	ajaxallct.css('display', 'block');
	ajaxallct.addClass('ajaxcontinue');
	autoct(ajaxcartmsg);
	jQuery('button.closemsg').click(function(){ ajaxallct.hide(); });
	ajaxallct.delay(4500).fadeOut(200, function() {
		ajaxallct.removeClass('ajaxcontinue');
	});
}

function popupLogin() {
	jQuery('.skip-links .skip-account').trigger('click');
	jQuery('div.shadow').addClass('active-form');
}
function isLoggedIn(onSuccess, onFailed) {
	jQuery.getJSON('/customers/ajaxLoggedIn', function(jData) {
		if (jData && jData.status == 1) {
			if (onSuccess && jQuery.isFunction(onSuccess)) onSuccess();
		} else {
			if (onFailed && jQuery.isFunction(onFailed)) onFailed();
		}
	});
}
function checkout() {
	var cartQty = jQuery.trim( jQuery('#ajaxcart #total_cart_qty').text() );
	if (cartQty && cartQty > 0) {
		showLoading();
		jQuery('#ajaxcart:visible').hide();
		jQuery("#ajaxallct").hide();
		isLoggedIn (function() {
			hideLoading();
			setLocation('/checkout');
		}, function() {
			hideLoading();
			global_state_data.loginData.profileUrl = '/checkout';
			popupLogin();
		});
	}
}

function showLoading() {
	jQuery("#ajaxcartloading").show();
	jQuery("#ajopacity").show();
}
function hideLoading() {
	jQuery("#ajaxcartloading").hide();
	jQuery("#ajopacity").hide();
}

/*paypal setup*/
function setupPaypalButton () {
	var CREATE_PAYMENT_URL  = 'http://dev.wowmart.com/orders/paypalstart',
		EXECUTE_PAYMENT_URL = 'http://dev.wowmart.com/orders/paypalpay',
		paypalEnv = 'sandbox'; //'production';
	
	if (window.paypal != undefined) {
		paypal.Button.render({

			env: paypalEnv, // Or 'sandbox'

			//commit: true, // Show a 'Pay Now' button
			style: {
				size: 'medium',
				color: 'blue',
				shape: 'rect',
				label: 'pay'
			},

			payment: function() {
				return paypal.request.post(CREATE_PAYMENT_URL).then(function(data) {
					alert(3333333333333);
					console.log(data);
					return data.id;
				});
			},

			onAuthorize: function(data) {
				return paypal.request.post(EXECUTE_PAYMENT_URL, {
					paymentID: data.paymentID,
					payerID:   data.payerID
				}).then(function() {

					// The payment is complete!
					// You can now show a confirmation message to the customer
				});
			}

		}, '#paypal_button_wrap');
	}
}
	
jQuery.noConflict();

jQuery(function($) {
	function removeNode(node, callback) {
		var $node = typeof node == 'object' ? node : $('#' + node);
		
		$node.fadeTo('slow', 0.2, function() { 
			$node.slideUp('normal', function() {
				$node.remove();
				if (callback && $.isFunction(callback)) {
					callback();
				}
			}); 
		});
	}
	function setupGeneralPopup() {
		var $popup = $('#popup-general-window');
		if (!$popup.length) {
			var htmlPopup = '<div class="popup-general-body"></div>' + 
							'<div class="popup-general-buttons">' + 
							'	<button class="button btn-popup-close"><span><span>Close</span></span></button>' + 
							'</div>';
			$popup = $('<div id="popup-general-window">').appendTo('body');
			
			$popup
				.html(htmlPopup)
				.dialog({
					autoOpen: false,
					show: {effect: "fade"},
					hide: {effect: "fade"},
					//height: 100,
					//width: 30,
					dialogClass: 'popup-general-window',
					resizable: false,
					modal: true,
					title: '',
					close: function() {
						jQuery('.ui-widget-overlay').unbind('click');
					},
					open: function() {
						jQuery('.ui-widget-overlay').unbind('click').click(function(e) {
							$popup.dialog('close');
						});
					}
				});	
			
			$('.btn-popup-close', $popup).unbind('click').click(function(e) {
				$popup.dialog('close');
			});
		}
	}
	function showGeneralPopup(htmlContent) {
		setupGeneralPopup();
		$('#popup-general-window .popup-general-body').html(htmlContent);
		$('#popup-general-window').dialog('open');
	}
	function hideGeneralPopup() {
		$('#popup-general-window .popup-general-body').empty();
		$('#popup-general-window').dialog('close');
	}
	
	function updateCart() {
		var $form = $('#key_form_cart');
		showLoading();
		$.post('/carts/ajaxupdate', $form.serialize(), function(jData) {
			hideLoading();
			if (jData.status == 1) {
				var cart = jData.data.ajaxCartData,
					items = cart.items;
				
				renderCart(jData.data);
				
				$("#shopping-cart-totals-table").find('.price').text('$' + cart.total);
				$.each(items, function(i, product) {
					var prod = product.CartItem,
						$qtyWrap = $('input.qty[name="cart[' + prod.product_id + '][qty]"]', $form).parent(),
						$amountWrap = $qtyWrap.next();
					$amountWrap.find('.price').text('$' + prod.total);
				});
			}
		}, 'JSON');
	}
	function updateCartItem(itemId, qty) {
		showLoading();
		$.post('/carts/ajaxupdate', {product_id: itemId, product_qty: qty}, function(jData) {
			hideLoading();
			if (jData.status == 1) {
				var cart = jData.data.ajaxCartData;
				
				renderCart(jData.data);
					
				$("#shopping-cart-totals-table").find('.price').text('$' + cart.total);
				
			}
		}, 'JSON');
	}
	function deleteCartItem(itemId) {
		showLoading();
		$.post('/carts/ajaxdelete', {product_id: itemId}, function(jData) {
			hideLoading();
			if (jData.status == 1) {
				var cart = jData.data.ajaxCartData,
					items = cart.items,
					$itemWrap = $('#key_prod_' + itemId);
				
				renderCart(jData.data);
					
				$("#shopping-cart-totals-table").find('.price').text('$' + cart.total);
				removeNode($itemWrap);
				
			}
		}, 'JSON');
	}
	function addCartEvents() {
		$('#cart-btn-groups').off('click').on('click', 'button', function(e) {
			var action = this.name;
			switch(action) {
				case 'cart_update' :
					updateCart();
					break;
			}
		});
		
		$('#key_form_cart .btn-remove').unbind('click').click(function(e) {
			var $itemWrap = $(this).parents('.item:first'),
				itemId = $itemWrap.attr('id').substr(9);
				
			deleteCartItem(itemId);
		});
		
		$('#key-category-produts .btn-cart').unbind('click').click(function(e) {
			var $prod = $(this).parents('.item:first'),
				prodId = $prod.prop('id'),
				prodName = $prod.find('.product-name:first a').text(),
				
				prodNo = prodId.substr(5);
				
			showLoading();
			
			$.post('/carts/ajaxAdd', {product:prodNo, qty: 1}, function(jData) {
				renderCart(jData.data);
				
				hideLoading();
				
				jQuery("#ajaxallct").show();
				
			}, 'JSON');
		});
		
		$('#ajaxcart').off('click').on('click', '#cart-sidebar .btn-remove, #cart-sidebar .btn-cart', function(e) {
			var $this = $(this),
				$itemWrap = $this.parents('.item:first'),
				itemId = $itemWrap.attr('id').substr(9);
			
			if ($this.hasClass('btn-remove')) {
				deleteCartItem(itemId);
			
			} else if ($this.hasClass('btn-cart')) {
				var qty = $.trim($this.prev('input').val());
				updateCartItem(itemId, qty);
			}
		});
	}
	
	/*my account*/
	function getAddressDetail(addressId, model, callback) {
		var ajaxUrl = '/account/address_get',
			params = {address_id: addressId, model: model};
			
		$.post(ajaxUrl, params, function(jData) {
			if (jData.status == 1) {
				if (callback && $.isFunction(callback)) callback(jData.data);
			}
		}, 'JSON');
	}
	function deleteAddress(model, addressId) {
		var ajaxUrl = '/account/address_delete',
			params = {address_id: addressId, model: model};
			
		$.post(ajaxUrl, params, function(jData) {
			if (jData.status == 1) {
				var tableId = 'address-table-' + model.toLowerCase(),
					$objRow = $('#' + tableId + ' tr#address_' + addressId);
				
				$objRow.slideUp('normal', function() {
					$objRow.remove();
				});
				
				if (jData.data && jData.data.default_id) {
					$('#' + tableId + ' tr#address_' + jData.data.default_id + ' .default').text('Default');
				}
			}
		}, 'JSON');
	}
	function editAddress(model, addressId) {
		var ajaxUrl = '/account/address_edit',
			params = {address_id: addressId, model: model};
			
		$.post(ajaxUrl, params, function(jData) {
			if (jData.status == 1) {
				var htmlRow = populateAddress(jData.data);
			}
		}, 'JSON');
	}
	function saveAddress() {
		var ajaxUrl = '/account/address_save',
			params = $('#form-address').serialize(),
			model = $('#popup-address-form input[name=model]').val(),
			formType = $('#popup-address-form input[name=form_type]').val();
			
		$.post(ajaxUrl, params, function(jData) {
			if (jData.status == 1) {
				if (formType == 'checkout') {
					renderCheckoutAddress(jData.data, model);
					
				} else {
					var data = jData.data,
						htmlRow = renderOneRowAddress(data),
						tableId = 'address-table-' + model.toLowerCase(),
						$table = $('#' + tableId),
						$row = $table.find('tr#address_' + data.address_id);
					
					if (data.is_default && data.is_default == 1) {
						$table.find('tr td.default').empty();
					}
					if ($row.length > 0) $row.replaceWith(htmlRow);
					else $table.prepend(htmlRow);
				}
				
				$('#popup-address-form').dialog('close');
			
			} else {
				if (jData.errorMsg) alert(jData.errorMsg.join("\r\n"));
			}
			
		}, 'JSON');
	}
	function populateAddress(data) {
		var $popup = $('#popup-address-form');
		
		$('input[name=address_id]', $popup).val(data.id);
		$('input[name=firstname]', $popup).val(data.firstname);
		$('input[name=lastname]', $popup).val(data.lastname);
		if (data.company) $('input[name=company]', $popup).val(data.company);
		$('input[name=address1]', $popup).val(data.address1);
		$('select[name=state]', $popup).val(data.state);
		$('input[name=suburb]', $popup).val(data.suburb);
		$('input[name=postcode]', $popup).val(data.postcode);
		$('input[name=phone]', $popup).val(data.phone);
		if (data.is_default && data.is_default == 1) {
			$('input[name=is_default]', $popup)
				.prop({'checked': true, 'disabled' : true})
				.after('<input type="hidden" name="is_default" value=1 />');
		}
	}
	function renderOneRowAddress(address) {
		var contactName = address.firstname + ' ' + address.lastname;
			htmlRow = '<tr id="address_' + address.address_id + '">' + 
					  '		<td class="order-contact">' + contactName + '</td>' + 
					  '		<td class="order-address">' + address.address1 + '</td>' + 
					  '		<td class="order-suburb">' + address.suburb + '</td>' + 
					  '		<td class="order-state">' + address.state + '</td>' + 
					  '		<td class="order-postcode">' + address.postcode + '</td>' + 
					  '		<td class="order-default default">'+ (address.is_default ? 'Default' : '&nbsp;') + '</td>' + 
					  '		<td class="">' + 
                      '			<span class="nobr">' + 
                      '				<a class="btn-edit-address">View/Edit</a>' + 
                      '			</span>' + 
                      '		</td>' + 
					  '		<td class="last">' + 
                      '			<span class="nobr">' + 
                      '				<a class="btn-delete-address">Delete</a>' + 
                      '			</span>' + 
                      '		</td>' + 
					  '</tr>';
    	
		return htmlRow;
	}
	function renderAddressForm(model, action, formType) {
		var txtAction = action ? action : 'Add New',
			txtFormType = formType ? formType : '',
			htmlForm = '<form id="form-address">' + 
					   '<input type="hidden" name="form_type" value="' + txtFormType + '" />' + 
					   '<input type="hidden" name="address_id" value="" />' + 
					   '<input type="hidden" name="model" value="' + model + '" />' + 
					   '<div class="youama-window-box">' + 
                	   '	<div class="youama-window-subtitle youama-showhideme">' + 
					   '		<p>' + txtAction + ' ' + model + ' Address</p>' + 
                	   '	</div>' + 
                	   '	<div class="youama-window-content">' + 
					   '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">First Name <span>*</span></label>' + 
                       '			<input type="text" placeholder="First Name" name="firstname" value="">' + 
					   '		</div>' +
					   '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">Last Name <span>*</span></label>' + 
                       '			<input type="text" placeholder="Last Name" name="lastname" value="">' + 
					   '		</div>' + 
					   '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">Company <span></span></label>' + 
                       '			<input type="text" placeholder="Company (optional)" name="company" value="">' + 
					   '		</div>' + 
					   '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">Address <span>*</span></label>' + 
                       '			<input type="text" placeholder="Your address" name="address1" value="">' + 
					   '		</div>' + 
					   '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">State <span>*</span></label>' + 
                       '			<select name="state">' +
					   '				<option>ACT</option>' + 
					   '				<option>NSW</option>' +
					   '				<option>NT</option>' + 
					   '				<option>QLD</option>' + 
					   '				<option>SA</option>' + 
					   '				<option>TAS</option>' + 
					   '				<option>VIC</option>' + 
					   '				<option>WA </option>' + 
					   '			</select>' +  
					   '		</div>' + 
					    '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">Suburb <span>*</span></label>' + 
                       '			<input type="text" placeholder="Your suburb" name="suburb" value="">' + 
					   '		</div>' +
					   '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">Postcode <span>*</span></label>' + 
                       '			<input type="text" placeholder="Your Postcode" name="postcode" value="">' + 
					   '		</div>' +
					   '		<div class="input-fly youama-showhideme">' + 
                       '			<label for="youama-email">Phone <span></span></label>' + 
                       '			<input type="text" placeholder="Phone Number" name="phone" value="">' + 
					   '		</div>' + 
					   '		<div class="input-fly youama-showhideme checkbox-field">' + 
                       '			<input type="checkbox" id="default_address" name="is_default" value="1">' + 
					   '			<label for="default_address">Set to Default ' + model + ' Address</label>' + 
					   '		</div>' +
					   '	</div>' + 
					   '</div>' + 
					   '<div class="youama-window-box">' + 
					   '	<button id="btn-address-cancel" type="button" class="button youama-ajaxlogin-button">' + 
                       '		<span><span>Cancel</span></span>' + 
                       '	</button>' + 
					   '	<button id="btn-address-save" type="button" class="button youama-ajaxlogin-button">' + 
                       '		<span><span>Submit</span></span>' + 
                       '	</button>' + 
					   '</div>' + 
					   '</form>';
        
		return htmlForm;
	}
	function setupAddressForm() {
		var $popup = $('#popup-address-form');
		if (!$popup.length) {
			$popup = $('<div id="popup-address-form">').appendTo('body');
			
			$popup
				.dialog({
					autoOpen: false,
					show: {effect: "fade"},
					hide: {effect: "fade"},
					//height: 100,
					//width: 30,
					dialogClass: 'address-form-popup',
					resizable: false,
					modal: true,
					title: '',
					close: function() {
						jQuery('.ui-widget-overlay').unbind('click');
					},
					open: function() {
						$('#btn-address-save', $popup).unbind('click').click(function(e) {
							saveAddress();
						});
						$('#btn-address-cancel', $popup).unbind('click').click(function(e) {
							$popup.dialog('close');
						});
						jQuery('.ui-widget-overlay').unbind('click').click(function(e) {
							$popup.dialog('close');
						});
					}
				});	
		}
	}
	function addMyAccountEvents() {
		$('#my-address-book .data-table').off('click').on('click', 'td a', function(e) {
			var $this = $(this),
				$row = $this.parents('tr:first'),
				$table = $row.parents('table:first'),
				strAddress = $row.attr('id'),
				tableId = $table.attr('id'),
				addressId = strAddress.substr(8),
				model = tableId.substr(14);
			
			if ($this.hasClass('btn-edit-address')) {
				var htmlForm = renderAddressForm(model);
		
				$('#popup-address-form').html( htmlForm ).dialog('open');
				editAddress(model, addressId);
				
			} else if($this.hasClass('btn-delete-address')) {
				if (confirm('Are you sure to delete this address?')) {
					deleteAddress(model, addressId);
				}
			}
		});

		$('#my-address-book .btn-create-address').unbind('click').click(function(e) {
			var $table = $(this).parent('.box-title').next('table'),
				tableId = $table.attr('id'),
				model = tableId.substr(14),
				htmlForm = renderAddressForm(model);
		
			$('#popup-address-form').html( htmlForm ).dialog('open');
		});
	}
	function initMyAccount() {
		setupAddressForm();
		addMyAccountEvents();
	}
	/*end of my account*/
	
	/*checkout page*/
	function renderCheckoutAddress(data, model) {
		if (data) {
			var contactName = data.firstname + ' ' + data.lastname,
				htmlAddr = contactName + '<br>' + 
						   data.address1 + ', ' + data.suburb + '<br>' + 
						   data.state + ' ' + data.postcode + '<br><br>' + 
						   'Contact Number: ' + data.phone;
			$('#checkout-step-' + model + ' .current_address_info').html(htmlAddr);
			$('input#current_' + model + '_id').val(data.address_id);
		}
	}
	function addCheckoutEvents() {
		$('#checkoutSteps .btn_address_change').unbind('click').click(function(e) {
			var $wrap = $(this).parents('.section:first'),
				model = $('.current_address_model', $wrap).val(),
				addrId = $('input#current_' + model + '_id').val(),
				htmlOpts = $('#billing_options').html();
			
			showGeneralPopup(htmlOpts);
			
			$('.option').unbind('click').click(function(e) {
				var $this = $(this),
					optId = this.id,
					arrOpts = optId.split('_'),
					selAddrId = arrOpts[arrOpts.length - 1];
				$this
					.toggleClass('selected')
					.siblings().removeClass('selected');
				
				hideGeneralPopup();
				
				if (!optId) {
					if ($this.hasClass('new-address')) {
						var htmlForm = renderAddressForm(model, 'Add New', 'checkout');
		
						$('#popup-address-form').html( htmlForm ).dialog('open');
						editAddress(model);
					} else if ($this.hasClass('edit-address') && addrId) {
						var htmlForm = renderAddressForm(model, 'Edit', 'checkout');
		
						$('#popup-address-form').html( htmlForm ).dialog('open');
						editAddress(model, addrId);
					}
				} else {
					getAddressDetail(selAddrId, model, function(details) {
						renderCheckoutAddress(details, model);
					});
				}
		
			});
		});
		
		$('#billing-buttons-container .btn_continue').click(function(e) {
			var $btnContainer = $(this).parent(),
				useBilling = $btnContainer.prev().find('input[name=use_for_shipping]:checked').val();
			
			$('.section.active').children('.step').slideUp();
			if (useBilling == 1) {
				$('#opc-payment').addClass('active');
				$('#opc-payment .step').slideDown();
				$('#opc-shipping').removeClass('active');
			} else {
				$('#opc-shipping').addClass('active');
				$('#opc-shipping .step').slideDown();
			}
			
		});
		$('#shipping-buttons-container .btn_continue').click(function(e) {
			$('.section.active').children('.step').slideUp();
			$('#opc-payment').addClass('active');
			$('#opc-payment .step').slideDown();
			
		});

		$('#checkoutSteps .back-link').unbind('click').click(function(e) {
			$currSection = $(this).parents('.section:first');
			$currSection.prevAll('.active:first').children('.step').slideDown();
			$currSection.children('.step').slideUp();
		});

		 setupPaypalButton();
	}
	/*end of checkout*/

	addCartEvents();
	initMyAccount();
	
	addCheckoutEvents();
});


