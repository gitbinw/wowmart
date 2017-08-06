<?php 
	$permissions = $this->requestAction('admin/permissions/get');
	$selPerms = array();
	if (isset($thisItem['Permission']) && count($thisItem['Permission']) > 0) {
		foreach($thisItem['Permission'] as $selPerm) {
			if (isset($selPerm['id'])) $selPerms[] = $selPerm['id'];
		}
	}
?>
<div class="multi_select">
	<input type='hidden' name="data[Permission][]" ><!--unchecked all options-->
	<ul>
		<?php foreach ($permissions as $key => $perm): ?>
		<li>
			<input type='checkbox' name="data[Permission][]" 
							value='<?=$perm['Permission']['id'];?>' 
							<?=in_array($perm['Permission']['id'], $selPerms) ? "checked" : "";?>
			/>
			<?=$perm['Permission']['description'];?>(<?=$perm['Permission']['name'];?>)
		</li>
		<?php endforeach; ?>
	</ul>
</div>