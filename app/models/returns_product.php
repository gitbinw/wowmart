<?php
class ReturnsProduct extends AppModel {
	var $name = 'ReturnsProduct';
	var $belongsTo = "Order, Product";
	
	var $validate = array(
			'order_id' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Please select a returned order number.',
					'allowEmpty' => false,
					'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				)
			),
			'product_id' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Please select a returned product.',
					'allowEmpty' => false,
					'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				)
			),
      'total_refund' => array(
				'notempty' => array(
					'rule' => array('numeric'),
					'message' => 'Please enter a valid refund amount.',
					'allowEmpty' => false,
					'required' => true
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				)
			),
      'quantity' => array(
				'notempty' => array(
        	'rule' => array('numeric'),
					'message' => 'Please enter a valid quantity. Must be digital.',
					'allowEmpty' => false,
					'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				)
			)
  );
}
?>