<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AutoPsiaGlobal.com.au Australia :: <?php echo $title_for_layout?></title>
<?php echo $html->charsetTag('UTF-8');?>
<?php echo $javascript->link('myweb');?>
<?php echo $javascript->link('RollBar');?>
<?php echo $html->css('style');?>
</head>

<body>
<table class='container' cellpadding="0" cellspacing="0" align='center'>
<tr valign="top">
<td id='logo'><?=$html->image("main/logo.gif",array('border'=>'0'));?></td>
<td><table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td colspan="2" id='menubar'>
<?=$menu->tbMenu($main_menus,$sel_menu);?>
</td></tr>
<tr><td colspan="2" id='menubar_sub'>
<?=$menu->tbSubMenu($sub_menus,$main_menus,@$sel_sub_menu);?>
</td></tr>
<tr>
<td><?=$html->image("main/form01.gif",array('border'=>'0'));?></td>
<td><?=$html->image("main/form02.gif",array('border'=>'0'));?></td>
</tr>
<tr>
<form action="" method="get">
<td class='search_view'>
<input type="text" id="txt_keyword" name="txt_keyword" value="<?=@$txt_keyword;?>" size="20" maxlength='50'>
<a href="javascript:search('/<?=WEBROOT_DIR;?>/webitems/search');">
<img src="/<?=WEBROOT_DIR;?>/img/main/b_search.gif" width="79" height="20" alt="" border="0" align="absbottom">
</a>
</td>
</form>
<td class='cart_view'>
<table border="0" cellpadding="0" cellspacing="0" width="100%" background="">
<tr align="center">
<td><p style="color: #FFFFFF; font-size: 10px;"><b>TOTAL<br>
$<?=(!@$_SESSION['sess_cart']['amount']?'0.00':number_format($_SESSION['sess_cart']['amount'],2,'.',','));?></b></p></td>
<td><a href="/<?=WEBROOT_DIR;?>/webshops/view/"><img src="/<?=WEBROOT_DIR;?>/img/main/b_vew.gif" width="79" height="20" alt="" border="0"></a></td>
</tr>
</table>
	</td>
</tr>
</table>
</td></tr>

<tr valign="top">
<td id='main_left'>
<table id='left_side' cellpadding="0" cellspacing="0">
<tr><td align="right">	
<table class='board' cellpadding="0" cellspacing="0">
<tr><td class='board_head_1'><p class="title">CATEGORY BROWSER</p></td></tr>
<tr><td id='tree_view'><?=$categoryTree;?></td></tr>
<tr><td class='board_bot'></td></tr>
</table>	
<br>
<table class='board' cellpadding="0" cellspacing="0">
<tr><td class='board_head_2'><p class="title">CUSTOMER LOGIN</p></td></tr>
<form id='form_login' name='form_login' action='/<?=WEBROOT_DIR;?>/webclients/signin' method="POST">
<tr><td class='login_view'>
<?php
if (isset($_SESSION['sess_user']['username'])) {
	echo 	"<table align='center'><tr><td class='login_info_head'>Welcome</td></tr>".
		"<tr><td class='login_info_user'><a href='/".WEBROOT_DIR."/webclients/view'>".
		$_SESSION['sess_user']['username']."</a></td></tr>".
		"<tr><td><a href='/".WEBROOT_DIR."/webclients/login'>".
		$html->image("main/b_logout.gif",array('border'=>'0'))."</a></td></tr>";
} else {
?>
<table>
<tr><td><b>User Name:</b></td></tr>
<tr><td><input type='text' name='data[Client][username]' size='17'></td></tr>
<tr><td><b>Password:</b></td></tr>
<tr><td><input type='password' name='data[Client][password]' size='17'></td></tr>
<tr><td><input type='image' src="/<?=WEBROOT_DIR;?>/img/main/b_login.gif" width="79" height="20" border="0"></td></tr>
<?php
}
?>
</table>
</td></tr>
</form>
<tr><td class='board_bot'></td></tr>
</table>
</td>
</tr>

<tr><td class='left_bot'></td></tr>
</table>
</td>

<td id='main_right' align='center'>
<!--start main right-->
<div class='m_top'></div>
<div align='center' class='message'><?=$message;?></div>
<!--end of main right-->
</td>
</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="758" align="center">
<tr>
	<td height="52" align="right" background="/<?=WEBROOT_DIR;?>/img/main/bot.gif">
<table border="0" cellpadding="0" cellspacing="0" width="530" background="">
<tr><td align='center'>
<?=$menu->tbFooter($sub_menus);?>
</td></tr>
</table>
</td>
</tr>
<tr>
<td><p align="right" style="margin-right: 200px;">
Copyright &copy;2007 <a href='www.happy3.com.au'>happy3.com.au </a>
</p></td>
</tr>
</table>

</body>

</html>
