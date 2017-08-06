<?php
header('Cache-Control: no-cache, must-revalidate');
$responseText = "<table id='table_list' ctrl='orders' cellspacing='0' cellpadding='0'>".
								"<tr class='column'><td>Invoice No.</td><td>Order No.</td>" .
								"<td>Total Amount</td><td>Created Time</td><td></td></tr>";
if (isset($thisItem) && is_array($thisItem) && count($thisItem) > 0 ) {
	foreach ( $thisItem as $item ) {
		$responseText .= "<tr id='".$item['Invoice']['id']."'><td>".
										$item['Invoice']['invoice_no']."</td><td>".
			         			(isset($item['Order'][0]['order_no']) ? 
			         			 $item['Order'][0]['order_no'] : '') ."</td><td>".
			         			(isset($item['Order'][0]['total_amount']) ? 
			         			 "$" . $item['Order'][0]['total_amount'] : '') . "</td><td>" .
			         			$item['Invoice']['created']."</td><td>".
			         			"<input type='checkbox' name='chk_items[]'></td></tr>";
	}
} else {
	$responseText .= "<tr><td colspan='5' class='norecord'>" . 
									 "No records so far!" . 
									 "</td></tr>";
}
$responseText .= "</table>";

echo $responseText;
?>
