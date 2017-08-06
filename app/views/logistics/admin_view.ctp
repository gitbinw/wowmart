<?php
header('Cache-Control: no-cache, must-revalidate');
	
	$columns_config = array(
		array('name'=>'Company', 'field'=>'LogisticsCompany.logi_company'),
		array('name'=>'Type', 'field'=>'LogisticsCompany.logi_type'),
		array('name'=>'Unit', 'field'=>'LogisticsCompany.logi_unit'),
		array('name'=>'GST', 'field'=>'LogisticsCompany.logi_gst'),
		array('name'=>'Fuel Fee', 'field'=>'LogisticsCompany.logi_fuel'),
		array('name'=>'Created Time', 'field'=>'LogisticsCompany.created')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'LogisticsCompany.logi_company', 'asc');
	
	$colspan = count($columns_config) + 1;
	
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<table id='table_list' ctrl='Logistics' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Companies');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['LogisticsCompany']['id']."'>
				 	<td>".$item['LogisticsCompany']['logi_company']."</td>
				 	<td>".$item['LogisticsCompany']['logi_type']."</td>
				 	<td>".$item['LogisticsCompany']['logi_unit']."</td>
					<td>".number_format($item['LogisticsCompany']['logi_gst'], 2)."%</td>
					<td>".number_format($item['LogisticsCompany']['logi_fuel'], 2)."%</td>
					<td>" . date('d/m/Y H:i', strtotime($item['LogisticsCompany']['created'])) . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>