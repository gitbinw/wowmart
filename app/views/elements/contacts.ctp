<select id="<?=$contactId;?>" name="<?=$contactName;?>" style="width:200px;">
	<option value="0">&nbsp;</option>
<?php
	$contacts = $this->requestAction('/contacts/get/');
	if ($contacts && is_array($contacts)) {
		foreach($contacts as $key => $cnt) {
			$val = json_encode($cnt['Contact']);
?>
			<option value='<?=$val;?>'>
				<?=$cnt['Contact']['alias'];?>
			</option>
<?php
		}
	}
?>
</select>
<script language="javascript" type="text/javascript">
	$("#<?=$contactId;?>").change(function() {
		var data = $.parseJSON($(this).val());
		$.each(data, function(i, val) {
			$("#" + i).val(val);
		});
	});
</script>