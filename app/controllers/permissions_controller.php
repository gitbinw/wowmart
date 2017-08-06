<?php
class PermissionsController extends AppController {
	var $strListName = 'thisItem';
	var $components = array('RequestHandler');
	
	function admin_get ($pid=0) {
		$param = array(
			'recursive' => -1,
			'order' => array('Permission.name')
		);
		if (!empty($pid)) $param['conditions'] = array('Permission.id' => $pid);
		$data = $this->Permission->find('all', $param);
		
		return $data;
	}
	
	function admin_new () {
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			$param = array(
				'recursive' => 0,
				'order' => array('Permission.name')
		 	);
			$arrItems = $this->Permission->find('all', $param);
		
			$this->set ( $this->strListName,  $arrItems );
			$this->render ('admin_view', 'ajax');
		}
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				if ($this->Permission->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Permission']['id'])) {
						$this->data['Permission']['id'] = $this->Permission->getLastInsertID();
					}
						
					$this->admin_view($parentId);
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->Permission));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
			  $data = $this->Permission->findById($items[0]['id']);
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
			    $this->Permission->delete($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}
	
}
?>