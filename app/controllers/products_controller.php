<?php
class ProductsController extends AppController {
	/*NOTE: if put $uses = array('Category', 'Product'); then when you do Product->delete()
	 *it will delete all products in that category;
	 *Don't know why!!!! So, don't put 'Category' in uses;
	 */
	var $uses = array('Product');
	var $helpers = array ('utility','combobox','javascript', 'dropdown');
	var $components = array('RequestHandler', 'Utility', 'MediasManager');
	var $strListName = 'thisItem';
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('search', 'homeproducts', 'newarrivals', 'view');
	}
	
	function index () {}
	
	private function setCategories($category = NULL) {
		//if ($this->__permitted('categories', '')) {
		$this->set("categories", $this->Utility->getCategories());
		
		if (isset($category)) {
			$left = $category['lorder'];
			$right= $category['rorder'];
			$this->set("sub_category", $category['id']);
		}
		 
		if (isset($left) && isset($right) && $left > 0 && $right > 0 ) {
			$this->set("selected_categories", $this->Utility->getSelectedCategories($left, $right));
		} else {
			$this->set("selected_categories", array());
		}
		//} else {
		//	$this->set("categories", array());
		//}
	}
	
	/*Front-end methods*/
	function search() {
		if (!empty($this->params['pass'][0])) {
			
			$this->render('advance');
		} else {
			if (!empty($this->params['url']['keywords'])) {
				$this->params['named']['keywords'] = $this->params['url']['keywords'];
			} 
			
			if (!empty($this->params['named']['keywords'])) {
				$strSearch = '%' . $this->params['named']['keywords'] . '%';
				if (!empty($this->params['named']['category'])) {
					$cid = $this->params['named']['category'];
					$parents = $this->Product->Category->getParentNodeID($cid);
					$parentIds = array_values($parents);
					$parentIds[] = $cid;
					$categories = $this->Product->Category->CategoryDetail->find('list', array(
						'conditions' => array('category_id' => $parentIds),
						'fields' => array('category_id', 'name')
					));
					$catIds = array_values($this->Product->Category->getChildNodeID($cid));
					$catIds[] = $cid;
				}
				$this->Product->hasMany['Image']['conditions']['is_default'] = 1;
				$this->paginate = array(
					'Product' => array(
						'page' => 1,
						'order' => array('Product.name' => 'asc'),
						'limit' => $this->limit,
						'conditions' => array (
							'OR' => array(
								'Product.name LIKE' => $strSearch,
								'Product.serial_no LIKE' => $strSearch,
								'Product.long_desc LIKE' => $strSearch,
								'Supplier.biz_name LIKE' => $strSearch
							)
						),
						'joins' => array(
							array(
								'table' => $this->Product->tablePrefix . 'categories_products',
								'alias' => 'CategoriesProduct',
								'type' => 'inner',
								'conditions'=> array('CategoriesProduct.product_id = Product.id')
							),
							array(
								'table' => $this->Product->tablePrefix . $this->Product->Category->useTable,
								'alias' => 'Category',
								'type' => 'inner',
								'conditions'=> array(
									'Category.id = CategoriesProduct.category_id'
								)
							)
						)
					)
				);
				if (isset($catIds) && count($catIds) > 0) {
					$this->paginate['Product']['joins'][1]['conditions']['Category.id'] = $catIds;
					$this->set('currentCategoryId', $cid);
					$this->set(compact('categories'));
				}

				$products = $this->paginate('Product');
				$this->set(compact('products'));
			}
		}
	}
	
	function view($pid = 0) {
		$product = '';
		if ($pid) {
			if (!is_numeric($pid)) {
				$product = $this->Product->findByProductAlias($pid);
			} else {
				$product = $this->Product->findById($pid);
			}
		}
		
		if (!$product) {
			$this->redirect('/errors/notfound/product');
			exit();
		} 
		if (isset($product['Category'][0]['id'])) {
			$parentIds = array_values($this->Product->Category->getParentNodeID($product['Category'][0]['id']));
			if ($parentIds !== false) {
				$parentIds[] = $product['Category'][0]['id'];
				$categories = $this->Product->Category->CategoryDetail->find('all', array(
					'conditions' => array('category_id' => $parentIds),
					'fields' => array('category_id', 'category_alias', 'name')
				));
				$this->set(compact('categories'));
			}
		}

		$this->set(compact('product'));
	}
	
	function homeproducts() {
		$params = array(
			'conditions' => array('on_homepage'=>1),
			'fields' => array('Product.id', 'Product.name', 'Product.product_alias',
				'Product.price', 'Product.deal_price', 'Supplier.subdomain'
			),
			'order' => array('Product.id DESC')
		);
		$this->Product->unbindModel(array(
				'hasMany' => array('Media', 'Document', 'Feature'),
				'hasAndBelongsToMany' => array('Category', 'Type')
			)
		);
		$this->Product->hasMany['Image']['conditions']['is_default'] = 1;
		return $this->Product->find('all', $params);
	}
	
	function newarrivals() {
		$param = array(
				'recursive' => 2,
				'conditions' => array('ProductsType.type_id' => TYPE_NEW_ARRIVALS),
				'fields' => array('Product.id', 'Product.name', 'Product.product_alias',
					'Product.price', 'Product.deal_price', 'Supplier.subdomain'
				),
				'order' => array('Product.id DESC')
		);
		$this->Product->bindModel(array('hasOne' => array('ProductsType')));
		$this->Product->unbindModel(array(
				'hasMany' => array('Media', 'Document', 'Feature'),
				'hasAndBelongsToMany' => array('Category', 'Type')
			)
		);
		$this->Product->hasMany['Image']['conditions']['is_default'] = 1;
		return $this->Product->find('all', $param);
	}
	
	/*Admin methods*/
	function admin_alias() {
		if ($this->RequestHandler->isAjax()) {
			$prodName = $this->makeNeatUrlName($this->params['form']['prod_name']);
			
			$params = array(
				'conditions' => array(
									'supplier_id' => $this->params['form']['supp_id'],
									'product_alias' => $prodName . ".html",
									'id NOT' => $this->params['form']['prod_id']
								),
				'recursive' => -1
			);
			
			$count = $this->Product->find('count', $params);
			if ($count > 0) $prodName .= '-' . $count;
			
			$response['success'] = true;
			$response['value'  ] = $prodName . ".html";
			$response['serial' ] = $this->Utility->getProductSerialNo($this->params['form']['supp_id']);
			
			$this->autoRender = false;	
			echo json_encode($response);
		}
	}
	
	function admin_types () {
		$param = array(
			'recursive' => -1,
			'order' => array('Type.id')
		);
		$data = $this->Product->Type->find('list', $param);
		
		return $data;
	}
	
	function admin_new () {
		$this->set('logistics', $this->Utility->getLogisticsCompanies());
		$this->set('discounts', $this->Utility->getLiveDiscounts());
		$this->set('rewards', $this->Utility->getLiveRewards());
		$this->set('vouchers', $this->Utility->getLiveVouchers());
		
		$this->setCategories();
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0,$keywords='') {
		$this->Product->Category->unbindModel(array('hasAndBelongsToMany'=>array('Product')), false);
		$this->Product->unbindModel(array(
				'hasMany' => array('Image', 'Media', 'Document', 'Feature', 'Freight', 'Subproduct'),
				'hasAndBelongsToMany' => array('Type')
			), false
		);
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$strSearch = '%' . $keywords . '%';
		$this->paginate = array(
					'Product' => array(
						'page' => 1,
						'recursive' => 2,
						'order' => array('Product.created' => 'desc'),
						'limit' => $this->limit,
						'fields' => array('Product.id', 'Product.name','Product.serial_no','Product.on_homepage',
									'Product.stock','Product.created', 'Product.price', 'Product.deal_price',
									'Supplier.biz_name', 'LogisticsCompany.logi_company'),
						'conditions' => array(
								'OR' => array(
									array('Supplier.biz_name LIKE' => $strSearch),
									array('Product.serial_no LIKE' => $strSearch),  
									array('Product.name LIKE' => $strSearch),
									array('CategoryDetail.name LIKE' => $strSearch)
								)
						),
						'joins' => array(
							array(
								'table' => 'categories_products',
								'alias' => 'CategoriesProduct',
								'type' => 'LEFT',
								'conditions'=> array('CategoriesProduct.product_id = Product.id')
							),
							array(
								'table' => $this->Product->Category->useTable,
								'alias' => 'Category',
								'type' => 'LEFT',
								'conditions'=> array(
									'Category.id = CategoriesProduct.category_id'
								)
							),
							array(
								'table' => $this->Product->Category->CategoryDetail->useTable,
								'alias' => 'CategoryDetail',
								'type' => 'LEFT',
								'conditions'=> array(
									'Category.id = CategoryDetail.category_id'
								)
							)
						)
					)
				);
				
		if (isset($this->params['named']['on_homepage']) && $this->params['named']['on_homepage'] == 1) {
			$this->paginate['Product']['conditions']['on_homepage'] = 1;
		}
		$arrItems = $this->paginate('Product');

		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				$this->data['Product']['serial_no'] = $this->Utility->getProductSerialNo($this->data['Product']['supplier_id']);

				if ($this->Product->saveAll($this->data, array('validate'=>'first'))) {
					if (isset($this->params['form']['upload_image_on']) && 
						$this->params['form']['upload_image_on'] == 1 &&
						isset($this->data['Media']['Media'])) {
							
						$this->MediasManager->generateProductImages($this->data['Media']['Media'], 
							$this->data['Product']['product_alias'], $this->Product->id);
					}
					$keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
					$this->admin_view($parentId, $keywords);
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->Product));
					
					if (isset($this->data['Product']['id']) && !empty($this->data['Product']['id'])) { //update Product
						$data = $this->Product->findById($this->data['Product']['id']);
			  		if (isset($data['Category'][0]['lorder']) && isset($data['Category'][0]['rorder'])) {
							$this->setCategories($data['Category'][0]);
						} else {
							$this->setCategories();
						}
					} else {
						$this->setCategories();
					}
					
					$this->set('logistics', $this->Utility->getLogisticsCompanies());
					$this->set('discounts', $this->Utility->getLiveDiscounts());
					$this->set('rewards', $this->Utility->getLiveRewards());
					$this->set('vouchers', $this->Utility->getLiveVouchers());
					
					$this->render ('admin_edit', 'ajax');	
				}
			}
		}
	}
	
	function admin_edit ($parentId=0,$strItems='') {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
			  $data = $this->Product->findById($items[0]['id']);
			  
			  if (isset($data['Category'][0]['lorder']) && isset($data['Category'][0]['rorder'])) {
					$this->setCategories($data['Category'][0]);
				} else {
					$this->setCategories();
				}
				$this->set($this->strListName, $data);
			}
			
			$this->set('logistics', $this->Utility->getLogisticsCompanies());
			$this->set('discounts', $this->Utility->getLiveDiscounts());
			$this->set('rewards', $this->Utility->getLiveRewards());
			$this->set('vouchers', $this->Utility->getLiveVouchers());
			
			$this->render ('admin_edit', 'ajax');	
		}	
	}
	
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
					$this->Product->delete($item[ 'id' ]);
				}
			}
			$keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
			$this->admin_view($parentId, $keywords);
		}
	}
	
	function buyIt ($parentId=0,$strItems='') {
		if ( !empty ( $strItems ) ) {
			$items = unserialize(stripslashes($strItems));
			foreach ( $items as $item ) {	
				$this->requestAction('shops/add/'.$item['id']);
			}
		}
		$this->redirect ( 'shops/view' );
	}
	
}
?>