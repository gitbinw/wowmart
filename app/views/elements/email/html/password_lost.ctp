<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<body>

<table width="600px" cellpadding="0" cellspacing="0" border="0" bgcolor="#fcfcfa" style="font-family:Arial,Verdana,Helvetica,sans-serif">
	<tr><td width="300px" height="110px" style="border-bottom:4px solid #000000;"><a href="http://www.freshla.com.au" target="_blank">
		<img src="http://www.freshla.com.au/img/email/logo.gif" width="300" height="110" border="0" />
	</a></td>
	</tr>
	<tr><td height="20px"></td></tr>
	<tr><td>
		<table width="560px" cellpadding="0" cellspacing="0" border="0" style="margin:0 20px;">
			<tr>
				<td style="color:#5E703B;font-weight:bold;font-size:24px;">
					Your New Password
				</td>
			</tr>
			<tr><td height="20px"></td></tr>
			<tr><td>
				<table width="100%" cellspacing="10" cellpadding="0" border="0" style="font-size:14px;">
					<tr>
						<td style="padding:10px;">
							<p>Hi <?=!empty($data['UserProfile']['firstname']) ? 
													$data['UserProfile']['firstname'] : 'there';?>,</p>
							<p>You have requested to retrieve the lost password from <a href="http://www.freshla.com.au">freshla.com.au</a>!<p>
                            <p>We have reset your password. Here is your new password: <?=$data['User']['new_password'];?></p>
                            <p>To change the password, just login to <a href="http://www.freshla.com.au/account">my account</a> in freshla.com.au</p>
							<p>
								Cheers,<br>
								<a href="mailto:support@freshla.com.au">The Freshla Team</a>
							</p>
						</td>
					</tr>
				</table>
			</td></tr>
		</table>
	</td><tr>
</table>

</body>
</html>