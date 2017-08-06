<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'Menu Name', 'field'=>'AdminMenu.name'),
		array('name'=>'Controller', 'field'=>'AdminMenu.ctrl'),
		array('name'=>'Params', 'field'=>'AdminMenu.params'),
		array('name'=>'Show Children', 'field'=>'AdminMenu.showChildren'),
		array('name'=>'Priority', 'field'=>'AdminMenu.priority')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'AdminMenu.name', 'asc');

	$colspan = count($columns_config) + 1;
	
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<table id='table_list' ctrl='console' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Menus');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['AdminMenu']['id']."'>
				 	<td>".$item['AdminMenu']['name']."</td>
					<td>".$item['AdminMenu']['ctrl']."</td>
					<td>".$item['AdminMenu']['params']."</td>
					<td>".($item['AdminMenu']['showChildren']==1 ? 'Yes' : 'No'). "</td>
					<td>".$item['AdminMenu']['priority']."</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>