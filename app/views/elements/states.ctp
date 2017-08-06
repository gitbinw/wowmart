<?php
	$arrStates = array(
		array('label'=>'Please select a state', 'value'=>"", 'name'=>'Please select a state'),
		array('label'=>'Australian Capital Territory', 'value'=>"ACT", 'name'=>'Australian Capital Territory'),
		array('label'=>'New South Wales', 'value'=>"NSW", 'name'=>'New South Wales'),
		array('label'=>'Victoria', 'value'=>"VIC", 'name'=>'Victoria'),
		array('label'=>'Queensland', 'value'=>"QLD", 'name'=>'Queensland'),
		array('label'=>'South Australia', 'value'=>"SA", 'name'=>'South Australia'),
		array('label'=>'Western Australia', 'value'=>"WA", 'name'=>'Western Australia'),
		array('label'=>'Tasmania', 'value'=>"TAS", 'name'=>'Tasmania'),
		array('label'=>'Northern Territory', 'value'=>"NT", 'name'=>'Northern Territory')
	);
?>

<select id="<?=$stateId;?>" name="<?=$stateName;?>" class="<?=$stateClass;?>">
<?php
	foreach($arrStates as $state) {
		$selected = "";
		if (isset($stateValue) && $stateValue == $state['value']) {
			$selected = "selected";
		}
?>
		<option label="<?=$state['label'];?>" value="<?=$state['value'];?>" <?=$selected;?>><?=$state['name'];?></option>
<?php
	}
?>
</select>