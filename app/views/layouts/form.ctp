<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Freshla :: <?php echo $title_for_layout?></title>
<?=$html->charset('UTF-8');?>
<link type="image/x-icon" href="/img/favicon.ico" rel="shortcut icon">
<?=$html->css('web');?>
</head>

<body>

<div id='container'>

<div id='header'>
	<a href="/"><div id='header_lft'>
		<div class="slogan">AUSTRALIA's No1 Collection of Gourmet Food</div>
	</div></a>
	<div id='header_mid'></div>
	<div id='header_rgt'></div>
</div>

<div class="black_nomargin_5"></div>

<div id='main_body'>
	<div id="main_form">
		<?php echo $content_for_layout;?>
  </div>
</div>

<div class="black_line_5"></div>

<?=$this->element('page_footer');?>

</div>

</body>
</html>