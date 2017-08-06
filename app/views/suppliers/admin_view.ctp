<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'#', 'field'=>'Supplier.identifier', 'width'=>30),
		array('name'=>'Subdomain', 'field'=>'Supplier.subdomain', 'width'=>60),
		array('name'=>'Business Name', 'field'=>'Supplier.biz_name'),
		array('name'=>'Email', 'field'=>'User.email', 'width'=>100),
		array('name'=>'Group', 'width'=>30),
		array('name'=>'Phone', 'field'=>'Supplier.phone', 'width'=>80),
		array('name'=>'Status', 'field'=>'User.active', 'width'=>60),
		array('name'=>'Created', 'field'=>'User.created', 'width'=>65)
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'User.created', 'desc');
	
	$colspan = count($columns_config) + 1;
?>

<table id='table_list' ctrl='suppliers' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Suppliers');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

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
				 	<td>".$item['Supplier']['identifier']."</td>
					<td>".$item['Supplier']['subdomain']."</td>
					<td>".$item['Supplier']['biz_name']."</td>
                    <td>".$item['User']['email']."</td>
                    <td>".$groups."</td>
                    <td>".$item['Supplier']['phone']."</td>
                    <td>".($item['User']['active'] == 1 ? "Verified" : "Unverified")."</td>
                    <td>".date('d/m/Y', strtotime($item['User']['created']))."</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
             ";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>