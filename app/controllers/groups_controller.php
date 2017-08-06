<?php
class GroupsController extends AppController {
	var $helpers = array ('combobox');
	var $strListName = 'thisItem';
	var $components = array('RequestHandler');
	
	private function buildTree ( &$tree, $parentId = null ) {
		if ( !isset ( $parentId ) ) $parentId = 0;
		$param = array(
			'conditions' => array('parent_id' => $parentId),
			'fields' => array('id','lorder','rorder','GroupDetail.name'),
			'order' => array('GroupDetail.name')
		 );
		$arrRes = $this->Group->find('all', $param);
    $tree .= "<ul>";
    foreach ( $arrRes as $node ) {
    	$tree .= "<li><a id='".$node['Group']['id']."' ctrl='groups'>".
    		       $node['GroupDetail']['name']."</a>";
      if ( $node['Group']['rorder'] - $node['Group']['lorder'] == 1 ) $tree .= "</li>";
      else {
        $this->buildTree ( $tree, $node['Group']['id'] );
        $tree .= "</li>";
      }
	  /*if ( $node['Group']['rorder'] - $node['Group']['lorder'] > 1 ) {
	  	$tree .= "<li><a id='".$node['Group']['id']."' ctrl='groups'>".
    		       $node['GroupDetail']['name']."</a>";
		$this->buildTree ( $tree, $node['Group']['id'] );
        $tree .= "</li>";
	  }*/
	  
    }
    $tree .= "</ul>";
	}
	
	function admin_get ($parentId=0, $return = false) {
		$param = array(
			'conditions' => array('parent_id' => $parentId),
			'recursive' => 0,
			'order' => array('GroupDetail.name')
		);
		$data = $this->Group->find('all', $param);
		
		$arrOptions = array ();
		foreach ( $data as $key => $option ) {
			$arrOptions [ $option [ 'Group' ][ 'id' ] ]= $option [ 'GroupDetail' ][ 'name' ];
		}
		
		$this->set($this->strListName,$arrOptions);	
		if ($return == true) return $arrOptions;
		else $this->render('admin_get', 'ajax');
	}
	
	function admin_tree() {
		$tree = '';
		$this->buildTree($tree);
		return $tree;
	}
	
	function admin_new () {
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0) {
		$param = array(
			'conditions' => array('parent_id' => $parentId),
			'recursive' => 0,
			'order' => array('GroupDetail.name')
		 );
		$arrCats = $this->Group->find('all', $param);
		$this->Group->User->unbindModel(array('hasAndBelongsToMany'=>array('Group')));
		
		$param = array(
			'conditions' => array('Group.id' => $parentId),
			'recursive' => 1,
			'order' => array('GroupDetail.name')
		 );
		$arrProds = $this->Group->find('first', $param);
		
		$this->set ( "arrGroup",  $arrCats );
		$this->set ( "arrClient", $arrProds );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				if (empty($this->data['Group']['id'])) {//if create a new group
					$this->Group->getMPTT($parentId, $this->data);
				}
				if ($this->Group->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Group']['id'])) {//if create a new group
						$this->data['Group']['id'] = $this->Group->getLastInsertID();
						$this->Group->updateMPTT($this->data['Group']['lorder'] - 1, 
																		 $this->data['Group']['id']);
					}
					
					$arrNewItem = array ( 
											'id'=>$this->data['Group']['id'],
											'ctrl'=>'groups',
											'name'=>$this->data['GroupDetail']['name']
										);
					$this->set('arrNewItem', $arrNewItem);
						
					$this->admin_view($parentId);
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->Group));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
			  $data = $this->Group->findById($items[0]['id']);

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
			    $this->Group->deleteNode($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}
	
}
?>