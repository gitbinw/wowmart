<?php
header('Cache-Control: no-cache, must-revalidate');

$columns_config = array(
	array('name'=>'Name', 'field'=>'Media.media_name'),
	array('name'=>'File Name', 'field'=>'Media.file_name'),
	array('name'=>'Folder', 'field'=>'Media.dir'),
	array('name'=>'File Size', 'field'=>'Media.file_size'),
//	array('name'=>'External URL', 'field'=>'Media.external_url', 'width'=>150),
	array('name'=>'Created', 'field'=>'Media.created', 'width'=>75)
);
$columns_string = $utility->getPaginationSort($paginator, $columns_config, $thisItem, 'Media.created', 'desc');

$colspan = count($columns_config) + 1;
?>

<table id='table_list' ctrl='medias' cellspacing='0' cellpadding='0'>
<tr>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<?=$utility->getPaginationBar($paginator, $parentUrlParams, 'Medias');?>
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
                 <tr id='".$item['Media']['id']."'>
				 	<td>".$item['Media']['media_name']."</td>
				 	<td>".$item['Media']['file_name']."</td>
					<td>".$item['Media']['dir']."</td>
					<td>".$item['Media']['file_size']."K</td>
				" . //	<td>".$item['Media']['external_url']."</td>
                "    <td>".date('d/m/Y', strtotime($item['Media']['created']))."</td>
                    <td><input type='checkbox' name='chk_items[]'></td>
                  </tr>
             ";
	}

} else {
        echo "<tr><td colspan='$colspan' class='norecord'>No records so far!</td></tr>";
}
?>
</table>