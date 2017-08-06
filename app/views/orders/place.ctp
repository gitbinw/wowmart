<?php
header('Cache-Control: no-cache, must-revalidate');
$error_b_message = '';
$error_s_message = '';
if ( isset($html->validationErrors) && count($html->validationErrors) >0 ){
	$errors = $html->validationErrors;
	if (isset($errors['ClientProfile'])) {
		foreach($errors['ClientProfile'] as $key=>$err) {
			if ($key == 'bill_field_empty') {
                                $error_b_message .= "<span class='msg_warn'>Please fill up all required fields.</span><br>";
                        }
			if ($key == 'bill_email_invalid') {
				$error_b_message .= "<span class='msg_warn'>Email</span> is not valid. ";
			}
                        if ($key == 'bill_phone_invalid') {
                                $error_b_message .= "<span class='msg_warn'>Phone</span> is not valid. ";
                        } 
                }
	}
	if (isset($errors['Shipping'])) {
		$error_s_message .= "<span class='msg_warn'>Please fill up all required fields.</span>";
	}
}

if (isset($cart['order_id']) && !empty($cart['order_id'])) {
	echo $utility->orderEditClue($cart);
}
?>
<div id='order_status_list'><?=$utility->orderStatusTrack(@$status,@$sel_status,@$invoice,@$order_id);?></div>
<p class='cart_title'>Your current order sheet</p>

<form method='POST' name='form_detail' id='form_detail'>
<table class='cart_view_list' cellspacing='0' cellpadding='0'>
<tr class='column'><td width='50'>Image</td><td>Item No.</td><td>Product</td>
<td>Price($)</td><td>Dis.(%)</td><td width='60'>Deal($)</td><td width='50'>Qty.</td><td width='60px'>Total($)</td></tr>
<?php
foreach (@$cart['items'] as $key => $prod) {
	if ( !empty($user_discount) && @$user_discount>$prod['disc'] ) {
		$prod['disc'] = $user_discount;
		$prod['deal'] = (1-$user_discount/100) * $prod['price'];
		$old_ttl = $prod['ttl'];
		$prod['ttl'] = $prod['deal']*$prod['qty'];
		$cart['amount'] = $cart['amount'] + $prod['ttl'] - $old_ttl;
	}
	echo	"<tr><td>".$html->image(@$prod['img'],array('style'=>'width:35px;height:32px;','border'=>0)).
	     	"</td><td>".$prod['serial']."</td><td>".$prod['name']."</td><td>".number_format($prod['price'],2,'.',',').
		"</td><td>".number_format($prod['disc'],1,'.',',')."</td><td>".number_format($prod['deal'],2,'.',',')."</td>".
		"<td>".$prod['qty']."</td>".
	     	"<td class='rgt'>".number_format($prod['ttl'],2,'.',',')."</td></tr>";
}
$cart ['subtotal'] = $cart ['amount'] + @$cart['freight'];
$cart ['gst'] = $cart ['subtotal'] * $gst_percent;
$cart ['total'] = $cart ['subtotal'] + $cart ['gst'];
?>
<tr><td colspan="7" class="lft">FREIGHT:</td>
<td class="cart_freight">
<INPUT TYPE='TEXT' NAME='data[Order][freight]' VALUE='<?=number_format(@$cart['freight'],2,'.',',');?>' onkeyup="calCart(this,<?=$cart ['amount'];?>,0.1);">
</td></tr>
<tr><td colspan="7" class="lft">SUBTOTAL:</td>
<td id='cart_sub' class='ttl'><?=number_format($cart['subtotal'],2,'.',',');?></td></tr>
<tr><td colspan="7" class="lft">GST:</td>
<td id='cart_gst' class='ttl'><?=number_format($cart['gst'],2,'.',',');?></td></tr>
<tr><td colspan="7" class="lft">TOTAL:</td>
<td id='cart_total' class='ttl'><?=number_format($cart['total'],2,'.',',');?></td></tr>
</table>

<table class='reg_form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Billing Address Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<input type='hidden' name='data[Client][id]' value='<?=@$user['Client']['id'];?>'>
<input type='hidden' name='data[ClientProfile][id]' value='<?=@$user['ClientProfile']['id'];?>'>
<tr><td colspan="2" class='msg_note'><?=@$error_b_message;?></td></tr>
<tr>
<td>Company Name:</td>
<td><input type='text' name='data[ClientProfile][company]' id='txt_b_company' value='<?=@$user['ClientProfile']['company'];?>'>
</td>
</tr>
<tr>
<td>First Name:</td>
<td><input type='text' name='data[ClientProfile][firstname]' id='txt_b_fname' value='<?=@$user['ClientProfile']['firstname'];?>'></td>
</tr>
<tr>
<td>Last Name:</td>
<td><input type='text' name='data[ClientProfile][lastname]' id='txt_b_lname' value='<?=@$user['ClientProfile']['lastname'];?>'></td>
</tr>
<tr>
<td>Email:</td>
<td><input type='text' name='data[ClientProfile][email]' id='txt_b_email' size='40' value='<?=@$user['ClientProfile']['email'];?>'>
</td>
</tr>
<tr>
<td>Phone:</td>
<td><input type='text' name='data[ClientProfile][phone]' id='txt_b_phone' value='<?=@$user['ClientProfile']['phone'];?>'></td>
</tr>
<tr>
<td>Fax:</td>
<td><input type='text' name='data[ClientProfile][fax]' id='txt_b_fax' value='<?=@$user['ClientProfile']['fax'];?>'></td>
</tr>
<tr>
<td>Mobile:</td>
<td><input type='text' name='data[ClientProfile][mobile]' id='txt_b_mobile' value='<?=@$user['ClientProfile']['mobile'];?>'></td>
</tr>

<tr>
<td>Address:</td>
<td><input type='text' name='data[ClientProfile][address]' id='txt_b_address' size='40' value='<?=@$user['ClientProfile']['address'];?>'></td>
</tr>
<tr>
<td>Suburb:</td>
<td><input type='text' name='data[ClientProfile][suburb]' id='txt_b_suburb' value='<?=@$user['ClientProfile']['suburb'];?>'></td>
</tr>
<tr>
<td>State:</td>
<td><input type='text' name='data[ClientProfile][state]' id='txt_b_state' value='<?=@$user['ClientProfile']['state'];?>'></td>
</tr>
<tr>
<td>Postcode:</td>
<td><input type='text' name='data[ClientProfile][postcode]' id='txt_b_postcode' value='<?=@$user['ClientProfile']['postcode'];?>'></td>
</tr>
<tr><td colspan="2" style='padding:5px 0;'>
<input type='checkbox' onclick='cpBilling(this);'><b>Use the same information as Billing for Shipping</b>
</td></tr>
</table></td></tr></table>

<table class='reg_form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Shipping Address Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">
<input type='hidden' name='data[Shipping][id]' value='<?=@$sel_ship['Shipping']['id'];?>'>
<tr><td colspan="2" class='msg_note'><?=@$error_s_message;?></td></tr>
<tr>
<td>Shop Name:</td>
<td><input type='text' name='data[Shipping][company]' id='txt_s_company' value='<?=@$sel_ship['Shipping']['company'];?>'>
<span class='msg_note'>required</span></td>
</tr>
<tr>
<td>First Name:</td>
<td><input type='text' name='data[Shipping][firstname]' id='txt_s_fname' value='<?=@$sel_ship['Shipping']['firstname'];?>'>
<span class='msg_note'>required</span></td>
</tr>
<tr>
<td>Last Name:</td>
<td><input type='text' name='data[Shipping][lastname]' id='txt_s_lname' value='<?=@$sel_ship['Shipping']['lastname'];?>'>
<span class='msg_note'>required</span></td>
</tr>
<tr>
<td>Address:</td>
<td><input type='text' name='data[Shipping][address]' id='txt_s_address' size='40' value='<?=@$sel_ship['Shipping']['address'];?>'>
<span class='msg_note'>required</span></td>
</tr>
<tr>
<td>Suburb:</td>
<td><input type='text' name='data[Shipping][suburb]' id='txt_s_suburb' value='<?=@$sel_ship['Shipping']['suburb'];?>'>
<span class='msg_note'>required</span></td>
</tr>
<tr>
<td>State:</td>
<td><input type='text' name='data[Shipping][state]' id='txt_s_state' value='<?=@$sel_ship['Shipping']['state'];?>'>
<span class='msg_note'>required</span></td>
</tr>
<tr>
<td>Postcode:</td>
<td><input type='text' name='data[Shipping][postcode]' id='txt_s_postcode' value='<?=@$sel_ship['Shipping']['postcode'];?>'>
<span class='msg_note'>required</span></td>
</tr>
<tr>
<td>Phone:</td>
<td><input type='text' name='data[Shipping][phone]' id='txt_s_phone' value='<?=@$sel_ship['Shipping']['phone'];?>'></td>
</tr>
<tr>
<td>Mobile:</td>
<td><input type='text' name='data[Shipping][mobile]' id='txt_s_mobile' value='<?=@$sel_ship['Shipping']['mobile'];?>'></td>
</tr>
<tr>
<td>Fax:</td>
<td><input type='text' name='data[Shipping][fax]' id='txt_s_fax' value='<?=@$sel_ship['Shipping']['fax'];?>'></td>
</tr>
</table></td></tr></table>

</form>
