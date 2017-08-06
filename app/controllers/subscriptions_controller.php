<?php
class SubscriptionsController extends AppController {
	var $currentItem  = 'thisItem';
	var $listItems    = 'arrItems';
	var $helpers = array ('Javascript');
	var $components = array('RequestHandler');
	
	function beforeFilter () {
		parent::beforeFilter();
		
		$this->Auth->allow('subscribe', 'unsubscribe');
	}
	
	/*
	 ************* Front End Methods   ********************
	 */
	 
	function subscribe () {
		if (!empty ($this->data)) {
			if ($this->Subscription->saveAll($this->data, array('validate'=>'first'))) {
				$this->set('success', true);
			} else {
				$this->set('errors', $this->validateErrors($this->Subscription));
			}
			$this->set($this->currentItem, $this->data);
		}
	}
	
	/*
	 ************* Admin Panel Methods ********************
	 */
	 
}
?>
