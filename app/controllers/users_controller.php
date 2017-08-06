<?php
class UsersController extends AppController {
	var $uses = array('User');
	var $helpers = array('Javascript', 'combobox');
	var $strListName = 'thisItem';
	var $components = array('RequestHandler', 'Email');
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('admin_logout');
		
		/*****The following code is to avoid password auto-encript*****/
		if($this->action == 'admin_save') {
			$this->Auth->authenticate = $this->User;
		}
	}
	
	function admin_login() {
		if ($this->RequestHandler->isAjax()) {
			if ($this->Auth->user()) {
				/*if (!empty($this->data)) {
					if (empty($this->data['User']['remember_me'])) {
						$this->Cookie->del('User');
					} else {
						$cookie = array();
						$cookie['email'] = $this->data['User']['email'];
						$cookie['token'] = $this->data['User']['pasword'];
						$this->Cookie->write('User', $cookie, true, '+2 weeks');
					}
					unset($this->data['User']['remember_me']);
				}*/
				//$this->redirect($this->Auth->redirect());
				
				$this->User->id = $this->Auth->user('id');
				$this->User->saveField('login_time', date('Y-m-d H:i:s'));
				$response['success' ] = true;
				$response['redirect'] = '/admin'; 
			} else {
				$response['success'] = false;
			}
			
			$this->autoRender = false;
			echo json_encode($response); 
		}
	}
	
	function admin_logout() {
		if ($this->Auth->user()) {
			$this->set('errorMessage', $this->Auth->authError);
		}

		$this->Auth->Session->delete('Permissions');
     	$this->redirect($this->Auth->logout());
	}

  
  private function setGroups($left = null, $right = null) {
  	if ($this->__permitted('groups', '')) {
  		$this->set("groups", $this->requestAction ("/admin/groups/get/0/true"));
  		
  		if (isset($left) && isset($right) && $left > 0 && $right > 0 ) {
  			$this->set("selected_groups", $this->getSelectedGroups($left, $right));
  		} else {
				$this->set("selected_groups", array());	
			}
		} else {
			$this->set("groups", array());
		}
  }
  
  private function getSelectedGroups ($left,$right) {
		$arrCats = array ();
		$params = array(
			'conditions' => array('lorder <=' => $left, 'rorder >=' . $right),
			'order' => array('lorder'),
			'recursive' => -1
		);
		$data = $this->User->Group->find('all', $params);		
		
		$lastLevelCat = '';
		foreach ( $data as $key=>$val ) {
			$arrCats [$key]['sel'][$val['Group']['id']]	= $val['Group']['id'];
			$lastLevelCat = $val['Group']['id'];
			$params = array(
				'conditions' => array('parent_id' => $val['Group']['parent_id']),
				'fields' => array('id', 'GroupDetail.name'),
				'order' => array('GroupDetail.name'),
				'recursive' => 0
			);
			$arrTmp = $this->User->Group->find('all', $params);
			
			$arrOptions = array ();
			foreach ( $arrTmp as $index => $option ) {
				$arrOptions [ $option [ 'Group' ][ 'id' ] ]= $option [ 'GroupDetail' ][ 'name' ];
			}
			$arrCats [$key]['list'] = $arrOptions;
		}
		
		$params = array(
				'conditions' => array('parent_id' => $lastLevelCat),
				'fields' => array('id', 'GroupDetail.name'),
				'order' => array('GroupDetail.name'),
				'recursive' => 0
		);
		$arrTmp = $this->User->Group->find('all', $params);
		
		if (count($arrTmp) > 0) {
			$index = count($arrCats);
			$arrCats [$index]['sel'] = array(0);
			$arrOptions = array ();
			foreach ( $arrTmp as $option ) {
				$arrOptions [ $option [ 'Group' ][ 'id' ] ]= $option [ 'GroupDetail' ][ 'name' ];
			}
			$arrCats [$index]['list'] = $arrOptions;
		}
		return $arrCats;
	}
	
  function admin_new() {
  	$this->setGroups();
		
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0, $groupId = 0) {
		$param = array(
				'recursive' => 2,
				'order' => array('UserProfile.firstname', 'UserProfile.lastname')
		);
		if (isset($groupId) && $groupId > 0) {//retrieving HABTM data 
			$this->User->bindModel(array('hasOne' => array('UsersGroup')));
			$param['conditions'] = array('UsersGroup.group_id' => $groupId);
	  }
		$arrItems = $this->User->find('all', $param);
		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				if (isset($this->data['User']['id']) && !empty($this->data['User']['id'])) { //update user
					if (!isset($this->data['change_psw']) || $this->data['change_psw'] != 1) {
						$this->User->resetPasswordValidate(); //if not to change password, then do not validate password
						unset($this->data['User']['password']);
					}
				} 
				
				if ($this->User->saveAll($this->data, array('validate'=>'first'))) {
					$this->admin_view($parentId);
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->User));
					
					if (isset($this->data['User']['id']) && !empty($this->data['User']['id'])) { //update user
						$data = $this->User->findById($this->data['User']['id']);
			  		if (isset($data['Group'][0]['lorder']) && isset($data['Group'][0]['rorder'])) {
							$this->setGroups($data['Group'][0]['lorder'], $data['Group'][0]['rorder']);
						} else {
							$this->setGroups();
						}
					} else {
						$this->setGroups();
					}
					
					$this->render ('admin_edit', 'ajax');	
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
			  $data = $this->User->findById($items[0]['id']);
			  if (isset($data['Group'][0]['lorder']) && isset($data['Group'][0]['rorder'])) {
					$this->setGroups($data['Group'][0]['lorder'], $data['Group'][0]['rorder']);
				} else {
					$this->setGroups();
				}
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
			    $this->User->delete($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}

}
?>
