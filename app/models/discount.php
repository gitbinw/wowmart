<?php
class Discount extends AppModel {
	var $name = 'Discount';
	
	var $validate = array(
		'dis_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name.',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'dis_alias' => array(
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
				'message' => 'The discount is already existing.'
			)
		),	
		'dis_percent' => array(
			'notempty' => array(
				//'rule' => array('decimal', 2),
				'rule' => array('notempty'),
				'message' => 'Please enter a percentage (e.g. 10.00) for this discount.',
				'allowEmpty' => false,
				'required' => true
			),
			'pattern' => array(
				'rule'	=> '/^[1-9]\d*(\.\d+)?$/i',
				'message' => 'Please enter a valid value (e.g. 10.00) for this discount.'
			)
		)	
	);

}
?>