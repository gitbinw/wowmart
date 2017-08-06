<?php
class ErrorsController extends AppController {
	var $uses = array();
	var $helpers = array('javascript');
	
	function beforeFilter() {
		parent::beforeFilter();
	
		$this->Auth->allow('index', 'admin_index', 'notfound');
	}
	
	function index($errorCode) {
		$error = "";
		switch($errorCode) {
			case '403' :
				$error = "";
				if ($this->Auth->user()) {
					$this->Auth->Session->delete('Permissions');
					$this->Auth->logout();
					$error = "Sorry, you are lacking access. Please contact system administrator.";
				}
				else $error = "You don't have permission to access this page. <br>" . 
											"Please log in!";
				$this->set('errorMessage', $error);
				$this->autoRender = false;
				$this->render('index', 'page');
				break;
		}
	}
	
	function admin_index($errorCode) {
		$error = "";
		$layout = 'ajax';
		switch($errorCode) {
			case '403' :
				$error = "";
				if ($this->Auth->user()) {
					$this->Auth->Session->delete('Permissions');
					$this->Auth->logout();
					$error = "Sorry, you are lacking access. Please contact system administrator.";
					$layout = 'admin';
				}
				else $error = "You have lost the session to operate the admin page. <br>" . 
											"Please login again!";
			//	$this->Session->setFlash($error, "/admin/users/logout", 10, 'ajax');
				$this->set('errorMessage', $error);
				$this->autoRender = false;
				$this->render('admin_index', $layout);
				break;
		}
	}
	
	function notfound($model) {
		$error = "The " . $model . " you are looking for is not existing.";
		$this->set('errorMessage', $error);
		$this->autoRender = false;
		$this->render('notfound', 'page');
	}
	
}
?>
