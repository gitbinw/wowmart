<?php
header('Cache-Control: no-cache, must-revalidate');

	$logiCompany = isset($parentItem['LogisticsCompany']['logi_company']) ? 
	$parentItem['LogisticsCompany']['logi_company'] : '';
	
	$columns_config = array(
		array('name'=>'Company', 'field'=>'LogisticsPrice.logi_company'),
		array('name'=>'Post Code', 'field'=>'LogisticsPrice.logi_postcode'),
		array('name'=>'Basic Price', 'field'=>'LogisticsPrice.logi_basic_price'),
		array('name'=>'Unit Price', 'field'=>'LogisticsPrice.logi_price'),
		array('name'=>'Zone', 'field'=>'LogisticsPrice.logi_zone'),
		array('name'=>'State', 'field'=>'LogisticsPrice.logi_state'),
		array('name'=>'Created Time', 'field'=>'LogisticsPrice.created')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'LogisticsPrice.logi_company', 'asc');
	
	$colspan = count($columns_config) + 1;
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>
<table id='table_list' ctrl='Logistics' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' align="center" class="reg_form_title"><?=$logiCompany;?> Shipping Prices</td>
</tr>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Prices');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['LogisticsPrice']['id']."'>
				 	<td>".$item['LogisticsPrice']['logi_company']."</td>
				 	<td>".$item['LogisticsPrice']['logi_postcode']."</td>
					<td>$".number_format($item['LogisticsPrice']['logi_basic_price'], 2)."</td>
					<td>$".number_format($item['LogisticsPrice']['logi_price'], 2)."</td>
					<td>".$item['LogisticsPrice']['logi_zone']."</td>
					<td>".$item['LogisticsPrice']['logi_state']."</td>
					<td>" . date('d/m/Y H:i', strtotime($item['LogisticsPrice']['created'])) . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>