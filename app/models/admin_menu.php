<?php
class AdminMenu extends AppModel {
	var $name = "AdminMenu";
	
	var $validate = array(
			'name' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please enter the menu name.',
							'allowEmpty' => false,
							'required' => true
					)
			),
			'ctrl' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please enter a controller name.',
							'allowEmpty' => false,
							'required' => true
					)
			),
			'priority' => array(
					'notempty' => array(
							'rule' => array('notempty'),
							'message' => 'Please enter priority.',
							'allowEmpty' => false,
							'required' => true
					)
			)	
	);
}
?>