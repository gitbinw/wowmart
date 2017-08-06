<?php header('Cache-Control: no-cache, must-revalidate');?>

<div style="padding:5px;height:20px;">
<form id="form_status">
<input type='hidden' id='order_id' name='data[Order][id]' value='<?=$order['Order']['id'];?>' />
<div style="float:left;width:120px;line-height:20px;">
	<b>Status:&nbsp;<span style='color:#804040;' id='txt_status_value'>
	<?=$order['Status']['name'];?>
</span></b></div>

<?php if ($order['Status']['id'] != TYPE_ORDER_NOT_PAID) { ?>
<div style="float:left;width:90px;line-height:20px;">
	<b>Change Status:&nbsp;</b>
</div>
<div style="float:left;width:110px;">
	<select id="order_status" name="data[Status][id]">
		<option value='0'></option>
		<?php foreach ($statuses as $key => $status) { ?>
		<option value='<?=$key;?>' <?=$key==$order['Status']['id'] ? 'selected' : '';?>><?=$status;?></option>
		<?php } ?>
	</select>
</div>

<div style="float:left;width:20px;height:20px;">
	<img src='/img/icons/loading.gif' id='img_loading' style='display:none;' />
</div>

<div style="float:left;padding-left:20px;">
	<b>Invoice Number:</b>
	<span id="txt_invoice_no" style='color:#804040;font-weight:bold;'>
		<a onclick="call('/admin/invoices/get/<?=$order['Invoice']['id'];?>', 2);" href="#">
			<?=$order['Invoice']['invoice_no'] ? $order['Invoice']['invoice_no'] : 'Not generated yet.';?>
		</a>
	</span>
</div>
<?php } ?>

</form>
</div>

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
	<b>ORDER SHEET</b>
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
<td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Delivery</td>
<td width="40px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Qty.</td>
<td width="60px" style="border:1px solid #cccccc;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;">Subtotal</td>
</tr>
<?php
foreach ($order['Product'] as $key => $prod) {
	if (!empty($prod['Subproduct']) && count($prod['Subproduct']) > 0) {
			foreach ($prod['Subproduct'] as $subprod) {
				if ($subprod['id'] == $prod['OrdersProduct']['subproduct_id']) {
					$prod['name'] = $prod['name'] . " " . $subprod['name'];
					break;
				}
			}
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
