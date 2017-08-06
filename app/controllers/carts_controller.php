<?php
class CartsController extends AppController {
	var $uses = array ('Product', 'CartItem');
	var $helpers = array ('Javascript');
	var $components = array('Session');
	var $layout = 'nobanner_fullsize';
	var $response = array('status' => 0, 'errorCode' => 0, 'errorMsg' => '', 'data' => '');
	
	/*Delivery fee list. all products should convert to  ***
	 **weight-based delivery fee. The unit is g.
	 **Weight > 6kg (6000g), will add $1($fee_factor) per kg.
	 */
	var $letter_fees = array(
		array('weight'=>250, 'fee'=> 1.80),
		array('weight'=>500, 'fee' => 3.00),
		array('weight'=>1000, 'fee' => 10.50),
		array('weight'=>2000, 'fee' => 12.80),
		array('weight'=>3000, 'fee' => 14.35),
		array('weight'=>4000, 'fee' => 15.85),
		array('weight'=>5000, 'fee' => 17.35)
	);
	var $parcel_fees = array(
		array('weight'=>250, 'fee'=> 4.55),
		array('weight'=>500, 'fee' => 5.80),
		array('weight'=>1000, 'fee' => 10.50),
		array('weight'=>2000, 'fee' => 12.80),
		array('weight'=>3000, 'fee' => 14.35),
		array('weight'=>4000, 'fee' => 15.85),
		array('weight'=>5000, 'fee' => 17.35)
	);
	var $fee_factor = 1;
	var $weight_max = 5000;
	var $fee_max = 17.35;
	var $brasa_fee  = 5.00; //only for chicken delivery

	function beforeFilter () {
	 	parent::beforeFilter();
		
		$this->Auth->allow('*');
	}
	
	private function isPositiveInt ($num) {
		$reg = "/^[1-9][0-9]*$/";
		if ( !preg_match($reg, $num) ) {
			return false;
		}
		else return true;
	}
	
	private function calAmount (&$cart) {
		if (count($cart['items']) == 0 ) $cart['businessCode'] = 0;
		$amount = $shipping = 0.0;
		if ($cart['businessCode'] == BUSINESS_BRASA_DELIVERY) {
			foreach ($cart['items'] as $prod) {
				$amount += $prod['subtotal']; 
				$shipping = $this->brasa_fee;
			}
		} else {
			foreach ($cart['items'] as $prod) {
				$amount += $prod['subtotal'];
				$shipping += $prod['shipping'];
			}
		}
		$cart['totalAmount'] = $amount;
		$cart['totalShipping'] = $shipping;
		
		return $cart;
	}
	
	function getCart() {
		return $this->Utility->getCart($this->Session);
	}
	
	function view () {
		$cart = $this->getCart();
   		$this->set ('cart',$cart);
		
		if (!empty($cart->cartError)) {
			$this->set('cart_error', $cart->cartError);
			$cart->cartError = '';
			$this->Session->write('sess_cart', $cart);
		}
	}
	
	function add ($pid = 0, $subid = 0) {
		$cart = $this->addOne($pid, $subid);
		$this->calAmount($cart);
    	$this->Session->write('sess_cart',$cart);
		
    	$this->redirect('/carts/view');
    	exit();
	}
	
	function ajaxLoadCart () {
		if ($this->RequestHandler->isAjax()) {
			$cart = $this->getCart();
			$this->response['status'] = 1;
			$this->response['data'] = array(
				'ajaxCountItem' => $cart->totalCount, 
				'ajaxCartData' => $cart
			);
			
			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
	
	function ajaxAdd ($pid = 0, $qty =1) {
		if ($this->RequestHandler->isAjax()) {
			$form = $this->params['form'];
			$itemNo = isset($form['product']) && !empty($form['product']) ? $form['product'] : $pid;
			$quantity = isset($form['qty']) && !empty($form['qty']) ? $form['qty'] : $qty;

			if ($itemNo) {
				$this->Product->recursive = -1;
				$prod = $this->Product->findBySerialNo($itemNo, array('id', 'product_alias', 'name'));
				$itemId = $prod['Product']['id'];

				if (!$this->Session->valid()) {
					$this->Session->renew();
				}
				if (!$this->Session->check('sess_cart')) {
					$sessionId = $this->gainGUID('CartItem', 'session_id');
					$this->Session->write('sess_cart', $sessionId);
				} else {
					$sessionId = $this->Session->read('sess_cart');
				}

				if ($sessionId) {
					$params = array('conditions' => array('session_id' => $sessionId), 'recusrive' => -1);
					$cartItems = $this->CartItem->find('all', $params);

					if ($cartItems && count($cartItems) > 0) {
						foreach($cartItems as $item) {
							if ($item['CartItem']['product_id'] == $itemId) {
								$data = $item;
								if ($data['CartItem']['qty']) $data['CartItem']['qty'] += $quantity;
								else $data['CartItem']['qty'] = $quantity;
								
								break;
							}
						}
					} 
					if (!isset($data)) {
						$data['CartItem']['session_id'] = $sessionId;
						$data['CartItem']['product_id'] = $itemId;
						$data['CartItem']['qty'] = $quantity;
					}
					
					if ($data) $this->CartItem->save($data);
				}
			}
			
			$cart = $this->getCart();
			$this->response['status'] = 1;
			$this->response['data'] = array(
				'ajaxAddedItem' => $prod['Product'],
				'ajaxCountItem' => $cart->totalCount, 
				'ajaxCartData' => $this->getCart()
			);
			
			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
	function ajaxUpdate() {
		if ($this->RequestHandler->isAjax()) {
			$form = $this->params['form'];
			$cart = isset($form['cart']) && !empty($form['cart']) ? $form['cart'] : null;
			$itemNo = isset($form['product_id']) && !empty($form['product_id']) ? $form['product_id'] : '';
			$qty = isset($form['product_qty']) && !empty($form['product_qty']) ? $form['product_qty'] : 1;
			
			if ($itemNo && $qty) {
				$prodId = $this->Utility->getItemIdBySerialNo($itemNo);
				$cart[$prodId]['qty'] = $qty;
			}
			
			if ($cart) {
				if (!$this->Session->valid()) {
					$this->Session->renew();
				}
				if (!$this->Session->check('sess_cart')) {
					$sessionId = $this->gainGUID('CartItem', 'session_id');
					$this->Session->write('sess_cart', $sessionId);
				} else {
					$sessionId = $this->Session->read('sess_cart');
				}

				if ($sessionId) {
					$params = array('conditions' => array('session_id' => $sessionId), 'recusrive' => -1);
					$cartItems = $this->CartItem->find('all', $params);

					if ($cartItems && count($cartItems) > 0) {
						foreach($cartItems as $item) {
							$itemId = $item['CartItem']['product_id'];
							$newQty = 1;
							
							if ( isset($cart[$itemId]['qty']) && is_numeric($cart[$itemId]['qty']) ) {
								$newQty = $cart[$itemId]['qty'];
							}
							$this->CartItem->id = $item['CartItem']['id'];
							$this->CartItem->saveField('qty', $newQty);
						}
					} 
				}
			}
			
			$cart = $this->getCart();
			$this->response['status'] = 1;
			$this->response['data'] = array(
				'ajaxCountItem' => $cart->totalCount, 
				'ajaxCartData' => $this->getCart()
			);
			
			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
	function ajaxDelete() {
		if ($this->RequestHandler->isAjax()) {
			$form = $this->params['form'];
			$itemNo = isset($form['product_id']) && !empty($form['product_id']) ? $form['product_id'] : '';

			if ($itemNo) {
				$prodId = $this->Utility->getItemIdBySerialNo($itemNo);
				
				if (!$this->Session->valid()) {
					$this->Session->renew();
				}
				if (!$this->Session->check('sess_cart')) {
					$sessionId = $this->gainGUID('CartItem', 'session_id');
					$this->Session->write('sess_cart', $sessionId);
				} else {
					$sessionId = $this->Session->read('sess_cart');
				}

				if ($sessionId) {
					$params = array('conditions' => array('session_id' => $sessionId), 'recusrive' => -1);
					$cartItems = $this->CartItem->find('all', $params);

					if ($cartItems && count($cartItems) > 0) {
						foreach($cartItems as $item) {
							if ( $item['CartItem']['product_id'] == $prodId) {
								$this->CartItem->delete($item['CartItem']['id']);
								break;
							}
						}
					} 
				}
			}
			
			$cart = $this->getCart();
			$this->response['status'] = 1;
			$this->response['data'] = array(
				'ajaxCountItem' => $cart->totalCount, 
				'ajaxCartData' => $this->getCart()
			);
			
			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
		
	function update () {
		if (!$this->Session->valid()) {
            $this->Session->renew();
        }
        if (!$this->Session->check('sess_cart')) {
            return false;
        } 
        $cart = $this->Session->read('sess_cart');
        foreach ($cart['items'] as $key => $prod) {
        	if ( isset ($this->data['qty'][$key]) && $this->isPositiveInt($this->data['qty'][$key])) {
        		$cart['items'][$key]['quantity'] = $this->data['qty'][$key];
        	} else {
        		$cart['items'][$key]['quantity'] = 1;
        	}
        	$cart['items'][$key]['subtotal'] = $cart['items'][$key]['quantity'] * $cart['items'][$key]['amount'];
        	$cart['items'][$key]['shipping'] = $this->getShippingFee($cart['items'][$key]);
        }
        
        $this->calAmount($cart);
        $this->Session->write('sess_cart',$cart);
        if (isset($this->params['pass'][0]) && $this->params['pass'][0] == 'payment') {
        	$this->redirect('/checkout/payment');
        } else {
        	$this->redirect('/carts/view');
        }
        exit();
	}
	
	function remove ($itemid) {
		if (!$this->Session->valid()) {
            $this->Session->renew();
        }
        if (!$this->Session->check('sess_cart')) {
            return false;
        } 
        $cart = $this->Session->read('sess_cart');
        foreach ($cart['items'] as $key => $prod) {
        	if (trim($key) === trim($itemid)) { //must use ===, otherwise 12=='12-1'
        		unset($cart['items'][$key]);
        		break;
        	}
        }
		//$this->setRemovedItems($cart['items'], $itemid); //only for brasa delivery
		
        $this->calAmount($cart);
        $this->Session->write('sess_cart',$cart);
        $this->redirect('/carts/view');	
        exit();
	}
	
	function clear () {
		if (!$this->Session->valid()) {
            $this->Session->renew();
        }
        if ($cart=$this->Session->check('sess_cart')) {
			//$this->setRemovedItems($cart['items']); //only for brasa delivery
            $this->Session->delete('sess_cart');
        } 
        $this->render('view');
	}
	
	private function setRemovedItems($cartItems, $removedId=0) {
		if ($cart['businessCode'] == BUSINESS_BRASA_DELIVERY) {
			$cart_removed = $this->Session->read('sess_cart_removed');
			if (!empty($removedId)) {
				foreach ($cartItems as $key => $prod) {
					if (trim($key) === trim($removedId)) { 
						$cart_removed[] = $key;
					}
				}
			} else {
				foreach ($cartItems as $key => $prod) {
					$cart_removed[] = $key;
				}
			}
			$this->Session->write('sess_cart_removed', $cart_removed);
		}
	}
}
?>
