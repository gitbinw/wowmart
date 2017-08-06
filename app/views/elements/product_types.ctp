<?php 
	$types = $this->requestAction('/admin/products/types');
	$selTypes = array();
	if (isset($thisItem['Type']) && count($thisItem['Type']) > 0) {
		foreach($thisItem['Type'] as $selType) {
			if (isset($selType['id'])) $selTypes[] = $selType['id'];
		}
	}
?>

<input type='hidden' name="data[Type][]" ><!--unchecked all options-->
<ul>
<?php foreach ($types as $key=>$type): ?>
	<li><input type='checkbox' name='data[Type][]' value='<?=$key;?>'
			<?=in_array($key, $selTypes) ? 'CHECKED' : '';?> />
			<?=$type;?>
	</li>
<?php endforeach; ?>
</ul>