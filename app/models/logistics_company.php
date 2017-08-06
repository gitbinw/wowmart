<?php
class LogisticsCompany extends AppModel {
	var $name = 'LogisticsCompany';
	
	var $validate = array(
		'logi_company' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter the company name.',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'logi_alias' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You have to enter an alias name.',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'The company is already existing.'
			)
		),
		'logi_type' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please select a type.',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'logi_gst' => array(
			'pattern' => array(
				'rule'	=> '/^[1-9]\d*(\.\d+)?$/i',
				'message' => 'Please enter a valid GST (e.g. 10.00).',
				'allowEmpty' => true,
				'required' => false
			)
		),	
		'logi_fuel' => array(
			'pattern' => array(
				'rule'	=> '/^[1-9]\d*(\.\d+)?$/i',
				'message' => 'Please enter a valid fuel fee (e.g. 100.00).',
				'allowEmpty' => true,
				'required' => false
			)
		)	
	);

}
?>