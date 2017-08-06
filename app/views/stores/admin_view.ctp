<?php
header('Cache-Control: no-cache, must-revalidate');

$responseText = "<table id='table_list' ctrl='stores' cellspacing='0' cellpadding='0'>".
								"<tr class='column'><td>Store Name</td><td>Identifier</td>" . 
								"<td>First Name</td>" .
								"<td>Last Name</td><td>Phone</td><td></td></tr>";
if (isset($thisItem) && is_array($thisItem) && count($thisItem) > 0 ) {
	foreach ( $thisItem as $item ) {
		$responseText .= "<tr id='".$item['Store']['id']."'><td>".
										$item['Store']['company']."</td><td>".
										$item['Store']['alias']."</td><td>".
			         			$item['Store']['firstname']."</td><td>".
								$item['Store']['lastname']."</td><td>".
								$item['Store']['phone']."</td><td>".
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