You've Received a New Application!

Business Name:
<?=$data['Supplier']['biz_name'];?>
							
Phone Number:
<?=$data['Supplier']['phone'];?>
							
Username/Email:
<?=$data['User']['email'];?>

Subdomain:
<?=$data['Supplier']['subdomain'];?>.freshla.com.au

Contact Name:
<?=!empty($data['Supplier']['contact_name']) ? $data['Supplier']['contact_name'] : 'Not Provided';?>

Shipping Return Address:
<?=$data['Supplier']['return_address1'] . " " . $data['Supplier']['return_address2'];?>

Suburb:
<?=$data['Supplier']['return_suburb'];?>

Postcode:
<?=$data['Supplier']['return_postcode'];?>

State:
<?=$data['Supplier']['return_state'];?>

Website:
<?=!empty($data['Supplier']['website']) ? $data['Supplier']['website'] : 'Not Provided';?>

About Us:
<?=!empty($data['Supplier']['aboutus']) ? $data['Supplier']['aboutus'] : 'Not Provided';?>

Cheers,
The Freshla Team
