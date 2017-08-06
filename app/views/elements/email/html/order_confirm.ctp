<?php
$colspan = 4;
$str_body = 'Your order items will be delivered by Australia Post within 10 working days from date of purchase. You will be asked to sign for the item on receipt, and if you are not home, Australia Post will leave you a card asking you to pick up the parcel from your local post office.<br><br>
								If you do not receive your order within these times, please contact us by sending an email to <a href="mailto:sales@freshla.com.au">sales@freshla.com.au</a>. Please quote your email address registered in Freshla and order number when contacting Freshla.';

if ($order['Order']['business_code'] == BUSINESS_BRASA_DELIVERY) {
	$str_body = 'Your order will be deliveried within 60 minutes only. If you do not receive your order, please contact us by phone 0421 491 287, or by sending an email to <a href="mailto:sales@freshla.com.au">sales@freshla.com.au</a>. Please quote your email address registered in Freshla and order number when contacting Freshla.';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<title>Freshla - Payment Confirmation Email</title>
<body>

<table width="600px" cellpadding="0" cellspacing="0" border="0" bgcolor="#fcfcfa" style="font-family:Arial,Verdana,Helvetica,sans-serif">
	<tr><td width="300px" height="110px" style="border-bottom:4px solid #000000;"><a href="http://www.freshla.com.au" target="_blank">
		<img src="http://www.freshla.com.au/img/email/logo.gif" width="300" height="110" border="0" />
	</a></td>
	</tr>
	<tr><td height="20px"></td></tr>
	<tr><td>
		<table width="560px" cellpadding="0" cellspacing="0" border="0" style="margin:0 20px;">
			<tr>
				<td style="color:#5E703B;font-weight:bold;font-size:24px;">
					Payment Confirmation from Freshla!
				</td>
			</tr>
			<tr><td height="20px"></td></tr>
			<tr><td>
				<table width="100%" cellspacing="10" cellpadding="0" border="0" style="font-size:14px;">
					<tr>
						<td style="padding:10px;">
							<p>Dear <?=!empty($order['User']['UserProfile']['firstname']) ? 
													$order['User']['UserProfile']['firstname'] : 'Customer';?>,</p>
							<p>
                            	Thank you for your payment from Paypal. Your order number for this  purchase is <strong><?=$order['Order']['order_no'];?></strong>. <br />
                                To view or print your receipt, please <a href="http://www.freshla.com.au/account">click here</a> to login to My Account on Freshla.
							</p>
							<p>
                            	Here is your order item list:<br />
                            <table style="width:100%;border-collapse:collapse;" cellspacing='0' cellpadding='0'>
                            <tr style="font-weight:bold;background-color:#025932;color:#FFFFFF;">
                            <td style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Item</td>
                            <td width="80px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Item No.</td>
                            <td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Price</td>
                         <?php 
						 	if ($order['Order']['business_code'] != BUSINESS_BRASA_DELIVERY) {
								$colspan = 5;
						 ?>
                            <td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Delivery</td>
                        <?php
							}
						?>
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
                                        echo    "<tr>" .
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
                                        echo "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>$" . 
                                                number_format($prod['OrdersProduct']['freight'], 2) .
                                            "</td>";
									}
                                     echo "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" . 
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
							</p>
							<p>
								<?=$str_body;?>
							</p>
							<p>
								Again, thanks for your purchase! Love food, Love Freshla.
							</p>
							<p>
								Cheers,<br>
								<a href="mailto:sales@freshla.com.au">The Freshla Team</a>
							</p>
						</td>
					</tr>
				</table>
			</td></tr>
		</table>
	</td><tr>
</table>

</body>
</html>