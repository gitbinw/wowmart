<!DOCTYPE html>
<html>
<head>
<title>Wowmart CMS System :: <?php echo $title_for_layout?></title>
<?=$html->charset('UTF-8');?>
<link type="image/x-icon" href="/img/favicon.ico" rel="shortcut icon">
<?=$html->css('jquery-ui-1.10.4.custom.min.css');?>
<?=$html->css('cms');?>
<?=$html->css('jquery.my.popwindow.css');?>
<?=$html->css('jquery.my.splitpanel.css');?>
<?=$html->css('jquery.autopicker.css');?>
<?=$html->css('jquery.jscrollpane.css');?>
<?=$html->css('jquery-ui-timepicker-addon.css');?>
</head>

<body>

<div id='container'>

<div id='header'>
	<div id='header_lft'>
		<!--<img src='img/icons/logo.gif' border='0'>-->
	</div>
	<div id='header_mid'>
		Wowmart CMS System
	</div>
	<div id='header_rgt'>
		<?=$this->element('admin_topnav');?>
	</div>
</div>

<div id='main_body'>
<?php echo $content_for_layout;?>
</div>

<div id='footer'>
	<div class="footer_author">Powered by Bin Wang</div>
</div>

</div>

<?=$javascript->link('jquery/jquery-1.11.0.min.js');?>
<?=$javascript->link('jquery/jquery-ui-1.11.0/jquery-ui.min.js');?>
<?=$javascript->link('jquery/jquery-ui-timepicker-addon.js');?>
<?=$javascript->link('jquery/i18n/jquery-ui-timepicker-addon-i18n.min.js');?>
<?=$javascript->link('jquery/jquery.cycle.min.js');?>
<?=$javascript->link('jquery/jquery.my.splitpanel.js');?>
<?=$javascript->link('jquery/jquery.my.slideviewer.js');?>
<?=$javascript->link('jquery/jquery.event.freeze.js');?>
<?=$javascript->link('jquery/jquery.my.autopicker.js');?>
<?=$javascript->link('jquery/jquery.my.popwindow.js');?>
<?=$javascript->link('jquery/jquery.my.mediamanager.js');?>
<?=$javascript->link('jquery/jquery.utility.js');?>
<?=$javascript->link('jquery/jquery.my.uploader.js');?>
<?=$javascript->link('numeral.min.js');?>
<?=$javascript->link('phpserializer.js');?>
<?=$javascript->link('utf');?>
<?=$javascript->link('base64');?>
<?=$javascript->link('myfuns');?>

</body>
</html>