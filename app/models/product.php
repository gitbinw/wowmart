<?php
class Product extends AppModel {
	var $name = 'Product';
	var $hasAndBelongsToMany = 'Category,Media,Type';
	 
	/*var $hasAndBelongsToMany = array(
            'Category' => array('className' => 'Category',
                        'joinTable' => 'categories_products',
                        'foreignKey' => 'product_id',
                        'associationForeignKey' => 'category_id',
                        'unique' => true
            )
  );*/
	//var $hasMany = 'Image,Feature';
	var $belongsTo = "Supplier, LogisticsCompany";
	var $hasMany = array(
										/*'Image' => array(
												'foreignKey' => 'model_id',
												'conditions' =>  array('model' => 'product'),
												'fields' => array('id', 'extension', 'model', 'is_default'),
												'dependent' => true
										 ),
										 'Media' => array(
										 	'foreignKey' => 'model_id',
											'conditions' =>  array('model' => 'product'),
											'dependent' => true
										 ),*/
										 'Document' => array(
										 	'foreignKey' => 'model_id',
											'conditions' =>  array('model' => 'product'),
											'dependent' => true
										 ),
										 'Feature' => array(
										 		'dependent' => true
										 ),
										 'Freight' => array(
										 		'dependent' => true
										 ),
										 'Subproduct' => array(
										 		'dependent' => true
										 )
								);
	
	var $validate = array(
      'name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Product name is required',
					'allowEmpty' => false,
					'required' => true
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				)
			),
	  'serial_no' => array(
	  	'unique'=>array(
			'rule' => 'isUnique',
			'message' => 'Serial number is duplicated, please check.'
		),
		'notempty' => array(
			'rule' => 'notempty',
			'message' => 'Serial number is required.',
			'allowEmpty' => false,
			'required' => false
		)
	  ),
	  'product_alias' => array(
	  			'unique' => array(
					'rule' => array('checkUnique'),
					'message' => 'This alias is duplicated, please check.'
				),
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => 'Product alias is required',
					'allowEmpty' => false,
					'required' => true
				)
			),
      'stock' => array(
				'notempty' => array(
        	'rule' => array('numeric'),
					'message' => 'Stock must be a digit.',
					'allowEmpty' => false,
					'required' => true,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				)
			)
  );
	
	function afterSave() {
		/**Cakephp doesn't automatically delete hasMany records, when they are not associated.
		 **The following code is to delete features/medias which are not removed from this product.
		**/
		if (!empty($this->data['Product']['id'])) {
			if (!empty($this->data['Feature'])) {
				$conditions = array('Feature.product_id' => $this->data['Product']['id'], 
									'NOT' => array('Feature.id' => array_keys($this->data['Feature'])) );
			} else {
				$conditions = array('Feature.product_id' => $this->data['Product']['id']);
			}
			$this->Feature->deleteAll($conditions);
			
			if (!empty($this->data['Freight'])) {
				$conditions = array('Freight.product_id' => $this->data['Product']['id'], 
									'NOT' => array('Freight.id' => array_keys($this->data['Freight'])) );
			} else {
				$conditions = array('Freight.product_id' => $this->data['Product']['id']);
			}
			$this->Freight->deleteAll($conditions);
			
			if (!empty($this->data['Subproduct'])) {
				$conditions = array('Subproduct.product_id' => $this->data['Product']['id'], 
									'NOT' => array('Subproduct.id' => array_keys($this->data['Subproduct'])) );
			} else {
				$conditions = array('Subproduct.product_id' => $this->data['Product']['id']);
			}
			$this->Subproduct->deleteAll($conditions);
			
			if (!empty($this->data['Media'])) {
				$conditions = array(
												'Media.model_id' => $this->data['Product']['id'], 
											  'Media.model' => 'product',
												'NOT' => array('Media.id' => array_keys($this->data['Media'])) 
											);
			} else {
				$conditions = array(
											'Media.model_id' => $this->data['Product']['id'], 
											'Media.model' => 'product'
										);
			}
			$this->Media->deleteAll($conditions);
		}
	}
	
	function checkUnique($check) {
		$params = array(
			'conditions' => array(
								'supplier_id' => $this->data['Product']['supplier_id'],
								'product_alias' => $check['product_alias'],
								'id NOT' => $this->data['Product']['id']
							),
			'recursive' => -1
		);
		if ($this->find('count', $params) > 0) return false;
		return true;
	}
}
?>