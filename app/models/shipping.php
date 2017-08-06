<?php
class Shipping extends AppModel {
	var $name = "Shipping";
	
	var $validate = array(
		'firstname' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your first name',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'lastname' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your last name',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'address1' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your address',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'suburb' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your suburb',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'state' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your state',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'postcode' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your postcode',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'phone' => array(
			'match' => array(
					'rule'	=>	array('checkPhone'),
					'required' => false,
					'message'	=>	'Please enter at least one value for your phone or mobile.'
				)
		)
	);
	
	function checkPhone($data) {
		if (empty($this->data[$this->alias]['phone']) && empty($this->data[$this->alias]['mobile'])) {
			return false;
		}
    return true;
  }  
  
}
?>