<?php
class CategoryDetail extends AppModel {
	var $name = 'CategoryDetail';
	
	var $belongsTo = array('Discount', 'Reward', 'Voucher');
	
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a category name',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'category_alias' => array(
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'This alias is duplicated, please check.'
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Category alias is required',
				'allowEmpty' => false,
				'required' => true
			)
		)
	);
}
?>