<?php
class Subscription extends AppModel {
	var $name = 'Subscription';
	
	var $validate = array(
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
					'message' => 'Your email has already been subscribed.'
				),
				'required' => array(
        	'rule' => 'notEmpty',
        	'message' => 'Please enter your email to subscribe.'
     		)
			),
			
			'fullname' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Please enter your full name.',
					'allowEmpty' => false,
					'required' => true
				)
			)
	);
}
?>