<?php
header('Cache-Control: no-cache, must-revalidate');

	$columns_config = array(
		array('name'=>'Reward Name', 'field'=>'Reward.rew_name'),
		array('name'=>'Reward Value', 'field'=>'Reward.rew_value'),
		array('name'=>'Expire Date', 'field'=>'Reward.rew_expiry'),
		array('name'=>'Turned On', 'field'=>'Reward.rew_on'),
		array('name'=>'System', 'field'=>'Reward.is_system'),
		array('name'=>'Reward Comments', 'field'=>'Reward.rew_comments'),
		array('name'=>'Created Time', 'field'=>'Reward.created')
	);
	$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'Reward.rew_name', 'asc');
	
	$colspan = count($columns_config) + 1;
	
	
	if(isset($arrNewItem)) {
		$strItem = serialize($arrNewItem);
		echo "<input type='hidden' id='new_node' value='$strItem'>";
	}
?>

<table id='table_list' ctrl='rewards' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Rewards');?>
    </td>
</tr>

<tr class='column'>
<?=$columns_string;?>
<td width="15"></td></tr>

<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	foreach ( $thisItem as $item ) {
        echo "
                 <tr id='".$item['Reward']['id']."'>
				 	<td>".$item['Reward']['rew_name']."</td>
					<td>$".number_format($item['Reward']['rew_value'], 2)."</td>
					<td>". (isset($item['Reward']['rew_expiry']) ? substr($item['Reward']['rew_expiry'], 0, 16) : '') ."</td>
					<td>". (isset($item['Reward']['rew_on']) && $item['Reward']['rew_on'] == 1 ? 'YES' : 'NO') ."</td>
					<td>". (isset($item['Reward']['is_system']) && $item['Reward']['is_system'] == 1 ? 'YES' : 'NO') ."</td>
					<td>". substr(htmlentities($item['Reward']['rew_comments']), 0, 100) . "</td>
					<td>" . date('d/m/Y H:i', strtotime($item['Reward']['created'])) . "</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
		";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>