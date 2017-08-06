<?php
class FormsController extends AppController {
	var $currentItem  = 'thisItem';
	var $helpers = array ('Javascript');
	var $components = array('RequestHandler');
	var $uses = array ('Subscription', 'Recipe', 'Competition');
	
	function beforeFilter () {
		//parent::beforeFilter();
		$this->Auth->allow('*');
	}
	
	/*
	 ************* Front End Methods   ********************
	 */
	 
	function subscribe () {
		if ($this->RequestHandler->isAjax()) {
			$response['success'] = false;
			if (!empty ($this->data)) {
				if (!empty($this->data['month']) && !empty($this->data['day']))
					$this->data['Subscription']['dob'] = $this->data['day'] . "/" . $this->data['month'];
					
				if ($this->Subscription->saveAll($this->data, array('validate'=>'first'))) {
					$response['success'    ] = true;
				} else {
					//$response['errors' ] = str_replace('%%email%%', $this->data['Subscription']['email'],
					//																		$this->validateErrors($this->Subscription));	
					$response['errors' ] = $this->validateErrors($this->Subscription);
				} 
				$this->set($this->currentItem, $this->data);
			}
			$this->autoRender = false;
			echo json_encode($response);
		}
	}
	
	function recipe () {
		if ($this->RequestHandler->isAjax()) {
			$response['success'] = false;
			if (!empty ($this->data)) {
				if ($this->Recipe->saveAll($this->data, array('validate'=>'first'))) {
					$response['success'    ] = true;
				} else {
					$response['errors' ] = $this->validateErrors($this->Recipe);	
				} 
			}
			$this->autoRender = false;
			echo json_encode($response);
		}
	}
	
	function competition () {
		if ($this->RequestHandler->isAjax()) {
			$response['success'] = false;
			if (!empty ($this->data)) {
				if (isset($this->data['Competition']['friend']) && count($this->data['Competition']['friend']) > 0) {
					$validate = new Validation();
					$this->data['Competition']['friendEmails'] = '';
					foreach($this->data['Competition']['friend'] as $key=>$email) {
						if ($validate->email($email)) {
							if($key==0) $this->data['Competition']['friendEmails'] = $email;
							else $this->data['Competition']['friendEmails'] .= ',' . $email;
						}
					}
				}
				if ($this->Competition->saveAll($this->data, array('validate'=>'first'))) {
					$response['success'    ] = true;
				} else {
					$response['errors' ] = $this->validateErrors($this->Competition);	
				} 
			}
			$this->autoRender = false;
			echo json_encode($response);
		}
	}
	
	/*
	 ************* Admin Panel Methods ********************
	 */
	 
}
?>
