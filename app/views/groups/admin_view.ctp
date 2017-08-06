<?php
header('Cache-Control: no-cache, must-revalidate');

if(isset($arrNewItem)) {
	$strItem = serialize($arrNewItem);
	echo "<input type='hidden' id='new_node' value='$strItem'>";
}
$thisCatId = $arrClient [ 'Group' ][ 'id' ];
$thisCatName = $arrClient [ 'GroupDetail' ][ 'name' ]; 
$responseText = "<table id='table_list' ctrl='groups' cellspacing='0' cellpadding='0'>".
				"<tr class='column'><td>Group Name</td><td>Group Comments</td><td>Created Time</td><td></td></tr>";
if ( isset ( $arrGroup ) && is_array ( $arrGroup ) && count ( $arrGroup ) > 0 ) {
	foreach ( $arrGroup as $cat ) {
		$responseText .= "<tr id='".$cat['Group']['id']."'><td>".$cat['GroupDetail']['name']."</td><td>".
			         		$cat['GroupDetail']['comment']."</td><td>".$cat['GroupDetail']['created'].
			         	 "</td><td><input type='checkbox' name='chk_items[]'></td></tr>";
	}
} else {
	$responseText .= "<tr><td colspan='4' class='norecord'>No Sub-groups under the Group of '$thisCatName' so far!</td></tr>";
}
$responseText .= "</table>";

echo $responseText;
?>