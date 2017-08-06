<?php
class VouchersController extends AppController {
	var $uses = array('Voucher', 'VoucherCode');
	var $currentItem  = 'thisItem';
	var $listItems    = 'arrItems';
	var $helpers = array ('Javascript');
	var $components = array('RequestHandler');
	var $strListName = 'thisItem';
	var $layout = 'default';
	
	function beforeFilter () {
		parent::beforeFilter();
	}
	
	private function buildTree ( &$tree, $parentId = null ) {
		if ( !isset ( $parentId ) ) $parentId = 0;
		$param = array(
			'recursive' => -1,
			'fields' => array('id','vou_name'),
			'order' => array('vou_name')
		);
		$arrRes = $this->Voucher->find('all', $param);
    	$tree .= "<ul>";
    	foreach ( $arrRes as $node ) {
    		$tree .= "<li><a id='".$node['Voucher']['id']."' ctrl='vouchers'>".
    		       		$node['Voucher']['vou_name']."</a></li>";
    	}
    	$tree .= "</ul>";
	}
	
	private function generateVoucherCode ($voucherId, $num) {
		for($i=0; $i<$num; $i++) {
			$vouCode = $voucherId . $this->generateRandomString();
			$data = array(
				'VoucherCode' => array(
					'vou_code' => $vouCode,
					'voucher_id' => $voucherId
				)
			);
			
			$this->VoucherCode->recursive = -1;
			$this->VoucherCode->create();
			if ($this->VoucherCode->save($data)) {
				$vouCodeId = $this->VoucherCode->getLastInsertID();
				$vouCode = 'V' . $vouCodeId . $vouCode;
				$this->VoucherCode->id = $vouCodeId;
				$this->VoucherCode->saveField('vou_code', $vouCode);
			}
		}
	}
	
	private function getNumberOfVoucherCode($voucherId) {
		$this->Voucher->recursive = -1;
		$vou = $this->Voucher->findById($voucherId, array('vou_number'));
		$num = isset($vou['Voucher']['vou_number']) && !empty($vou['Voucher']['vou_number']) ? $vou['Voucher']['vou_number'] : 0;
		
		return $num;
	}
	
	/*
	 ************* Admin Panel Methods ********************
	 */
	
	private function admin_codes_view($parentId, $keywords = '') {
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$this->paginate = array(
			'VoucherCode' => array(
				'page' => 1,
				'recursive' => -1,
				'order' => array('VoucherCode.vou_code' => 'asc'),
				'limit' => $this->limit,
				'fields' => array('VoucherCode.*'),
				'conditions' => array(
					'OR' => array(
						array('VoucherCode.vou_code LIKE' => '%' . $keywords . '%'), 
						array('VoucherCode.vou_comments LIKE' => '%' . $keywords . '%')
					),
					'voucher_id' => $parentId
				)
			)
		);
				
		$arrItems = $this->paginate('VoucherCode');
		
		$this->set ( $this->strParentId,  $parentId );
		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_codes_view', 'ajax');
	}
	
	function admin_tree() {
		$tree = '';
		$this->buildTree($tree);
		return $tree;
	}
	
	function admin_new () {
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0, $keywords='') {
		if (!empty($parentId)) {
			
			$this->admin_codes_view($parentId, $keywords);
			
		} else {
			$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
			$this->paginate = array(
				'Voucher' => array(
					'page' => 1,
					'recursive' => -1,
					'order' => array('Voucher.vou_name' => 'asc'),
					'limit' => $this->limit,
					'fields' => array('Voucher.*'),
					'conditions' => array(
							'OR' => array(
								array('Voucher.vou_name LIKE' => '%' . $keywords . '%'),
								array('Voucher.vou_value LIKE' => '%' . $keywords . '%'),  
								array('Voucher.vou_comments LIKE' => '%' . $keywords . '%')
							)
					)
				)
			);
					
			$arrItems = $this->paginate('Voucher');
	
			$this->set ( $this->strListName,  $arrItems );
			$this->render ('admin_view', 'ajax');
		}
	}
	
	function admin_codes_save($parentId = 0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				if ($this->VoucherCode->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['VoucherCode']['id'])) {//if create a new Voucher Code
						$this->data['VoucherCode']['id'] = $this->VoucherCode->getLastInsertID();
					}
					
					$this->admin_view($parentId);
				} else {
					$this->Voucher->recursive = -1;
					$this->data['Voucher'] = $this->Voucher->findById($this->data['VoucherCode']['voucher_id']);
					
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->VoucherCode));
					$this->render ('admin_codes_edit', 'ajax');
				}
			}
		}
	}
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($parentId)) {
					
				$this->admin_codes_save($parentId);
				
			} else if (!empty ($this->data)) {
				
				$this->data['Voucher']['vou_alias'] = $this->makeAlias($this->data['Voucher']['vou_name']);
				if (!isset($this->data['Voucher']['vou_expiry']) || empty($this->data['Voucher']['vou_expiry']) ) {
					$this->data['Voucher']['vou_expiry'] = NULL;
				}
				
				$this->data['Voucher']['existing_number'] = 0;
				if (isset($this->data['Voucher']['id']) && !empty($this->data['Voucher']['id'])) {
					$this->data['Voucher']['existing_number'] = $this->getNumberOfVoucherCode($this->data['Voucher']['id']);
				}
				
				if ($this->Voucher->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Voucher']['id'])) {//if create a new voucher
						$this->data['Voucher']['id'] = $this->Voucher->getLastInsertID();
					}
					
					$vouNum = $this->data['Voucher']['vou_number'] - $this->data['Voucher']['existing_number'];
					$this->generateVoucherCode($this->data['Voucher']['id'], $vouNum);
					
					/*$arrNewItem is for adding a node into menu hierachy*/
					$arrNewItem = array ( 
											'id'=>$this->data['Voucher']['id'],
											'ctrl'=>'vouchers',
											'name'=>$this->data['Voucher']['vou_name']
										);
					$this->set('arrNewItem', $arrNewItem);
					/*****************************************************/
					
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->Voucher));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_codes_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
	
				$data = $this->VoucherCode->findById($items[0]['id']);
	
				$this->set($this->currentItem, $data);
			}
				
			$this->render ('admin_codes_edit', 'ajax');
		}
	}
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($parentId)) {
					
				$this->admin_codes_edit($parentId);
			
			} else {
				
				if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
					$items = unserialize(stripslashes($this->params['form']['selItems']));
	
					$data = $this->Voucher->findById($items[0]['id']);
	
					$this->set($this->currentItem, $data);
				}
				
				$this->render ('admin_edit', 'ajax');	
			}
		}
	}
	
	function admin_codes_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				$count = 0;
				foreach ( $items as $item ) {
					$this->VoucherCode->delete($item[ 'id' ]);
					$count ++;
				}
				if ($count > 0) {
					$this->Voucher->recursive = -1;
					$voucher = $this->Voucher->findById($parentId, array('vou_number'));
					$newCount = $voucher['Voucher']['vou_number'] - $count;
					if ($newCount < 0) $newCount = 0;
					
					$this->Voucher->id = $parentId;
					$this->Voucher->saveField('vou_number', $newCount);
				}
			}
			$this->admin_codes_view($parentId);
		}
	}
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($parentId)) {
					
				$this->admin_codes_delete($parentId);
			
			} else {
	
				if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
					$items = unserialize(stripslashes($this->params['form']['selItems']));
					foreach ( $items as $item ) {
				    	$this->Voucher->delete($item[ 'id' ]);
					}
				}
				$this->admin_view($parentId);
			}
		}
	}
}
?>
