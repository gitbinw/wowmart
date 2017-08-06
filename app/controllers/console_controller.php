<?php
class ConsoleController extends AppController {
	var $uses = array('AdminMenu');
	var $pageTitle = 'Console Panel';
	var $helpers = array ('Javascript');
	var $components = array('RequestHandler');
	var $strListName = 'thisItem';
	var $currentItem  = 'thisItem';
	var $listItems    = 'arrItems';
	
	function beforeFilter () {
		parent::beforeFilter();
	}
	
	function admin_index () {
	}
	
	function admin_menu() {
		if ($this->RequestHandler->isAjax()) {
			$params = array(
				'recursive' => -1,
				'conditions' => array(
					'parent_id' => 0,
					'showChildren' => 1
				),
				'fields' => array('ctrl')
			);
			$treeMenu = $this->AdminMenu->find('all', $params);
			$arrMenuList = array();
			if (isset($treeMenu) && is_array($treeMenu)) {
				foreach($treeMenu as $m) {
					$arrMenuList[] = $m['AdminMenu']['ctrl'];
				}
			}
			
			$tree = '';
			$this->buildTree($tree);
			$this->set ( "menu_tree", $tree );
			$this->set ( "menu_list", $arrMenuList );
			$this->render ('admin_menu', 'ajax');
		}
	}
	
	private function buildTree ( &$tree, $parentId = null ) {
		$this->autoRender = false;
		if ( !isset ( $parentId ) ) $parentId = 0;
		
		$param = array(
			'conditions' => array('AdminMenu.parent_id' => $parentId),
			'order' => 'AdminMenu.showChildren DESC, AdminMenu.name'
		 );

		$arrRes = $this->AdminMenu->find('all', $param);
		$tree .= "<ul id='menu_tree'>";
    	foreach ( $arrRes as $node ) {
    		if($this->__permitted($node['AdminMenu']['ctrl'], '')){
				$strAdmin = $node['AdminMenu']['ctrl'] == 'console' ? 'data-menu="admin"' : '';
    			$tree .= "<li><a id='0' ctrl='".
    						$node['AdminMenu']['ctrl'].
    						"' " . $strAdmin . " args='".trim($node['AdminMenu']['params'])."'>".
         					$node['AdminMenu']['name']."</a>";
				if ( $node['AdminMenu']['showChildren'] == 1 ) {
					$tree .= $this->requestAction("/admin/" . $node['AdminMenu']['ctrl'] . "/tree")."</li>";
				
				} else if ($node['AdminMenu']['ctrl'] == 'console') {
					$tree .= $this->getAdminMenu() . "</li>";
					
				} else if ( !$this->AdminMenu->hasAny('parent_id='.$node['AdminMenu']['id']) ) {
					$tree .= "</li>";
				} else {
        			$this->buildTree ( $tree, $node['AdminMenu']['id'] );
        			$tree .= "</li>";
      			}
      		}
    	}
    	$tree .= "</ul>";
  	}
	
	private function getAdminMenu() {
		$param = array(
			'conditions' => array(
				'AdminMenu.parent_id' => 0,
				'AdminMenu.ctrl <>' => 'console'
			),
			'order' => array('priority')
		 );

		$arrRes = $this->AdminMenu->find('all', $param);
		
		$tree = "<ul id='menu_tree'>";
    	foreach ( $arrRes as $node ) {
    		if($this->__permitted($node['AdminMenu']['ctrl'], '')){
    			$tree .= "<li><a id='" . $node['AdminMenu']['id'] . "' ctrl='console' args=''>".
         					$node['AdminMenu']['name']."</a>";
      		}
    	}
    	$tree .= "</ul>";
		
		return $tree;
	}
	
	function admin_view ($parentId=0, $keywords='') {
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$this->paginate = array(
			'AdminMenu' => array(
				'page' => 1,
				'recursive' => -1,
				'order' => array('AdminMenu.name' => 'asc'),
				'limit' => $this->limit,
				'fields' => array('AdminMenu.*'),
				'conditions' => array(
					'OR' => array(
						array('AdminMenu.name LIKE' => '%' . $keywords . '%'),
						array('AdminMenu.ctrl LIKE' => '%' . $keywords . '%')
					),
					'parent_id' => $parentId
				)
			)
		);
				
		$arrItems = $this->paginate('AdminMenu');

		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_menu_view', 'ajax');
	}
	
	function admin_new () {
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				$this->data['AdminMenu']['parent_id'] = $parentId;

				if ($this->AdminMenu->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['AdminMenu']['id'])) {//if create a new menu
						$this->data['AdminMenu']['id'] = $this->AdminMenu->getLastInsertID();
					}
					
					/*$arrNewItem is for adding a node into menu hierachy*/
					$arrNewItem = array ( 
											'id'=>$this->data['AdminMenu']['id'],
											'ctrl'=>'console',
											'name'=>$this->data['AdminMenu']['name']
										);
					$this->set('arrNewItem', $arrNewItem);
					/*****************************************************/
					
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->AdminMenu));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));

				$data = $this->AdminMenu->findById($items[0]['id']);

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
			    	$this->AdminMenu->deleteNode($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}
}
?>
