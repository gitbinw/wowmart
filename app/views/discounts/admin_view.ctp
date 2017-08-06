<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'Discount Name', 'field'=>'Discount.dis_name'),
		array('name'=>'Discount Percentage', 'field'=>'Discount.dis_percent'),
		array('name'=>'Expire Date', 'field'=>'Discount.dis_expiry'),
		array('name'=>'Turned On', 'field'=>'Discount.dis_on'),
		array('name'=>'System', 'field'=>'Discount.is_system'),
		array('name'=>'Discount Comments', 'field'=>'Discount.dis_comments'),
		array('name'=>'Created Time', 'field'=>'Discount.created')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'Discount.dis_name', 'asc');

	$colspan = count($columns_config) + 1;
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<table id='table_list' ctrl='discounts' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Discounts');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['Discount']['id']."'>
				 	<td>".$item['Discount']['dis_name']."</td>
					<td>$".number_format($item['Discount']['dis_percent'], 2)."</td>
					<td>". (isset($item['Discount']['dis_expiry']) ? substr($item['Discount']['dis_expiry'], 0, 16) : '') ."</td>
					<td>". (isset($item['Discount']['dis_on']) && $item['Discount']['dis_on'] == 1 ? 'YES' : 'NO') ."</td>
					<td>". (isset($item['Discount']['is_system']) && $item['Discount']['is_system'] == 1 ? 'YES' : 'NO') ."</td>
					<td>". substr(htmlentities($item['Discount']['dis_comments']), 0, 100) . "</td>
					<td>" . date('d/m/Y H:i', strtotime($item['Discount']['created'])) . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>