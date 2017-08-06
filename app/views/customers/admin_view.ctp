<?php
	header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'First Name', 'field'=>'UserProfile.firstname'),
		array('name'=>'Last Name', 'field'=>'UserProfile.lastname'),
		array('name'=>'Email', 'field'=>'User.email'),
		array('name'=>'Group', 'field'=>'GroupDetail.name'),
		array('name'=>'Last Login', 'field'=>'User.login_time')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'UserProfile.firstname', 'asc');
	
	$colspan = count($columns_config) + 1;
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>
<table id='table_list' ctrl='customers' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Customers');?>
    </td>
</tr>

<tr class='column'><?=$columns_string;?><td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
		$groups = "";
		if (isset($item['Group']) && count($item['Group']) > 0) {
			foreach($item['Group'] as $key=>$grp) {
				if ($key == 0)$groups .= $grp['GroupDetail']['name'];
				else $groups .= ',' . $grp['GroupDetail']['name'];
			}
		}
		
        echo "
                 <tr id='".$item['User']['id']."'>
				 	<td>".$item['UserProfile']['firstname']."</td>
				 	<td>".$item['UserProfile']['lastname']."</td>
				 	<td>".$item['User']['email']."</td>
					<td>" . $groups . "</td>
					<td>".$item['User']['login_time']."</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>