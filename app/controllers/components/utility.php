<?php
class UtilityComponent extends Object {
	private function getModel($modelName) {
        $objModel = null;
        $model = ucwords($modelName);
        if (App::import('Model', $model)) {
            $objModel = new $model;
        }

        return $objModel;
    }
	
	function validateKeyword ($keyword) {
		$keyword = trim($keyword);
		$pattern = "/(\s)+/";
		$replace = ' ';
		$keyword = preg_replace($pattern,$replace,$keyword);
		return $keyword;
	}
	
	function transData ($data,$val,$disp) {
		$arrOptions = array ();
		foreach ( $data as $index => $option ) {
			$arrOptions [ $option [ $val['m'] ][ $val['id'] ] ]= $option [ $disp['m'] ][ $disp['name'] ];
		}
		return $arrOptions;
	}
	
	function email ($mail_from,$mail_sender,$mail_subject,$mail_content,$mail_to,$attach="",$encoding="utf-8",$html=true) {
		/* Checking valid emails */
    		$mailFilter = "/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i";
    		$strFilter = "/^[a-zA-Z\s]+$/";
    		$intMailMatch = preg_match ( $mailFilter, $mail_from );
    		$intStrMatch = preg_match ( $strFilter, $mail_sender );
    		if ( $intMailMatch == 0 ) return false;
    		if ( $intStrMatch == 0 ) return false;

    		/* Checking is the OS Windows or Mac or Linux */
    		if ( strtoupper ( substr ( PHP_OS, 0, 3 ) == 'WIN' ) ) {
      			$eol="\r\n";
    		} else if ( strtoupper ( substr ( PHP_OS, 0, 3 ) == 'MAC' ) ) {
      			$eol="\r";
    		} else {
      			$eol="\n";
    		}

    		if ( $encoding != "utf-8" && !empty ( $mail_content ) ) {
      			$mail_content = mb_convert_encoding ( $mail_content, $encoding, "utf-8" );
    		}

    		$message = "";

    		/* Setting common Headers */
    		$mail_header = "From: ".$mail_sender." <".$mail_from.">".$eol;
    		$mail_header .= "Reply-To: ".$mail_sender." <".$mail_from.">".$eol;
    		$mail_header .= "Return-Path: ".$mail_sender." <".$mail_from.">".$eol;    // These two to set reply address
   		$mail_header .= "Message-ID: <".time()." TheSystem@".$_SERVER [ 'SERVER_NAME' ].">".$eol;
    		$mail_header .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters
    		$mail_header .= 'MIME-Version: 1.0'.$eol;

    		/* Setting content type */
    		$contentType = "text/plain";
    		if ( $html === true ) {
      			$contentType = "text/html";
    		}

    		/* Setting attachments */
    		if ( isset ( $attach ) && !empty ( $attach ) ) {
      			/* Boundry for marking the split & Multitype Headers */
      			$mime_boundary = md5 ( time() );
      			$mail_header .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol;

      			/* File for Attachment */
      			if ( is_array ( $attach ) ) {
        			foreach ( $attach as $fileName ) {
          				$blnValid =  validateAttachment ( $fileName );
          				if ( $blnValid === true ) {
            					$attachment_name = $_FILES [ $fileName ] [ "name" ];
            					$attachment_type = $_FILES [ $fileName ] [ "type" ];
            					$attachment = $_FILES [ $fileName ] [ "tmp_name" ];
            					if ( is_uploaded_file ( $attachment ) ) { // have a file uploaded?
              						$fp = fopen ( $attachment, "rb" );
							$data = fread ( $fp, filesize ( $attachment ) );
              						$data = chunk_split ( base64_encode ( $data ) ); //Chunk it up and encode it as base64 so it can emailed
        						fclose ( $fp );
            					}

            				$message .= "--".$mime_boundary.$eol;
           			 	$message .= "Content-Type: ".$attachment_type."; name=\"".$attachment_name."\"".$eol;
            				$message .= "Content-Transfer-Encoding: base64".$eol;
            				$message .= "Content-Disposition: attachment; filename=\"".
                        				$attachment_name."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
            				$message .= $data.$eol.$eol;
            				$message .= "Content-Type: multipart/alternative".$eol;
          			}
        		}
      		} else {
        		$fileName = $attach;
        		$blnValid =  validateAttachment ( $fileName );
        		if ( $blnValid === true ) {
          			$attachment_name = $_FILES [ $fileName ] [ "name" ];
          			$attachment_type = $_FILES [ $fileName ] [ "type" ];
          			$attachment = $_FILES [ $fileName ] [ "tmp_name" ];
          			if ( is_uploaded_file ( $attachment ) ) { // have a file uploaded?
            				$fp = fopen ( $attachment, "rb" );
            				$data = fread ( $fp, filesize ( $attachment ) );
            				$data = chunk_split ( base64_encode ( $data ) ); //Chunk it up and encode it as base64 so it can emailed
            				fclose ( $fp );
          			}
          			$message .= "--".$mime_boundary.$eol;
          			$message .= "Content-Type: ".$attachment_type."; name=\"".$attachment_name."\"".$eol;
          			$message .= "Content-Transfer-Encoding: base64".$eol;
          			$message .= "Content-Disposition: attachment; filename=\"".
                      				$attachment_name."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
          			$message .= $data.$eol.$eol;
          			$message .= "Content-Type: multipart/alternative".$eol;
        		}
      		}
      		$message .= "--".$mime_boundary.$eol;
      		$message .= "Content-Type: ".$contentType."; charset=\"".$encoding."\"".$eol;
      		$message .= "Content-Transfer-Encoding: 8bit".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
      		$message .= $mail_content.$eol.$eol;
      		$message .= "--".$mime_boundary."--".$eol.$eol;  // finish with two eol's for better security. see Injection.
    	} else {
      		$mail_header .= "Content-Type: ".$contentType."; charset=\"".$encoding."\"".$eol;
      		$mail_header .= "Content-Transfer-Encoding: 8bit".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
      		$message .= $mail_content.$eol.$eol;
    	}
    	if(isset($mail_to) && !empty($mail_to)){
      		$arrLog [ 'mail_sender' ] = $mail_sender;
     		$arrLog [ 'mail_from' ] = $mail_from;
      		$arrLog [ 'mail_subject' ] = $mail_subject;
      		$arrLog [ 'mail_content' ] = $mail_content;
      		$arrLog [ 'mail_to' ] = $mail_to;
      		//echoLog ( $arrLog );

      		$success = mail ( $mail_to, $mail_subject, $message, $mail_header );

      		return $success;
        }
    	return false;
  	}
  
  	function word ($content) {
  		header("Content-Type: text/csv; charset=UTF-8");
  		header("Pragma:");
  		header("Cache-Control: private,max-age=3600");
  		header("Content-Disposition: attachment; filename=msdoc.doc");
  		header("Content-Transfer-Encoding: binary");
  		header("Expires: 0");
  		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
  		$strBody = 	"<html xmlns:x='urn:schemas-microsoft-com:office:word'>".
  					"<head><meta http-equiv=Content-Type content='text/csv; charset=utf-8'><style>".
  					"table{border:none;} .invoice {width:100%;}.invoice .head {font-weight:bold;}
  					 .invoice .owner_name{font-size:14pt;font-weight:bold;}
					 .invoice .cart {width:100%;border-collapse:collapse;border:none;}
					 .invoice .cart .column{font-weight:bold;background-color:#1973BA;color:#FFFFFF;}
					 .invoice .cart td{border:1px solid #7F9BB9;height:25px;text-align:center;padding:0 1px;word-wrap:break-word;overflow:hidden;}
					 .invoice .cart .title{font-weight:bold;text-align:left;padding-left:2px;}
					 .invoice .cart .rgt{text-align:right;}.invoice .cart .ttl{text-align:right;font-weight:bold;}
					 .invoice_title {font-weight:bold;font-size:14pt;}
					 .signature {padding-top:10px;width:50%;border-bottom:1px solid #000000;text-align:left;font-weight:bold;}".
  					"</style></head><body>";
 		$strBody .= $content;
 		$strBody .= "</table></body></html>";
 		return $strBody;
  	}
  	
  	function getLiveDiscounts() {
  		$model = 'Discount';
  		$arrRes = array();
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			$params = array(
  				'recursive' => -1,
  				'fields' => array('id', 'dis_name', 'dis_expiry'),
  				'conditions' => array(
  					'dis_on' => 1,
  					'OR' => array(
  						array('dis_expiry' => NULL),
  						array('dis_expiry >= NOW()')
  					)
  				)
  			);
  			$arrRes = $objModel->find('all', $params);
  		}
  		
  		return $arrRes;
  	}
  	
  	function getLiveRewards() {
  		$model = 'Reward';
  		$arrRes = array();
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			$params = array(
  					'recursive' => -1,
  					'fields' => array('id', 'rew_name', 'rew_expiry'),
  					'conditions' => array(
  							'rew_on' => 1,
  							'OR' => array(
  									array('rew_expiry' => NULL),
  									array('rew_expiry >= NOW()')
  							)
  					)
  			);
  			$arrRes = $objModel->find('all', $params);
  		}
  	
  		return $arrRes;
  	}
  	
  	function getLiveVouchers() {
  		$model = 'Voucher';
  		$arrRes = array();
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			$params = array(
  					'recursive' => -1,
  					'fields' => array('id', 'vou_name', 'vou_expiry'),
  					'conditions' => array(
  							'vou_on' => 1,
  							'OR' => array(
  									array('vou_expiry' => NULL),
  									array('vou_expiry >= NOW()')
  							)
  					)
  			);
  			$arrRes = $objModel->find('all', $params);
  		}
  	
  		return $arrRes;
  	}
  	
  	function getLogisticsCompanies() {
  		$model = 'LogisticsCompany';
  		$arrRes = array();
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			$params = array(
  					'recursive' => -1,
  					'fields' => array('id', 'logi_company', 'logi_alias'),
  					'order' => array('logi_company' => 'asc')
  			);
  			$arrRes = $objModel->find('all', $params);
  		}
  		 
  		return $arrRes;
  	}
  	
  	function getCategories($parentId = 0) {
  		$model = 'Category';
  		$arrRes = array();
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			$param = array(
  				'conditions' => array('parent_id' => $parentId),
  				'recursive' => 0,
  				'order' => array('CategoryDetail.name')
  			);
  			$data = $objModel->find('all', $param);
  		
  			$arrOptions = array ();
  			foreach ( $data as $key => $option ) {
  				$arrRes [ $option [ 'Category' ][ 'id' ] ]= $option [ 'CategoryDetail' ][ 'name' ];
  			}
  		}
  		
  		return $arrRes;
  	}
  	function getSelectedCategories ($left,$right) {
  		$model = 'Category';
  		$arrCats = array ();
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			$params = array(
  				'conditions' => array('lorder <=' => $left, 'rorder >=' . $right),
  				'order' => array('lorder'),
  				'recursive' => -1
  			);
  			$data = $objModel->find('all', $params);
  	
  			$lastLevelCat = '';
  			foreach ( $data as $key=>$val ) {
  				$arrCats [$key]['sel'][$val['Category']['id']]	= $val['Category']['id'];
  				$lastLevelCat = $val['Category']['id'];
  				$params = array(
  					'conditions' => array('parent_id' => $val['Category']['parent_id']),
  					'fields' => array('id', 'CategoryDetail.name'),
  					'order' => array('CategoryDetail.name'),
  					'recursive' => 0
  				);
  				$arrTmp = $objModel->find('all', $params);
  				
  				$arrOptions = array ();
  				foreach ( $arrTmp as $index => $option ) {
  					$arrOptions [ $option [ 'Category' ][ 'id' ] ]= $option [ 'CategoryDetail' ][ 'name' ];
  				}
  				$arrCats [$key]['list'] = $arrOptions;
  			}
  	
  			$params = array(
  				'conditions' => array('parent_id' => $lastLevelCat),
  				'fields' => array('id', 'CategoryDetail.name'),
  				'order' => array('CategoryDetail.name'),
  				'recursive' => 0
  			);
  			$arrTmp = $objModel->find('all', $params);
  	
  			if (count($arrTmp) > 0) {
  				$index = count($arrCats);
  				$arrCats [$index]['sel'] = array(0);
  				$arrOptions = array ();
  				foreach ( $arrTmp as $option ) {
  					$arrOptions [ $option [ 'Category' ][ 'id' ] ]= $option [ 'CategoryDetail' ][ 'name' ];
  				}
  				$arrCats [$index]['list'] = $arrOptions;
  			}
  		}
  		
  		return $arrCats;
  	}
  	/*New Category Hierechy for 3 Levels Structure*/
  	public function generateTreeByLevels($maxLevel=1, $level=0, $parentId = 0) {
  		$model = 'Category';
  		$arrCats = array ();
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			
	  		if ($level <= $maxLevel) {
	  			$param = array(
	  					'recursive' => 0,
	  					// is_node = 0 means don't show category on the website
	  					'conditions' => array('parent_id' => $parentId, 'is_node' => 1),
	  					'fields' => array(
	  							'id','lorder','rorder','CategoryDetail.name','CategoryDetail.category_alias',
	  							'CategoryDetail.icon_name'
	  					),
	  					'order' => array('CategoryDetail.name')
	  			);
	  	
	  			$arrRes = $objModel->find('all', $param);
	  			foreach($arrRes as $node) {
	  				$tmp = $node['CategoryDetail'];
	  				if ( $node['Category']['rorder'] - $node['Category']['lorder'] > 1 ) {
	  					$newLevel = $level + 1;
	  					$pid = $node['Category']['id'];
	  					$tmp['children'] = $this->generateTreeByLevels($maxLevel, $newLevel, $pid);
	  				}
	  				$arrCats[] = $tmp;
	  			}
	  		}
  		}
  	
  		return $arrCats;
  	}
  	
  	function getProductSerialNo($supplierId) {
  		$model = 'Product';
		$serialNo = '';
		
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			
  			$objModel->recursive = -1;
  			$serial = $objModel->findBySupplierId($supplierId, array('MAX(serial_no)+1 AS serial_no'));
  			if (!empty($serial[0]['serial_no'])) {
  				$serialNo = $serial[0]['serial_no'];
  			} else {
  				$objModel->Supplier->recursive = -1;
  				$sup_serial = $objModel->Supplier->findById($supplierId, array('identifier'));
  				$serialNo = $sup_serial['Supplier']['identifier'] . '0001';
  			}
  			$zeros = 8 - strlen($serialNo);
  			for ($i=0; $i < $zeros; $i++) {
  				$serialNo = '0' . $serialNo;
  			}
  		}
  	
  		return $serialNo;
  	}
	
	function getItemIdBySerialNo($serialNo) {
		$model = 'Product';
		$itemId = '';
		
  		if (App::import('Model', $model)) {
  			$objModel = new $model;
  			
  			$objModel->recursive = -1;
  			$item = $objModel->findBySerialNo($serialNo);
  			if (isset($item['Product']['id']) && !empty($item['Product']['id'])) {
  				$itemId = $item['Product']['id'];
  			} 
  		}
  	
  		return $itemId;
	}

    private function buildCart($cartItems) {
        $model = $this->getModel('Product');

		$delivery_fees = SHIPPING_USERDEFINED_BASIC;
		$total = 0;
		$ttlQty = 0;
		$totalCount = 0;
		$returnItems = array();
		
		foreach($cartItems as $item) {
			$model->recursive = -1;
			$prod = $model->findById($item['CartItem']['product_id']);
			$ttlQty += $item['CartItem']['qty'];
			$price = !empty($prod['Product']['deal_price']) ? $prod['Product']['deal_price'] : $prod['Product']['price'];
			$subTotal = $price * $item['CartItem']['qty'];
			$total += $subTotal;
			
			$item['CartItem']['serial_no'] = $prod['Product']['serial_no'];
			$item['CartItem']['price'] = number_format($price, 2);
			$item['CartItem']['total'] = number_format($subTotal, 2); 
			$item['CartItem']['name'] = $prod['Product']['name']; 
			$item['CartItem']['product_alias'] = $prod['Product']['product_alias']; 
			$returnItems[] = $item;
		}
		$totalCount = $ttlQty;
		if ($ttlQty > SHIPPING_USERDEFINED_MAX) {
			$ttlQty = SHIPPING_USERDEFINED_MAX;
		}
		$extraQty = $ttlQty - 1;
		$shipping = SHIPPING_USERDEFINED_BASIC + $extraQty * SHIPPING_USERDEFINED_PERITEM;
		
		$cart = new stdClass();
		$cart->total = number_format($total, 2);
		$cart->shipping = $shipping;
		$cart->items = $returnItems;
		$cart->totalCount = $totalCount;
		
		return $cart;
	}

    public function getCart($session) {
        $objCartItem = $this->getModel('CartItem');
		$cartItems = array();
		$cart = array();
		
		if (!$session->valid()) {
			$session->renew();
    	}
   	 	if (!$session->check('sess_cart')) {
    		$cart = new stdClass();
			$cart->total = 0.0;
			$cart->shipping = 0.0;
			$cart->items = array();
			$cart->totalCount = 0;
    	} else {
    		$sessionId = $session->read('sess_cart');
    	}
		
		if (isset($sessionId) && !empty($sessionId)) {
			$params = array(
				'conditions' => array('session_id' => $sessionId), 
				'recusrive' => -1,
				'fields' => array('product_id', 'qty')
			);
			//$this->CartItem->bindModel(array(
			//	'belongsTo' => array('Product')
			//));
			$cartItems = $objCartItem->find('all', $params);
			$cart = $this->buildCart($cartItems);
		}
		
    	return $cart;
	}
}
?>
