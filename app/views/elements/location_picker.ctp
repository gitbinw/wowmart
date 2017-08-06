<div class="input">
<label>Suburb, City or Postcode</label>
<input type='text' name='data[Location][keywords]' id='location_keywords' class='validation' />
<input type='hidden' name='data[Location][id]' id='location_id' />
<input type='hidden' name='data[Country][id]' id='location_country' value=1 />
</div>

<script language='javascript' type='text/javascript'>
	var options = {
			url : '/locations/ajaxSearch',
			returnField : 'location_id',
			otherFields : ['location_country']
	};								 
	$("#location_keywords").autopicker(options);
</script>