<?php
class OrderComponent extends Object {
    private function getModel($modelName) {
        $objModel = null;
        $model = ucwords($modelName);
        if (App::import('Model', $model)) {
            $objModel = new $model;
        }

        return $objModel;
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

    public function getCart() {
        $objCartItem = $this->getModel('CartItem');
		$cartItems = array();
		$cart = array();
		
		if (!$this->Session->valid()) {
			$this->Session->renew();
    	}
   	 	if (!$this->Session->check('sess_cart')) {
    		$cart = new stdClass();
			$cart->total = 0.0;
			$cart->shipping = 0.0;
			$cart->items = array();
			$cart->totalCount = 0;
    	} else {
    		$sessionId = $this->Session->read('sess_cart');
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