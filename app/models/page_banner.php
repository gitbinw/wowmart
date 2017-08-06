<?php
class PageBanner extends AppModel {
	var $name = 'PageBanner';
	
	var $validate = array(
		/*'alias' => array(
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
		)*/
	);	
}
?>