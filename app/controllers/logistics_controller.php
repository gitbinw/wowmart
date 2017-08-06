<?php
class LogisticsController extends AppController {
	var $uses = array('LogisticsCompany', 'LogisticsPrice');
	var $currentItem  = 'thisItem';
	var $listItems    = 'arrItems';
	var $helpers = array ('Javascript');
	var $components = array('RequestHandler', 'DocumentsUploader');
	var $strListName = 'thisItem';
	var $strParentName = 'parentItem';
	var $layout = 'default';
	
	function beforeFilter () {
		parent::beforeFilter();
	}
	
	private function buildTree ( &$tree, $parentId = null ) {
		if ( !isset ( $parentId ) ) $parentId = 0;
		$param = array(
			'recursive' => -1,
			'fields' => array('id','logi_company'),
			'order' => array('logi_company')
		);
		$arrRes = $this->LogisticsCompany->find('all', $param);
    	$tree .= "<ul>";
    	foreach ( $arrRes as $node ) {
    		$tree .= "<li><a id='".$node['LogisticsCompany']['id']."' ctrl='logistics'>".
    		       		$node['LogisticsCompany']['logi_company']."</a></li>";
    	}
    	$tree .= "</ul>";
	}
	
	/*
	 ************* Admin Panel Methods ********************
	 */
	
	function admin_prices_view($parentId, $keywords = '') {
		$this->LogisticsCompany->recursive = -1;
		$logi = $this->LogisticsCompany->findById($parentId, array('logi_alias', 'logi_company'));
		
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$this->paginate = array(
				'LogisticsPrice' => array(
						'page' => 1,
						'recursive' => -1,
						'order' => array('LogisticsPrice.logi_postcode' => 'asc'),
						'limit' => $this->limit,
						'fields' => array('LogisticsPrice.*'),
						'conditions' => array(
								'OR' => array(
										array('LogisticsPrice.logi_postcode LIKE' => '%' . $keywords . '%'),
										array('LogisticsPrice.logi_price LIKE' => '%' . $keywords . '%')
								),
								'LogisticsPrice.logi_alias' => $logi['LogisticsCompany']['logi_alias']
						)
				)
		);
		
		$arrItems = $this->paginate('LogisticsPrice');
		
		$this->set ( $this->strParentId,  $parentId );
		$this->set ( $this->strParentName,  $logi );
		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_prices_view', 'ajax');
	}
	
	function admin_tree() {
		$tree = '';
		$this->buildTree($tree);
		return $tree;
	}
	
	function admin_new ($parentId = 0) {
		if (!empty($parentId)) {
			$this->LogisticsCompany->recursive = -1;
			$logi = $this->LogisticsCompany->findById($parentId, array('logi_company', 'logi_alias'));
			
			$this->set ( $this->strParentName,  $logi );
			
			$this->render ('admin_prices_edit', 'ajax');
				
		} else {
			$this->render ('admin_edit', 'ajax');
		}	
	}
	
	function admin_view ($parentId=0, $keywords='') {
		if (!empty($parentId)) {
			
			$this->admin_prices_view($parentId, $keywords);
			
		} else {
			$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
			$this->paginate = array(
				'LogisticsCompany' => array(
					'page' => 1,
					'recursive' => -1,
					'order' => array('LogisticsCompany.logi_company' => 'asc'),
					'limit' => $this->limit,
					'fields' => array('LogisticsCompany.*'),
					'conditions' => array(
							'OR' => array(
								array('LogisticsCompany.logi_company LIKE' => '%' . $keywords . '%'),
								array('LogisticsCompany.logi_type LIKE' => '%' . $keywords . '%'),  
								array('LogisticsCompany.logi_unit LIKE' => '%' . $keywords . '%'),
								array('LogisticsCompany.logi_gst LIKE' => '%' . $keywords . '%'),
								array('LogisticsCompany.logi_fuel LIKE' => '%' . $keywords . '%')
							)
					)
				)
			);
					
			$arrItems = $this->paginate('LogisticsCompany');
	
			$this->set ( $this->strListName,  $arrItems );
			$this->render ('admin_view', 'ajax');
		}
	}
	
	function admin_prices_save($parentId = 0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
		
				$this->data['LogisticsPrice']['logi_alias'] = $this->makeAlias($this->data['LogisticsPrice']['logi_company']);
		
				if ($this->LogisticsPrice->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['LogisticsPrice']['id'])) {//if create a new Logistics Company
						$this->data['LogisticsPrice']['id'] = $this->LogisticsPrice->getLastInsertID();
					}
							
					/*$arrNewItem is for adding a node into menu hierachy*/
					//$arrNewItem = array (
					//	'id'=>$this->data['LogisticsPrice']['id'],
					//	'ctrl'=>'logistics',
					//	'name'=>$this->data['LogisticsPrice']['logi_company']
					//);
					//$this->set('arrNewItem', $arrNewItem);
					/*****************************************************/
							
					$this->admin_prices_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->LogisticsPrice));
					$this->admin_new($parentId);
				}
			}
		}
	}
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($parentId)) {
					
				$this->admin_prices_save($parentId);
				
			} else if (!empty ($this->data)) {
				
				$this->data['LogisticsCompany']['logi_alias'] = $this->makeAlias($this->data['LogisticsCompany']['logi_company']);

				if ($this->LogisticsCompany->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['LogisticsCompany']['id'])) {//if create a new Logistics Company
						$this->data['LogisticsCompany']['id'] = $this->LogisticsCompany->getLastInsertID();
					}
					
					/*$arrNewItem is for adding a node into menu hierachy*/
					$arrNewItem = array ( 
											'id'=>$this->data['LogisticsCompany']['id'],
											'ctrl'=>'logistics',
											'name'=>$this->data['LogisticsCompany']['logi_company']
										);
					$this->set('arrNewItem', $arrNewItem);
					/*****************************************************/
					
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->LogisticsCompany));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_prices_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
	
				$data = $this->LogisticsPrice->findById($items[0]['id']);
	
				$this->set($this->currentItem, $data);
			}
				
			$this->render ('admin_prices_edit', 'ajax');
		}
	}
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($parentId)) {
					
				$this->admin_prices_edit($parentId);
			
			} else {
				
				if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				
					$items = unserialize(stripslashes($this->params['form']['selItems']));

					$data = $this->LogisticsCompany->findById($items[0]['id']);

					$this->set($this->currentItem, $data);
				}
			
				$this->render ('admin_edit', 'ajax');
			}	
		}
	}
	
	function admin_prices_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
					$this->LogisticsPrice->delete($item[ 'id' ]);
				}
			}
			$this->admin_prices_view($parentId);
		}
	}
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($parentId)) {
					
				$this->admin_prices_delete($parentId);
			
			} else {
				if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				
					$items = unserialize(stripslashes($this->params['form']['selItems']));
					foreach ( $items as $item ) {
			    		$this->LogisticsCompany->delete($item[ 'id' ]);
					}
			
				}
				$this->admin_view($parentId);
			}
		}
	}
	
	function admin_prices_upload () {
		if ($this->RequestHandler->isAjax()) {
			$res = $this->DocumentsUploader->extractCsvFile('uploaded_file', 
						array('postcode', 'basic_price', 'price'), 'LogisticsPrice');
			
			$this->autoRender = false;
	
			echo json_encode($res);
		}
	}
}
?>
