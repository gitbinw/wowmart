<?php 
	header('Cache-Control: no-cache, must-revalidate');
	
	$selectedCat = isset($parentItem) && !empty($parentItem) ? $parentItem : '';
	
	$newCatUrl  = SITE_URL_ROOT . '/admin/categories/newCategory/' . $selectedCat;
	$newProdUrl = SITE_URL_ROOT . '/admin/categories/newProduct/' . $selectedCat;
?>
<table ctrl='categories' cellspacing='0' cellpadding='20px' align='center'>
	<tr>
		<td><input type='button' id='btn_new_category' value='Create a New Sub-Category' /></td>
		<td><input type='button' id='btn_new_product' value='Create a New Product' /></td>
	</tr>
</table>

<script>
	$('#btn_new_product').unbind('click').click(function(e) {
		$.ajax ( { 
			type: "GET", 
			url: "<?=$newProdUrl;?>", 
			beforeSend:loading, 
			success:function(msg) {
				display (msg,g_body_id);
			}
		} );
	});
	$('#btn_new_category').unbind('click').click(function(e) {
		$.ajax ( { 
			type: "GET", 
			url: "<?=$newCatUrl;?>", 
			beforeSend:loading, 
			success:function(msg) {
				display (msg,g_body_id);
			} 
		} );
	});
</script>