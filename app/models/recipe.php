<?php
class Recipe extends AppModel {
	var $name = 'Recipe';
	
	var $validate = array(
      'email' => array(
				'validemail' => array(
					'rule' => array('email'),
					'message' => 'Your email: \'%%email%%\' is a not valid email address.',
					//'allowEmpty' => false,
					//'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'required' => array(
        	'rule' => 'notEmpty',
        	'message' => 'Please enter your email.'
     		)
			),
			'recipe' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Please enter your recipe.',
					'allowEmpty' => false,
					'required' => true
				)
			)
	);
}
?>