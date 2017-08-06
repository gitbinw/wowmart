Hi <?=!empty($data['UserProfile']['firstname']) ? 
	$data['UserProfile']['firstname'] : 'there';?>,

You have requested to retrieve the lost password from freshla.com.au! 
We have reset your password. Here is your new password: <?=$data['User']['new_password'];?>  
To change the password, just login to my account in freshla.com.au

Cheers,
The Freshla Team
