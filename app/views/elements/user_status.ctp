<?php
	$arrStatus = array(
		array('label'=>'Not Verified', 'value'=>"0", 'name'=>'Not Verified'),
		array('label'=>'Verified', 'value'=>"1", 'name'=>'Verified')
	);
?>

<select id="<?=$statusId;?>" name="<?=$statusName;?>">
<?php
	foreach($arrStatus as $status) {
		$selected = "";
		if (isset($statusValue) && $statusValue == $status['value']) {
			$selected = "selected";
		}
?>
		<option label="<?=$status['label'];?>" value="<?=$status['value'];?>" <?=$selected;?>><?=$status['name'];?></option>
<?php
	}
?>
</select>