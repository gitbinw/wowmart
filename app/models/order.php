<?php
class Order extends AppModel {
	var $name = 'Order';
	var $hasAndBelongsToMany = 'Product';
	var $hasOne = array('Billing' => array(
													'className' => 'Billing',
													'conditions' => '',
													'order' => '',
													'dependent' => true,
													'foreignKey'   => 'order_id'
												),
												'Shipping' => array(
													'className' => 'Shipping',
													'conditions' => '',
													'order' => '',
													'dependent' => true,
													'foreignKey'   => 'order_id'
												)
						);
	var $belongsTo = 'User,Status,Invoice';
}
?>