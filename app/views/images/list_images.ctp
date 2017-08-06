<?php
	$img_html = addslashes( $html->image( $imgPath,array('border'=>0,'style'=>'width:120px;height:100px;') ) );
?>
<script>
parent.$('#img_list').html("<?=$img_html;?>");
parent.$("#form_detail").append("<input id='main_image' type='hidden' name='data[Image][1]' value='<?=$imgPath;?>'>");
</script>