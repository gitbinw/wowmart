<?php
class Supplier extends AppModel {
	var $name = 'Supplier';
	var $hasMany = array(
										'Product',
										'Image' => array(
												'foreignKey' => 'model_id',
												'conditions' =>  array('model' => 'supplier'),
												'fields' => array('id', 'extension', 'model', 'image_type'),
												'dependent' => true
										 )
								);
	var $belongsTo = 'User';
	var $validate = array(
		'identifier' => array(
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Serial number is duplicated, please check.'
			),
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Serial number is required.',
				'allowEmpty' => false,
				'required' => false
			)
		),
		'biz_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your business name',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'phone' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a valid phone number',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'subdomain' => array(
				'max' => array(
					'rule' => array('maxLength', 255),
					'message' => 'Your subdomain is too long.',
				),
				'alphaNumeric' => array(
					'rule' => 'alphaNumeric',
					'message' => 'Your subdomain can only contain letters (a-z) or numbers (0-9).'
				),
				'unique' => array(
					'rule' => array('isUnique'),
					'message' => 'This subdomain has been registered. Please try others.'
				),
				'filter' => array(
					'rule' => array('filterString', array('www', 'http', 'https', 'freshla', 'admin', 'support')),
					'message' => 'This subdomain is reserved by Freshla. Please try others.'
				),
				'required' => array(
        	'rule' => 'notEmpty',
        	'message' => 'Please enter your subdomain.'
     		)
			),
		'aboutus' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please tell us a bit about your passion for your products and yourself.',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'contact_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please kindly provide a contact name.',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'return_address1' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your shipping return address.',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'return_suburb' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your suburb name.',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'return_postcode' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your postcode.',
				'allowEmpty' => false,
				'required' => true
			),
		),
		'return_state' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter your state.',
				'allowEmpty' => false,
				'required' => true
			),
		)
	);
	
	function filterString ($data, $filters) {
		if (in_array($data['subdomain'], $filters)) {
			return false;
		}
		return true;
	}
}
?>
