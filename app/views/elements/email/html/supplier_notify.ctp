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
					You've Received a New Application!
				</td>
			</tr>
			<tr><td height="20px"></td></tr>
			<tr><td>
				<table width="100%" cellspacing="10" cellpadding="0" border="0" style="font-size:14px;">
					<tr>
						<td style="padding:10px;">
							<p>
								<h4>Business Name:</h4>
								<?=$data['Supplier']['biz_name'];?>
							</p>
							<p>
								<h4>Phone Number:</h4>
								<?=$data['Supplier']['phone'];?>
							</p>
							<p>
								<h4>Username/Email:</h4>
								<?=$data['User']['email'];?>
							</p>
							<p>
								<h4>Subdomain:</h4>
								<?=$data['Supplier']['subdomain'];?><b>.freshla.com.au</b>
							</p>
							<p>
								<h4>Contact Name:</h4>
								<?=!empty($data['Supplier']['contact_name']) ? $data['Supplier']['contact_name'] : 'Not Provided';?>
							</p>
							<p>
								<h4>Shipping Return Address:</h4>
								<?=$data['Supplier']['return_address1'] . " " . $data['Supplier']['return_address2'];?>
							</p>
							<p>
								<h4>Suburb:</h4>
								<?=$data['Supplier']['return_suburb'];?>
							</p>
							<p>
								<h4>Postcode:</h4>
								<?=$data['Supplier']['return_postcode'];?>
							</p>
							<p>
								<h4>State:</h4>
								<?=$data['Supplier']['return_state'];?>
							</p>
							<p>
								<h4>Website:</h4>
								<?=!empty($data['Supplier']['website']) ? $data['Supplier']['website'] : 'Not Provided';?>
							</p>
							<p>
								<h4>About Us:</h4>
								<?=!empty($data['Supplier']['aboutus']) ? $data['Supplier']['aboutus'] : 'Not Provided';?>
							</p>
							<p>
								Cheers,<br>
								<a href="mailto:suppliers@freshla.com.au">The Freshla Team</a>
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