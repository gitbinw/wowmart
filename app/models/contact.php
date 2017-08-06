<?php
class Contact extends AppModel {
	var $name = "Contact";
	
	var $validate = array(
		/*'alias' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name for this address.',
				'allowEmpty' => false,
				'required' => true
			),
		),*/
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
					'rule'	=>	array('phone', '/^[0-9\s-+()]*$/', 'au'),
					'required' => false,
					'message'	=>	'Please enter your phone or mobile number.'
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