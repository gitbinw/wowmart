<?php
class LogisticsPrice extends AppModel {
	var $name = 'LogisticsPrice';
	
	var $validate = array(
		'logi_company' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter the company name.',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'logi_postcode' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a postcode.',
				'allowEmpty' => false,
				'required' => true
			)
		),
		'logi_price' => array(
			'pattern' => array(
				'rule'	=> '/^[1-9]\d*(\.\d+)?$/i',
				'message' => 'Please enter a valid price (e.g. 10.00).',
				'allowEmpty' => false,
				'required' => true
			)
		)
	);

}
?>