<?php
class VoucherCodesController extends AppController {
	var $uses = array('Voucher', 'VoucherCode', 'User');
	var $currentItem  = 'thisItem';
	var $listItems    = 'arrItems';
	var $helpers = array ('Javascript');
	var $components = array('RequestHandler');
	var $strListName = 'thisItem';
	var $layout = 'default';
	
	function beforeFilter () {
		parent::beforeFilter();
	}
	
	private function verifyVoucherCode($voucherCode) {
		$params = array(
			'recursive' => -1,
			'conditions' => array('vou_code' => $voucherCode)
		);
		$count = $this->VoucherCode->find('count', $params);
		
		if ($count > 0) return true;
		return false;
	}
	
	function addVoucher($voucherCode) {
		if ($this->loginId) {
			$flag = $this->VoucherCode->updateAll(
				array('user_id' => $this->loginId),
				array('vou_code' => $voucherCode)
			);
			if ($flag) return true;
		}
		
		return false;
	}
	
	function voucherCodes() {
		$arrCodes = array();
		if ($this->loginId) {
			$params = array(
				'recursive' => -1,
				'conditions' => array(
					'customer_id' => $this->loginId
				)
			);
			$arrCodes = $this->VoucherCode->find('all', $params);
		}
		
		return $arrCodes;
	}
	
	/*
	 ************* Admin Panel Methods ********************
	 */
	
	function admin_new () {
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0, $keywords='') {
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$this->paginate = array(
			'VoucherCode' => array(
				'page' => 1,
				'recursive' => -1,
				'order' => array('VoucherCode.vou_code' => 'asc'),
				'limit' => $this->limit,
				'fields' => array('Voucher.*'),
				'conditions' => array(
					'OR' => array(
						array('VoucherCode.vou_code LIKE' => '%' . $keywords . '%'),
						array('VoucherCode.vou_comments LIKE' => '%' . $keywords . '%')
					)
				)
			)
		);
				
		$arrItems = $this->paginate('VoucherCode');

		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				
				if ($this->Voucher->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['VoucherCode']['id'])) {//if create a new Voucher Code
						$this->data['VoucherCode']['id'] = $this->VoucherCode->getLastInsertID();
					}
					
					$this->set('arrNewItem', $arrNewItem);
					
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->Voucher));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));

				$data = $this->VoucherCode->findById($items[0]['id']);

				$this->set($this->currentItem, $data);
			}
			
			$this->render ('admin_edit', 'ajax');	
		}
	}
	
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
			    	$this->VoucherCode->delete($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}
}
?>
