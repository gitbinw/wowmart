<?php
header('Cache-Control: no-cache, must-revalidate');

$currLimit = "";
$media_counts = $paginator->counter(array('format' => '%count%'));
$page_counts = $paginator->counter(array('format' => '%pages%'));

if (isset($thisItem) && is_array($thisItem)) {
	$currPage = isset($paginator->options['url']['page']) ? $paginator->options['url']['page'] : 1;
	$currSort = isset($paginator->options['url']['sort']) ? $paginator->options['url']['sort'] : 'Media.created';
	$currDir  = isset($paginator->options['url']['direction']) ? $paginator->options['url']['direction'] : 'desc';
	$currDir = $currDir == 'desc' ? 'asc' : 'desc';
	$currSortDir = $currSort . ':' . $currDir;
	$sortUrl  = SITE_URL_ROOT . '/admin/medias/list/page:' . $currPage;
	
	if (isset($paginator->options['url']['limit'])) {
		$currLimit = '/limit:' . $paginator->options['url']['limit'];
	}
}

$columns_string = '';
$columns_config = array(
	array('name'=>'Name', 'field'=>'Media.media_name'),
	array('name'=>'File Name', 'field'=>'Media.file_name'),
	array('name'=>'Folder', 'field'=>'Media.dir'),
	array('name'=>'File Size', 'field'=>'Media.file_size'),
//	array('name'=>'External URL', 'field'=>'Media.external_url', 'width'=>150),
	array('name'=>'Created', 'field'=>'Media.created', 'width'=>75)
);
foreach($columns_config as $col) {
	$class = $width = '';
	if (isset($col['width'])) $width = 'width="' . $col['width'] . '"';
	$columns_string .= '<td ' . $width . '>';
	if (isset($col['field'])) {
		if ($currSort == $col['field']) $class = 'class="' . $currDir . '"';
		$columns_string .= '<a href="' . $sortUrl . '/sort:' . $col['field'] . 
						   '/direction:' . $currDir . $currLimit . '" ' . $class . '>' . 
						  $col['name'] . '</a>';
	} else {
		$columns_string .= $col['name'];
	}
	$columns_string .= '</td>';
}
$colspan = count($columns_config) + 1;
?>

<?=$html->css('cms');?>

<table id='table_list' ctrl='medias' cellspacing='0' cellpadding='0'>
<tr class='pagination'>
	<td colspan='<?=$colspan;?>' class='pagination'>
    	<div class="page_info">Total <span><?=$media_counts;?></span> Medias</div>
        <div class="page_bar">
			<ul>
				<li class="page_title">
					Pages:
				</li>
				<li class="page_prev" alt="Previous Page" title="Previous Page">
					<?=$paginator->prev('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
				</li>
					<?=$paginator->numbers(array('tag' => 'li', 'separator' => '', 'url'=>array('action'=>'list')));?>
				<li class="page_next" alt="Next Page" title="Next Page">
					<?=$paginator->next('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
				</li>
			</ul>
		</div>
        
        <div id="search_box" style="float:right;">
        	<form action="<?=$sortUrl . '/sort:' . $col['field'] . 
					'/direction:' . $currDir . $currLimit;?>" method="POST">
        		<input id="keywords" type="text" name="keywords" 
                	value="<?= isset($_POST['keywords']) ? $_POST['keywords'] : '';?>">
        		<input id="btn_search" type="submit" value="Search" name="btn_search">
            </form>
        </div>
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

<?=$javascript->link('jquery/jquery-1.11.0.min.js');?>
<script>
$(document).ready(function(e) {
	var imgPathRoot = '<?php echo IMAGE_URL_ROOT; ?>';
	$('#table_list tr[class!=column][class!=pagination]').unbind('click').click(function(e) {
		var $this = $(this),
			mediaId = this.id ? this.id : '',
			$td = $this.children('td:nth-child(2)'),
			$dir = $this.children('td:nth-child(3)'),
			imgSrc = $.trim($td.text()),
			dir = $.trim($dir.text()),
			strPath = dir.replace(/\\/gi, "\/");

		imgSrc = imgPathRoot + strPath + '/' + imgSrc;
		
		if (opener && opener.window.setSelectedMedia) {
			window.close();
			opener.window.setSelectedMedia(imgSrc, mediaId);
		}
	});
});
</script>