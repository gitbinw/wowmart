<?php
class Media extends AppModel {
	var $name = "Media";
	
	var $validate = array(
		'media_name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter media name.',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'file_name' => array(
	  		'needone' => array(
				'rule' => 'checkOne',
        		'message' => 'You must upload a local media OR enter an external URL.'
			)
		)
	);
	
	function checkOne($data) {
    	if(!empty($this->data[$this->alias]['file_name']) 
    		|| !empty($this->data[$this->alias]['external_url'])) {
        	return TRUE;
    	} else {
        	return FALSE;
    	}
	}
}
?>