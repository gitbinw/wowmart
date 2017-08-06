<?php
header('Cache-Control: no-cache, must-revalidate');

$responseText = "<table id='table_list' ctrl='permissions' cellspacing='0' cellpadding='0'>".
								"<tr class='column'><td>Permission Name</td><td>Permission Description</td>" .
								"<td>Created Time</td><td></td></tr>";
if (isset($thisItem) && is_array($thisItem) && count($thisItem) > 0 ) {
	foreach ( $thisItem as $item ) {
		$responseText .= "<tr id='".$item['Permission']['id']."'><td>".
										$item['Permission']['name']."</td><td>".
			         			$item['Permission']['description']."</td><td>".
			         			$item['Permission']['created']."</td><td>".
			         			"<input type='checkbox' name='chk_items[]'></td></tr>";
	}
} else {
	$responseText .= "<tr><td colspan='4' class='norecord'>" . 
									 "No records so far!" . 
									 "</td></tr>";
}
$responseText .= "</table>";

echo $responseText;
?>