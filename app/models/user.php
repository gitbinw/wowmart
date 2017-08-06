<?php
class User extends AppModel {
	var $name = "User";
	var $displayField = 'email';
	var $hasOne = array('UserProfile' => array(
													'className' => 'UserProfile',
													'conditions' => '',
													'order' => '',
													'dependent' => true,
													'foreignKey'   => 'user_id'
												),
											'Supplier' => array(
													'className' => 'Supplier',
													'dependent' => true,
													'foreignKey'   => 'user_id'
												)
						);
  var $hasAndBelongsToMany = array(
      	'Group' => array('className' => 'Group',
                    'joinTable' => 'users_groups',
                    'foreignKey' => 'user_id',
                    'associationForeignKey' => 'group_id',
                    'unique' => true
            		)
  );
  var $hasMany = array(
      	'Contact' => array(
      							'className' => 'Contact',
										'dependent' => true
            		),
        'Order' => array(
        						'className' => 'Order',
        						'dependent' => true
        				)
  );
  
  var $validate = array(
      'email' => array(
				'validemail' => array(
					'rule' => array('email'),
					'message' => 'Your email is not valid.',
					//'allowEmpty' => false,
					//'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'unique' => array(
					'rule' => array('isUnique'),
					'message' => 'This email has been used.'
				),
				'required' => array(
        	'rule' => 'notEmpty',
        	'message' => 'Please enter your email.'
     		)
			),
      'password' => array(
				'min' => array(
        	'rule' => array('minLength', 6),
					'message' => 'Password must be at least 6 characters.',
					//'allowEmpty' => false,
					//'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
				'required' => array(
        	'rule' => 'notEmpty',
        	'message'=>'Please enter a password.'
     	 	)
			),
			'confirm_password' => array(
				'match' => array(
					'rule'	=>	array('confirmPassword'),
					'allowEmpty' => false,
					'required' => false,
					'message'	=>	'Password is not matched.'
				)
			),
			
			'confirm_email' => array(
				'match' => array(
					'rule'	=>	array('confirmEmail'),
					'allowEmpty' => false,
					'required' => false,
					'message'	=>	'Your email is not matched.'
				)
			)
  );
  
  function resetPasswordValidate() {
  	$this->validate['password']['min']['allowEmpty'] = true;
  	$this->validate['confirm_password']['match']['allowEmpty'] = true;
  }
  
  //overwrite Auth components hashPasswords
	function hashPasswords($data, $enforce=false) {
		if($enforce && isset($this->data[$this->alias]['password'])) {
			if(!empty($this->data[$this->alias]['password'])) {
				$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], null, true);
			}
		}
		return $data;
  }
  
  function confirmPassword($data) {
    if ($this->data[$this->alias]['password'] !== $data['confirm_password']) {
      return false;
    }
    return true;
  }  
  
  function confirmEmail($data) {
    if ($this->data[$this->alias]['email'] !== $data['confirm_email']) {
      return false;
    }
    return true;
  }  

  function beforeSave() {
		$this->hashPasswords(null, true);
		return true;
  }

}
?>