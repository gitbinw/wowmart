<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'Page Name', 'field'=>'PageDetail.name'),
		array('name'=>'Page Content', 'field'=>'PageDetail.content'),
		array('name'=>'Homepage', 'field'=>'PageDetail.is_home_page'),
		array('name'=>'Published', 'field'=>'PageDetail.is_shown'),
		array('name'=>'Top Menu', 'field'=>'PageDetail.is_menu'),
		array('name'=>'Footer Menu', 'field'=>'PageDetail.is_foot_menu'),
		array('name'=>'Priority', 'field'=>'PageDetail.priority')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'PageDetail.name', 'asc');
	
	$colspan = count($columns_config) + 1;
	
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<table id='table_list' ctrl='pages' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Pages');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['PageDetail']['page_id']."'>
				 	<td>".$item['PageDetail']['name']."</td>
					<td>". substr(htmlentities($item['PageDetail']['content']), 0, 100) . "</td>
					<td>" . ($item['PageDetail']['is_home_page'] == 1 ? 'Yes' : 'No') . "</td>
			        <td>" . ($item['PageDetail']['is_shown'] == 1 ? 'Yes' : 'No') . "</td>
			        <td>" . ($item['PageDetail']['is_menu'] == 1 ? 'Yes' : 'No') . "</td>
					<td>" . ($item['PageDetail']['is_foot_menu'] == 1 ? 'Yes' : 'No') . "</td>
					<td>" . $item['PageDetail']['priority'] . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
             ";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>