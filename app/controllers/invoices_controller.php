<?php
class InvoicesController extends AppController {
	var $uses = array ('Order', 'Invoice', 'Store');
	var $helpers = array ('Javascript', 'Fpdf');
	var $components = array ('Session', 'RequestHandler');
	var $strListName = 'thisItem';
	
	function admin_view() {
		$param = array(
			'recursive' => 1,
			'order' => array('Invoice.created DESC')
		);
		$arrItems = $this->Invoice->find('all', $param);
		
		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
			    if ($this->Invoice->delete($item[ 'id' ])) {
			    	$this->Order->updateAll(
			    		array('invoice_id' => null), 
			    		array('invoice_id' => $item[ 'id' ])
			    	);
			    }
				}
			}
			$this->admin_view($parentId);
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				$this->admin_get($items[0]['id']);
			}	
		}
	}
	
	function admin_get($invoiceId=0) {
		$this->Invoice->recursive = 1;
		$invoice = $this->Invoice->findById($invoiceId);
		$this->Order->recursive = 2;
		$this->set('store', $this->Store->findByAlias(STORE_NAME));
		$this->set('order', $this->Order->findById($invoice['Order'][0]['id']));
		$this->render ('admin_edit', 'ajax');	
	}
	
	
	function admin_output () {
		if (isset($this->params['named']['type']) && isset($this->params['named']['pid'])) {
			$type = $this->params['named']['type'];
			$invoiceId = $this->params['named']['pid'];
			switch($type) {
				case 'pdf' :
					$this->Invoice->recursive = 1;
					$invoice = $this->Invoice->findById($invoiceId);
					
					$this->Order->recursive = 2;
					$this->set('order', $this->Order->findById($invoice['Order'][0]['id']));
					$this->set('store', $this->Store->findByAlias(STORE_NAME));
          $this->render('pdf', 'file/pdf');
          
					break;
			}
		}
	}
	
}
?>