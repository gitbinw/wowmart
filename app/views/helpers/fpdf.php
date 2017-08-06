<?php 
App::import('Vendor', 'fpdf/fpdf');

if (!defined('PARAGRAPH_STRING')) define('PARAGRAPH_STRING', '~~~');

class fpdfHelper extends FPDF {
	var $title;
	var $store = array();
	var $helpers = array();
	
	function __construct($View, $settings = array()) {
		parent::FPDF();
	}
	
	function setup ($orientation='P',$unit='mm',$format='A4') {
		$this->FPDF($orientation, $unit, $format); 
	}
	
	function fpdfOutput ($name = 'page.pdf', $destination = 's') {
		return $this->Output($name, $destination);
	}
	
	function Header()
	{
	    //Logo
	    $this->Image(WWW_ROOT.DS.'img/home/logo.gif',10,10,70);  
		// you can use jpeg or pngs see the manual for fpdf for more info
	    //Arial bold 15
	    $this->SetFont('Arial','B', 12);
	    $this->Cell(190, 5, $this->store['company'], 0, 0, 'R');
	    $this->Ln();
	    $this->SetFont('','', 10);
	   // $this->Cell(190, 5, $this->store['address'], 0, 0, 'R');
	   // $this->Ln();
	   // $this->Cell(190, 5, $this->store['suburb'] . $this->store['state'] . 
	   // 											$this->store['postcode'], 0, 0, 'R');
	   // $this->Ln();
	    $this->Cell(190, 5, 'ACN: ' . $this->store['abn'], 0, 0, 'R');
	    $this->Ln();
	  //  $this->Cell(190, 5, 'TEL: ' . $this->store['phone'], 0, 0, 'R');
	  //  $this->Ln();
	  //  $this->Cell(190, 5, 'FAX: ' . $this->store['fax'], 0, 0, 'R');
	  //  $this->Ln();
	    $this->Cell(190, 5, 'Website: ' . $this->store['web'], 0, 0, 'R');
	    $this->Ln();
	    
	    $this->SetFont('','B', 14);
	    //Move to the right
	    $this->Cell(75);
	    //Title
	    $this->Cell(50,10,$this->title,0,0,'C');
	    //Line break
	    $this->Ln();
	}

	//Page footer
	function Footer()
	{
	    //Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    //Arial italic 8
	    $this->SetFont('Arial','I',8);
	    //Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function setOrderSheet($header, $colWidth, $data, $isInvoice=false) {
		$this->SetTextColor(0);
		$this->SetFont('','B', 12);
		
		$ref_title = 'Order Number: ';
		$ref_value = $data['Order']['order_no'];
		$ref_date  = date('d/m/Y', strtotime($data['Order']['created']));
		if ($isInvoice === true) {
			$ref_title = 'Invoice Number: ';
			$ref_value = $data['Invoice']['invoice_no'];
			$ref_date  = date('d/m/Y', strtotime($data['Invoice']['created']));
		}
			
		$this->Cell(190, 8, $ref_title . $ref_value, 0, 0, 'R', 0);
		$this->Ln();
		$this->Cell(190, 8, 'Date: ' . $ref_date, 0, 0, 'R', 0);
		$this->Ln();
		
		
    	//Colors, line width and bold font
    	$this->SetFillColor(2, 89, 50);
    	$this->SetTextColor(255);
    	$this->SetDrawColor(204, 204, 204);
    	$this->SetLineWidth(.3);
    	$this->SetFont('','B', 10);
    	
    	$this->Cell(95, 11, "Billing Details", 'TBL', 0, 'C', 1);
    	$this->Cell(95, 11, "Shipping Details", 'TBLR', 0, 'C', 1);
    	$this->Ln();
    	
    	$this->SetFont('','', 8);
    	$this->SetTextColor(0);
    	$this->Cell(95, 2, " ", 'LR', 0, 'L', 0);
    	$this->Cell(95, 2, " ", 'R', 0, 'L', 0);
    	$this->Ln();
    	
    	$this->Cell(95, 4, $data['Billing']['firstname'] . " " . $data['Billing']['lastname'], 'LR', 0, 'L', 0);
    	$this->Cell(95, 4, $data['Shipping']['firstname'] . " " . $data['Shipping']['lastname'], 'R', 0, 'L',0);
    	$this->Ln();
    	
    	$this->Cell(95, 4, $data['Billing']['address1'] . " " . $data['Billing']['address2'], 'LR', 0, 'L', 0);
    	$this->Cell(95, 4, $data['Shipping']['address1'] . " " . $data['Shipping']['address2'], 'R', 0, 'L',0);
    	$this->Ln();
    	
    	$this->Cell(95, 4, $data['Billing']['suburb'] . " " . $data['Billing']['state'] . " " . $data['Billing']['postcode'], 'LR', 0, 'L', 0);
    	$this->Cell(95, 4, $data['Shipping']['suburb'] . " " . $data['Shipping']['state'] . " " . $data['Shipping']['postcode'], 'R', 0, 'L',0);
    	$this->Ln();
    	
    	$this->Cell(95, 4, $data['Billing']['country'], 'LR', 0, 'L', 0);
    	$this->Cell(95, 4, $data['Shipping']['country'], 'R', 0, 'L',0);
    	$this->Ln();
    	
    	$this->Cell(95, 4, "Phone: " . $data['Billing']['phone'], 'LR', 0, 'L', 0);
    	$this->Cell(95, 4, "Phone: " . $data['Shipping']['phone'], 'R', 0, 'L',0);
    	$this->Ln();
    	
    	$this->Cell(95, 4, "Mobile: " . $data['Billing']['mobile'], 'LR', 0, 'L', 0);
    	$this->Cell(95, 4, "Mobile: " . $data['Shipping']['mobile'], 'R', 0, 'L',0);
    	$this->Ln();
    	
    	$this->Cell(95, 2, " ", 'LR', 0, 'L', 0);
    	$this->Cell(95, 2, " ", 'R', 0, 'L', 0);
    	$this->Ln();
    	
    	//Header
    	$this->SetFont('','B', 10);
    	$this->SetTextColor(255);
    	$colNum = count($header) - 1;
    	for($i=0; $i <= $colNum; $i++) {
    		if ($i ==0) {
    			$this->Cell($colWidth[$i],11,$header[$i],'TBL',0,'R',1);
    		} else if ($i == $colNum) {
    			$this->Cell($colWidth[$i],11,$header[$i],1,0,'R',1);
    		} else {
       	 		$this->Cell($colWidth[$i],11,$header[$i],1,0,'C',1);
    		}
    	}
   	 	$this->Ln();
    	//Color and font restoration
    	$this->SetFillColor(224,235,255);
    	$this->SetTextColor(0);
    	$this->SetFont('','', 8);
    	//Data
    	$fill=0;
    	$lftWidth = 0;
    	
    	for ($j=0; $j < (count($colWidth) - 1); $j++) {
    		$lftWidth += $colWidth[$j];
    	}
    	
    	if (isset($data['Product']) && count($data['Product']) > 0) {
    		foreach($data['Product'] as $key => $prod) {
				if (!empty($prod['Subproduct']) && count($prod['Subproduct']) > 0) {
					foreach ($prod['Subproduct'] as $subprod) {
						if ($subprod['id'] == $prod['OrdersProduct']['subproduct_id']) {
							$prod['name'] = $subprod['name'];
							break;
						}
					}
				}
    			if (isset($prod['Image'][0])) {
    				$img_src = WWW_ROOT.DS."/img/images/product/" . $prod['Image'][0]['id'] .
    				"/" . $prod['Image'][0]['id'] . "a4" . $prod['Image'][0]['extension'];
    			} else {
    				$img_src = WWW_ROOT.DS."/img/home/noimage_small.gif";
    			}
    			$i = 0;
    			$imgY = 102 + $key * 11; //122 + $key * 11; 
    			$prodName = !empty($prod['OrdersProduct']['prod_desc']) ? str_replace('<br>', '\r\n', $prod['OrdersProduct']['prod_desc']) : $prod['name'];
    			if (strlen($prodName) > 65) {
    				$prodName = substr($prodName, 0, 65) . "...";
    			}
    			$this->Cell($colWidth[$i++], 11, $this->Image($img_src, 10, $imgY, 12, 9), 'LR', 0, 'L', ''); 
    			$this->Cell($colWidth[$i++], 11, $prodName, 'LR', 0, 'C', $fill);
    			$this->Cell($colWidth[$i++], 11, $prod['serial_no'], 'LR', 0, 'C', $fill);
    			$this->Cell($colWidth[$i++], 11, "$" . number_format($prod['OrdersProduct']['deal_price'], 2), 'LR', 0, 'C', $fill);
				if ($data['Order']['business_code'] != BUSINESS_BRASA_DELIVERY) {
	    			$this->Cell($colWidth[$i++], 11, "$" . number_format($prod['OrdersProduct']['freight'], 2), 'LR', 0, 'C', $fill);
				}
    			$this->Cell($colWidth[$i++], 11, $prod['OrdersProduct']['quantity'], 'LR', 0, 'C', $fill);
    			$this->Cell($colWidth[$i++], 11, "$" . number_format($prod['OrdersProduct']['subtotal'], 2), 'LR', 0, 'R', $fill);
    			

    			$this->Ln();
    			$fill=!$fill;
    		}
    		
    		$this->SetFont('','B', '');
    		$this->Cell($lftWidth, 11, "Item Total:", 'TLR', 0, 'R', $fill);
    		$this->SetFont('');
    		$this->Cell($colWidth[count($colWidth) - 1], 11, "$" . number_format($data['Order']['subtotal'], 2), 'TLR', 0, 'R', $fill);
    		
    		$this->Ln();
    		$fill=!$fill;
    		$this->SetFont('','B', '');
    		$this->Cell($lftWidth, 11, "Delivery Fee:", 'TLR', 0, 'R', $fill);
    		$this->SetFont('');
    		$this->Cell($colWidth[count($colWidth) - 1], 11, "$" . number_format($data['Order']['freight'], 2), 'TLR', 0, 'R', $fill);
    		
    		$this->Ln();
    		$fill=!$fill;
    		$this->SetFont('','B', '');
    		$this->Cell($lftWidth, 11, "Total:", 'TBLR', 0, 'R', $fill);
    		$this->SetFont('');
    		$this->Cell($colWidth[count($colWidth) - 1], 11, "$" . number_format($data['Order']['total_amount'], 2), 'TBLR', 0, 'R', $fill);
    		
    		$this->Cell(array_sum($data['Product']),0,'','T');
    	}
	} 

}
?>