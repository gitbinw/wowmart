<?php
class Category extends AppModel {
	var $name = 'Category';
	var $hasOne = array('CategoryDetail'=>array(
													'className' => 'CategoryDetail',
													'conditions' => '',
													'order' => '',
													'dependent' => true,
													'foreignKey'   => 'category_id'
												)
						);
	var $hasAndBelongsToMany = array(
						'Product' => array('className' => 'Product',
                        'joinTable' => 'categories_products',
                        'foreignKey' => 'category_id',
                        'associationForeignKey' => 'product_id',
                        'unique' => true
            )
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
	
	function hasProduct($parentId) {
		$this->CategoriesProduct->recursive = -1;
		$num = $this->CategoriesProduct->findByCategoryId($parentId, array('COUNT(*) AS num'));
		
		if (isset($num) && isset($num[0]['num']) && $num[0]['num'] > 0) return true;
	
		return false;
	}
	
	function hasChild($parentId) {
		$this->recursive = -1;
		$parent = $this->findById($parentId, array('lorder', 'rorder'));
		if ($parent === false) return false;
		
		if ($parent[$this->name]['rorder'] - $parent[$this->name]['lorder'] > 1) return true;
		
		return false;
	}
	
	function getChildNodeID($parentId) {
		$parent = $this->findById($parentId, array('lorder', 'rorder'));
		if ($parent === false) return false;   
		$params = array(
			'conditions' => array($this->name . ".LORDER >" => $parent[$this->name]['lorder'], 
												$this->name . ".RORDER <" => $parent[$this->name]['rorder']),
			'fields' => array('id')
		);
		return $this->find('list', $params);
	}
	
	function getParentNodeID($childId) {
		$child = $this->findById($childId, array('lorder', 'rorder')); 
		if ($child === false) return false;
		$params = array(
			'conditions' => array($this->name . ".LORDER <" => $child[$this->name]['lorder'], 
												$this->name . ".RORDER >" => $child[$this->name]['rorder']),
			'fields' => array('id')
		);
		return $this->find('list', $params);
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