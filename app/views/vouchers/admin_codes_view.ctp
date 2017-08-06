<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'Voucher Code', 'field'=>'VoucherCode.vou_code'),
		array('name'=>'Voucher Comments', 'field'=>'VoucherCode.vou_comments'),
		array('name'=>'Created Time', 'field'=>'VoucherCode.created')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'VoucherCode.vou_code', 'asc');

	$colspan = count($columns_config) + 1;
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<input type="hidden" id="action_button_status" value="disable_new" />

<table id='table_list' ctrl='vouchers' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Codes');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['VoucherCode']['id']."'>
				 	<td>".$item['VoucherCode']['vou_code']."</td>
					<td>". substr(htmlentities($item['VoucherCode']['vou_comments']), 0, 100) . "</td>
					<td>" . date('d/m/Y H:i', strtotime($item['VoucherCode']['created'])) . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>