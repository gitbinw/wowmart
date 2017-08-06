<?php
class CategoriesController extends AppController {
	var $uses = array('Category', 'Product');
	var $helpers = array ('combobox', 'dropdown', 'Javascript');
	var $strParentName = 'parentItem';
	var $currentItem  = 'thisItem';
	var $listItems    = 'arrItems';
	var $components = array('RequestHandler', 'Utility', 'MediasManager');
	
	function beforeFilter () {
		parent::beforeFilter();
		
		$this->Auth->allow('retrieveHierarchy', 'hierarchy', 'view');
	}
	
	//this is for admin panel tree view
	private function buildTree ( &$tree, $parentId = null ) {
		if ( !isset ( $parentId ) ) $parentId = 0;
		$param = array(
			'conditions' => array('parent_id' => $parentId),
			'fields' => array('id','lorder','rorder','CategoryDetail.name'),
			'order' => array('CategoryDetail.name')
		 );
		$arrRes = $this->Category->find('all', $param);
    $tree .= "<ul>";
    foreach ( $arrRes as $node ) {
    	$tree .= "<li><a id='".$node['Category']['id']."' ctrl='categories'>".
    		       $node['CategoryDetail']['name']."</a>";
      if ( $node['Category']['rorder'] - $node['Category']['lorder'] == 1 ) $tree .= "</li>";
      else {
        $this->buildTree ( $tree, $node['Category']['id'] );
        $tree .= "</li>";
      }
	   /*if ( $node['Category']['rorder'] - $node['Category']['lorder'] > 1 ) {
	   		$tree .= "<li><a id='".$node['Category']['id']."' ctrl='categories'>".
    		       $node['CategoryDetail']['name']."</a>";
			
			 $this->buildTree ( $tree, $node['Category']['id'] );
	         $tree .= "</li>";
	   }*/
    }
    $tree .= "</ul>";
	}
	
	//this is for front-end tree view
	private function generateTree ( &$tree, $parentId = null ) {
		if ( !isset ( $parentId ) ) $parentId = 0;
		$param = array(
			'conditions' => array('parent_id' => $parentId, 'is_node' => 1),
			'fields' => array('id','lorder','rorder','CategoryDetail.name','CategoryDetail.icon_name'),
			'order' => array('CategoryDetail.name')
		 );
		$arrRes = $this->Category->find('all', $param);
		
		$isFirst = false;
		if (!isset($tree) || empty($tree)) {
			$isFirst = true; 
			$tree .= "<ul class='menubar'>";
		} else {
			$tree .= "<ul><li class='li_top'></li>";
		}

    	foreach ( $arrRes as $key=>$node ) {
    		$tree .= "<li class='li_node " . ($key == 0 ? 'first' : '') . "' onmouseover='mover(this);' onmouseout='mout(this);'>" .
    					 "<a href='" . SITE_URL . "/category/".$node['Category']['id']."' class='" . 
    					 $node['CategoryDetail']['icon_name'] . "'>".
    		       $node['CategoryDetail']['name']."</a>";
      		if ( $node['Category']['rorder'] - $node['Category']['lorder'] == 1 ) $tree .= "</li>";
     		else {
        		$this->generateTree ( $tree, $node['Category']['id'] );
        		$tree .= "</li>";
      		}
    	}
    	if (!$isFirst) $tree .= "<li class='li_bottom'></li>";
   		$tree .= "</ul>";
	}
	/*New Category Hierechy for 3 Levels Structure*/
	private function generateTreeByLevels($maxLevel=1, $level=0, $parentId = 0) {
		$arrCats = array();
		
		if ($level <= $maxLevel) {
			$param = array(
				'recursive' => 0,
				// is_node = 0 means don't show category on the website
				'conditions' => array('parent_id' => $parentId, 'is_node' => 1),
				'fields' => array(
					'id','lorder','rorder','CategoryDetail.name','CategoryDetail.category_alias',
					'CategoryDetail.icon_name'
				),
				'order' => array('CategoryDetail.name')
			 );
			 
			 $arrRes = $this->Category->find('all', $param);
			 foreach($arrRes as $node) {
			 	$tmp = $node['CategoryDetail'];
				if ( $node['Category']['rorder'] - $node['Category']['lorder'] > 1 ) {
					$newLevel = $level + 1;
					$pid = $node['Category']['id'];
					$tmp['children'] = $this->generateTreeByLevels($maxLevel, $newLevel, $pid);
				}
				$arrCats[] = $tmp;
			 }
		}
		
		return $arrCats;
	}
	
	private function setCategories($parentId) {
		//if ($this->__permitted('categories', '')) {
		$this->set("categories", $this->Utility->getCategories());
		$this->set("sub_category", $parentId);
		
		$this->Category->recursive = -1;
		$cat = $this->Category->findById($parentId, array('lorder', 'rorder'));
  	
  		if (isset($cat) && isset($cat['Category']['lorder']) && isset($cat['Category']['rorder']) 
  				&& $cat['Category']['lorder'] > 0 && $cat['Category']['rorder'] > 0 ) {
  					
  			$this->set("selected_categories", $this->Utility->getSelectedCategories($cat['Category']['lorder'], $cat['Category']['rorder']));
  			
  		} else {
  			$this->set("selected_categories", array());
  		}
  		//} else {
  		//	$this->set("categories", array());
  		//}
  	}
	
	function hierarchy() {
		$tree = '';
		$this->generateTree($tree);
		return $tree;
	}
	
	function retrieveHierarchy() {
		//generate 3 levels categories
		$arrCats = $this->generateTreeByLevels(3);
		
		return $arrCats;
	}
	
	
	/*Front-end methods*/
	function view($cid = 0) {
		$parents = false;
		if ($cid) {
			if (!is_numeric($cid)) {
				$this->Category->CategoryDetail->recursive = -1;
				$arrRec = $this->Category->CategoryDetail->findByCategoryAlias($cid, array('category_id'));
				$cid = $arrRec['CategoryDetail']['category_id'];
			}
			$parents = $this->Category->getParentNodeID($cid);
		}
		
		if ($parents === false) {
			$this->redirect('/errors/notfound/category');
			exit();
		}
		$parentIds = array_values($parents);
		$parentIds[] = $cid;
		$categories = $this->Category->CategoryDetail->find('all', array(
			'conditions' => array('category_id' => $parentIds),
			'fields' => array('category_id', 'category_alias', 'name', 'comment')
		));
		$catIds = array_values($this->Category->getChildNodeID($cid));
		$catIds[] = $cid;
    	$this->paginate = array(
    		'Product' => array(
    			'page' => 1,
    			'order' => array('Product.name' => 'asc'),
    			'limit' => $this->limit, 
    			'joins' => array( 
        			array( 
            			'table' => 'categories_products', 
            			'alias' => 'CategoriesProduct', 
            			'type' => 'inner',  
            			'conditions'=> array('CategoriesProduct.product_id = Product.id') 
        			), 
        			array( 
            			'table' => $this->Category->useTable, 
            			'alias' => 'Category', 
            			'type' => 'inner',  
            			'conditions'=> array( 
                			'Category.id = CategoriesProduct.category_id', 
                			'Category.id' => $catIds
            			) 
        			)
        		)
        	)
        );

		$products = $this->paginate('Product');
    	$this->set(compact('products'));
    	$this->set(compact('categories'));
    	$this->set('currentCategoryId', $cid);
	}
	
	
	/*Admin panel methods*/
	
	function admin_get ($parentId=0, $return = false) {

		$arrOptions = $this->Utility->getCategories($parentId);
		
		$this->set($this->currentItem,$this->Utility->getCategories($parentId));	
		if ($return == true) return $arrOptions;
		else $this->render('admin_get', 'ajax');
	}
	
	function admin_tree() {
		$tree = '';
		$this->buildTree($tree);
		return $tree;
	}
	
	function admin_new ($parentId=0, $type = '') {
		$this->set('discounts', $this->Utility->getLiveDiscounts());
		$this->set('rewards', $this->Utility->getLiveRewards());
		$this->set('vouchers', $this->Utility->getLiveVouchers());
		
		if (!empty($parentId)) {
			$this->setCategories($parentId);
			$this->set ( $this->strParentName,  $parentId );
			$this->set ( 'currentFormType',  $type );
			
			if ($type == 'product' || $this->Category->hasProduct($parentId)) {
				
				$this->set ( 'currentFormType',  'product' );
				$this->set('logistics', $this->Utility->getLogisticsCompanies());
				
				$this->render ('admin_products_edit', 'ajax');
				
			} else if ($type == 'category' || $this->Category->hasChild($parentId)) {
				
				$this->render ('admin_edit', 'ajax');
				
			} else {
				
				$this->render ('admin_new', 'ajax');
			}
				
		} else {
		
			$this->render ('admin_edit', 'ajax');
		}	
	}
	function admin_newProduct ($parentId) {
		if (!empty($parentId)) {
			$this->admin_new($parentId, 'product');
		} else {
			$this->render ('admin_new', 'ajax');
		}
	}
	function admin_newCategory ($parentId) {
		if (!empty($parentId)) {
			$this->admin_new($parentId, 'category');
		} else {
			$this->render ('admin_new', 'ajax');
		}
	}
	
	function admin_products_view($parentId, $keywords = '') {
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
								),
								'Category.id' => $parentId
						),
						'joins' => array(
							array(
								'table' => 'categories_products',
								'alias' => 'CategoriesProduct',
								'type' => 'inner',
								'conditions'=> array('CategoriesProduct.product_id = Product.id')
							),
							array(
								'table' => $this->Product->Category->useTable,
								'alias' => 'Category',
								'type' => 'inner',
								'conditions'=> array(
									'Category.id = CategoriesProduct.category_id'
								)
							),
							array(
								'table' => $this->Category->CategoryDetail->useTable,
								'alias' => 'CategoryDetail',
								'type' => 'inner',
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
		
		$this->set ( $this->strParentId,  $parentId );
		$this->set ( $this->currentItem,  $arrItems );
		$this->render ('admin_products_view', 'ajax');
	}
	
	function admin_view ($parentId=0, $keywords='') {
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$strSearch = '%' . $keywords . '%';
		
		if (!empty($parentId) && $this->Category->hasProduct($parentId)) {
			$this->admin_products_view($parentId, $keywords);
			
		} else {
			$this->paginate = array(
				'Category' => array(
				'page' => 1,
				'recursive' => 1,
				'order' => array('CategoryDetail.name' => 'asc'),
				'limit' => $this->limit,
				'fields' => array('CategoryDetail.*'),
					'conditions' => array(
						'OR' => array(
							array('CategoryDetail.name LIKE' => $strSearch),
							array('CategoryDetail.comment LIKE' => $strSearch),
							array('CategoryDetail.category_status LIKE' => $strSearch)
						),
						'Category.parent_id' => $parentId
					)
				)
			);
				
			$arrItems = $this->paginate('Category');
				
			$this->set ( $this->currentItem,  $arrItems );
			$this->render ('admin_view', 'ajax');
		}
		
	}
	
	function admin_products_save($parentId) {
		if (!empty($parentId)) {
			$this->data['Product']['serial_no'] = $this->Utility->getProductSerialNo($this->data['Product']['supplier_id']);
			
			$this->data['Category']['Category'][] = $parentId;
			
			if ($this->Product->saveAll($this->data, array('validate'=>'first'))) {
				if (isset($this->params['form']['upload_image_on']) && 
					$this->params['form']['upload_image_on'] == 1 &&
					isset($this->data['Media']['Media'])) {
						
					$this->MediasManager->generateProductImages($this->data['Media']['Media'], 
						$this->data['Product']['product_alias'], $this->Product->id);
				}
					
				$keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
				$this->admin_products_view($parentId, $keywords);
			} else {

				$this->setCategories($parentId);
			
				$this->set($this->currentItem, $this->data);
				$this->set('errors', $this->validateErrors($this->Product));
				
				$this->admin_newProduct($parentId);
			}
		
		}
	}
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			$formType = isset($this->params['form']['form_type']) ? $this->params['form']['form_type'] : '';
			if ($formType == 'product') {
					
				$this->admin_products_save($parentId);
			
			} else if (!empty ($this->data)) {
				
				if (empty($this->data['Category']['id'])) {//if create a new Category
					$this->Category->getMPTT($parentId, $this->data);
				}
				if ($this->Category->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Category']['id'])) {//if create a new Category
						$this->data['Category']['id'] = $this->Category->getLastInsertID();
						$this->Category->updateMPTT($this->data['Category']['lorder'] - 1, 
																		 $this->data['Category']['id']);
					}
					
					$arrNewItem = array ( 
											'id'=>$this->data['Category']['id'],
											'ctrl'=>'categories',
											'name'=>$this->data['CategoryDetail']['name']
										);
					$this->set('arrNewItem', $arrNewItem);
						
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->Category));
					$this->admin_newCategory($parentId);
				}
			}
		}
	}
	
	function admin_products_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				$data = $this->Product->findById($items[0]['id']);
				
				$this->setCategories($parentId);
				
				$this->set($this->currentItem, $data);
			}
			
			$this->set ( 'currentFormType',  'product' );
			$this->set ( $this->strParentName,  $parentId );
			$this->render ('admin_products_edit', 'ajax');
		}
	}
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			$this->set('discounts', $this->Utility->getLiveDiscounts());
			$this->set('rewards', $this->Utility->getLiveRewards());
			$this->set('vouchers', $this->Utility->getLiveVouchers());
			
			if (!empty($parentId) && $this->Category->hasProduct($parentId)) {

				$this->set('logistics', $this->Utility->getLogisticsCompanies());
				
				$this->admin_products_edit($parentId);
					
			} else {
				
				if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
			
					$items = unserialize(stripslashes($this->params['form']['selItems']));
			 		$data = $this->Category->findById($items[0]['id']);
					
			 		if (!empty($parentId)) $this->setCategories($parentId);
			 		
					$this->set($this->currentItem, $data);
				}
				
				$this->render ('admin_edit', 'ajax');
			}
			
		}
	}
	
	function admin_products_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
					/*NOTE: if put $uses = array('Category', 'Product'); then when you do Product->delete()
					 *it will delete all products in that category;
					 *Don't know why!!!! So, don't put 'Category' in uses if you use Product->delete();
					 *See admin_delete in products_controller.php. 
					 *In Category, when we delete a prodcut, it means remove this product from this category,
					 *so, the product is not actually deleted, it is just removed from this category,
					 *so, we use CategoriesProduct->deleteAll;
					 *If we need delete the product, then go to products section to delete!!!!
					 */

					$this->Product->CategoriesProduct->deleteAll(array('product_id'=>$item['id']), false, false);
				}
			}
			$this->admin_products_view($parentId);
		}
	}
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty($parentId) && $this->Category->hasProduct($parentId)) {
					
				$this->admin_products_delete($parentId);
			
			} else {
				if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				
					$items = unserialize(stripslashes($this->params['form']['selItems']));
					foreach ( $items as $item ) {
			    		$this->Category->deleteNode($item[ 'id' ]);
					}
				}
				$this->admin_view($parentId);
			}
		}
	}
}
?>