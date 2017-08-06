<?php
class Permission extends AppModel {
	var $name = "Permission";
	var $hasAndBelongsToMany = array(
            'Group' => array('className' => 'Group',
                        'joinTable' => 'groups_permissions',
                        'foreignKey' => 'permission_id',
                        'associationForeignKey' => 'group_id',
                        'unique' => true
            )
    );
    
  var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please enter a permission name',
				'allowEmpty' => false,
				'required' => true
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		)
	);

}
?>