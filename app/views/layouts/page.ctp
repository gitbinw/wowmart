<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Freshla - <?=!empty($page_title) ? $page_title : DEFAULT_PAGE_TITLE;?></title>
<?=$html->charset('UTF-8');?>
<meta name="description" content="<?=!empty($meta_desc) ? $meta_desc : DEFAULT_META_DESC;?>" />
<meta name="keywords" content="<?=!empty($meta_keywords) ? $meta_keywords : DEFAULT_META_KEYWORDS;?>" />
<meta name="robots" content="INDEX,FOLLOW" />
<link type="image/x-icon" href="/img/favicon.ico" rel="shortcut icon">
<?=$html->css('web');?>
</head>

<body>

<div id='container'>

<div id='header'>
	<a href="<?=SITE_URL;?>"><div id='header_lft'>
		<div class="slogan">AUSTRALIA's No1 Collection of Gourmet Food</div>
	</div>
	</a>
	<div id='header_rgt'>
		<?=$this->element('header_menu');?>
	</div>
</div>

<div id="topmenu">
	<?=$this->element('topmenu_bar', array('cache'=>true));?>
</div>

<div id='main_body_page'>
	<div id="category_trigger"></div>
	<div id="left_side_page">
		<?=$this->element('category_tree',  array('cache'=>true));?>
	</div>
	<div id="body_content_page">
		<?php echo $content_for_layout;?>
	</div>
</div>

<?=$this->element('page_footer',  array('cache'=>true));?>

</div>

<?=$javascript->link('jquery/jquery-1.4.1.js');?>
<?=$javascript->link('jquery/jquery.utility.js');?>
<?=$javascript->link('jquery/jquery.cookie.js');?>
<?=$javascript->link('jquery/jquery.my.slideviewer.js');?>
<?=$javascript->link('jquery/jquery.my.popwindow.js');?>
<?=$javascript->link('myweb');?>
<?=$javascript->link('dishmenu.js');?>
<script language="javascript" type="text/javascript">
	$(document).ready(function() {
		$(document).bind('mouseover.category', switchCategory);
		loadTabs('tab_view');
		toggleBlock();
		
		$("select.sort_opts").change(function() {document.location.href=$(this).val();});
		
		if ($("select#subitems_list").length > 0) {
			var value = $.parseJSON($("select#subitems_list").val());
			$("input#subitems_id").val(value['id']);
			$("div#subitems_name").html(value['name']);
			$("span#subitems_price").html(value['price']);
			
			$("select#subitems_list").change(function() {
				var value = $.parseJSON($(this).val());
				$("input#subitems_id").val(value['id']);
				$("div#subitems_name").html(value['name']);
				$("span#subitems_price").html(value['price']);
			});
		} else if($("select#extraitems_list").length > 0) {
			$("select#extraitems_list").val("");
			$("#extra_items .remove").live('click', function(e) {
				$(this).parent().remove();
			});
			$("select#extraitems_list").change(function() {
				addExtraItem($(this));
			});
		}
		
		var $extraAdd = $("#tab_view .btn_extra_add");
		if ($extraAdd.length > 0) {
			$extraAdd.click(function(e) {
				var btnId = this.id.substring(4);
				var optId = 'opt_' + btnId;
				var obj = $("select#extraitems_list option[id|=" + optId + "]").attr('selected', true);
				addExtraItem(obj);
			});
		}
		
		loadProdImages();
	});	
	
	function addExtraItem(obj) {
		var value = obj.val();
		if (!value) return false;
		var value = $.parseJSON(value);
		var qty = 1;
		var $extra = $("#extra_" + value['id']);
		if ($("#extra_" + value['id']).length > 0) {
			qty = parseInt($extra.val()) + 1;
			$extra.val(qty);
		} else {
		  var lst = "<li>" + 
					"	<div class='remove' alt='remove' title='remove'></div>" + 
					"	<div class='name'>" + value['name'] + "</div>" + 
					" <div class='price'>$" + value['price'] + "</div>" +  
					" <div class='last'>" + 
					"		<input type='text' id='extra_" + 
						  value['id'] + "' name='data[extra][" + value['id'] + "]' value='" + qty + 
						  "' class='qty_input' />" + 
					"	</div>" + 
					"</li>";
		  $("#extra_items").append(lst);
		}
	}
</script>
</body>
</html>