<?php 
	$colspan = '5';
	if (!isset($order) || empty($order)) {
		echo '<div class="item_not_found">
			You have finished your order. 
			The session is now closed. <br>
			You can login to <a href="/account">My Account</a> to review your order.
			</div>';
	} else {
		if (!isset($is_view) || $is_view != true) {
?>
<div class="main_title">
	<i>Thank you for your payment.</i><br>
</div>
<div class="sub_title">
	Your order has been successfully submited. 
	We will process your order as soon as possible. 
	A confirmation  email has been sent to your email: <?=$Auth['User']['email'];?>. <br>
	Also, you can login to <a href="/account">My Account</a> to review your order.<br><br>
</div>
<?php
}
?>

<div id='email_content' style='padding: 10px 10px;'>
<table style="text-align:center;width:100%;background-color:#ffffff;" cellspacing='0' cellpadding='0'>
<tr>
<td align='left' style='padding-left:10px;'>
	<img src="/img/home/logo.gif" />
</td>
<td style="font-size:10pt;" align="right">
	<span style="font-size:12pt;"><b><?=$store['Store']['company'];?></b><br></span>
	<b>ACN:</b> <?=$store['Store']['abn'];?><br>
	<b>Website:</b> <?=$store['Store']['web'];?><br>
	<br>
</td>
</tr>

<tr>
<td colspan="2" style="font-size:14pt;" align="center">
	<b>Order Sheet</b>
	<br>
</td>
</tr>

<tr><td align='right' colspan='2'>
<table><tr><td>
	<b>Order Number:</b><br>
	<b>Date:</b>
</td>
<td style="color:#804040;font-weight:bold;padding:5px 0;text-align:left;">
	<?=$order['Order']['order_no'];?><br>
	<?=date('d/m/Y', strtotime($order['Order']['created']));?>
</td>
</tr></table>
</td></tr>

<tr style="font-weight:bold;background-color:#025932;color:#FFFFFF;">
<td style="height:25px;border:1px solid #cccccc;">
	<span style="font-size:10pt;"><b>Billing Details:</b></span>
</td>
<td style="height:25px;border:1px solid #cccccc;border-left: none;">
	<span style="font-size:10pt;"><b>Delivery Details:</b></span>
</td>
</tr>

<tr>
<td style="border-left:1px solid #cccccc;width:50%;vertical-align:top;padding:5px 0;">
<table cellpadding='0' cellspacing='0' style="width:100%;font-size:8pt;">
<tr>
<td style="text-align:left;padding:0px 5px;">
<b><?=$order['Billing']['firstname'] . "&nbsp;" . $order['Billing']['lastname'];?></b><br>
<?=$order['Billing']['address1'] . "&nbsp;" . $order['Billing']['address2'] . "<br>" .
   $order['Billing']['suburb'] . "&nbsp;" .$order['Billing']['state'] . "&nbsp;" .
   $order['Billing']['postcode'] . "<br>" . "Australia" . "<br>" .
   (!empty($order['Billing']['phone']) ? "Phone: " . $order['Billing']['phone'] . "<br>" : "") . 
   (!empty($order['Billing']['mobile']) ? "Mobile: " . $order['Billing']['mobile'] . "<br>" : "");
?>
</td>
</tr>
</table>
</td>

<td style="border-left:1px solid #cccccc;border-right:1px solid #cccccc;width:50%;vertical-align:top;padding:5px 0;">
<table cellpadding='0' cellspacing='0' style="width:100%;font-size:8pt;">
<tr>
<td style="text-align:left;padding:0px 5px;">
<b><?=$order['Shipping']['firstname'] . "&nbsp;" . $order['Shipping']['lastname'];?></b><br>
<?=$order['Shipping']['address1'] . "&nbsp;" . $order['Shipping']['address2'] . "<br>" .
   $order['Shipping']['suburb'] . "&nbsp;" .$order['Shipping']['state'] . "&nbsp;" . 
   $order['Shipping']['postcode'] . "<br>" . "Australia" . "<br>" .
   (!empty($order['Shipping']['phone']) ? "Phone: " . $order['Shipping']['phone'] . "<br>" : "") . 
   (!empty($order['Shipping']['mobile']) ? "Mobile: " . $order['Shipping']['mobile'] . "<br>" : "");
?>
</td>
</tr>
</table>
</td></tr>


<tr><td colspan='2'>
<table style="width:100%;border-collapse:collapse;" cellspacing='0' cellpadding='0'>
<tr style="font-weight:bold;background-color:#025932;color:#FFFFFF;">
<td colspan="2" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Item</td>
<td width="80px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Item No.</td>
<td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Price</td>
<?php 
	if ($order['Order']['business_code'] != BUSINESS_BRASA_DELIVERY) {
		$colspan = 6;
?>
<td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Delivery</td>
<?php } ?>
<td width="40px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Qty.</td>
<td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Subtotal</td>
</tr>
<?php
foreach ($order['Product'] as $key => $prod) {
		if (!empty($prod['Subproduct']) && count($prod['Subproduct']) > 0) {
			foreach ($prod['Subproduct'] as $subprod) {
				if ($subprod['id'] == $prod['OrdersProduct']['subproduct_id']) {
					$prod['name'] = $subprod['name'];
					break;
				}
			}
		}
		if (!empty($prod['OrdersProduct']['prod_desc'])) {
			$prod['name'] = $prod['OrdersProduct']['prod_desc'];
		}
        if (isset($prod['Image'][0])) {
			$img_src= SITE_URL . "/img/images/product/" . $prod['Image'][0]['id'] . "/" . $prod['Image'][0]['id'] . "a4" . $prod['Image'][0]['extension'];
		} else { 
			$img_src= SITE_URL . "/img/home/noimage_small.gif";
		}
        echo    "<tr>" . 
        		"<td width='40' style='border:1px solid #cccccc;border-right:none;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" .
        		"	<img src='" . $img_src . "' width='36' height='27' border='0'>" . 
        		"</td>".
                "<td style='border:1px solid #cccccc;border-left:none;height:25px;text-align:left;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" .
                	$prod['name'] .
                "</td>" .
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" . 
                	$prod['serial_no'] . 
                "</td>" .
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>$" . 
                	number_format($prod['OrdersProduct']['deal_price'], 2) . 
                "</td>";
		if ($order['Order']['business_code'] != BUSINESS_BRASA_DELIVERY) { 
			echo
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>$" . 
                	number_format($prod['OrdersProduct']['freight'], 2) .
                "</td>";
		}
		echo 
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" . 
                	$prod['OrdersProduct']['quantity'] . 
                "</td>".
                "<td style='border:1px solid #cccccc;height:25px;text-align:right;padding:0 1px;padding-right: 2px; word-wrap:break-word;overflow:hidden;'>$" .
                	number_format($prod['OrdersProduct']['subtotal'], 2) . 
                "</td>" .
                "</tr>";
}
?>
<tr><td colspan="<?=$colspan;?>" align="right" style='border-left:1px solid #cccccc;padding:0 1px;word-wrap:break-word;overflow:hidden;'><b>Item Total:</b></td>
<td align="right" height="25" style="border-bottom:1px solid #cccccc;border-right: 1px solid #cccccc;padding-right: 2px;"><b>$<?=number_format($order['Order']['subtotal'], 2);?></b></td></tr>
<tr><td colspan="<?=$colspan;?>" height="25" align="right" style='border-left:1px solid #cccccc;padding:0 1px;word-wrap:break-word;overflow:hidden;'><b>Delivery Fee:</b></td>
<td align="right" style="border-bottom:1px solid #cccccc;border-right: 1px solid #cccccc;padding-right: 2px;"><b>$<?=number_format($order['Order']['freight'], 2);?></b></td></tr>
<tr><td colspan="<?=$colspan;?>" height="25" align="right" style='border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;padding:0 1px;word-wrap:break-word;overflow:hidden;'><b>Total:</b></td>
<td align="right" style="border-bottom:1px solid #cccccc;border-right: 1px solid #cccccc;padding-right: 2px;"><b>$<?=number_format($order['Order']['total_amount'], 2);?></b></td></tr>
</table>
</td></tr>
</table></div>

<div class='thankyou_buttons'>
<div class='iframe_email'><iframe name='iframe_email'></iframe></div>
<form action='' name='form_email' method='POST'>
<textarea id='txt_order_sheet' class='email_content' name='data[content]'></textarea>
<input type='hidden' name='data[pid]' value='<?=@$order['Order']['id'];?>'>
<div class='btn_print' onclick="docPrint('email_content');">Print</div>
<div class='btn_pdf' onclick="runForm('/orders/ordersheet.pdf','email_content','txt_order_sheet','_blank');">
	 PDF Version
</div>
</form>
</div>

<?php 
}
?>