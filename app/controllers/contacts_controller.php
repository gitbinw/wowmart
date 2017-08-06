<?php
class ContactsController extends AppController {
	var $helpers = array('Javascript');
	var $components = array('RequestHandler');
	var $strListName = 'thisItem';
	
	function get() {
		if ($this->Auth->user()) {
			$params = array(
				'conditions' => array('user_id' => $this->Auth->user('id')),
				'order' => array('alias'),
				'recursive' => -1,
				'fields' => array('alias', 'firstname', 'lastname', 'address1',
								  'address2', 'suburb', 'state', 'postcode', 
								  'country', 'company', 'phone', 'mobile')
			);
			return $this->Contact->find('all', $params);
		} else {
			return false;
		}
	}
	
	function save() {
		if ($this->RequestHandler->isAjax()) {
			$response['success'] = false;
			$invalidContact = false;
			if (!empty ($this->data)) {
				//if edit contact
				if (isset($this->data['Contact']['id']) && !empty($this->data['Contact']['id'])) { 
					//Firstly, check if this contact belongs to this user
					$params = array(
						'conditions' => array(
							'user_id' => $this->Auth->user('id'),
							'id'	  => $this->data['Contact']['id']
						)
					);
					if (!$this->Contact->find('count', $params)) {
						$invalidContact = true;
					}
				} 
				
				if ($invalidContact) {
					$response['errors'] = array('id' => "You don't have this contact.");
				} else {
					$this->data['Contact']['user_id'] = $this->Auth->user('id');
					if (!$this->Contact->hasAny('is_billing=1 AND user_id=' . $this->Auth->user('id'))) {
						$this->data['Contact']['is_billing'] = 1;
					}
					if (!$this->Contact->hasAny('is_shipping=1 AND user_id=' . $this->Auth->user('id'))) {
						$this->data['Contact']['is_shipping'] = 1;
					}
					if ($this->Contact->saveAll($this->data, array('validate' => 'first'))) {
						unset($this->data['Contact']['user_id']);
						if (empty($this->data['Contact']['id'])) {
							$this->data['Contact']['id'] = $this->Contact->getLastInsertID();
						}
						$response['success'    ] = true;
						$response['returnValue'] = $this->data['Contact'];
					} else {
						$response['errors' ] = $this->validateErrors($this->Contact);	
					}
				}
			}
			$this->autoRender = false;
			echo json_encode($response);
		}
	}
	
	function view() {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->data['Contact']['id']) && !empty($this->data['Contact']['id'])) {
				$params = array(
					'conditions' => array(
						'user_id' => $this->Auth->user('id'),
						'id' => $this->data['Contact']['id']
					),
					'recursive' => -1
				);
				$this->set($this->strListName, $this->Contact->find('first', $params));
			}
			$this->render('contact', 'ajax');
		}
	}
	
	function remove() {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->data['Contact']['id']) && !empty($this->data['Contact']['id'])) {
				$response['success'] = false;
				$conditions = array(
					'user_id' => $this->Auth->user('id'),
					'id'	  => $this->data['Contact']['id']
				);
				if ($this->Contact->deleteAll($conditions)) {
					$response['success'] = true;
					$response['returnValue'] = $this->data['Contact']['id'];
				}
				$this->autoRender = false;
				echo json_encode($response);
			}
		}
	}
	
	function setDefault() {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->data['Contact']['id']) && !empty($this->data['Contact']['id'])) {
				$response['success'] = false;
				$this->Contact->id = $this->data['Contact']['id'];
				$params = array(
					'conditions' => array(
						'user_id' => $this->Auth->user('id'),
						'id'	  => $this->data['Contact']['id']
					),
					'recursive' => -1
				);
				if ($row = $this->Contact->find('first', $params)) {
					$fieldName = 'is_' . $this->data['type'];
					if ($this->Contact->updateAll(
										array($fieldName => 0), 
										array('user_id' => $this->Auth->user('id'))
									)) {
						if ($this->Contact->saveField('is_' . $this->data['type'], 1)) {
							$response['success'] = true;
							$response['returnValue']['is_billing'] = $row['Contact']['is_billing'];
							$response['returnValue']['is_shipping'] = $row['Contact']['is_shipping'];
							$response['returnValue'][$fieldName] = 1;	
						}
					}
				}
				$this->autoRender = false;
				echo json_encode($response);
			}
		}
	}
}
?>