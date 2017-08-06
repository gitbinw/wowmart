<?php
class Competition extends AppModel {
	var $name = 'Competition';
	
	var $validate = array(
		'title' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Please select your title.',
					'allowEmpty' => false,
					'required' => true
				)
			),
			
			'firstname' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Please enter your first name.',
					'allowEmpty' => false,
					'required' => true
				)
			),
			'lastname' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Please enter your last name.',
					'allowEmpty' => false,
					'required' => true
				)
			),
		  	'email' => array(
					'validemail' => array(
						'rule' => array('email'),
						//'message' => 'Your email: \'%%email%%\' is a not valid email address.',
						'message' => 'Your email is a not valid email address.'
						//'allowEmpty' => false,
						//'required' => true,
						//'last' => false, // Stop validation after this rule
						//'on' => 'create', // Limit validation to 'create' or 'update' operations
					),
					'unique' => array(
						'rule' => array('isUnique'),
						//'message' => 'Your email: \'%%email%%\' has already been subscribed.'
						'message' => 'Your has already been entered in our competition.'
					),
					'required' => array(
						'rule' => 'notEmpty',
						'message' => 'Please enter your email to subscribe.'
					)
			),
			'phone' => array(
				'notempty' => array(
					'rule' => array('phone', "/[0-9\s-+]/"),
					'message' => 'Please enter a valid phone or mobile number.',
					'allowEmpty' => false,
					'required' => true
				)
			),
			
	);
}
?>