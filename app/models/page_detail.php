<?php
class PageDetail extends AppModel {
	var $name = 'PageDetail';
	var $belongsTo = 'Page';
	
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a page name',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'alias' => array(
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
				'message' => 'This alias is existing. It must be unique.'
			)
		)
	);
}
?>