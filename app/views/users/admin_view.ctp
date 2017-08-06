<?php
header('Cache-Control: no-cache, must-revalidate');

$responseText = "<table id='table_list' ctrl='users' cellspacing='0' cellpadding='0'>".
								"<tr class='column'><td>First Name</td><td>Last Name</td><td>Email</td>" .
								"<td>Group</td><td>Last Login</td><td></td></tr>";
if (isset($thisItem) && is_array($thisItem) && count($thisItem) > 0 ) {
	foreach ( $thisItem as $item ) {
		$groups = "";
		if (isset($item['Group']) && count($item['Group']) > 0) {
			foreach($item['Group'] as $key=>$grp) {
				if ($key == 0)$groups .= $grp['GroupDetail']['name'];
				else $groups .= ',' . $grp['GroupDetail']['name'];
			}
		}
		$responseText .= "<tr id='".$item['User']['id']."'><td>".
										$item['UserProfile']['firstname']."</td><td>".
			         			$item['UserProfile']['lastname']."</td><td>".
			         			$item['User']['email']."</td><td>".
			         			$groups."</td><td>".
			         			$item['User']['login_time']."</td><td>".
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