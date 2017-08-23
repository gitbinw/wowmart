<?php
require_once(APP_VENDORS_PAYPAL_REST . DS . 'paypalConfig.php');
debug($order_items);
$htmlBillingOpts = '';
$htmlDefaultBilling = '';
$defaultBillingId = '';
$defaultShippingId = '';
if (isset($billings) && count($billings) > 0) {
	$count = count($billings);
	$htmlBillingOpts = '<div class="edit-address option">
							<span class="opt-bg">
								<span class="opt-icon"></span>	
							</span>
							<span class="opt-text">Edit this address</span>
						</div>
						<div class="new-address option">
							<span class="opt-bg">
								<span class="opt-icon"></span>	
							</span>
							<span class="opt-text">Add a new address</span>
						</div>';
	if ($count > 1) {
		$htmlBillingOpts .= '<h3 class="address_info">OR</h3>
							 <p class="address_info">Pick up another address:</p>';
	}
	foreach($billings as $billing) {
		$c = $billing['Contact'];
		if ($c['is_default'] == 1) {
			$defaultBillingId = $c['id'];
			$htmlDefaultBilling = $c['firstname'] . ' ' . $c['lastname'] . '<br>' . 
								  $c['address1'] . ', ' . $c['suburb'] . '<br>' . 
								  $c['state'] . ' ' . $c['postcode'] . '<br><br>' . 
								  'Contact Number: ' . $c['phone'];
		}
		if ($count >1) {
			$htmlBillingOpts .= '<div class="option" id="address_' . $c['id'] . '">	
									<span class="opt-bg"><span class="opt-icon"></span></span>
									<span class="opt-text">' . 
									$c['address1'] . ' ' . $c['suburb'] . ' ' . $c['state'] . ' ' . $c['postcode'] .
								'	</span>
								</div>';
		}
	}
}
?>
<div class="page-title">
    <h1>Checkout</h1>
</div>
<ol class="opc" id="checkoutSteps">
    <li id="opc-billing" class="section allow active">
        <div class="step-title">
            <span class="number">1</span>
            <h2>Billing Information</h2>
            <a class="btn btn_address_change">Change</a>
        </div>
        <div id="checkout-step-billing" class="step a-item">
        	<input type="hidden" class="current_address_model" value="billing" />
            <input type="hidden" id="current_billing_id" name="billing_id" value="<?php echo $defaultBillingId;?>" />
        	<div id="billing_options" class="force-hidden"><?php echo $htmlBillingOpts;?></div>
        	<p class="current_address_info">
            	<?php echo $htmlDefaultBilling; ?>
            </p>
            
            <ul class="form-list">
                <li class="control">
                    <input name="use_for_shipping" id="billing_use_for_shipping_yes" value="1" type="radio" 
                    	title="Ship to this address" class="radio" checked>
                        <label for="billing_use_for_shipping_yes">Ship to this address</label>
                </li>
                <li class="control">
                    <input name="use_for_shipping" id="billing_use_for_shipping_no" value="0" type="radio" 
                    	title="Ship to a different address" class="radio">
                        <label for="billing_use_for_shipping_no">Ship to a different address</label>
                </li>
            </ul> <!-- end of form-list -->

            <div class="buttons-set" id="billing-buttons-container">
                <button type="button" title="Continue" class="button btn_continue"><span><span>Continue</span></span></button>
                <span class="please-wait" id="billing-please-wait" style="display:none;">
                    <img src="/img/icons/icon_load.gif" alt="Loading next step..." title="Loading next step..." class="v-middle"> Loading next step...        
                </span>
            </div>
                
         </div>
     </li>
        
     <li id="opc-shipping" class="section">
        <div class="step-title">
            <span class="number">2</span>
            <h2>Shipping Information</h2>
            <a class="btn btn_address_change">Change</a>
        </div>
        <div id="checkout-step-shipping" class="step a-item">
        	<input type="hidden" class="current_address_model" value="shipping" />
            <input type="hidden" id="current_shipping_id" name="shipping_id" value="<?php echo $defaultBillingId;?>" />
        	<div id="shipping_options" class="force-hidden"><?php echo $htmlBillingOpts;?></div>
        	<p class="current_address_info">
            	<?php echo $htmlDefaultBilling; ?>
            </p>

            <div class="buttons-set" id="shipping-buttons-container">
                <p class="back-link"><a><small>« </small>Back</a></p>
                <button type="button" title="Continue" class="button btn_continue"><span><span>Continue</span></span></button>
                <span class="please-wait" id="shipping-please-wait" style="display:none;">
                    <img src="/img/icons/icon_load.gif" alt="Loading next step..." title="Loading next step..." class="v-middle"> Loading next step...        
                </span>
            </div>
                
        </div>
    </li>
    <li id="opc-payment" class="section">
        <div class="step-title">
            <span class="number">3</span>
            <h2>Payment</h2>
        </div>
        <div id="checkout-step-payment" class="step a-item">
            <form action="" id="co-payment-form">
                <fieldset>
                    <dl class="sp-methods" id="checkout-payment-method-load">
                        <li class="payment_method_paypal"></li>
                    </dl>
                </fieldset>
                <div class="payment_details">
                    <fieldset>
                        <label id="payment-items-qty"></label><span id="payment-items-total"></span>
                    </fieldset>
                    <fieldset>
                        <label>Delivery</label><span id="payment-delivery-fee">Free</span>
                    </fieldset>
                    <fieldset>
                        <label class="payment_total_label">Total</label><span id="payment-total"></span>
                    </fieldset>
                </div>
            </form>
            <div class="tool-tip" id="payment-tool-tip" style="display:none;">
                <div class="btn-close"><a href="#" id="payment-tool-tip-close" title="Close">Close</a></div>
                <div class="tool-tip-content"><img src="Checkout1_files/cvv.gif" alt="Card Verification Number Visual Reference" title="Card Verification Number Visual Reference"></div>
            </div>
            <div class="buttons-set" id="payment-buttons-container">
                <p class="back-link"><a><small>« </small>Back</a></p>
                <!--<button type="button" class="button" onclick="payment.save()"><span><span>Pay Now with Paypal</span></span></button>-->
                <input type="hidden" name="csrf" id="payment_csrf" value="<?php echo $csrf;?>" />
                <div id="paypal_button_wrap" class="text-right"></div>
                <span class="please-wait" id="payment-please-wait" style="display:none;">
                    <img src="/img/icons/icon_load.gif" alt="Loading next step..." title="Loading next step..." class="v-middle"> Loading next step...    
                </span>
            </div>

        </div>
    </li>
</ol>