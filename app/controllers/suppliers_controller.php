<?php
class SuppliersController extends AppController {
	var $uses = array('User', 'Supplier', 'Product');
	var $helpers = array('Javascript', 'combobox');
	var $strListName = 'thisItem';
	var $components = array('RequestHandler', 'Email', 'Session');
	var $limit = 25;
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('register', 'viewBySubdomain', 'viewProduct');
		
		/*****The following code is to avoid password auto-encript*****/
		if($this->action == 'admin_save' || $this->action == 'register') {
			$this->Auth->authenticate = $this->User;
		}
		
		$this->layout = 'form';
	}
	
	private function sendConfirmEmail() {
		$this->Email->to = $this->data['User']['email'];
		//$this->Email->bcc = array('secret@example.com');
		$this->Email->subject = 'Freshla Application Confirmation';
		$this->Email->replyTo = 'suppliers@freshla.com.au';
		$this->Email->from = 'Freshla <suppliers@freshla.com.au>';
		$this->Email->template = 'supplier_register'; // note no '.ctp'
		//Send as 'html', 'text' or 'both' (default is 'text')
		$this->Email->sendAs = 'both'; // because we like to send pretty mail
		$this->Email->send();
		
		/*Send a email to sales person with the supplier details*/		
		$this->Email->reset(); //reset the previous one
		
		$this->Email->to = 'suppliers@freshla.com.au';
		$this->Email->subject = 'New Application';
		$this->Email->replyTo = 'suppliers@freshla.com.au';
		$this->Email->from = 'Freshla <suppliers@freshla.com.au>';
		$this->Email->template = 'supplier_notify'; // note no '.ctp'
		//Send as 'html', 'text' or 'both' (default is 'text')
		$this->Email->sendAs = 'both'; // because we like to send pretty mail
		$this->set('data', $this->data);
		$this->Email->send();		
	}
	
	function register() {
		$view = 'register';
		if (!empty ($this->data)) {
				$this->data['User']['active'] = 0; //deactive supplier, will send confirm email to active
				$this->data['Group']['Group'][0] = USER_GROUP_SUPPLIER; //Can only create as a supplier;
				if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
					$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
					
					$this->sendConfirmEmail();
 
					if ($this->Auth->login($this->data)) {
						$this->redirect('/account');
					} else {
						$view = 'success';
					}
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->User));
				}
		}
		
		$this->render ($view, 'form');
	}
	
	function viewBySubdomain($subdomain) {
		$this->Supplier->unbindModel (array('hasMany'=>array('Product')));
		$this->Supplier->recursive = 1;	
		$arrItems = $this->Supplier->findBySubdomain($subdomain);
		
		$this->Product->unbindModel(
				array(
					'belongsTo' => array('Supplier'),
					'hasMany'   => array('Media', 'Document', 'Feature'),
					'hasAndBelongsToMany'    => array('Type')
				), false 							//set false to make unbind work in all operations.
		);
		$this->Product->hasMany['Image']['conditions']['is_default'] = 1;
		$this->paginate = array(
			'conditions' => array('Product.supplier_id' => $arrItems['Supplier']['id']),
			'order' => 'Product.name',
			'limit' => $this->limit
		);
		$products = $this->paginate('Product');
		
		$this->set(compact('products'));
		$this->set ( $this->strListName,  $arrItems );
		$this->render ('view', 'page');
	}
	
	function viewProduct($subdomain, $alias) {
		$this->Supplier->Product->unbindModel (array('belongsTo'=>array('Supplier')));
		$this->Supplier->hasMany['Product']['limit'] = 4;
		$this->Supplier->hasMany['Product']['conditions'] = array('product_alias <>' => $alias);
		$this->Supplier->recursive = 2;	
		$supp = $this->Supplier->findBySubdomain($subdomain);

		$params = array(
			'conditions' => array(
				'supplier_id' => $supp['Supplier']['id'],
				'product_alias' => $alias
			)
		);
		$this->Product->unbindModel (array('belongsTo'=>array('Supplier')));
		$product = $this->Product->find('first', $params);
		
		/**Here is to get removed items from cart, and clear cookie at the product page**/
		/*$cart_removed = $this->Session->read('sess_cart_removed');
		if (isset($cart_removed) && count($cart_removed) > 0) {
			$this->set('removed_items', json_encode($cart_removed));
			$this->Session->delete('sess_cart_removed');
		}*/
		
		$this->set($this->strListName, $supp);
		$this->set('product', $product);
		
		$page_view = 'product';
		if ($product['Product']['for_delivery'] == 1) {
			$page_view = 'product_delivery';
		}
		$this->render ($page_view, 'page');
	}
  	
	/*Admin panel methods*/
	function admin_serial() {
		if ($this->RequestHandler->isAjax()) {
			$params =  array(
				'fields' => array('MAX(identifier)+1 AS serial_no'),
				'recursive' => -1
			);
			$serial = $this->Supplier->find('first', $params);
			$serial[0]['serial_no'] = !empty($serial[0]['serial_no']) ? $serial[0]['serial_no'] : 1;
			$zeros = 4 - strlen($serial[0]['serial_no']);
			for ($i=0; $i < $zeros; $i++) {
				$serial[0]['serial_no'] = '0' . $serial[0]['serial_no'];
			}
			$this->autoRender = false;	
			echo $serial[0]['serial_no'];
		}
	}
	
	function admin_get ($pid=0) {
		$param = array(
			'conditions' => array('User.active' => 1, 'User.id=Supplier.user_id'),
			'fields' => array('Supplier.id', 'Supplier.biz_name'),
			'recursive' => 0,
			'order' => array('Supplier.biz_name')
		);
		if (!empty($pid)) $param['conditions'] = array('Supplier.id' => $pid);
		$data = $this->User->find('all', $param);

		return $data;
	}
	
  function admin_new() {
		//$this->set('importances',$this->getImportances());
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0,$keywords='') {
		$this->User->Group->unbindModel(array(
			'hasAndBelongsToMany'=>array('User','Permission')
			), false);
		$this->User->Supplier->unbindModel(array(
			'hasMany' => array('Product', 'Image')
			), false);
		$this->User->unbindModel(array(
			'hasOne' => array('UserProfile'),
			'hasMany' => array('Order', 'Contact')
		 ), false);
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$this->paginate = array(
					'User' => array(
						'page' => 1,
						'recursive' => 2,
						'order' => array('User.created' => 'desc'),
						'limit' => $this->limit,
						'fields' => array('User.id', 'User.email','User.active','User.created',
										  'Supplier.identifier', 'Supplier.phone','Supplier.subdomain','Supplier.biz_name'),
						'conditions' => array(
								'OR' => array(
									array('Supplier.biz_name LIKE' => '%' . $keywords . '%'),
									array('User.email LIKE' => '%' . $keywords . '%'),  
									array('Supplier.subdomain LIKE' => '%' . $keywords . '%')
								)
						),
						'joins' => array(
							array(
								'table' => 'users_groups',
								'alias' => 'UsersGroup',
								'type' => 'inner',
								'conditions'=> array('UsersGroup.user_id = User.id', 
										'UsersGroup.group_id' => USER_GROUP_SUPPLIER)
							),
							array(
								'table' => $this->User->Group->useTable,
								'alias' => 'Group',
								'type' => 'inner',
								'conditions'=> array(
									'Group.id = UsersGroup.group_id'
								)
							)
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
				$this->data['Group']['Group'][0] = USER_GROUP_SUPPLIER; //Can only create as a customer;
				if (isset($this->data['User']['id']) && !empty($this->data['User']['id'])) { //update user
					if (!isset($this->data['change_psw']) || $this->data['change_psw'] != 1) {
						$this->User->resetPasswordValidate(); //if not to change password, then do not validate password
						unset($this->data['User']['password']);
					}
				} 
				
				if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
					$keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
					$this->admin_view($parentId, $keywords);
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
			$keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
			$this->admin_view($parentId, $keywords);
		}
	}

}
?>
