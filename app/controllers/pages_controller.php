<?php
class PagesController extends AppController {
	var $currentItem  = 'thisItem';
	var $listItems    = 'arrItems';
	var $helpers = array ('Javascript');
	var $components = array('RequestHandler');
	var $strListName = 'thisItem';
	var $layout = 'default';
	
	function beforeFilter () {
		parent::beforeFilter();
		
		$this->Auth->allow('display', 'topmenus', 'dropdownMenus', 'footerMenus');
	}
	
	private function buildTree ( &$tree, $parentId = null ) {
		if ( !isset ( $parentId ) ) $parentId = 0;
		$param = array(
			'conditions' => array('parent_id' => $parentId),
			'fields' => array('id','lorder','rorder','PageDetail.name'),
			'order' => array('PageDetail.name')
		 );
		$arrRes = $this->Page->find('all', $param);
    $tree .= "<ul>";
    foreach ( $arrRes as $node ) {
    	$tree .= "<li><a id='".$node['Page']['id']."' ctrl='pages'>".
    		       $node['PageDetail']['name']."</a>";
      if ( $node['Page']['rorder'] - $node['Page']['lorder'] == 1 ) $tree .= "</li>";
      else {
        $this->buildTree ( $tree, $node['Page']['id'] );
        $tree .= "</li>";
      }
	  
	  /*if ($node['Page']['rorder'] - $node['Page']['lorder'] > 1 ) {
	  	$tree .= "<li><a id='".$node['Page']['id']."' ctrl='pages'>".
    		       $node['PageDetail']['name']."</a>";
		$this->buildTree ( $tree, $node['Page']['id'] );
        $tree .= "</li>";
	  }*/
    }
    $tree .= "</ul>";
	}
	
	private function checkHomepage() {
		$params = array(
			'conditions' => array('is_home_page' => 1),
			'fields' => array('name', 'alias')
		);
		$this->set('homepage', $this->Page->PageDetail->find('first', $params));
	}
	
	/*
	 ************* Front End Methods   ********************
	 */
	function topmenus() {
		$params = array(
			'conditions' => array(
					'is_shown' => 1,
					'is_menu' => 1
			),
			'recursive' => 0
		);
		$pg = $this->Page->PageDetail->find('all', $params);

		return $pg;
	}
	function dropdownMenus() {
		$params = array(
			'conditions' => array(
					'PageDetail.is_menu' => 1
			),
			'recursive' => 0,
			'order' => array('PageDetail.priority')
		);
		$pages = $this->Page->find('all', $params);
		$arrMenus = array();
		foreach($pages as $p) {
			$index = $p['Page']['parent_id'];
			$arrMenus[$index][] = array(
				'menu_id' => $p['PageDetail']['page_id'],
				'menu_name' => $p['PageDetail']['name'],
				'menu_url' => $p['PageDetail']['url'],
				'menu_url_target' => $p['PageDetail']['url_target'],
				'menu_alias' => $p['PageDetail']['alias'],
				'menu_only' => $p['PageDetail']['is_display_only']
			);
		}

		return $arrMenus;
	}
	function footerMenus() {
		$params = array(
			'conditions' => array(
					'PageDetail.is_foot_menu' => 1
			),
			'recursive' => 0,
			'order' => array('PageDetail.priority')
		);
		$pages = $this->Page->find('all', $params);
		$arrMenus = array();
		foreach($pages as $p) {
			$index = $p['Page']['parent_id'];
			$arrMenus[$index][] = array(
				'menu_id' => $p['PageDetail']['page_id'],
				'menu_name' => $p['PageDetail']['name'],
				'menu_url' => $p['PageDetail']['url'],
				'menu_alias' => $p['PageDetail']['alias']
			);
		}

		return $arrMenus;
	}
	
	private function getHomeTopBanners() {
		$arrBanners = array();
		$this->Page->PageDetail->recursive = -1;
		$pd = $this->Page->PageDetail->findByAlias('home', array('page_id'));
		if (isset($pd['PageDetail']['page_id']) && !empty($pd['PageDetail']['page_id'])) {
			$params = array(
				'recursive' => -1,
				'conditions' => array(
					'page_id' => $pd['PageDetail']['page_id'],
					'banner_type' => PAGE_BANNER_TYPE_TOP
				)
			);
			$arrBanners = $this->Page->PageBanner->find('all', $params);
		}
		
		$top_banners = array();
		if (isset($arrBanners)) {
			foreach($arrBanners as $key => $bn) {
				$bannerType = $bn['PageBanner']['banner_type'];
				$lnkUrl = isset($bn['PageBanner']['url']) ? $bn['PageBanner']['url'] : '';
				$imgSrc = isset($bn['PageBanner']['image_src']) ? $bn['PageBanner']['image_src'] : '';
				$alt = isset($bn['banner_text']) ? $bn['banner_text'] : '';
				$hoverText = isset($bn['hover_text']) ? $bn['hover_text'] : '';
				
				if ($bannerType == PAGE_BANNER_TYPE_TOP) {
					$top_banners[] = array('img_src' => $imgSrc, 'img_url' => $lnkUrl, 'alt' => $alt, 'text' => $hoverText);
				
				}
			}
		}
		
		return $top_banners;
	}
	function display() {
		$params = array(
			'recursive' => 1,
			'conditions' => array(
				'PageDetail.is_shown' => 1,
				'PageDetail.alias' => $this->params['pass'][0]
			)
		);
		$pg = $this->Page->find('first', $params);
		
		if (isset($pg['PageTemplate']['alias']) && !empty($pg['PageTemplate']['alias'])) {
			$this->layout = $pg['PageTemplate']['alias'];
		}

		$home_banners = array();
		$top_banners = array();
		if (isset($pg['PageBanner'])) {
			foreach($pg['PageBanner'] as $key => $bn) {
				$bannerType = $bn['banner_type'];
				$lnkUrl = isset($bn['url']) ? $bn['url'] : '';
				$imgSrc = isset($bn['image_src']) ? $bn['image_src'] : '';
				$alt = isset($bn['banner_text']) ? $bn['banner_text'] : '';
				$slog = isset($bn['banner_slog']) ? $bn['banner_slog'] : '';
				$linkName = isset($bn['link_name']) ? $bn['link_name'] : '';
				$hoverText = isset($bn['hover_text']) ? $bn['hover_text'] : '';
				
				if ($bannerType == PAGE_BANNER_TYPE_TOP) {
					$top_banners[] = array(
						'img_src' => $imgSrc, 
						'img_url' => $lnkUrl, 
						'banner_title' => $alt, 
						'banner_slog' => $slog, 
						'link_name' => $linkName, 
						'text' => $hoverText
					);
				
				} else { //other banners e.g. feature_banner in home page
					$home_banners[] = array(
						'img_src' => $imgSrc, 
						'img_url' => $lnkUrl, 
						'banner_title' => $alt, 
						'banner_slog' => $slog, 
						'link_name' => $linkName, 
						'text' => $hoverText
					);
				}
			}
		}
		
		if (count($top_banners) <= 0) {
			$top_banners = $this->getHomeTopBanners();
		}
		
		if ($this->layout == 'default') $this->set('top_banners', $top_banners);
		
		if (!empty($pg['PageDetail']['url'])) {
			$this->redirect(trim($pg['PageDetail']['url']));
		} else {
			$ctp = $pg['PageDetail']['alias'];
			
			if ($pg['PageDetail']['is_home_page'] == 1) {
				$this->set('home_banners', $home_banners);	
				$ctp = 'home';			
			} else if (!empty($pg['PageDetail']['content'])) {
				$this->set('page_content', $pg['PageDetail']['content']);
				$ctp = 'customerised_page';
			}
			
			$this->set('page_title', $pg['PageDetail']['name']);
			$this->set('details', $pg['PageDetail']);
			$this->set('page_id', $pg['PageDetail']['page_id']);
			$this->set('feature_banners', $home_banners);	
		}
		
		if ($this->layout == 'service') {
			$ctp = $this->layout;
			$this->layout = 'default';
		}
		
		$this->autoRender = false;
		$this->render($ctp);
	}
	
	/*
	 ************* Admin Panel Methods ********************
	 */
	function admin_clearCache($viewName) {
		$file = CACHE . 'views' . DS . 'element__' . $viewName;
		if (is_file($file))	unlink($file);
	}
	function admin_get ($parentId=0, $return = false) {
		$param = array(
			'conditions' => array('parent_id' => $parentId),
			'recursive' => 0,
			'order' => array('PageDetail.name')
		);
		$data = $this->Page->find('all', $param);
		
		$arrOptions = array ();
		foreach ( $data as $key => $option ) {
			$arrOptions [ $option [ 'Page' ][ 'id' ] ]= $option [ 'PageDetail' ][ 'name' ];
		}
		
		$this->set($this->currentItem,$arrOptions);	
		if ($return == true) return $arrOptions;
		else $this->render('admin_get', 'ajax');
	}
	
	function admin_tree() {
		$tree = '';
		$this->buildTree($tree);
		return $tree;
	}
	
	function admin_new () {
		$this->getTemplates();
		$this->checkHomepage();
		$this->render ('admin_edit', 'ajax');	
	}
	
	function admin_view ($parentId=0, $keywords='') {
		$param = array(
			'conditions' => array('parent_id' => $parentId),
			'recursive' => -1
		 );
		$arrCats = $this->Page->find('all', $param);
		
		$arrPageIds = array();
		foreach($arrCats as $cat) {
			$arrPageIds[] = $cat['Page']['id'];
		}
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$this->paginate = array(
			'PageDetail' => array(
				'page' => 1,
				'recursive' => 2,
				'order' => array('PageDetail.name' => 'asc'),
				'limit' => $this->limit,
				'fields' => array('PageDetail.*'),
				'conditions' => array(
						'OR' => array(
							array('PageDetail.name LIKE' => '%' . $keywords . '%'),
							array('PageDetail.content LIKE' => '%' . $keywords . '%'),  
							array('PageDetail.priority LIKE' => '%' . $keywords . '%')
						),
						'page_id' => $arrPageIds
				)
			)
		);
				
		$arrItems = $this->paginate('PageDetail');

		$this->set ( $this->strListName,  $arrItems );
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				if (empty($this->data['Page']['id'])) {//if create a new Page
					$this->Page->getMPTT($parentId, $this->data);
				} else {
					$delCon = array('page_id' => $this->data['Page']['id']);
					$existId = array();
					if (isset($formData['feature_banner_id']) && count($formData['feature_banner_id']) > 0)
						$existId = array_merge($existId, $formData['feature_banner_id']);
					if (isset($formData['top_banner_id']) && count($formData['top_banner_id']) > 0)
						$existId = array_merge($existId, $formData['top_banner_id']);
						
					if (count($existId) > 0) $delCon['id NOT'] = $existId;
					
					$this->Page->PageBanner->deleteAll($delCon, false);
				}
				if (!empty($this->data['PageDetail']['video_url'])) {
					$this->data['PageDetail']['video_url'] = str_replace('watch?v=', 'embed/', 
															$this->data['PageDetail']['video_url']);
				}
				
				$formData = $this->params['form'];
				
				if (isset($formData['feature_banner_images'])) {
					foreach($formData['feature_banner_images'] as $key=>$src) {
						$theSrc = trim($src);
						$lnkUrl = isset($formData['feature_banner_urls'][$key]) ? trim($formData['feature_banner_urls'][$key]) : '';
						if (!empty($theSrc)) {
							$tmpBanner = array();
							$tmpBanner['image_src'] = $theSrc;
							$tmpBanner['banner_type'] = PAGE_BANNER_TYPE_FEATURE;
							$tmpBanner['banner_text'] = isset($formData['feature_banner_alts'][$key]) ? $formData['feature_banner_alts'][$key] : '';
							$tmpBanner['hover_text'] = isset($formData['feature_banner_text'][$key]) ? $formData['feature_banner_text'][$key] : '';
							$tmpBanner['id'] = isset($formData['feature_banner_id'][$key]) ? $formData['feature_banner_id'][$key] : '';
							
							if (!empty($lnkUrl)) {
								$tmpBanner['url'] = $lnkUrl;
							}
							
							$this->data['PageBanner'][] = $tmpBanner;
						}
					}
				}
				
				if (isset($formData['top_banner_images'])) {
					foreach($formData['top_banner_images'] as $key=>$src) {
						$theSrc = trim($src);
						$lnkUrl = isset($formData['top_banner_urls'][$key]) ? trim($formData['top_banner_urls'][$key]) : '';
						if (!empty($theSrc)) {
							$tmpBanner = array();
							$tmpBanner['image_src'] = $theSrc;
							$tmpBanner['banner_type'] = PAGE_BANNER_TYPE_TOP;
							$tmpBanner['banner_text'] = isset($formData['top_banner_titles'][$key]) ? $formData['top_banner_titles'][$key] : '';
							$tmpBanner['banner_slog'] = isset($formData['top_banner_slogs'][$key]) ? $formData['top_banner_slogs'][$key] : '';
							$tmpBanner['link_name'] = isset($formData['top_banner_links'][$key]) ? $formData['top_banner_links'][$key] : '';
							$tmpBanner['id'] = isset($formData['top_banner_id'][$key]) ? $formData['top_banner_id'][$key] : '';
							
							if (!empty($lnkUrl)) {
								$tmpBanner['url'] = $lnkUrl;
							}
							
							$this->data['PageBanner'][] = $tmpBanner;
						}
					}
				}

				if ($this->Page->saveAll($this->data, array('validate'=>'first'))) {
					if (empty($this->data['Page']['id'])) {//if create a new Page
						$this->data['Page']['id'] = $this->Page->getLastInsertID();
						$this->Page->updateMPTT($this->data['Page']['lorder'] - 1, 
																		 $this->data['Page']['id']);
					}
					
					$arrNewItem = array ( 
											'id'=>$this->data['Page']['id'],
											'ctrl'=>'pages',
											'name'=>$this->data['PageDetail']['name']
										);
					$this->set('arrNewItem', $arrNewItem);
					
					/**clear menubar cache to refresh the top menu bar and footer menubar**/
					if (isset($this->data['PageDetail']['is_menu']) && 
							$this->data['PageDetail']['is_menu'] == 1) {
						$this->admin_clearCache('topmenu_bar');
					}
					if (isset($this->data['PageDetail']['is_menu']) && 
							$this->data['PageDetail']['is_foot_menu'] == 1) {
						$this->admin_clearCache('topmenu_bar');	
					}
					/********************end of clear cache*********************************/
					
					$this->admin_view($parentId);
				} else {
					$this->set($this->currentItem, $this->data);
					$this->set('errors', $this->validateErrors($this->Page));
					$this->admin_new();
				}
			}
		}
	}
	
	function admin_edit ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));

				$data = $this->Page->findById($items[0]['id']);

				$this->set($this->currentItem, $data);
			}
			
			$this->getTemplates();
			$this->checkHomepage();
			$this->render ('admin_edit', 'ajax');	
		}
	}
	
	private function getTemplates() {
		$this->Page->PageTemplate->recursive = -1;
		$data = $this->Page->PageTemplate->find('all');
		
		$arrTemplates = array();
		foreach($data as $d) {
			$arrTemplates[] = $d['PageTemplate'];
		}
		
		$this->set('pageTemplates', $arrTemplates);
	}
	
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
			    $this->Page->deleteNode($item[ 'id' ]);
				}
			}
			$this->admin_view($parentId);
		}
	}
}
?>
