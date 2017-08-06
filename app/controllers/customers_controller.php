<?php
class CustomersController extends AppController {
	var $uses = array('User', 'Billing', 'Shipping', 'Order');
	var $helpers = array('Javascript', 'combobox', 'PaypalIpn.Paypal');
	var $strListName = 'thisItem';
	var $components = array('RequestHandler', 'Email', 'Session');
	var $layout = 'nocategory';
	var $response = array('status' => 0, 'errorCode' => 0, 'errorMsg' => '', 'data' => '');
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('register', 'login', 'checkout_login', 'logout', 'forgot', 'ajaxForgot', 'ajaxLoggedIn');
		
		/*****The following code is to avoid password auto-encript*****/
		$action_filter = array('admin_save', 'register', 'forgot');
		if(in_array($this->action, $action_filter)) {
			$this->Auth->authenticate = $this->User;
		}
	}
	
	function login () {
		if ($this->Auth->user()) {
			/*if (!empty($this->data)) {
				if (empty($this->data['User']['remember_me'])) {
					$this->Cookie->del('User');
				} else {
					$cookie = array();
					$cookie['email'] = $this->data['User']['email'];
					$cookie['token'] = $this->data['User']['pasword'];
					$this->Cookie->write('User', $cookie, true, '+2 weeks');
				}
				unset($this->data['User']['remember_me']);
			}*/
			//$this->redirect($this->Auth->redirect());
			
			$this->User->id = $this->Auth->user('id');
			$this->User->saveField('login_time', date('Y-m-d H:i:s'));
			$this->response['status'] = 1;
			
		} else {
			$errorMsg = 'wronglogin';
			$this->response['status'] = 0;
			$this->response['errorMsg'] = $errorMsg;
			if (!empty($this->data)) $this->set($this->strListName, $this->data);
			$this->set('login_errors', array($errorMsg));
		}
		
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
			
			echo json_encode($this->response);
			
		} else {
			
			if ($this->response['status'] == 1) {
				$this->redirect('/account');
			}
			
			$this->render('register');
		}
	}

	function ajaxLoggedIn() {
		if ($this->RequestHandler->isAjax()) {
			if ($this->Auth->user()) {
				$this->response['status'] = 1;
			}
			
			$this->autoRender = false;
			echo json_encode($this->response);
		}
	}
	
	function logout() {
		if ($this->Auth->user()) {
			$this->set('errorMessage', $this->Auth->authError);
		}

		$this->Auth->Session->delete('Permissions');
     	$this->redirect($this->Auth->logout());
	}
	
	private function sendPasswordEmail($user) {
		$this->set('data', $user);
		$this->Email->to = $user['User']['email'];
		//$this->Email->bcc = array('secret@example.com');
		$this->Email->subject = 'Your New Password from Freshla';
		$this->Email->replyTo = 'support@freshla.com.au';
		$this->Email->from = 'Freshla Support <support@freshla.com.au>';
		$this->Email->template = 'password_lost'; // note no '.ctp'
		//Send as 'html', 'text' or 'both' (default is 'text')
		$this->Email->sendAs = 'both'; // because we like to send pretty mail
		$this->Email->send();	
	}
	
	private function createPassword($length) {
		$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$i = 0;
		$password = "";
		while ($i <= $length) {
			$password .= $chars{mt_rand(0,strlen($chars))};
			$i++;
		}
		return $password;
	}
	
	function ajaxForgot() {
		if ($this->RequestHandler->isAjax()) {
			$response['success'] = false;
			if (!empty ($this->data)) {
				$this->User->set($this->data);
				unset($this->User->validate['email']['unique']);
				if ($this->User->validates(array('fieldList'=>array('email')))) {
					$this->User->hasOne = array();
					$this->User->bindModel(array('hasOne'=>array('UserProfile')));
					$user = $this->User->find('first', array(
														'conditions'=>array('email'=>$this->data['User']['email']), 
														'recursive' => 0));
					if (!empty($user)) {
						$user['User']['new_password'] = $this->createPassword(8);
						$this->User->id = $user['User']['id'];
						if ($this->User->saveField('password', $user['User']['new_password'])) {
							$this->sendPasswordEmail($user);
							$response['success'] = true;
						} else {
							$response['errors'] = array('This service is now suspent. Please contact us.');
						}
					} else {
						$response['errors'] = array('This email is not existing.');
					}
				} else {
					$response['errors'] = $this->validateErrors($this->User);
				}
				$this->autoRender = false;
				echo json_encode($response);
			} else {
				$this->render('forgot_password', 'ajax');
			}
		}
	}
	
	function forgot() {
		$response['success'] = false;
		if (!empty ($this->data)) {
			$this->User->set($this->data);
			unset($this->User->validate['email']['unique']);
			if ($this->User->validates(array('fieldList'=>array('email')))) {
				$this->User->hasOne = array();
				$this->User->bindModel(array('hasOne'=>array('UserProfile')));
				$user = $this->User->find('first', array(
						'conditions'=>array('email'=>$this->data['User']['email']),
						'recursive' => 0));
				if (!empty($user)) {
					$user['User']['new_password'] = $this->createPassword(8);
					$this->User->id = $user['User']['id'];
					if ($this->User->saveField('password', $user['User']['new_password'])) {
						$this->sendPasswordEmail($user);
						$response['success'] = true;
					} else {
						$response['errors'] = array('This service is now suspent. Please contact us.');
					}
				} else {
					$response['errors'] = array('This email is not existing.');
				}
			} else {
				$response['errors'] = $this->validateErrors($this->User);
			}
		}
		
		$this->render('forgot_password');
	}
	
	function checkout_login() {
		if ($this->Auth->user()) {
			/*if (!empty($this->data)) {
				if (empty($this->data['User']['remember_me'])) {
					$this->Cookie->del('User');
				} else {
					$cookie = array();
					$cookie['email'] = $this->data['User']['email'];
					$cookie['token'] = $this->data['User']['pasword'];
					$this->Cookie->write('User', $cookie, true, '+2 weeks');
				}
				unset($this->data['User']['remember_me']);
			}*/
			//$this->redirect($this->Auth->redirect());
			
			$this->User->id = $this->Auth->user('id');
			$this->User->saveField('login_time', date('Y-m-d H:i:s'));
			$this->redirect('/checkout/billing');
		} else if (!empty ($this->data)) {
			$this->set($this->strListName, $this->data);
			$this->set('login_errors', array('Your email or password is not correct.'));
		}
	}
	
	private function getCart() {
		if (!$this->Session->valid()) {
			$this->Session->renew();
    }
    if (!$this->Session->check('sess_cart')) {
    	$cart = array ('items'=>array(),'amount'=>0.0, 'shipping'=>0.0);
    } else {
    	$cart = $this->Session->read('sess_cart');
    }
    return $cart;
	}
	
	private function checkPassword($pass) {
		$this->User->recursive = -1;
		$user = $this->User->read('password', $this->Auth->user('id'));
		if ($user['User']['password'] == Security::hash($pass, null, true)) return true;
		return false;
	}
	
	function register() {
		$view = 'register';

		if (isset($this->params['form']) && !empty ($this->params['form'])) {
			$data = $this->params['form'];
			$this->data['Group']['Group'][0] = USER_GROUP_CUSTOMER; //Can only create as a customer;
			$this->data['User']['active'] = 1;
			$this->data['User']['email'] = $data['email'];
			$this->data['User']['password'] = $data['password'];
			$this->data['User']['confirm_password'] = $data['passwordsecond'];
			$this->data['UserProfile']['firstname'] = $data['firstname'];
			$this->data['UserProfile']['lastname'] = $data['lastname'];
			$this->data['UserProfile']['subscribed'] = strtolower($data['newsletter']) == 'ok' ? 1 : 0;
			
			//unset($this->User->UserProfile->validate['firstname']);
			//unset($this->User->UserProfile->validate['lastname']);
			if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
				$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
				if ($this->Auth->login($this->data)) {
					$this->response['status'] = 1;
					$redirection = '/account';
				} else {
					$redirection = '/login';
				}
				
			} else {
				$errors = $this->validateErrors($this->User);
				
				$this->set($this->strListName, $this->data);
				$this->set('errors', $errors);
				$this->response['errorMsg'] = $errors;
			}
		}
		
		$this->set('isRegister', true);
		
		if ($this->RequestHandler->isAjax()) {
			$this->autoRender = false;
			
			echo json_encode($this->response);
		
		} else {
			if (isset($redirection) && !empty($redirection)) {
				$this->redirect($redirection);
			} 
			
			$this->render ($view);
		}
	}
	
	/*My Account functions*/
	function account() {
		$this->layout = 'myaccount';
		if (isset($this->params['pass'][0]) && !empty($this->params['pass'][0])) {
			$action = $this->params['pass'][0];
			switch($action) {
				case 'edit':
					$this->account_edit();
					break;
				
				case 'update':
					$this->account_update(); 
					break;
					
				case 'address':
					$this->address_book(); 
					break;
					
				case 'address_get':
					$this->address_edit(); 
					break;
				
				case 'address_save':
					$this->address_save(); 
					break;
					
				case 'address_edit':
					$this->address_edit();
					break;
				
				case 'address_delete':
					$this->address_delete();
					break;
				
				default:
					$this->myaccount();
					break;
			}
			
		} else {
			$this->myaccount();
		}
		/*if (isset($this->params['pass'][0]) && $this->params['pass'][0] == 'update') {
			if ($this->RequestHandler->isAjax()) {
				$response['success'] = false;
				if (isset($this->data['User'])) {
					$this->data['User']['id'] = $this->Auth->user('id');
					
					if (!$this->checkPassword($this->data['User']['old_password'])) {
						$response['errors' ] = array('password' => "Password you have entered is not right.");
					} else if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
						unset($this->data['User']['id']);
						unset($this->data['User']['old_password']);
						unset($this->data['User']['password']);
						unset($this->data['User']['confirm_password']);
						$response['success'] = true;
						$response['account'] = $this->data['User'];
					} else {
						$response['errors' ] = $this->validateErrors($this->User);
					}
				} else if (isset($this->data['UserProfile'])) {
					$params = array(
							'conditions' => array('user_id' => $this->Auth->user('id')),
							'recursive' => -1,
							'fields' => array('id')
					);
					$profile = $this->User->UserProfile->find('first', $params);
					if (isset($profile['UserProfile']['id'])) {
						$this->data['UserProfile']['id'] = $profile['UserProfile']['id'];	
					}
					$this->data['User']['id'] = $this->Auth->user('id');
					if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
						unset($this->data['User']['id']);
						unset($this->data['UserProfile']['id']);
						$response['success'] = true;
						$response['account'] = $this->data['UserProfile'];
					} else {
						$errors = $this->validateErrors($this->User);
						$response['errors' ] = $errors['UserProfile'];
					}
				} 
				
				$this->autoRender = false;
				echo json_encode($response);
			}
		} else {
			$this->User->Order->unbindModel(array('belongsTo' => array('User', 'Invoice')));
			$this->User->unbindModel(array('hasAndBelongsToMany' => array('Group')));
			$this->User->hasMany['Order']['order'] = 'Order.created DESC';
			if (isset($this->params['pass'][0]) && !empty($this->params['pass'][0])) {
				$this->set('init_tab', $this->params['pass'][0]);
			}
			$this->User->recursive = 2;
			$this->set('user', $this->User->findById($this->Auth->user('id')));
		}*/
	}
	private function myaccount() {
		$params = array(
				'conditions' => array('user_id' => $this->Auth->user('id')),
				'recursive' => -1,
				'fields' => array('firstname', 'lastname', 'subscribed')
		);
		$profile = $this->User->UserProfile->find('first', $params);
		
		$this->set('profile', $profile['UserProfile']);
		$this->render('account');
	}
	private function account_edit() {
		$params = array(
				'conditions' => array('user_id' => $this->Auth->user('id')),
				'recursive' => -1,
				'fields' => array('firstname', 'lastname', 'subscribed')
		);
		$profile = $this->User->UserProfile->find('first', $params);
		
		$this->set('profile', $profile['UserProfile']);
		$this->render ('account_edit');
	}
	private function account_update() {
		$data = $this->params['form'];
		$params = array(
				'conditions' => array('user_id' => $this->Auth->user('id')),
				'recursive' => -1,
				'fields' => array('id')
		);
		$profile = $this->User->UserProfile->find('first', $params);
		
		$this->data['User']['id'] = $this->Auth->user('id');
		$this->data['User']['email'] = isset($data['email']) ? $data['email'] : '';
		$this->data['UserProfile']['id'] = $profile['UserProfile']['id'];
		$this->data['UserProfile']['firstname'] = isset($data['firstname']) ? $data['firstname'] : '';
		$this->data['UserProfile']['lastname'] = isset($data['lastname']) ? $data['lastname'] : '';
		
		if (isset($data['change_password']) && $data['change_password'] == 1) {
			$oldPassword = $data['current_password'];
			$this->data['User']['password'] = $data['password'];
			$this->data['User']['confirm_password'] = $data['confirm_password'];
		} else {
			$this->User->resetPasswordValidate(); //if not to change password, then do not validate password
		}
		
		if (isset($oldPassword) && !$this->checkPassword($oldPassword)) {
			$errors = array('password' => "The Current password you have entered is not right.");
			
		} else if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
			$this->Session->write('Auth.User.email', $this->data['User']['email']);
			$this->set('Auth', $this->Auth->user());
			
			$this->response['status'] = 1;
			
		} else {
			$arrErrors = array();
			
			$errors = $this->validateErrors($this->User);
			
			$this->set($this->strListName, $this->data);
			if (isset($errors['UserProfile'])) $arrErrors = $errors['UserProfile'];
			else $arrErrors = $errors;
			
			$this->response['errorMsg'] = $arrErrors;
		}
		
		$this->set('response', $this->response);
		
		$this->account_edit();
	}
	private function address_edit() {
		if ($this->RequestHandler->isAjax()) {
			$data = $this->params['form'];
			if (isset($data['address_id']) && !empty($data['address_id'])) {
				$model = strtolower($data['model']);
				
				$params = array(
						'conditions' => array(
							'user_id' => $this->Auth->user('id'), 
							'id' => $data['address_id']
						),
						'recursive' => -1
				);
				$address = $this->User->Contact->find('first', $params);
				
				$this->response['status'] = 1;
				$this->response['data'] = $address['Contact'];
				$this->response['data']['address_id'] = $address['Contact']['id'];
			}
			
			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
	private function address_delete() {
		if ($this->RequestHandler->isAjax()) {
			$data = $this->params['form'];
			$model = strtolower($data['model']);
			$addressType = $model == 'billing' ? 'is_billing' : 'is_shipping';

			if (isset($data['address_id']) && !empty($data['address_id'])) {
				$model = strtolower($data['model']);
				
				$params = array(
					'user_id' => $this->Auth->user('id'), 
					'id' => $data['address_id']
				);
				$address = $this->User->Contact->deleteAll($params);
				
				$defaultId = $this->setDefaultAddress($addressType);
				
				$this->response['status'] = 1;
				if ($defaultId) $this->response['data']['default_id'] = $defaultId;
			}
			
			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
	private function address_save() {
		if ($this->RequestHandler->isAjax()) {
			$data = $this->params['form'];
			$model = strtolower($data['model']);
			$addressType = $model == 'billing' ? 'is_billing' : 'is_shipping';
	
			$this->data['Contact'] = $data;
			$this->data['Contact']['user_id'] = $this->Auth->user('id');
			$this->data['Contact'][$addressType] = 1;
			if (isset($data['address_id']) && !empty($data['address_id'])) {
				$this->data['Contact']['id'] = $data['address_id'];
			}
			
			if ($this->User->Contact->saveAll($this->data, array('validate'=>'first'))) {
				$this->response['status'] = 1;
				$this->response['data'] = $data;
				if (!isset($this->data['Contact']['id'])) {
					$this->response['data']['address_id'] = $this->User->Contact->getLastInsertId();
					
					//check default, if no default then set to default
					$defaultId = $this->setDefaultAddress($addressType, $this->response['data']['address_id']);
					if ($defaultId) $this->response['data']['is_default'] = 1;
				}
				if (isset($data['is_default']) && $data['is_default'] == 1) {
					$this->User->Contact->updateAll(array('is_default' => 0), array(
						'user_id' => $this->Auth->user('id'),
						'id <>' => $this->response['data']['address_id'],
						$addressType => 1
					));
				}
				
			} else {
				$errors = $this->validateErrors($this->User->Contact);
				$this->response['status'] = 0;
				$this->response['errorMsg'] = array_values($errors);
			}

			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
	private function setDefaultAddress($addressType = 'is_billing', $addressId = '') {
		$params = array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				$addressType => 1,
				'is_default' => 1
			),
			'recursive' => -1
		);
		$count = $this->User->Contact->find('count', $params);
		$defaultAddressId = '';
		if ($count <= 0) {
			if (!empty($addressId)) $defaultAddressId = $addressId;
			else {
				$params = array(
					'conditions' => array(
						'user_id' => $this->Auth->user('id'),
						$addressType => 1
					),
					'recursive' => -1,
					'order' => array('id DESC')
				);
				$address = $this->User->Contact->find('first', $params);
				if ($address && isset($address['Contact']['id'])) $defaultAddressId = $address['Contact']['id'];
			}
		}
		
		if (!empty($defaultAddressId)) {
			$this->User->Contact->id = $defaultAddressId;
			$this->User->Contact->saveField('is_default', 1);
		}
		
		return $defaultAddressId;
	}
	private function address_book() {
		$this->set('billings', $this->getBillings());
		$this->set('shippings', $this->getShippings());
		
		$this->render('address_book');
	}
	private function getBillings() {
		$params = array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'is_billing' => 1
			),
			'recursive' => -1
		);
		$billings = $this->User->Contact->find('all', $params);
		
		return $billings;
	}
	private function getShippings() {
		$params = array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'is_shipping' => 1
			),
			'recursive' => -1
		);
		$shippings = $this->User->Contact->find('all', $params);
		
		return $shippings;
	}
	/*End of MyAccount*/
	
  
	/*Admin panel methods*/
  	function admin_new() {
		//$this->set('importances',$this->getImportances());
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0, $keywords='') {
		//$param = array(
		//		'conditions' => array('UsersGroup.group_id' => USER_GROUP_CUSTOMER),//only access to customers
		//		'recursive' => 2,
		//		'order' => array('UserProfile.firstname', 'UserProfile.lastname')
		//);
		//retrieving HABTM data 
		//$this->User->bindModel(array('hasOne' => array('UsersGroup')));
		
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$strSearch = '%' . $keywords . '%';
		$this->paginate = array(
				'User' => array(
						'page' => 1,
						'recursive' => 2,
						'order' => array('UserProfile.firstname' => 'asc', 'UserProfile.lastname' => 'asc'),
						'limit' => $this->limit,
						'fields' => array('User.*'),
						'conditions' => array(
								'OR' => array(
										array('UserProfile.firstname LIKE' => $strSearch),
										array('UserProfile.lastname LIKE' => $strSearch),
										array('User.email LIKE' => $strSearch)
										
								),
								'UsersGroup.group_id' => USER_GROUP_CUSTOMER
						),
						'joins' => array(
								array(
										'table' => 'users_groups',
										'alias' => 'UsersGroup',
										'type' => 'LEFT',
										'conditions'=> array('UsersGroup.user_id = User.id')
								),
								array(
										'table' => $this->User->Group->useTable,
										'alias' => 'Group',
										'type' => 'LEFT',
										'conditions'=> array('UsersGroup.group_id = Group.id')
								),
						)
				)
		);
		
		$arrItems = $this->paginate('User');

		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				$this->data['Group']['Group'][0] = USER_GROUP_CUSTOMER; //Can only create as a customer;
				if (isset($this->data['User']['id']) && !empty($this->data['User']['id'])) { //update user
					if (!isset($this->data['change_psw']) || $this->data['change_psw'] != 1) {
						$this->User->resetPasswordValidate(); //if not to change password, then do not validate password
						unset($this->data['User']['password']);
					}
				} 
				
				if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
					$this->admin_view($parentId);
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->User));
					
					$this->render ('admin_edit', 'ajax');	
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
			  $data = $this->User->findById($items[0]['id']);
				$this->set($this->strListName, $data);
			}
			
			$this->render ('admin_edit', 'ajax');	
		}
	}
	
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
			    $this->User->delete($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}

}
?>
