<?php
class ReturnsController extends AppController {
	var $uses = array ('Order', 'ReturnsProduct', 'Store');
	var $helpers = array ('Javascript', 'Utility', 'Fpdf');
	var $components = array ('Session', 'RequestHandler');
	var $strListName = 'thisItem';
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->unbindOtherModels();
	}
	
	private function unbindOtherModels() {
		$this->Order->unbindModel(array('hasOne' => array('Billing', 'Shipping')));
		$this->Order->User->unbindModel(array(
			'hasOne' => array('UserProfile'),
			'hasAndBelongsToMany' => array('Group'),
			'hasMany' => array('Order', 'Contact')
		));
		$this->Order->Product->unbindModel(array(
			'hasAndBelongsToMany' => array('Category', 'Type'),
			'hasMany' => array('Media', 'Document', 'Feature')
		));
		$this->Order->hasAndBelongsToMany['Product']['fields'] = array('id', 'serial_no');
		$this->ReturnsProduct->unbindModel(array('belongsTo' => array('Order', 'Product')));
		$this->Order->Invoice->unbindModel(array('hasMany' => array('Order')));
		$this->Order->bindModel(array('hasMany' => array('ReturnsProduct')));
	}
	
	/********Admin Panel Methods*******/
	function admin_new () {
		$orderNo = $this->Order->find('list', array(
			'recursive' => -1,
			'conditions' => array(
				'NOT' => array('status_id' => array(TYPE_ORDER_NOT_PAID, TYPE_ORDER_PAY_REVIEW))
			),
			'fields' => array('order_no'),
			'order' => array('Order.created DESC')
		));
		
		$this->set('orderNumber', $orderNo);
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_get($orderId = 0) {
		$response['success'] = false;
		if ($this->RequestHandler->isAjax()) {
			$this->Order->recursive = 2;
			$response['order'] = $this->Order->findById($orderId);
			$response['success'] = true;
			
			$this->autoRender = false;
			echo json_encode($response);
		}
	}
	
	function admin_view ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			$param = array(
				'recursive' => 0,
				'conditions' => array(
					'is_returned' => 1
				),
				'order' => array('Order.updated DESC')
		 	);

			$arrItems = $this->Order->find('all', $param);
			$this->set ( $this->strListName,  $arrItems );
			$this->render ('admin_view', 'ajax');
		}
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax() && isset($this->data['Order']['id'])) {
			if (!empty ($this->data)) {
				if ($this->ReturnsProduct->saveAll($this->data['ReturnsProduct'], array('validate'=>'first'))) {
					//delete all returns product that have been remvoved from return list.
					$prod_line = array();
					foreach($this->data['ReturnsProduct'] as $rtn) $prod_line[] = $rtn['id'];
					$this->ReturnsProduct->deleteAll(array(
						'order_id' => $this->data['Order']['id'],
						'NOT' => array('ReturnsProduct.id' => $prod_line)
					));
					$this->Order->id = $this->data['Order']['id'];
					$this->Order->saveField('is_returned', 1);
					$this->admin_view($parentId);
				} else {
					$this->unbindOtherModels();
					$this->Order->recursive = 2;
					$returnData = $this->Order->findById($this->data['Order']['id']);
					$returnData['ReturnsProduct'] = $this->data['ReturnsProduct'];
					$this->set($this->strListName, $returnData);
					$this->set('errors', $this->validateErrors($this->ReturnsProduct));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				$order_line = array();
				foreach ( $items as $item ) {
					$order_line[] = $item[ 'id' ];
				}
				if ($this->ReturnsProduct->deleteAll(array(
							'ReturnsProduct.order_id' => $order_line
						))) {
					$this->Order->updateAll(array('is_returned' => 0), array('Order.id' => $order_line));
				}
			}
			$this->admin_view($parentId);
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				$this->Order->recursive = 2;
				$this->set($this->strListName, $this->Order->findById($items[0]['id']));
				
				$orderNo = $this->Order->find('list', array(
					'recursive' => -1,
					'conditions' => array(
						'NOT' => array('status_id' => array(TYPE_ORDER_NOT_PAID, TYPE_ORDER_PAY_REVIEW))
					),
					'fields' => array('order_no'),
					'order' => array('Order.created DESC')
				));
		
				$this->set('orderNumber', $orderNo);
				$this->set('is_edit', true);
			}
			
			$this->render ('admin_edit', 'ajax');	
		}
	}
}
?>