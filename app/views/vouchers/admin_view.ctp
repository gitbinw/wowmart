<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'Voucher Name', 'field'=>'Voucher.vou_name'),
		array('name'=>'Voucher Value', 'field'=>'Voucher.vou_value'),
		array('name'=>'Voucher Number', 'field'=>'Voucher.vou_number'),
		array('name'=>'Expire Date', 'field'=>'Voucher.vou_expiry'),
		array('name'=>'Turned On', 'field'=>'Voucher.vou_on'),
		array('name'=>'System', 'field'=>'Voucher.is_system'),
		array('name'=>'Voucher Comments', 'field'=>'Voucher.vou_comments'),
		array('name'=>'Created Time', 'field'=>'Voucher.created')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'Voucher.vou_name', 'asc');

	$colspan = count($columns_config) + 1;
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<table id='table_list' ctrl='vouchers' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Vouchers');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['Voucher']['id']."'>
				 	<td>".$item['Voucher']['vou_name']."</td>
					<td>$".number_format($item['Voucher']['vou_value'], 2)."</td>
					<td>".$item['Voucher']['vou_number']."</td>
					<td>". (isset($item['Voucher']['vou_expiry']) ? substr($item['Voucher']['vou_expiry'], 0, 16) : '') ."</td>
					<td>". (isset($item['Voucher']['vou_on']) && $item['Voucher']['vou_on'] == 1 ? 'YES' : 'NO') ."</td>
					<td>". (isset($item['Voucher']['is_system']) && $item['Voucher']['is_system'] == 1 ? 'YES' : 'NO') ."</td>
					<td>". substr(htmlentities($item['Voucher']['vou_comments']), 0, 100) . "</td>
					<td>" . date('d/m/Y H:i', strtotime($item['Voucher']['created'])) . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>