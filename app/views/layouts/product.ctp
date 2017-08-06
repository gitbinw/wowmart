<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Freshla :: <?php echo $title_for_layout?></title>
<?=$html->charset('UTF-8');?>
<link type="image/x-icon" href="/img/favicon.ico" rel="shortcut icon">
<?=$html->css('web');?>
<?=$html->css('jquery.my.popwindow.css');?>
<?=$javascript->link('jquery/jquery-1.4.1.js');?>
<?=$javascript->link('jquery/jquery.my.slideviewer.js');?>
<?=$javascript->link('jquery/jquery.my.popwindow.js');?>
<?=$javascript->link('myweb');?>
</head>

<body>

<div id='container'>

<div id='header'>
	<a href="/"><div id='header_lft'>
		<div class="slogan">AUSTRALIA's No1 Collection of Gourmet Food</div>
	</div>
	</a>
	<div id='header_mid'>
		<a href="/suppliers/register"><img src="/img/home/btn_newsupplier.png" border="0" /></a>
		<a href="/p/why-freshla.html"><img src="/img/home/btn_whychoose.png" border="0" /></a>
	</div>
	<div id='header_rgt'></div>
</div>

<div id="topmenu">
	<?=$this->element('topmenu_bar', array('cache'=>true));?>
</div>

<div id='main_body'>
	<div id="left_side">
		<?=$this->element('category_tree',  array('cache'=>true));?>
	</div>
	<div id="body_content">
		<?php echo $content_for_layout;?>
	</div>
</div>

<div id='banner_media'>
	<div class="media_main">
		<ul>
			<li class="facebook">
				<b>BECOME OUR FAN</b><br>
				For exclusive offers <br>
				and competitions
			</li>
			<li class="twitter">
				<b>FOLLOW US</b><br>
				For daily updates
			</li>
			<li class="youtube">
				<b>FOLLOW US</b><br>
				For daily videos
			</li>
			<li class="community">
				<b>COMMUNITY</b><br>
				News
			</li>
			<li class="subscribe">
				<b>SUBSCRIBE</b><br>
				Win $200 gift	voucher
			</li>
			<li class="contactus">
				<b>CONTACT US</b><br>
				By Email or Phone
			</li>
		</ul>
	</div>
</div>

<div class="black_line_5"></div>

<div id="footer_links">
	<div class="links_main">
		<ul class="link_list">
			<li>
				<h4>Gift Category</h4>
				<h5><a href="#">Getaways</a></h5>
				<h5><a href="#">Gift Boxes</a></h5>
				<h5><a href="#">Gourmet Gifts</a></h5>
			</li>
			<li>
				<h4>Occasions</h4>
				<h5><a href="#">Birthday</a></h5>
				<h5><a href="#">Anniversary</a></h5>
				<h5><a href="#">Engagement</a></h5>
				<h5><a href="#">Valentine's Day</a></h5>
				<h5><a href="#">Wedding</a></h5>
				<h5><a href="#">Mother's Day</a></h5>
			</li>
			<li>
				<h4>Gift Certificate</h4>
				<h5><a href="#">$50 Gift Certificate</a></h5>
				<h5><a href="#">$100 Gift Certificate</a></h5>
				<h5><a href="#">$200 Gift Certificate</a></h5>
			</li>
			<li>
				<h4>Recipients</h4>
				<h5><a href="#">Gift for kids</a></h5>
				<h5><a href="#">Gift for her</a></h5>
				<h5><a href="#">Gift for hime</a></h5>
				<h5><a href="#">Gift for couple</a></h5>
				<h5><a href="#">All gift recipients</a></h5>
			</li>
			<li>
				<h4>Community</h4>
				<h5><a href="#">Become our fan</a></h5>
				<h5><a href="#">Community news</a></h5>
			</li>
			<li>
				<h4>Help Centre</h4>
				<h5><a href="#">Use your gift certificate</a></h5>
				<h5><a href="#">Use your voucher</a></h5>
				<h5><a href="#">Extend your voucher</a></h5>
				<h5><a href="#">Exchange your voucher</a></h5>
			</li>
			<li>
				<h4>About Us</h4>
				<h5><a href="#">About Freshla</a></h5>
				<h5><a href="#">Freshla Careers</a></h5>
				<h5><a href="#">Life at Freshla</a></h5>
			</li>
		</ul>
			
		<ul class="footer_logos">
			<li class="paypal"></li>
			<li class="payment"></li>
			<li class="verisign"></li>
			<li class="mcafee"></li>
		</ul>
	</div>
</div>

<?=$this->element('page_footer',  array('cache'=>true));?>

</div>

</body>
</html>