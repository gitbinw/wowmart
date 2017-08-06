<?php
class Voucher extends AppModel {
	var $name = 'Voucher';
	var $hasMany = array('VoucherCode');
	
	var $validate = array(
		'vou_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a name.',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'vou_alias' => array(
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
				'message' => 'The voucher is already existing.'
			)
		),	
		'vou_value' => array(
			'notempty' => array(
				//'rule' => array('decimal', 2),
				'rule' => array('notempty'),
				'message' => 'Please enter a value (e.g. 50) for this voucher.',
				'allowEmpty' => false,
				'required' => true
			),
			'pattern' => array(
				'rule'	=> '/^[1-9]\d*(\.\d+)?$/i',
				'message' => 'Please enter a valid value (e.g. 50.00) for this voucher.'
			)
		),	
		'vou_number' => array(
			'notempty' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter how many voucher codes for this voucher.',
				'allowEmpty' => false,
				'required' => true
			),
			'length' => array(
				'rule' => array('maxLength', 4),
				'message' => 'The number no more than 9999.',
			),
			'checknumber' => array(
				'rule' => array('checkVoucherNumber'),
				'message' => 'The number must not be lower than the current.'
			)
		)
			
	);
	
	function checkVoucherNumber($data) {
    	if ($this->data[$this->alias]['existing_number'] > $data['vou_number']) {
      		return false;
    	}
    	return true;
  	}  
}
?>