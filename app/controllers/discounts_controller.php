<?php
class DiscountsController extends AppController {
	var $uses = array('Discount');
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
			'fields' => array('id','dis_name'),
			'order' => array('dis_name')
		);
		$arrRes = $this->Discount->find('all', $param);
    	$tree .= "<ul>";
    	foreach ( $arrRes as $node ) {
    		$tree .= "<li><a id='".$node['Discount']['id']."' ctrl='discounts'>".
    		       		$node['Discount']['dis_name']."</a></li>";
    	}
    	$tree .= "</ul>";
	}
	
	/*
	 ************* Admin Panel Methods ********************
	 */
	
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
			
		} else {
			$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
			$this->paginate = array(
				'Discount' => array(
					'page' => 1,
					'recursive' => -1,
					'order' => array('Discount.dis_name' => 'asc'),
					'limit' => $this->limit,
					'fields' => array('Discount.*'),
					'conditions' => array(
							'OR' => array(
								array('Discount.dis_name LIKE' => '%' . $keywords . '%'),
								array('Discount.dis_percent LIKE' => '%' . $keywords . '%'),  
								array('Discount.dis_comments LIKE' => '%' . $keywords . '%')
							)
					)
				)
			);
					
			$arrItems = $this->paginate('Discount');
	
			$this->set ( $this->strListName,  $arrItems );
			$this->render ('admin_view', 'ajax');
		}
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				
				$this->data['Discount']['dis_alias'] = $this->makeAlias($this->data['Discount']['dis_name']);
				if (!isset($this->data['Discount']['dis_expiry']) || empty($this->data['Discount']['dis_expiry']) ) {
					$this->data['Discount']['dis_expiry'] = NULL;
				}
				
				if ($this->Discount->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Discount']['id'])) {//if create a new discount
						$this->data['Discount']['id'] = $this->Discount->getLastInsertID();
					}
					
					/*$arrNewItem is for adding a node into menu hierachy*/
					$arrNewItem = array ( 
											'id'=>$this->data['Discount']['id'],
											'ctrl'=>'discounts',
											'name'=>$this->data['Discount']['dis_name']
										);
					$this->set('arrNewItem', $arrNewItem);
					/*****************************************************/
					
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->Discount));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));

				$data = $this->Discount->findById($items[0]['id']);

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
			    	$this->Discount->delete($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}
}
?>
