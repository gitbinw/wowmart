<?php
class Group extends AppModel {
	var $name = 'Group';
	var $hasOne = array('GroupDetail'=>array(
													'className' => 'GroupDetail',
													'conditions' => '',
													'order' => '',
													'dependent' => true,
													'foreignKey'   => 'group_id'
												)
						);

	var $hasAndBelongsToMany = array(
						'Permission' => array('className' => 'Permission',
                        'joinTable' => 'groups_permissions',
                        'foreignKey' => 'group_id',
                        'associationForeignKey' => 'permission_id',
                        'unique' => true
            ),
            'User' => array('className' => 'User',
                        'joinTable' => 'users_groups',
                        'foreignKey' => 'group_id',
                        'associationForeignKey' => 'user_id',
                        'unique' => true
            )
  );
  
  var $validate = array(
        /*'Permission' => array(
        	//	'notempty' => array(
            	'rule' => array('multiple', array('min' => 1)),
            	'required' => true,
            	'message' => 'Please select at least one permission for this group.'
          //  )
        )*/
  );
	
	function getMPTT($parentId=0, &$data) {
		$parent = $this->findById($parentId,'lorder');   
    if ($parent[$this->name]['lorder'] == null || !$parent[$this->name]['lorder']) {
      $leftId = 0;
    } else {
      $leftId = $parent[$this->name]['lorder'];
    }
    
    $data[$this->name]['parent_id'] = $parentId;	
    $data[$this->name]['lorder'] = $leftId + 1;
    $data[$this->name]['rorder'] = $leftId + 2;
    
    return $data;
	}
	
	function updateMPTT($leftId, $currentId) { //Modified Preorder Tree Traversal
		$conditions = array("RORDER >" => $leftId, $this->name . ".id <>" => $currentId);
		if ($this->updateAll(array("RORDER" => "`RORDER` + 2"), $conditions)) {
			$conditions = array("LORDER >" => $leftId, $this->name . ".id <>" => $currentId);
			$this->updateAll(array("LORDER" => "`LORDER` + 2"), $conditions);
		}
	}
	
	function deleteNode($nodeId) {
		$data = $this->findById($nodeId);
		$delLeft = $data [$this->name][ 'lorder' ];
		$delRight = $data [$this->name][ 'rorder' ];
		
		$conditions = array($this->name . ".LORDER >=" => $delLeft, 
												$this->name . ".RORDER <=" => $delRight);
		
		if ($this->deleteAll($conditions)) {
			$intNum = 2 * ( ( $delRight - $delLeft - 1 ) / 2 + 1 );
			$conditions = array($this->name . ".LORDER >" => $delLeft);
			if ($this->updateAll(array("LORDER" => "`LORDER` - " . $intNum), $conditions)) {
				$conditions = array($this->name . ".RORDER >" => $delLeft);
				$this->updateAll(array("RORDER" => "`RORDER` - " . $intNum), $conditions);
			}
		}
	}
	
}
?>