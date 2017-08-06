<?php
	$titleEmpty = '';
	$showCart = true;
	if (!isset($cart) || count($cart->items) <= 0) {
		$showCart = false;
		$titleEmpty = 'Is Empty';
	}
?>
<div class="cart">
	<div class="page-title title-buttons">
		<h1>Shopping Cart <?php echo $titleEmpty;?></h1>
		<ul class="checkout-types">
			<li> 
            	<button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout" onclick="setLocation('/checkout');"><span><span>Proceed to Checkout</span></span></button>
			</li>
		</ul>
	</div>
    
<?php if ($showCart === true) { ?>

	<form action="/cart/update" method="post" id="key_form_cart">
		<fieldset>
			<table id="shopping-cart-table" class="data-table cart-table">
				<colgroup>
                	<col width="1">
					<col>
                    <col width="1">
                    <col width="1">
                    <col width="1">
                    <col width="1">
                </colgroup>
            	<thead>
                    <tr class="first last">
                    <th rowspan="1">&nbsp;</th>
                    <th rowspan="1"><span class="nobr">Product Name</span></th>
                    <th class="a-center" colspan="1"><span class="nobr">Unit Price</span></th>
                    <th rowspan="1" class="a-center">Qty</th>
                    <th class="a-center" colspan="1">Subtotal</th>
                    <th rowspan="1" class="a-center">&nbsp;</th>
                    </tr>
				</thead>
				<tfoot>
					<tr class="first last">
						<td colspan="50" class="a-right last" id="cart-btn-groups">
							<button type="button" name="continue_shopping" title="Continue Shopping" class="button btn-continue" onclick="setLocation('/')"><span><span>Continue Shopping</span></span></button>
							<button type="button" name="cart_update" title="Update Shopping Cart" class="button btn-update"><span><span>Update Shopping Cart</span></span></button>
							<button type="button" name="cart_empty" title="Clear Shopping Cart" class="button btn-empty" onclick="setLocation('/carts/clear')"><span><span>Clear Shopping Cart</span></span></button>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php
						if (isset($cart)) {
							$totalAmount = $cart->total;
							if (isset($cart->items) && count($cart->items) > 0) {
								foreach($cart->items as $item) {
									$prodId = $item['CartItem']['product_id'];
									$prodNo = $item['CartItem']['serial_no'];
									$prodName = $item['CartItem']['name'];
									$prodAlias = $item['CartItem']['product_alias'];
									$amount = $item['CartItem']['total'];
									$imgSrc = $utility->getProductImageUrl(PRODUCT_IMAGE_URL_ROOT, $prodId, $prodAlias, 'xsmall');
					?>
                                <tr id="key_prod_<?php echo $prodNo;?>" class="item">
                                    <td>
                                    	<a href="/product/<?php echo $prodAlias;?>" title="<?php echo $prodName;?>" class="product-image">
                                        	<img src="<?php echo $imgSrc;?>" width="75" alt="<?php echo $prodName;?>"/>
                                        </a>
                                    </td>
                                    <td>
                                    <h2 class="product-name">
                                    <a href="/product/<?php echo $prodAlias;?>" title="<?php echo $prodName;?>">
                                    	<?php echo $prodName;?>
                                    </a>
                                    </h2>
                                    </td>
                                    <td class="a-right">
                                    <span class="cart-price">
                                    <span class="price">$<?php echo $item['CartItem']['price'];?></span>
                                    </span>
                                    </td>
                                     
                                    <td class="a-center">
                                    <input name="cart[<?php echo $prodId;?>][qty]" value="<?php echo $item['CartItem']['qty'];?>" size="4" title="Qty" class="input-text qty" maxlength="2"/>
                                    </td>
                                     
                                    <td class="a-right">
                                    <span class="cart-price">
                                    <span class="price">$<?php echo $amount;?></span>
                                    </span>
                                    </td>
                                    <td class="a-center"><a title="Remove item" class="btn-remove btn-remove2">Remove item</a></td>
                                 </tr>
                    <?php
								}
							}
						}
					?>
             	</tbody>
        	</table>
		</fieldset>
	</form>
	
    <div class="cart-collaterals">
    	<div class="totals">
			<table id="shopping-cart-totals-table">
				<colgroup>
                	<col>
					<col width="1">
				</colgroup>
                <tfoot>
                    <tr>
                    	<td style="" class="a-right" colspan="1">
                    		<strong>Grand Total</strong>
                    	</td>
                    	<td style="" class="a-right">
                    		<strong><span class="price">$<?php echo $totalAmount;?></span></strong>
                    	</td>
                    </tr>
            	</tfoot>
				<!--<tbody>
					<tr>
						<td style="" class="a-right" colspan="1">
							Subtotal 
                       	</td>
                        <td style="" class="a-right">
                        	<span class="price">$<?php echo $totalAmount;?></span> 
                       	</td>
                 	</tr>
             	</tbody>-->
			</table>
			<ul class="checkout-types">
				<li> 
                	<button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout" onclick="window.location='/checkout';"><span><span>Proceed to Checkout</span></span></button>
				</li>
          	</ul>
		</div>
	</div>
    
<?php } else { ?>

	<div style="text-align:center;">
		<h2>You have no items in your shopping cart.</h2>
		<a href="/">Click here to continue shopping</a>.
	</div>
    
<?php } ?>
             
</div>