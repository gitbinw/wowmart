<?php 
	header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'NO. #', 'field'=>'Product.serial_no', 'width'=>60),
		array('name'=>'Product Name', 'field'=>'Product.name'),
		//array('name'=>'Supplier', 'field'=>'Supplier.biz_name', 'width'=>100),
		array('name'=>'Stock', 'field'=>'Product.stock', 'width'=>30),
		array('name'=>'Price', 'field'=>'Product.price'),
		array('name'=>'Now Price', 'field'=>'Product.deal_price'),
		array('name'=>'Category', 'field'=>'Category.id', 'width'=>100),
		array('name'=>'Shipping', 'field'=>'LogisticsCompany.logi_company', 'width'=>100),
		array('name'=>'Homepage', 'field'=>'Product.on_homepage', 'width'=>80),
		array('name'=>'Created', 'field'=>'Product.created', 'width'=>65)
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'Product.created', 'desc');
	
	$colspan = count($columns_config) + 1;
?>

<table id='table_list' ctrl='categories' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Products');?>
    </td>
</tr>

<tr class="column">
<?=$columns_string;?>
<td width="15"></td></tr>
<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
		$cats = "";
		if (isset($item['Category']) && count($item['Category']) > 0) {
			foreach($item['Category'] as $key=>$cat) {
				if ($key == 0)$cats .= $cat['CategoryDetail']['name'];
				else $cats .= ',' . $cat['CategoryDetail']['name'];
			}
		}
        echo "
                 <tr id='".$item['Product']['id']."'>
					<td>".$item['Product']['serial_no']."</td>
					<td>".$item['Product']['name']."</td>
                    <!--<td>".$item['Supplier']['biz_name']."</td>-->
                    <td>".$item['Product']['stock']."</td>
                    <td>".(!empty($item['Product']['price']) ? '$' .$item['Product']['price'] : '') ."</td>
                    <td>".(!empty($item['Product']['deal_price']) ? '$' .$item['Product']['deal_price'] : '')."</td>
                    <td>".$cats."</td>
                    <td>".$item['LogisticsCompany']['logi_company']."</td>
                    <td>".($item['Product']['on_homepage'] == 1 ? "Yes" : "No")."</td>
                    <td>".date('d/m/Y', strtotime($item['Product']['created']))."</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
             ";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>