<div class="checkout_head">
	<div class="checkout_cart" style="margin-left: 570px;"></div>
	<ul>
		<li class="step current">1 <div>Login</div> <div class="delimiter"></div></li>
		<li class="step current"><a href="/checkout/billing">2 <div>Billing</div></a> <div class="delimiter"></div></li>
		<li class="step current"><a href="/checkout/delivery">3 <div>Delivery</div></a> <div class="delimiter"></div></li>
		<li class="step current">4 <div>Confirm & Payment</div></li>
	</ul>
</div>

<div class="main_title">
	<i>Step 4 - Confirm & Payment</i><br>
</div>

<div class="item_list">
	<div class="list_title">Review your order items.</div>
	<table cellspacing=0 cellpadding=0 width="100%">
		<tr>
			<th colspan="2" align="center">Item</th>
			<th width="80px" align="center">Item No.</th>
			<th width="60px" align="center">Price</th>
            <?php 
				$colspan = 5;
				$payTitle = 'Select a payment method.';
				$payCash  = ' <div class="pay_method"><input type="radio" name="payment_method" value="cash" ' 
							. (isset($payment_method) && $payment_method == 'cash' ? 'checked' : '') 
							. '><b>Pay by Cash</b></div>'
							. '<div><img src="/img/home/pay_cash.gif" /></div>';
				if ($cart['businessCode'] != BUSINESS_BRASA_DELIVERY) { 
					$colspan = 6;
					$payTitle = 'Pay your order with Paypal.';
					$payCash  = '';
			?>
			<th width="60px" align="center">Delivery</th>
            <?php } ?>
			<th width="40px" align="center">Qty.</th>
			<th width="60px" align="right">Subtotal</th>
		</tr>
<?php
		foreach($cart['items'] as $key=>$item) {
?>
			<tr>
				<td width="105px" style="overflow:hidden;"><a href="/products/view/<?=$key;?>">
<?php				if (isset($item['img'])) { ?>
						<img width="60" height="45" src="/img/images/product/<?=$item['img'];?>/<?=$item['img'];?>a4<?=$item['ext'];?>" border="0" />
<?php 				} else { ?>
						<img width="60" height="45" src="/img/home/noimage_small.gif" border="0" />
<?php				}	?>
					</a></td>
				<td valign="top" align="left">
					<?=$item['item_name'];?>
				</td>
				<td valign="top" align="center">
					<?=$item['item_number'];?>
				</td>
				<td valign="top" align="center">
					$<?=$item['amount'];?>
				</td>
                <?php if ($cart['businessCode'] != BUSINESS_BRASA_DELIVERY) { ?>
				<td valign="top" align="center">
					$<?=number_format($item['shipping'], 2);?>
				</td>
                <?php } ?>
				<td valign="top" align="center">
					<?=$item['quantity'];?>
				</td>
				<td valign="top" align="right">
					$<?=number_format($item['subtotal'], 2);?>
				</td>
			</tr>
<?php
		}
?>
		<tr>
			<td colspan="<?=$colspan;?>" align="right" class="nobottom"><b>Item Total:</b></td>
			<td align="right" class="nobottom"><b>$<?=number_format($cart['totalAmount'], 2);?></b></td>
		</tr>
		<tr>
			<td colspan="<?=$colspan;?>" align="right" class="nobottom"><b>Delivery Fee:</b></td>
			<td align="right"><b>$<?=number_format($cart['totalShipping'], 2);?></b></td>
		</tr>
		<tr>
			<td colspan="<?=$colspan;?>" align="right" class="nobottom"><b>Total:</b></td>
			<td align="right"><b>$<?=number_format($cart['totalAmount'] + $cart['totalShipping'], 2);?></b></td>
		</tr>
	</table>
</div>

<div class="clear_line_30"></div>

<div class="item_list">
	<div class="list_title">Review your billing address and delivery address.</div>
	<table cellspacing=0 cellpadding=0 width="100%">
		<tr>
			<th width="50%" align="center">Billing Details</th>
			<th width="50%" align="center">Delivery Details</th>
		</tr>
		<tr>
			<td valign="top" class="address_view" style="border-right: 1px solid #cccccc;">
				<?="<b>" . $billingAddress['firstname'] . "&nbsp;" . $billingAddress['lastname'] . "</b><br>" .
					 $billingAddress['address1'] . "&nbsp;" . $billingAddress['address2'] . "<br>" .
					 $billingAddress['suburb'] . "&nbsp;" .$billingAddress['state'] . "&nbsp;" . 
					 $billingAddress['postcode'] . "<br>" . "Australia" . "<br>" .
					 (!empty($billingAddress['phone']) ? "Phone: " . $billingAddress['phone'] . "<br>" : "") . 
					 (!empty($billingAddress['mobile']) ? "Mobile: " . $billingAddress['mobile'] . "<br>" : "");
				?>
			</td>
			<td valign="top" class="address_view">
				<?="<b>" . $shippingAddress['firstname'] . "&nbsp;" . $shippingAddress['lastname'] . "</b><br>" .
					 $shippingAddress['address1'] . "&nbsp;" . $shippingAddress['address2'] . "<br>" .
					 $shippingAddress['suburb'] . "&nbsp;" .$shippingAddress['state'] . "&nbsp;" . 
					 $shippingAddress['postcode'] . "<br>" . "Australia" . "<br>" .
					 (!empty($shippingAddress['phone']) ? "Phone: " . $shippingAddress['phone'] . "<br>" : "") . 
					 (!empty($shippingAddress['mobile']) ? "Mobile: " . $shippingAddress['mobile'] . "<br>" : "");
				?>
			</td>
		</tr>
       
	</table>
</div>
<?php if (isset($shipping_error) && !empty($shipping_error)) { ?>
        <div class="delivery_error"><?=$shipping_error;?></div>
<?php } ?>
        
<div class="clear_line_30"></div>

<form action='/orders/place' id='Form_Payment' name='Form_Payment' 
		  method='post' onsubmit="beforeSubmit('btn_placeorder');">
<div class="item_list">
	<div class="list_title">
    	<?=$payTitle;?>
    </div>
	<div class="pay_options">
		<div class="pay_method">
			<input type="radio" name="payment_method" value="paypal" <?=(isset($payment_method) && $payment_method == 'cash' ? '' : 'checked');?>><b>Pay with Paypal</b>
		</div>
		<div><img src="/img/home/btn_paypal.gif" /></div>
        <?=$payCash;?>
	</div>
</div>

<div class="checkout_form">
	<div style="padding: 30px; margin:0 auto; width:300px;">
		<a href="/checkout/delivery">
			<div class='button btn_link' style="float:left;margin-right:10px;"><< Go Back</div>
		</a>
		<div  class="cart_checkout">
			<div class="submit">
					<input type="submit" value="<?=isset($orderPlaced) && $orderPlaced == true ? 
									"Continue to Pay" : "Place Order";?>" 
								 id="btn_placeorder">
			</div>
		</div>	
	</div>
</div>
</form>

<div class="clear_line_30"></div>
