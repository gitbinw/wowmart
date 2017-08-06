<?php
class RewardsController extends AppController {
	var $uses = array('Reward');
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
			'fields' => array('id','rew_name'),
			'order' => array('rew_name')
		);
		$arrRes = $this->Reward->find('all', $param);
    	$tree .= "<ul>";
    	foreach ( $arrRes as $node ) {
    		$tree .= "<li><a id='".$node['Reward']['id']."' ctrl='rewards'>".
    		       		$node['Reward']['rew_name']."</a></li>";
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
				'Reward' => array(
					'page' => 1,
					'recursive' => -1,
					'order' => array('Reward.rew_name' => 'asc'),
					'limit' => $this->limit,
					'fields' => array('Reward.*'),
					'conditions' => array(
							'OR' => array(
								array('Reward.rew_name LIKE' => '%' . $keywords . '%'),
								array('Reward.rew_value LIKE' => '%' . $keywords . '%'),  
								array('Reward.rew_comments LIKE' => '%' . $keywords . '%')
							)
					)
				)
			);
					
			$arrItems = $this->paginate('Reward');
	
			$this->set ( $this->strListName,  $arrItems );
			$this->render ('admin_view', 'ajax');
		}
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				
				$this->data['Reward']['rew_alias'] = $this->makeAlias($this->data['Reward']['rew_name']);
				if (!isset($this->data['Reward']['rew_expiry']) || empty($this->data['Reward']['rew_expiry']) ) {
					$this->data['Reward']['rew_expiry'] = NULL;
				}
				
				if ($this->Reward->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Reward']['id'])) {//if create a new Reward
						$this->data['Reward']['id'] = $this->Reward->getLastInsertID();
					}
					
					/*$arrNewItem is for adding a node into menu hierachy*/
					$arrNewItem = array ( 
											'id'=>$this->data['Reward']['id'],
											'ctrl'=>'rewards',
											'name'=>$this->data['Reward']['rew_name']
										);
					$this->set('arrNewItem', $arrNewItem);
					/*****************************************************/
					
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->Reward));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));

				$data = $this->Reward->findById($items[0]['id']);

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
			    	$this->Reward->delete($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}
}
?>
