<?php
class LocationsController extends AppController {

	var $uses = array('Location', 'Supplier');
	var $helpers = array('Javascript');
	var $components = array('RequestHandler');
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('index', 'countries', 'ajaxSearch', 'check');
	}
	
	function view($lid) {
		return $this->Location->findById($lid);
	}
	
	function check() {
		if ($this->RequestHandler->isAjax()) {
			$loc = $this->params['form']['location'];
		}
	}
	
	function ajaxSearch() {
		if ($this->RequestHandler->isAjax()) {
			$countryId = $this->data['Country']['id'];
			$keywords  = $this->data['Location']['keywords'];
		
			$conditions = array(
		  									'AND' => array(
		  											array('Location.country_id' => $countryId)
		  									),
		  									'OR' => array (
		  											array('Location.postcode LIKE' => $keywords . '%'),
		  											array('Location.suburb LIKE'   => '%' . $keywords . '%'),
														array('CONCAT(Location.suburb, " ", 
																	Location.postcode) LIKE' => $keywords . '%')
		  									)
		  							);
			$results = $this->Location->find('all', array(
																					'fields' => array('Location.id', 'Location.suburb', 'Location.state', 'Location.postcode'),
																					'conditions' => $conditions,
																					'order' =>  array('Location.suburb', 'Location.state'),
																					'limit' => 10
																			));
			
  		$this->autoRender = false;													
  		
  		$response = new stdClass;
  		$response->success = ($results && count($results) > 0) ? true : false;
  		$response->results = array();
  		
  		foreach($results as $res) {
  				$lineId = $res['Location']['id'];
  				$line = ucwords(strtolower($res['Location']['suburb'])) . " " . 
  						strtoupper($res['Location']['state']) . " " . 
  						$res['Location']['postcode'];
  				$response->results[$lineId] = $line;
  		}

  		echo json_encode($response);
  	}
	}
	
	function admin_get($str_locations, $is_supplier = 0) {
		if ($is_supplier == 1) {
			$this->Supplier->recursive = -1;
			$supplier = $this->Supplier->findById ($str_locations, array('locations'));
			$str_locations = $supplier['Supplier']['locations'];
		}
		$locations = explode(',', $str_locations);
		$locParams = array(
			'conditions' => array('id' => $locations),
			'recursive' => -1,
			'fields' => array('id', 'suburb', 'postcode', 'state')
		);
		
		$locs = $this->Location->find('all', $locParams);
		$this->autoRender = false;
		if ($is_supplier == 1) {	
			echo json_encode($locs);
		} else {
			return $locs;
		}
	}

}
?>