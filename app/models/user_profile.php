<?php
class UserProfile extends AppModel {
	var $name = 'UserProfile';
		
	var $validate = array(
		'firstname' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your first name',
				'allowEmpty' => false,
				'required' => false
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'lastname' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your last name',
				'allowEmpty' => false,
				'required' => false
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);
}
?>
