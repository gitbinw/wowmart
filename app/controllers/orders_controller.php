<?php
require_once(APP_VENDORS_PAYPAL . DS . 'vendor' . DS . 'autoload.php');

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Address;
use \PayPal\Api\ShippingAddress;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class OrdersController extends AppController {
	var $uses = array ('Order', 'Invoice', 'Contact', 'Store');
	var $helpers = array ('Javascript','Combobox','Utility', 'Fpdf');
	var $components = array ('Utility', 'Session', 'RequestHandler');
	var $strListName = 'thisItem';
	var $layout = 'default';
	
	private function generateOrderNumber () {
		$newNo = time() . $this->Auth->user('id');
		return $newNo;
	}

	private function sendConfirmEmail($order) {
		$this->set('order', $order);
		$this->Email->to = $order['User']['email'];
		$this->Email->subject = 'Freshla Order Confirmation';
		$this->Email->replyTo = EMAIL_SALES_REPLY;
		$this->Email->from = EMAIL_SALES_FROM;
		$this->Email->template = 'order_place'; // note no '.ctp'
		//Send as 'html', 'text' or 'both' (default is 'text')
		$this->Email->sendAs = 'both'; // because we like to send pretty mail
		$this->Email->send();
                
        if ($order['Order']['pay_method'] == PAYMENT_METHOD_CASH) {//if pay cash, directly send order to suppliers
			$this->Email->reset();
			$this->Email->to = EMAIL_SALES_TO;
			$this->Email->bcc = explode(',', EMAIL_BCC);
			$this->Email->subject = 'New Order from Freshla';
			if ($order['Order']['business_code'] == BUSINESS_BRASA_DELIVERY) {
				$this->Email->subject = 'New Brasa Delivery Order';
			}
			$this->Email->replyTo = EMAIL_SALES_REPLY;
			$this->Email->from = EMAIL_SALES_FROM;
			$this->Email->template = 'order_notify'; // note no '.ctp'
			//Send as 'html', 'text' or 'both' (default is 'text')
			$this->Email->sendAs = 'both'; // because we like to send pretty mail
			$this->Email->send();
		}
	} 

	
	/**
	* Helper method for getting an APIContext for all calls
	* @param string $clientId Client ID
	* @param string $clientSecret Client Secret
	* @return PayPal\Rest\ApiContext
	*/
	private function getApiContext($clientId, $clientSecret) {

		// #### SDK configuration
		// Register the sdk_config.ini file in current directory
		// as the configuration source.
		/*
		if(!defined("PP_CONFIG_PATH")) {
			define("PP_CONFIG_PATH", __DIR__);
		}
		*/


		// ### Api context
		// Use an ApiContext object to authenticate
		// API calls. The clientId and clientSecret for the
		// OAuthTokenCredential class can be retrieved from
		// developer.paypal.com

		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				$clientId,
				$clientSecret
			)
		);

		// Comment this line out and uncomment the PP_CONFIG_PATH
		// 'define' block if you want to use static file
		// based configuration

		$apiContext->setConfig(
			array(
				'mode' => 'sandbox',
				'log.LogEnabled' => true,
				'log.FileName' => '../PayPal.log',
				'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
				'cache.enabled' => true,
				// 'http.CURLOPT_CONNECTTIMEOUT' => 30
				// 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
				//'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
			)
		);

		// Partner Attribution Id
		// Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
		// To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
		// $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

		return $apiContext;
	}
	
	function paypalStart() {
		$apiContext = $this->getApiContext(PAYPAL_SANDBOX_CLIENT_ID, PAYPAL_SANDBOX_CLIENT_SECRET);

		$payer = new Payer();
		$payer->setPaymentMethod("paypal");

		if(isset($_REQUEST['csrf']) && $_REQUEST['csrf'] == $this->Session->read('csrf')) {
			$order =  $this->Utility->getCart($this->Session);
			$shippingAddress = $this->getAddress($this->params['form']['shipping_address_id']);
			$shipAddress = $shippingAddress['Contact'];
			$countryCode = !isset($shipAddress['country']) || empty($shipAddress['country']) ? 'AU' : $shipAddress['country'];
			$arrNames = array();
			if (isset($shipAddress['firstname']) && !empty($shipAddress['firstname'])) $arrNames[] = $shipAddress['firstname'];
			if (isset($shipAddress['lastname']) && !empty($shipAddress['lastname'])) $arrNames[] = $shipAddress['lastname'];
			$shipName = implode(' ', $arrNames);

			$arrPaypalItems = array();

			foreach($order->items as $item) {
				$payPalItem = new Item();
				$payPalItem->setName($item['CartItem']['name'])
							->setCurrency('AUD')
							->setQuantity($item['CartItem']['qty'])
							->setSku($item['CartItem']['serial_no']) 
							->setPrice($item['CartItem']['price']);
				
				$arrPaypalItems[] = $payPalItem;
			}

			$shipping_address = new ShippingAddress();

			$shipping_address->setCity($shipAddress['suburb']);
			$shipping_address->setCountryCode($countryCode);
			$shipping_address->setPostalCode($shipAddress['postcode']);
			$shipping_address->setLine1($shipAddress['address1']);
			$shipping_address->setState($shipAddress['state']);
			$shipping_address->setRecipientName($shipName);

			$itemList = new ItemList();
			$itemList->setItems($arrPaypalItems)
					 ->setShippingAddress($shipping_address);
			
			$details = new Details();
			$details->setShipping($order->shipping)
					->setTax(0)
					->setSubtotal($order->total);
					
			$amount = new Amount();
			$amount->setCurrency("AUD")
					->setTotal((float)$order->total + (float)$order->shipping)
					->setDetails($details);
			
			$transaction = new Transaction();
			$transaction->setAmount($amount)
						->setItemList($itemList)
						->setDescription("Payment description")
						->setInvoiceNumber($this->generateOrderNumber());
			
			$baseUrl =  $this->Utility->getBaseUrl();
			$redirectUrls = new RedirectUrls();
			$redirectUrls->setReturnUrl("$baseUrl/orders/paypalExecutePay?success=true")
						 ->setCancelUrl("$baseUrl/orders/paypalExecutePay?success=false");
						
			$payment = new Payment();
			$payment->setIntent("sale")
					->setPayer($payer)
					->setRedirectUrls($redirectUrls)
					->setTransactions(array($transaction));

			try {
				$payment->create($apiContext);
			} catch (Exception $ex) {

				exit(1);
			}

			$approvalUrl = $payment->getApprovalLink();
			
		}

		$this->autoRender = false;

		return $payment;

		die();
	}

	function paypalExecutePay() {
		$apiContext = $this->getApiContext(PAYPAL_SANDBOX_CLIENT_ID, PAYPAL_SANDBOX_CLIENT_SECRET);

		if (isset($_REQUEST['success']) && $_REQUEST['success'] == 'true') {

			if(isset($_REQUEST['csrf']) && $_REQUEST['csrf'] == $this->Session->read('csrf')) {
				// Get the payment Object by passing paymentId
				// payment id was previously stored in session in
				// CreatePaymentUsingPayPal.php
				$paymentId = $_REQUEST['paymentID'];
				$payment = Payment::get($paymentId, $apiContext);

				// ### Payment Execute
				// PaymentExecution object includes information necessary
				// to execute a PayPal account payment.
				// The payer_id is added to the request query parameters
				// when the user is redirected from paypal back to your site
				$execution = new PaymentExecution();
				$execution->setPayerId($_REQUEST['payerID']);

				// ### Optional Changes to Amount
				// If you wish to update the amount that you wish to charge the customer,
				// based on the shipping address or any other reason, you could
				// do that by passing the transaction object with just `amount` field in it.
				// Here is the example on how we changed the shipping to $1 more than before.
				$transaction = new Transaction();
				$amount = new Amount();
				$details = new Details();

				$order =  $this->Utility->getCart($this->Session);

				$details->setShipping($order->shipping)
						->setTax(0)
						->setSubtotal($order->total);

				$amount->setCurrency("AUD")
						->setTotal((float)$order->total + (float)$order->shipping)
						->setDetails($details);

				$transaction->setAmount($amount);

				// Add the above transaction object inside our Execution object.
				$execution->addTransaction($transaction);

				$this->autoRender = false;

				try {
					// Execute the payment
					// (See bootstrap.php for more on `ApiContext`)
					$result = $payment->execute($execution, $apiContext);

					try {
						$payment = Payment::get($paymentId, $apiContext);
						$arrPayment = json_decode($payment);
						$this->placeOrder($arrPayment);

					} catch (Exception $ex) {

						exit(1);
					}

				} catch (Exception $ex) {
					
					exit(1);
				}

				return $payment;

			}

		} else {

			exit;
		}

		die();
	}

	private function placeOrder($orderData) {
		if ($this->Auth->user()) {
			if ($orderData && !empty($orderData)) {
				$cart = $this->Utility->getCart($this->Session);

				if (isset($cart['items']) && count($cart['items']) > 0) {
		
					$data['Order']['user_id'] = $this->Auth->user('id');
					$data['Order']['order_no'] = $orderData->orderNumber;
					$data['Order']['is_paid'] = 1;
					$data['Order']['status_id'] = TYPE_ORDER_PAID;
					$data['Order']['subtotal']= $orderData->subtotal;
					$data['Order']['freight']= $orderData->shipping;
					$data['Order']['total_amount'] = $orderData->total;

					$data['Billing' ] = $orderData->billing;
					$data['Shipping'] = $orderData->shipping;
					
					$index = 0;
					foreach($orderData->items as $item) {
						$subitemId = !empty($item['subitem_id']) ? $item['subitem_id'] : 0;
						$data['OrdersProduct'][$index]['product_id'] = $item['product_id'];
						$data['OrdersProduct'][$index]['quantity'] = $item['quantity'];
						$data['OrdersProduct'][$index]['prod_desc'] = $item['item_name'];
						$data['OrdersProduct'][$index]['subtotal'] = $item['subtotal'];
						$data['OrdersProduct'][$index]['deal_price'] = $item['amount'];
						$data['OrdersProduct'][$index]['freight'] = $item['shipping'];
						$data['OrdersProduct'][$index]['subproduct_id'] = $subitemId;
						$index ++;
					}
					$this->Order->bindModel(array('hasMany'=>array('OrdersProduct')), false);
					if ($this->Order->saveAll($data)) {
						$this->Utility->clearCart($this->Session);
					}
				}
			}
		}
	}

	private function afterPayment() {
		$this->Order->read(null, $transaction['InstantPaymentNotification']['custom']);
		$this->Order->set(array('is_paid' => 1, 'status_id' =>TYPE_ORDER_PAID));
		$this->Order->save();
  
		$this->Order->unbindModel(array('belongsTo' => array('Status', 'Invoice')));
		$this->Order->User->unbindModel(array(
			'hasAndBelongsToMany' => array('Group'),
			'hasMany' => array('Contact', 'Order')
		));
		$this->Order->hasAndBelongsToMany['Product']['fields'] = array('id', 'name', 'serial_no');
		$this->Order->Product->unbindModel(array(
				'hasMany' => array('Media', 'Document', 'Feature'),
				'hasAndBelongsToMany' => array('Category', 'Type')
		));
		$this->Order->Product->hasMany['Image']['conditions']['is_default'] = 1;

		$params = array(
			'conditions' => array(
				'Order.id' =>  $transaction['InstantPaymentNotification']['custom'],
				'Order.status_id' => TYPE_ORDER_PAID
			),
			'recursive' => 2
		);
		if ($order = $thisOrder->find('first', $params)) {
			$this->sendPaymentEmail($order);
		}
	}
	private function getAddress($addressId) {
		$params = array(
				'conditions' => array(
					'user_id' => $this->Auth->user('id'), 
					'id' => $addressId
				),
				'recursive' => -1
		);
		$address = $this->Contact->find('first', $params);

		return $address;
	}
	function checkout () {
		if ($this->Auth->user()) {
			$params = array(
				'conditions' => array(
					'user_id' => $this->Auth->user('id'),
					'is_billing' => 1
				),
				'recursive' => -1,
				'order' => array('created DESC')
			);
			$billings = $this->Contact->find('all', $params);
			
			$params = array(
				'conditions' => array(
					'user_id' => $this->Auth->user('id'),
					'is_shipping' => 1
				),
				'recursive' => -1,
				'order' => array('created DESC')
			);
			$shippings = $this->Contact->find('all', $params);
			
			$csrf = bin2hex(openssl_random_pseudo_bytes(32));
			$this->Session->write('csrf', $csrf);
			$this->set('billings', $billings);
			$this->set('shippings', $shippings);
			$this->set('order_items', $this->Utility->getCart($this->Session));
			$this->set('csrf', $csrf);
		
			$this->layout = 'checkout';
			$this->render('checkout');
		}
	}
	
	function thankyou() {
		if ($this->Session->check('Order.orderId')) {
			$orderId = $this->Session->read('Order.orderId');
			$this->Order->updateAll(
				array('Order.status_id' => TYPE_ORDER_PAY_REVIEW),
				array('Order.status_id' => TYPE_ORDER_NOT_PAID, 'Order.id' => $orderId)
			);
			
			$this->Order->unbindModel(array('belongsTo' => array('Status', 'Invoice')));
			$this->Order->User->unbindModel(array(
				'hasAndBelongsToMany' => array('Group'),
				'hasMany' => array('Contact', 'Order'),
				'hasOne' => array('Supplier')
			));
			$this->Order->hasAndBelongsToMany['Product']['fields'] = array('id', 'name', 'serial_no');
			$this->Order->Product->unbindModel(array(
				'hasMany' => array('Media', 'Document', 'Feature'),
				'hasAndBelongsToMany' => array('Category', 'Type')
			));
			$this->Order->Product->hasMany['Image']['conditions']['is_default'] = 1;
			
			$params = array(
				'conditions' => array(
					'Order.user_id' => $this->Auth->user('id'),
					'Order.id'		  =>  $orderId,
					'Order.status_id NOT' => TYPE_ORDER_NOT_PAID
				),
				'recursive' => 2
			);
			if ($order = $this->Order->find('first', $params)) {
				$this->sendConfirmEmail($order);
				$this->set('store', $this->Store->findByAlias(STORE_NAME));
				$this->set('order', $order);
				
				$this->Session->delete('Order');
				$this->Session->delete('sess_cart');
			}
		}
	}
	
	function view() {
		if (isset($this->data['Order']['id']) && !empty($this->data['Order']['id'])) {
			$orderId = $this->data['Order']['id'];
		} else if (isset($this->params['pass'][0]) && !empty($this->params['pass'][0])) {
			$orderId = $this->params['pass'][0];
		}
		if (isset($orderId) && !empty($orderId)) {
			$params = array(
				'conditions' => array(
					'Order.user_id' => $this->Auth->user('id'),
					'Order.id'		  => $orderId
				),
				'recursive' => 2
			);
			$this->Order->unbindModel(array('belongsTo' => array('User', 'Status', 'Invoice')));
			$this->Order->hasAndBelongsToMany['Product']['fields'] = array('id', 'name', 'serial_no', 'product_alias', 'supplier_id');
			$this->Order->Product->belongsTo['Supplier']['fields'] = array('id','subdomain','biz_name');
			$this->Order->Product->unbindModel(array(
				'hasMany' => array('Media', 'Document', 'Feature', 'Freight'),
				'hasAndBelongsToMany' => array('Category', 'Type')
			));
			$this->Order->Product->hasMany['Image']['conditions']['is_default'] = 1;
			$order = $this->Order->find('first', $params);
			
			if ($this->RequestHandler->isAjax()) {
				$this->set('is_view', true);
				$this->set('store', $this->Store->findByAlias(STORE_NAME));
				$this->set('order', $order);
				$this->render('thankyou', 'ajax');
			
			} else {
				
				$this->Session->write('Order.Shipping', $order['Shipping']);
				$this->Session->write('Order.Billing', $order['Billing']);
				$this->Session->write('Order.orderId', $order['Order']['id']);
	
				/****Assemble shopping cart data*****/
				$cart = array ('items'=>array(),'totalAmount'=>0.0,'totalShipping'=>0.0);
    		foreach($order['Product'] as $prod) {
    			$index = $prod['id'];
    			if (!empty($prod['OrdersProduct']['subproduct_id'])) {
    				$index = $prod['id'] . "-" . $prod['OrdersProduct']['subproduct_id'];
    				$this->Order->Product->Subproduct->recursive = -1;
    				$subitem = $this->Order->Product->Subproduct->findById($prod['OrdersProduct']['subproduct_id']);
    				$prod['name'] = $prod['name'] . " " .$subitem['Subproduct']['name'];
    			}
    			$cart['items'][$index]['product_id'] = $prod['OrdersProduct']['product_id'];
    			$cart['items'][$index]['subitem_id'] = $prod['OrdersProduct']['subproduct_id'];
    			$cart['items'][$index]['subdomain'] = $prod['Supplier']['subdomain'];
    			$cart['items'][$index]['item_alias'] = $prod['product_alias'];
    			$cart['items'][$index]['amount'] = $prod['OrdersProduct']['deal_price'];
    			$cart['items'][$index]['quantity'] = $prod['OrdersProduct']['quantity'];
    			$cart['items'][$index]['shipping'] = $prod['OrdersProduct']['freight'];
    			$cart['items'][$index]['subtotal'] = $prod['OrdersProduct']['subtotal'];
    			$cart['items'][$index]['item_name'] = !empty($prod['OrdersProduct']['prod_desc']) ? $prod['OrdersProduct']['prod_desc'] : $prod['name'];
    			$cart['items'][$index]['item_number'] = $prod['serial_no'];
    			$cart['items'][$index]['img'] = $prod['Image'][0]['id'];
        	$cart['items'][$index]['ext'] = $prod['Image'][0]['extension'];
    		}
    		$cart['totalAmount'] = $order['Order']['subtotal'];
				$cart['totalShipping'] = $order['Order']['freight'];
    
    		$this->Session->write('sess_cart', $cart);
    		
    		$this->redirect('/checkout/confirm');
    		exit();
			}
		}
	}
	
	function remove() {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->data['Order']['id']) && !empty($this->data['Order']['id'])) {
				$response['success'] = false;
				$conditions = array(
					'Order.user_id' => $this->Auth->user('id'),
					'Order.id'	  => $this->data['Order']['id'],
					'Order.is_paid' => 0,
					'Order.status_id' => TYPE_ORDER_NOT_PAID
				);
				if ($this->Order->deleteAll($conditions)) {
					$response['success'] = true;
				}
				$this->autoRender = false;
				echo json_encode($response);
			}
		}
	}
	
	function output() {
		if (isset($this->params['pass'][0]) && !empty($this->params['pass'][0])) {
			$content = $this->data['content'];
			switch($this->params['pass'][0]) {
				case 'email' :
					break;
				case 'word' :
					$this->set('msword_order',$this->Utility->word($content));
					$this->render('word','file');
					break;
				case 'pdf' :
					if (isset($this->data['pid']) && !empty($this->data['pid'])) {
						$params = array(
							'conditions' => array(
								'Order.user_id' => $this->Auth->user('id'),
								'Order.id' => $this->data['pid']
							),
							'recursive' => 2
						);
						$this->set('store', $this->Store->findByAlias(STORE_NAME));
						$this->set('order', $this->Order->find('first', $params));
            			$this->render('pdf', 'file/pdf');
					}
					break;
			}
		}
	}
	
	/********Admin Panel Methods*******/
	
	function admin_output () {
		if (isset($this->params['named']['type']) && isset($this->params['named']['pid'])) {
			$type = $this->params['named']['type'];
			$orderId = $this->params['named']['pid'];
			switch($type) {
				case 'pdf' :
					$params = array(
							'conditions' => array(
								'Order.id' => $orderId
							),
							'recursive' => 2
					);
					$this->set('order', $this->Order->find('first', $params));
					$this->set('store', $this->Store->findByAlias(STORE_NAME));
          $this->render('pdf', 'file/pdf');
          
					break;
			}
		}
	}
	
	function admin_view ($parentId=0, $statusId=0) {
		$param = array(
			'recursive' => 0,
			'order' => array('Order.status_id', 'Order.created DESC')
		);
		if (!empty($statusId)) {
			$param['conditions'] = array(
				'Order.status_id' => $statusId
			);
		} else {
			$param['conditions'] = array(
				'NOT' => array('Order.status_id' => TYPE_ORDER_NOT_PAID)
			);
		}
		$arrItems = $this->Order->find('all', $param);
		
		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				$this->Order->recursive = 2;
				$this->set('order', $this->Order->findById($items[0]['id']));
				$this->set('store', $this->Store->findByAlias(STORE_NAME));
				
				$params = array(
					'conditions'=>array(
						'NOT' => array('Status.id' => array(TYPE_ORDER_NOT_PAID, TYPE_ORDER_RETURN))
					),
					'order' => 'Status.id',
					'fields' => array('name')
				);
				$this->set('statuses', $this->Order->Status->find('list', $params));
			}
			
			$this->render ('admin_edit', 'ajax');	
		}
	}
	
	function admin_setstatus () {
		if ($this->RequestHandler->isAjax()) {
			$response['success'] = false;
			if (isset($this->data['Order']['id']) && !empty($this->data['Order']['id']) &&
					isset($this->data['Status']['id']) && !empty($this->data['Status']['id'])) {
				$this->Order->id = $this->data['Order']['id'];
				$this->Order->saveField('status_id', $this->data['Status']['id']);
				
				if (in_array($this->data['Status']['id'], 
						array(TYPE_ORDER_PENDING, TYPE_ORDER_DELIVERED, TYPE_ORDER_COMPLETED))) {
					
					$this->Order->recursive = -1;
					$order = $this->Order->findById($this->data['Order']['id'], array('invoice_id'));
					if (!$order['Order']['invoice_id']) {
						$data['Invoice']['invoice_no'] = time();
						if ($this->Invoice->save($data)) {
							$this->Order->saveField('invoice_id', $this->Invoice->getLastInsertID());
							$response['invoice'] = $data['Invoice']['invoice_no'];
						}
					}
				}
				$response['success'] = true;
			}
			
			$this->autoRender = false;
			echo json_encode($response);
		}
	}
}
?>