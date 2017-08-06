<?php
class Page extends AppModel {
	var $name = 'Page';
	var $hasOne = array('PageDetail'=>array(
													'className' => 'PageDetail',
													'conditions' => '',
													'order' => '',
													'dependent' => true,
													'foreignKey'   => 'page_id'
												)
						);
	var $hasMany = array('PageBanner' => array(
		'className' => 'PageBanner',
		'conditions' => '',
		'order' => 'id ASC',
		'dependent' => true,
		'foreignKey'   => 'page_id'
	));
	var $belongsTo = array('PageTemplate');
	
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
		$parentId = $data [$this->name][ 'parent_id' ];
		$priority = $data ['PageDetail'][ 'priority' ];
		
		$conditions = array($this->name . ".LORDER >=" => $delLeft, 
												$this->name . ".RORDER <=" => $delRight);
		
		if ($this->deleteAll($conditions)) {
			$this->updatePriority($parentId, $priority);
			
			$intNum = 2 * ( ( $delRight - $delLeft - 1 ) / 2 + 1 );
			$conditions = array($this->name . ".LORDER >" => $delLeft);
			if ($this->updateAll(array("LORDER" => "`LORDER` - " . $intNum), $conditions)) {
				$conditions = array($this->name . ".RORDER >" => $delLeft);
				$this->updateAll(array("RORDER" => "`RORDER` - " . $intNum), $conditions);
			}
		}
	}
	
	function updatePriority($parentId, $priority) {
		$conditions = array(
			'recursive' => -1,
			'conditions'=>array('parent_id' => $parentId), 
			'feilds'=>array('id')
		);
		$arrPages = $this->find('all', $conditions);
		$arrPageIds = array();
		foreach($arrPages as $p) {
			$arrPageIds[] = $p['Page']['id'];
		}
		$conditions = array("page_id" => $arrPageIds, "priority >" => $priority);
		$this->PageDetail->updateAll(array("priority" => "`priority` - 1"), $conditions);
	}
}
?>