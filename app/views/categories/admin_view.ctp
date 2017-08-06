<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'Category Name', 'field'=>'CategoryDetail.name'),
		array('name'=>'Description', 'field'=>'CategoryDetail.comment'),
		array('name'=>'Discount', 'field'=>'CategoryDetail.discount_id'),
		array('name'=>'Reward', 'field'=>'CategoryDetail.reward_id'),
		array('name'=>'Voucher', 'field'=>'CategoryDetail.voucher_id'),
		array('name'=>'Status', 'field'=>'CategoryDetail.category_status'),
		array('name'=>'In Homepage', 'field'=>'CategoryDetail.on_home'),
		array('name'=>'Created Time', 'field'=>'CategoryDetail.created')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'CategoryDetail.name', 'asc');

	$colspan = count($columns_config) + 1;
	
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<table id='table_list' ctrl='categories' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Categories');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
		$status = $class = '';
		if (isset($item['CategoryDetail']['category_status']) && 
				!empty($item['CategoryDetail']['category_status']) ) {
			$arrStatus = $CATEGORY_PRODUCT_STATUSES[$item['CategoryDetail']['category_status']];
			$status = $arrStatus['name'];
			$class = strtolower($item['CategoryDetail']['category_status']);
		}
        echo "
                 <tr id='".$item['CategoryDetail']['category_id']."'>
				 	<td>".$item['CategoryDetail']['name']."</td>
				 	<td>".substr($item['CategoryDetail']['comment'], 0, 100)." ...</td>
				 	<td>".$item['CategoryDetail']['discount_id']."</td>
				 	<td>".$item['CategoryDetail']['reward_id']."</td>
				 	<td>".$item['CategoryDetail']['voucher_id']."</td>
					<td class='" . $class . "'>".$status."</td>
					<td>" . ($item['CategoryDetail']['on_home'] == 1 ? 'Yes' : 'No') . "</td>
					<td>" . date('d/m/Y H:i', strtotime($item['CategoryDetail']['created'])) . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>