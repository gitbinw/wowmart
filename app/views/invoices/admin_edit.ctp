<?php header('Cache-Control: no-cache, must-revalidate');?>

<div id='email_content' style='padding: 10px 10px;'>
<table style="text-align:center;width:100%;background-color:#ffffff;" cellspacing='0' cellpadding='0'>
<tr>
<td align='left' style='padding-left:10px;'>
	<img src="/img/home/logo.gif" />
</td>
<td style="font-size:10pt;" align="right">
	<span style="font-size:12pt;"><b><?=$store['Store']['company'];?></b><br></span>
	<?=$store['Store']['address'];?><br>
	<?=$store['Store']['suburb'];?>&nbsp;
	<?=$store['Store']['state'];?>&nbsp;
	<?=$store['Store']['postcode'];?><br>
	<b>ABN:</b> <?=$store['Store']['abn'];?><br>
	<b>TEL:</b> <?=$store['Store']['phone'];?><br>
	<b>FAX:</b> <?=$store['Store']['fax'];?><br>
	<b>WEBSITE:</b> <?=$store['Store']['web'];?><br>
	<br>
</td>
</tr>

<tr>
<td colspan="2" style="font-size:14pt;" align="center">
	<b>TAX INVOICE</b>
	<br>
</td>
</tr>

<tr>
<td align='right' colspan='2'>
<table border="0">
<tr>
<td>
	<input type='hidden' id='order_id' name='data[Invoice][id]' value='<?=$order['Invoice']['id'];?>' />
	<b>Invoice Number:</b><br>
	<b>Date:</b>
</td>
<td style="color:#804040;font-weight:bold;padding:5px 0;text-align:left;">
	<?=$order['Invoice']['invoice_no'];?><br>
	<?=date('d/m/Y', strtotime($order['Invoice']['created']));?>
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
<td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Delivery</td>
<td width="40px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Qty.</td>
<td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Subtotal</td>
</tr>
<?php
foreach ($order['Product'] as $key => $prod) {
        if (isset($prod['Image'][0])) {
			$img_src= SITE_URL . "/img/images/product/" . $prod['Image'][0]['id'] . "/" . $prod['Image'][0]['id'] . "a4" . $prod['Image'][0]['extension'];
		} else { 
			$img_src= SITE_URL . "/img/home/noimage_small.gif";
		}
        echo    "<tr>" . 
        		"<td width='40' style='border:1px solid #cccccc;border-right:none;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" .
        		"	<img src='" . $img_src . "' width='36' height='27' border='0'>" . 
        		"</td>".
                "<td style='border:1px solid #cccccc;border-left:none;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" .
                	$prod['name'] .
                "</td>" .
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" . 
                	$prod['serial_no'] . 
                "</td>" .
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>$" . 
                	number_format($prod['OrdersProduct']['deal_price'], 2) . 
                "</td>" .
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>$" . 
                	number_format($prod['OrdersProduct']['freight'], 2) .
                "</td>".
                "<td style='border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;'>" . 
                	$prod['OrdersProduct']['quantity'] . 
                "</td>".
                "<td style='border:1px solid #cccccc;height:25px;text-align:right;padding:0 1px;padding-right: 2px; word-wrap:break-word;overflow:hidden;'>$" .
                	number_format($prod['OrdersProduct']['subtotal'], 2) . 
                "</td>" .
                "</tr>";
}
?>
<tr><td colspan="6" align="right" style='border-left:1px solid #cccccc;padding:0 1px;word-wrap:break-word;overflow:hidden;'><b>Item Total:</b></td>
<td align="right" height="25" style="border-bottom:1px solid #cccccc;border-right: 1px solid #cccccc;padding-right: 2px;"><b>$<?=number_format($order['Order']['subtotal'], 2);?></b></td></tr>
<tr><td colspan="6" height="25" align="right" style='border-left:1px solid #cccccc;padding:0 1px;word-wrap:break-word;overflow:hidden;'><b>Delivery Fee:</b></td>
<td align="right" style="border-bottom:1px solid #cccccc;border-right: 1px solid #cccccc;padding-right: 2px;"><b>$<?=number_format($order['Order']['freight'], 2);?></b></td></tr>
<tr><td colspan="6" height="25" align="right" style='border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;padding:0 1px;word-wrap:break-word;overflow:hidden;'><b>Total:</b></td>
<td align="right" style="border-bottom:1px solid #cccccc;border-right: 1px solid #cccccc;padding-right: 2px;"><b>$<?=number_format($order['Order']['total_amount'], 2);?></b></td></tr>
</table>
</td></tr>
</table></div>

<script language='javascript' type='text/javascript'>
$("#order_status").change(function() {
	var txtStatus = $("#order_status option:selected").text();
	var opts = {
		type : 'POST',
		url : '/admin/orders/setstatus',
		data : $("#form_status").serialize(),
		dataType : 'json',
		beforeSend : function() {$("#img_loading").show();},
		success : function(data) {
			$("#img_loading").hide();
			if (data.success == true) {
				$("#txt_status_value").text(txtStatus);
				if (data.invoice != null) {
					$("#txt_invoice_no").text(data.invoice);
				}
			}
		}
	};
	if ($("#order_status").val() > 0) {
		$.ajax(opts);
	}
});
</script>
