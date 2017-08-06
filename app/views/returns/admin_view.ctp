<?php
header('Cache-Control: no-cache, must-revalidate');

$responseText = "<table id='table_list' ctrl='returns' cellspacing='0' cellpadding='0'>".
								"<tr class='column'><td>Order No.</td><td>Total Amount</td>" .
								"<td>Order Status</td><td>Customer</td><td>Updated Time</td><td></td></tr>";
if (isset($thisItem) && is_array($thisItem) && count($thisItem) > 0 ) {
	foreach ( $thisItem as $item ) {
		$responseText .= "<tr id='".$item['Order']['id']."'><td>".
										$item['Order']['order_no']."</td><td>$".
			         			$item['Order']['total_amount']."</td><td>".
			         			$item['Status']['name']."</td><td>".
			         			$item['User']['email']."</td><td>".
			         			$item['Order']['updated']."</td><td>".
			         			"<input type='checkbox' name='chk_items[]'></td></tr>";
	}
} else {
	$responseText .= "<tr><td colspan='6' class='norecord'>" . 
									 "No records so far!" . 
									 "</td></tr>";
}
$responseText .= "</table>";

echo $responseText;
?>