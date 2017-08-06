<?php
class VoucherCode extends AppModel {
	var $name = 'VoucherCode';
	var $belongsTo = array('Voucher', 'User');
	
	var $validate = array(
		'vou_code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You have to enter a voucher code.',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'The voucher code is already existing.'
			)
		),	
		'voucher_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You have to enter a voucher id.',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'update' // Limit validation to 'create' or 'update' operations
			)
		)
			
	);
	
}
?>