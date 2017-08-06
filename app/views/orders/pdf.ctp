<?php
	$header=array('', 'Item', 'Item No.', 'Price', 'Delivery', 'Qty.', 'Subtotal');
	$colWidth = array(12, 88, 20, 20, 20, 10, 20); 
	if ($order['Order']['business_code'] == BUSINESS_BRASA_DELIVERY) {
		$header=array('', 'Item', 'Item No.', 'Price', 'Qty.', 'Subtotal'); 
		$colWidth = array(12, 108, 20, 20, 10, 20); 
	}
	
	$fpdf->store = $store['Store'];
	$fpdf->setTitle('Order Sheet');
	$fpdf->SetFont('Arial','',10);
	
	$fpdf->AliasNbPages();
	$fpdf->AddPage();
  	
  	$fpdf->setOrderSheet($header, $colWidth, $order); 
  	echo $fpdf->fpdfOutput(); 
?> 