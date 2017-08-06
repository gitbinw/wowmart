<?php 
	$options = $this->requestAction('admin/suppliers/get');
?>
<select id="<?=$selectId;?>" name="<?=$selectName;?>">
	<option value="0"></option>
<?php
	foreach($options as $opt) {
		$selected = "";
		if (isset($selectedValue) && $selectedValue == $opt['Supplier']['id']) {
			$selected = "selected";
		}
?>
		<option value="<?=$opt['Supplier']['id'];?>" <?=$selected;?>><?=$opt['Supplier']['biz_name'];?></option>
<?php
	}
?>
</select>