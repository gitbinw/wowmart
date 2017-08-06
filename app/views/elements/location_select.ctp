<?php 
	$colspan = 3;
	$loc_button = "";
	$model = !empty($model) ? $model : "Supplier";
	if (isset($is_prod_loc) && $is_prod_loc == true) { 
		$colspan = 2;
		$model = "Product";
		$loc_button = '<input type="button" value="Get Supplier Locations" id="get_sup_loc" />';
	} 
	
	if (!empty($thisItem[$model]['locations'])) {
		$locations = $this->requestAction('/admin/locations/get/' . $thisItem[$model]['locations']);
	}
?>
<tr bgcolor="#CF9FFF">
<td style="height:150px;padding-top:12px;" valign="top" colspan="<?=$colspan;?>">
<table><tr>
<td valign="top">
<?=$this->element('location_picker');?>
</td><td valign="top">
<div>
<input type='button' value="Add >>" id="location_add" />
<input type='button' value="<< Remove" id="location_remove" />
<?=$loc_button;?>
</div>
<div id="multi_select">
<div class="process_icon"><img src="/img/icons/loading.gif" /></div>
<select multiple="multiple" id="location_list" style="height:100px;width:320px;">
<?php
	foreach(@$locations as $loc) {
?>
		<option value="<?=$loc['Location']['id'];?>">
			<?=$loc['Location']['suburb'] . " " . $loc['Location']['state'] . " " . $loc['Location']['postcode'];?>
		</option>
<?php
	}
?>
</select>
<input type="hidden" name="data[<?=$model;?>][locations]" id="supplier_locations" 
value="<?=@$thisItem[$model]['locations'];?>" />
</div>

<script language="javascript" type="text/javascript">
$("#location_add").click(function(e) {
	var locId = $("#location_id").val()								  ;
	if (locId) {
		var $locKey = $("#location_keywords");
		var $supLoc = $("#supplier_locations");
		var oldLoc = $supLoc.val();
		if (oldLoc.indexOf(locId) != -1) {
			alert('You have already added this location.');
		} else {
			$("<option>")
				.attr('value', locId)
				.text($locKey.val())
				.appendTo($("#location_list"));
		
			
			var newLoc = oldLoc ? oldLoc + ',' + locId : locId;
			$supLoc.val(newLoc);
		}
		$locKey.val('');
		$("#location_id").val('');
	}
});
$("#location_remove").click(function(e) {
	var $supLoc = $("#supplier_locations");
	var strLocs = $supLoc.val();
	var arrLocs = strLocs.split(",");
	$("#location_list option:selected").each(function(i, val) {
		var index = arrLocs.indexOf($(val).val());
		arrLocs.splice(index, 1);
		$(val).remove();
	});
	var newLocs = "";
	for(var j in arrLocs) {
		if (j == 0) newLocs += arrLocs[j];
		else newLocs += "," + arrLocs[j];
	}
	$supLoc.val(newLocs);
});

$("#get_sup_loc").click(function(e) {
	var $icon = $("#multi_select .process_icon");
	var $opts = {
		url: "/admin/locations/get/" + $("#supplier").val() + "/1",
		type: "post",
		dataType: 'json',
		beforeSend: function() {
			$icon
				.css ({ opacity: 0.5 })
				.show();
		},
		success: function(data) {
			var $loc_list = $("#location_list");
			var sup_locs = "";
			$loc_list.empty();
			$.each(data, function(i, val) {
				$("<option>")
					.attr('value', val.Location.id)
					.text(val.Location.suburb + ' ' + val.Location.state + ' ' + val.Location.postcode)
					.appendTo($loc_list);
				
				if (i == 0) sup_locs += val.Location.id;
				else sup_locs += ',' + val.Location.id;
			});
			$("#supplier_locations").val(sup_locs);
			$icon.hide();
		}
	};
	$.ajax($opts);
});
</script>
</td></tr>
</table>

</td>
</tr>
