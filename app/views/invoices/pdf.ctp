<?php
	$header=array('', 'Item', 'Item No.', 'Price', 'Delivery', 'Qty.', 'Subtotal'); 
	
	$fpdf->store = $store['Store'];
	$fpdf->setTitle('TAX INVOICE');
	$fpdf->SetFont('Arial','',10);
	
	$fpdf->AliasNbPages();
	$fpdf->AddPage();
  	$colWidth = array(12, 88, 20, 20, 20, 10, 20); 
  	$fpdf->setOrderSheet($header, $colWidth, $order, true); 
  	echo $fpdf->fpdfOutput(); 
?> 