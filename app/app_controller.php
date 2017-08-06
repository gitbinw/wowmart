<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
 
class AppController extends Controller {
	var $components = array('Auth', 'Session', 'Email', 'Utility', 'RequestHandler');
	var $helpers = array('utility', 'html', 'form', 'dropdown');
	var $isAuthorized = false;
	var $admin = '';
	var $loginId = 0;
	var $limit = 25;
	var $strParentId = 'parentId';
	var $strParentUrl = 'parentUrlParams';
	
	//System module labels - this is for system vouchers, discounts, rewards
	var $SYSTEM_MODULE_LABELS = array(
		array('id' => 'NEW_CUSTOMER_SIGNUP', 'name' => 'New Customer Sign Up'),
		array('id' => 'SHOPPING_CART_TOTAL', 'name' => 'Shopping Cart Total', 'need_value' => true)
	);
	//Shipping Types
	var $SHIPPING_TYPES = array(
		array('id' => 'SHIPPING_USERDEFINED', 'name' => 'User Defined'),
		array('id' => 'SHIPPING_POSTOFFICE', 'name' => 'Post Office'),
		array('id' => 'SHIPPING_COMPANY', 'name' => 'Shipping Company'),
		array('id' => 'SHIPPING_PICKUP', 'name' => 'Pick Up')
	);
	//Shipping Units
	var $SHIPPING_UNITS = array(
		array('id' => 'SHIPPING_UNIT_PERKG', 'name' => 'PER KG'),
		array('id' => 'SHIPPING_UNIT_PERITEM', 'name' => 'PER ITEM')
	);
	//State Options
	var $STATE_OPTIONS = array(
		array('id' => 'ACT', 'name' => 'ACT'),
		array('id' => 'NSW', 'name' => 'NSW'),
		array('id' => 'NT', 'name' => 'NT'),
		array('id' => 'QLD', 'name' => 'QLD'),
		array('id' => 'SA', 'name' => 'SA'),
		array('id' => 'TAS', 'name' => 'TAS'),
		array('id' => 'VIC', 'name' => 'VIC'),
		array('id' => 'WA', 'name' => 'WA')
	);
	//Category OR Product Status
	var $CATEGORY_PRODUCT_STATUSES = array(
//		array('id' => 'free_post', 'name' => 'Free Post'),
		'new_arrival' => array('id' => 'new_arrival', 'name' => 'New Arrival'),
		'sale' => array('id' => 'sale', 'name' => 'SALE')
	);
	
	function beforeFilter(){
		$this->setupCookie();
		
		$this->createNewSession();
		
		Security::setHash('md5');
		$this->Auth->userModel = 'User';
		$this->Auth->fields = array(
			'username' => 'email',
			'password' => 'password'
		);
		
		$adminPanel = false;
		$this->admin = Configure::read('Routing.prefixes.0');
		$this->Auth->loginAction = array('controller' => 'customers', 'action' => 'login');
		if (isset($this->params[$this->admin]) && $this->params[$this->admin]) {
			$adminPanel = true;
			
			$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		} /*else if ($this->action == 'checkout' || $this->action == 'checkout_login') {
			$this->Auth->loginAction = array('controller' => 'customers', 'action' => 'checkout_login');
		}*/
		$this->Auth->authorize = 'controller';
		$this->Auth->userScope = array('User.active' => 1);
    	
		$this->loginId = $this->Auth->user('id');
    	$this->set('Auth',$this->Auth->user());
    	$this->Auth->autoRedirect = false;
    	$this->Auth->authError = "Sorry, you are lacking access.";
    	
    	$this->set('SYSTEM_MODULE_LABELS', $this->SYSTEM_MODULE_LABELS);
    	$this->set('SHIPPING_TYPES', $this->SHIPPING_TYPES);
    	$this->set('SHIPPING_UNITS', $this->SHIPPING_UNITS);
		$this->set('STATE_OPTIONS', $this->STATE_OPTIONS);
		$this->set('CATEGORY_PRODUCT_STATUSES', $this->CATEGORY_PRODUCT_STATUSES);
		
		//set up the data feeds only for font-end
		if (!$adminPanel && in_array($this->layout, array('default', 'myaccount')) !== -1 ) {
			$this->set('CATEGORY_HIERACHY', $this->Utility->generateTreeByLevels(3));
		}
  }
  
  function redirect($url, $status = null, $exit = true) {
  	if ($status == 403 && $this->RequestHandler->isAjax()) {
  		$redirect = '/errors/index/403';
  		if (isset($this->params[$this->admin]) && $this->params[$this->admin]) {
  			$redirect = '/admin/errors/index/403';
  		}
  		parent::redirect($redirect);
  	} else {
  		parent::redirect($url, $status = null, $exit = true);
  	}
  }

  function beforeRender(){
    //If we have an authorised user logged then pass over an array of controllers
    //to which they have index action permission
    /*if($this->Auth->user()){
        $controllerList = Configure::listObjects('controller');
        $permittedControllers = array();
        foreach($controllerList as $controllerItem){
            if($controllerItem <> 'App'){
                if($this->__permitted($controllerItem,'index')){
                   $permittedControllers[] = $controllerItem;
                }
            }
        }
    }
    $this->set(compact('permittedControllers'));*/
    
    if (isset($this->params[$this->admin]) && $this->params[$this->admin]) {
        $this->layout = 'admin';
        
        $action = $this->params[$this->admin];
        $parentId = isset($this->params['pass'][0]) ? $this->params['pass'][0] : 0;
        $parentUrl = isset($parentId) && !empty($parentId) ? '/' . $parentId : '';
        
        $this->set ( $this->strParentId,  $parentId );
        $this->set ( $this->strParentUrl,  $parentUrl );
    }
    
    if (!$this->isAuthorized) {
    	$this->autoRender = false;
    	return '';
    }
  }

  function isAuthorized() {
  	$this->isAuthorized = $this->__permitted($this->name,$this->action);
  	if (!$this->isAuthorized) {
  		if (isset($this->params[$this->admin]) && $this->params[$this->admin]) {
  			$this->redirect("/admin/errors/index/403");
  		} else {
  			$this->redirect("/errors/index/403");
  		}
  	}
  	return $this->isAuthorized;
  }
   
  function __permitted($controllerName,$actionName){
    //Ensure checks are all made lower case
    $controllerName = low($controllerName);
    $actionName = low($actionName);
    //If permissions have not been cached to session...
    if(!$this->Auth->Session->check('Permissions')){
        //...then build permissions array and cache it
        $permissions = array();
        //Import the User Model so we can build up the permission cache
        App::import('Model', 'User');
        $thisUser = new User;
        //Now bring in the current users full record along with groups
         $thisGroups = $thisUser->find(array('User.id'=>$this->Auth->user('id')));
         $thisGroups = $thisGroups['Group'];
         foreach($thisGroups as $thisGroup){
             $thisPermissions = $thisUser->Group->find(array('Group.id'=>$thisGroup['id']));
             $thisPermissions = $thisPermissions['Permission'];
             foreach($thisPermissions as $thisPermission){
                 $permissions[]=$thisPermission['name'];
             }
         }
         //write the permissions array to session
         $this->Auth->Session->write('Permissions',$permissions);
     }else{
         //...they have been cached already, so retrieve them
         $permissions = $this->Auth->Session->read('Permissions');
     }
     //Now iterate through permissions for a positive match
     if (in_array('-*', $permissions)) {
     		return false;	
     } else if (in_array('-' . $controllerName.':*', $permissions)) {
     		return false;
     } else if (in_array('-' . $controllerName.':'.$actionName, $permissions)) {
     		return false;
     }

     foreach($permissions as $permission){
        if($permission == '*'){
            return true;//Super Admin Bypass Found
        }
        if($permission == $controllerName.':*'){
            return true;//Controller Wide Bypass Found
        }
        if($permission == $controllerName.':'.$actionName){
            return true;//Specific permission found
        }
    }
    return false;
  }
	
  function afterPaypalNotification($txnId){
    //Here is where you can implement code to apply the transaction to your app.
    //for example, you could now mark an order as paid, a subscription, or give the user premium access.
    //retrieve the transaction using the txnId passed and apply whatever logic your site needs.
    
    $transaction = ClassRegistry::init('PaypalIpn.InstantPaymentNotification')->findById($txnId);
    $this->log("transsaction: " . $transaction['InstantPaymentNotification']['id'] . 
               " Order ID: " . $transaction['InstantPaymentNotification']['custom'], 'paypal');

    //Tip: be sure to check the payment_status is complete because failure transactions 
    //     are also saved to your database for review.

    if($transaction['InstantPaymentNotification']['payment_status'] == 'Completed'){
      App::import('Model', 'Order');
      $thisOrder = new Order;
      $thisOrder->read(null, $transaction['InstantPaymentNotification']['custom']);
			$thisOrder->set(array('is_paid' => 1, 'status_id' =>TYPE_ORDER_PAID));
			$thisOrder->save();

      $thisOrder->unbindModel(array('belongsTo' => array('Status', 'Invoice')));
		$thisOrder->User->unbindModel(array(
			'hasAndBelongsToMany' => array('Group'),
			'hasMany' => array('Contact', 'Order'),
			'hasOne' => array('Supplier')
		));
		$thisOrder->hasAndBelongsToMany['Product']['fields'] = array('id', 'name', 'serial_no');
		$thisOrder->Product->unbindModel(array(
				'hasMany' => array('Media', 'Document', 'Feature'),
				'hasAndBelongsToMany' => array('Category', 'Type')
		));
		$thisOrder->Product->hasMany['Image']['conditions']['is_default'] = 1;

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
    else {
      //Oh no, better look at this transaction to determine what to do; like email a decline letter.
    }
  }

  private function sendPaymentEmail($order) {
		$this->set('order', $order);
		$this->Email->to = $order['User']['email'];
		$this->Email->subject = 'Freshla Payment Confirmation';
		$this->Email->replyTo = EMAIL_SALES_REPLY;
		$this->Email->from = EMAIL_SALES_FROM;
		$this->Email->template = 'order_confirm'; // note no '.ctp'
		//Send as 'html', 'text' or 'both' (default is 'text')
		$this->Email->sendAs = 'both'; // because we like to send pretty mail
		$this->Email->send();
		
		$this->Email->reset(); //reset the previous one
		$this->Email->to = EMAIL_SALES_TO;
		$this->Email->bcc = array(EMAIL_BCC);
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
				
		if ($order['Order']['business_code'] != BUSINESS_BRASA_DELIVERY) {
		/*assemble order for each supplier, the key is supplier id(unique)*/
		       $supplier_orders = array();
		       $supplier_ids = array();
		       foreach($order['Product'] as $prod) {
			        if (!isset($supplier_orders[$prod['Supplier']['id']])) {
				       $supplier_ids[] = $prod['Supplier']['id'];
				       $supplier_orders[$prod['Supplier']['id']]['Order'] = $order['Order'];
			        }
			        $supplier_orders[$prod['Supplier']['id']]['Product'][] = $prod;
			        $supplier_orders[$prod['Supplier']['id']]['Order']['freight'] += $prod['OrdersProduct']['freight'];
			        $supplier_orders[$prod['Supplier']['id']]['Order']['subtotal'] += $prod['OrdersProduct']['subtotal'];
			        $supplier_orders[$prod['Supplier']['id']]['Order']['total_amount'] = $supplier_orders[$prod['Supplier']['id']]['Order']['freight'] + $supplier_orders[$prod['Supplier']['id']]['Order']['subtotal'];
		       }
		
		       if (count($supplier_ids) > 0 && count($supplier_orders) > 0) {
			        App::import('Model', 'Supplier');
			        $supplier = new Supplier;
			        $params = array(
				        'conditions' => array('Supplier.id' => $supplier_ids),
				        'fields' => array('Supplier.id', 'User.email'),
				        'recursive' => 0
			        );
			        $arrSups = $supplier->find('all', $params);
			
			        foreach ($arrSups as $sup) {
				        /*Send a email to sales person with the supplier details*/
				        $this->set('order', $supplier_orders[$sup['Supplier']['id']]);
				
				        $this->Email->reset(); //reset the previous one
				        $this->Email->to = $sup['User']['email'];
						$this->Email->bcc = explode(',', EMAIL_TECH);
               			$this->Email->subject = 'New Order from Freshla';
          				$this->Email->replyTo = EMAIL_SALES_REPLY;
	        			$this->Email->from = EMAIL_SALES_FROM;
		        		$this->Email->template = 'order_notify'; // note no '.ctp'
			        	//Send as 'html', 'text' or 'both' (default is 'text')
	         			$this->Email->sendAs = 'both'; // because we like to send pretty mail
		        		$this->Email->send();
	                	}
         			}
                }
	} 
  
	private function defineSymbols() {
		$statuses = $this->Status->find('all');
		foreach ($statuses as $status) {
			if(!defined($status['Status']['symbol'])) {
				define($status['Status']['symbol'], $status['Status']['value']);
			}
		}
	}
	
	protected function convertToDbDate($date) {
		$arrDate = explode('/', $date);
		$euroDate = implode('-', $arrDate);
		
		return date('Y-m-d', strtotime($euroDate));
	}
	
	protected function getPageDetails($pageAlias = 'home') {
		$params = array(
			'recursive' => 1,
			'conditions' => array(
				'PageDetail.is_shown' => 1,
				'PageDetail.alias' => $pageAlias
			)
		);
		$pg = $this->Page->find('first', $params);
		
		$template = '';
		$templateId = '';
		if (isset($pg['PageTemplate']['alias']) && !empty($pg['PageTemplate']['alias'])) {
			$template = $pg['PageTemplate']['alias'];
			$templateId = $pg['PageTemplate']['id'];
		}

		$feature_banners = array();
		$top_banners = array();
		if (isset($pg['PageBanner']) && $templateId != PAGE_TEMPLATE_WITHOUTBANNER) {
			foreach($pg['PageBanner'] as $key => $bn) {
				$bannerType = $bn['banner_type'];
				$lnkUrl = isset($bn['url']) ? $bn['url'] : '';
				$imgSrc = isset($bn['image_src']) ? $bn['image_src'] : '';
				$alt = isset($bn['banner_text']) ? $bn['banner_text'] : '';
				$hoverText = isset($bn['hover_text']) ? $bn['hover_text'] : '';
				
				if ($bannerType == PAGE_BANNER_TYPE_TOP) {
					$top_banners[] = array('img_src' => $imgSrc, 'img_url' => $lnkUrl, 
											'alt' => $alt, 'text' => $hoverText);
				
				} else { //other banners e.g. feature_banner in home page
					$feature_banners[] = array('img_src' => $imgSrc, 'img_url' => $lnkUrl, 
											'alt' => $alt, 'text' => $hoverText);
				}
			}
		}
		
		if (count($top_banners) <= 0 && $templateId == PAGE_TEMPLATE_DEFAULT) {
			$homePageDetails = $this->getPageDetails();
			$top_banners = $homePageDetails['top_banners'];
		}
		
		$pageDetails =  array(
			'template_id' => $templateId,
			'template' => $template, 
			'top_banners' => $top_banners,
			'features' => $feature_banners
		);
		
		return $pageDetails;
	}
	
	protected function generateRandomString($length = 5) {
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	protected function makeAlias($name) {
		$strName = strtolower($name);
		$strName = preg_replace('/\s+/', '_', $strName);
	
		return $strName;
	}
	
	protected function makeNeatUrlName($name) {
		if (isset($name) && !empty($name)) {
			$aliasSearch  = array('/[^\&\s\da-zA-Z]/', '/\&+/', '/\s+/');
			$aliasReplace = array('', ' and ', '-');
			$neatName = preg_replace($aliasSearch, $aliasReplace, $name);
			$neatName = strtolower($neatName);
		}
	}
	
	private function setupCookie() {
		if (!isset($this->Cookie)) {
			$this->Cookie = new stdClass();
		}
	  $this->Cookie->name = COOKIE_WOWMART;
		//$this->Cookie->time =  3600;  // or '1 hour'
		//$this->Cookie->path = '/bakers/preferences/';
		//$this->Cookie->domain = 'example.com';
		//$this->Cookie->secure = true;  //i.e. only sent if using secure HTTPS
		//$this->Cookie->key = 'qSI232qs*&sXOw!';
	}
	protected function createNewSession($renew = false) {
		$cookieName = Configure::read('Session.cookie');

		if (!isset($_COOKIE[$cookieName]) || empty($_COOKIE[$cookieName]) || $renew === true) {
			$sessionid = $this->generateGUID();

        	if($sessionid != null && $sessionid != '') {
				$this->Session->id($sessionid);
				
				return $sessionid;
        	}
		}
		
		return false;
	}
	
	protected function generateGUID() {
		if (function_exists('com_create_guid')) {
			$uuid = com_create_guid();
		} else {
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtolower(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
    	}
		
		$startDate = '1967-12-31';
		$guid = str_replace('-', '', substr($uuid, 1, -1));
		$guid .= '-' . (int)( (strtotime(date('Y-m-d')) - strtotime($startDate)) / (3600 * 24) );
		
		return $guid;
	}
	protected function gainGUID($model_name = '', $guidField = '') {
		$guid = $this->generateGUID();
		$modelName = !empty($model_name) ? $model_name : $this->modelClass;
		$fieldName = $guidField ? $guidField : strtolower($modelName) . '_guid';

		$params = array(
			'conditions' => array(
				$fieldName => $guid
			),
			'recursive' => -1
		);
		$count = $this->{$modelName}->find('count', $params);
		
		if ($count > 0) return $this->gainGUID($model_name);
		else return $guid;
	}
}
?>