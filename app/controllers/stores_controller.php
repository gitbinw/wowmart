<?php
class StoresController extends AppController {
	var $uses = array('Store');
	var $pageTitle = 'Store Setting';
	var $components = array('RequestHandler');
	var $strListName = 'thisItem';
	
	function beforeFilter () {
      parent::beforeFilter();
	}
	
	function admin_new () {
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId) {
		$param = array(
			'recursive' => -1,
			'order' => array('Store.company')
		 );
		$arrData = $this->Store->find('all', $param);
		
		$this->set ( $this->strListName,  $arrData );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				if ($this->Store->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Store']['id'])) {
						$this->data['Store']['id'] = $this->Store->getLastInsertID();
					}
						
					$this->admin_view($parentId);
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->Store));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
			  $data = $this->Store->findById($items[0]['id']);
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
			    $this->Store->delete($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}

}
?>
