<?php
class UtilityHelper extends Helper {
	function pageBar ( $pageBar,$pageButton,$pageTotal,$current ) {
		$ul = "<ul>";
		
		$ul .= isset($pageButton['first'])?("<li><a href='".$pageButton['first']."'>1...</a></li>"):'';
		$ul .= isset($pageButton['prev'])?("<li><a href='".$pageButton['prev']."' ><b>&lt;&lt;</b></a></li>"):'';
		foreach ($pageBar as $key=>$val) {
			if ( $current == $key ) {
				$ul .= "<li><span class='current')>$key</span></li>";
			} else {
				$ul .= "<li><a href='$val'>$key</a></li>";
			}
		}
		$ul .= isset($pageButton['next'])?("<li><a href='".$pageButton['next']."'><b>&gt;&gt;</b></a></li>"):'';
		$ul .= isset($pageButton['last'])?("<li><a href='".$pageButton['last']."'>...$pageTotal</a></li>"):'';
		$ul .= "</ul>";
		return $ul;
	}
	
	function sortBar ( $sortMode, $current ) {
		$ul = "<ul>";
		
		foreach ($sortMode as $val) {
			$current_class = 'current_asc';
			$class = 'sort_asc';
			if ( $current == $val['SortMode']['id'] ) {
				if ($val['SortMode']['sort'] == 'desc') {
					$current_class = 'current_desc';
				}
				$ul .= "<li class='$current_class'><span>".
						$val['SortMode']['name']."</span></li>";
			} else {
				if ($val['SortMode']['sort'] == 'desc') {
					$class = 'sort_desc';
				}
				$ul .= "<li class='$class'><a href='".$val[0]['url']."'>".
				    	$val['SortMode']['name']."</a></li>";
			}
		}
		$ul .= "</ul>";
		return $ul;
	}
	
	function features ( $arrFeature ) {
		$features = "<table id='table_feature' cellpadding='0' cellspacing='1' width='100%'>";
		if ( is_array($arrFeature) ) {
			foreach ( $arrFeature as $key=>$feature ) {
				$index = isset($feature['id']) ? $feature['id'] : $key;
				$features .= "<tr><td class='form_label'>Feature:</td>".
					         "<td><input type='text' name='data[Feature][" . 
					         				$index . "][feature]' value='".
							 						@$feature['feature']."'>".
							 					"<input type='hidden' name='data[Feature][" . 
							 						$index . "][id]' value='". 
							 						$index . "' /></td>".
							 "<td class='del_feature' onclick='handleFeature(this);'></td></tr>";
			}
		}
		$features .= "</table>";
		return $features;
	}
	
	function medias ( $arrMedia ) {
		$medias = "<table id='table_media' cellpadding='0' cellspacing='1' width='100%'>";
		if ( is_array($arrMedia) ) {
			foreach ( $arrMedia as $key=>$media ) {
				$index = isset($media['id']) ? $media['id'] : $key;
				$medias .= "<tr><td class='form_label'>Youtube:</td>" .
					       "<td><textarea name='data[Media][" . $index . "][scripts]'>" . 
					       		@$media['scripts'] . 
					       "</textarea>" .
						   "<input type='hidden' name='data[Media][" . $index . "][id]' value='". 
							 	$index . "' /></td>".
						   "<td class='del_media' onclick='handleMedia(this);'></td></tr>";
			}
		}
		$medias .= "</table>";
		return $medias;
	}
	
	function freights ( $arrFreight ) {
		$str_freight = "<table id='table_freight' cellpadding='0' cellspacing='1' width='100%'>";
		if ( is_array($arrFreight) ) {
			foreach ( $arrFreight as $key=>$freight ) {
				$index = isset($freight['id']) ? $freight['id'] : $key;
				$str_freight .= "<tr>" .
					       "<td><b>Qty Range:</b><input type='text' " . 
					       " name='data[Freight][" . $index . "][minQty]' value='" . 
					       @$freight['minQty'] . "' />--<input type='text' " . 
					       " name='data[Freight][" . $index . "][maxQty]' value='" . 
					       @$freight['maxQty'] . "' />&nbsp;&nbsp;<b>Freight:</b>&nbsp;$<input type='text' " .
					       " name='data[Freight][" . $index . "][freight]' value='" . 
					       @$freight['freight'] . "' />" .
						   "<input type='hidden' name='data[Freight][" . $index . "][id]' value='". 
							 	$index . "' /></td>".
						   "<td class='del_freight' onclick='handleFreight(this);'></td></tr>";
			}
		}
		$str_freight .= "</table>";
		return $str_freight;
	}
	
	function subproducts ( $arrSubprod ) {
		$str_subprod = "<table id='table_subproduct' cellpadding='0' cellspacing='1' width='100%'>";
		if ( is_array($arrSubprod) ) {
			foreach ( $arrSubprod as $key=>$subprod ) {
				$index = isset($subprod['id']) ? $subprod['id'] : $key;
				$str_subprod .= "<tr>" .
					       "<td><b>Name:</b><input type='text' class='long' " . 
					       " name='data[Subproduct][" . $index . "][name]' value='" . 
					       @$subprod['name'] . "' />&nbsp;<b>Price:</b><input type='text' " . 
					       " name='data[Subproduct][" . $index . "][price]' value='" . 
					       @$subprod['price'] . "' class='short_field' />&nbsp;" .
						   "<select name='data[Subproduct][" . $index . "][prod_type]' " . 
						   "   class='medium_field'>" .
						   "<option value=''>Select Type</option>" . 
						   "<option value='sidedish' " . 
						   		(@$subprod['prod_type'] == 'sidedish' ? 'selected' : '') . ">Side Dish</option>" . 
						   "<option value='drink' " .
						   		(@$subprod['prod_type'] == 'drink' ? 'selected' : '') . ">Drink</option>" . 
						   "</select>" .
						   "&nbsp;<b>Factor:</b><input type='text' " .
					       " name='data[Subproduct][" . $index . "][factor]' value='" . 
					       @$subprod['factor'] . "' class='short_field' />&nbsp;&nbsp;<input type='radio' class='radio' " .
					       " name='rad_dummy' " . 
					     (isset($subprod['is_default'])&&$subprod['is_default']==1 ? 'checked' : '') . 
					       " /><b>Default</b>" . 
					     "<input type='hidden' name='data[Subproduct][" . $index . "][is_default]' " . 
					     " class='rad_subprod' value='" . @$subprod['is_default'] . "' />" . 
						   "<input type='hidden' name='data[Subproduct][" . $index . "][id]' value='". 
							 	$index . "' /></td>".
						   "<td class='del_subproduct' onclick='handleSubproduct(this);'></td></tr>";
			}
		}
		$str_subprod .= "</table>";
		return $str_subprod;
	}
	
	function productTypes ( $types, $sel_types ) {
		$ul = "<ul>";
		foreach ($types as $key=>$type) {
			$ul .= "<li><input type='checkbox' name='data[Type][]' value='".$key."'".
				   (in_array($key,$sel_types)?'CHECKED':'').">".$type."</li>";
		}
		$ul .= "</ul>";
		return $ul;
	}

	function orderGuider ($current=0) {
		$ul = "<ul class='order_guide'>";
		$max = 4;
		for ($i=0; $i<$max; $i++) {
			if ($i>0) {
				$ul .= "<li><img src='/".WEBROOT_DIR."/img/main/line.gif' border='0'></li>";
			}
			if ($i < $current) {
				$ul .= "<li><a href='/".WEBROOT_DIR."/weborders/proceed".($i>0?'/'.$i:'')."'>".
				       "<img src='/".WEBROOT_DIR."/img/main/g_done$i.gif' border='0'></a></li>";
			} else if ($i == $current) {
				$ul .= "<li><img src='/".WEBROOT_DIR."/img/main/g_curr$i.gif' border='0'></li>";
			} else {
				$ul .= "<li><img src='/".WEBROOT_DIR."/img/main/g_next$i.gif' border='0'></li>";
			}
		}
		$ul .= "</ul>";
		return $ul;
	}
	
	function orderEditClue ($cart) {
		$return = '';
		if (isset($cart['order_no']) && !empty($cart['order_no'])) {
			$return = "<table class='order_number' cellspacing='0' cellpadding='0'>".
				  "<tr><td class='header'>Order Number:</td>".
				  "<td class='field'>".$cart['order_no']."</td>".
				  "<td class='clue'>Modifying</td></tr></table>";
		}
		return $return;
	}
	
	function orderStatusTrack ($status,$sel_status=array(),$invoice='',$orderId='') {
		$return = '';
		if (count($status) > 0) {
			$return = "<table class='order_status_tb' cellspacing='0' cellpadding='0'><tr>";
			$return .=  "<td><table class='order_status' cellspacing='0' cellpadding='0'>".
						"<tr class='header'><td>Done</td><td>Status</td><td>Operations</td></tr>";
			foreach ($status as $key=>$st) {
				$disabled = '';
				$return .= '<tr>';
				if (in_array($key,$sel_status)) {
					$return .= "<td><img src='/".WEBROOT_DIR."/img/icons/check.gif'></td>";
				} else {
					$return .= "<td>&nbsp;</td>";
				}
				$return .= "<td>".$st."</td>";
				if ($key == 1) {
					$disabled = 'DISABLED';
				}
				$return .= 	"<td><input type='button' value='Yes' onclick=\"if(sure(4,'".$st."')) setStatus('/".WEBROOT_DIR."/orders/setStatus/".$key."');\" $disabled>".
							"<input type='button' value='No' onclick=\"if(sure(5,'".$st."')) setStatus('/".WEBROOT_DIR."/orders/unsetStatus/".$key."');\" $disabled></td>";
				$return .= "</tr>";
			}
			$return .= "</table></td>";
			if (empty($invoice) && empty($orderId)) {
				$return .= "<td></td>";
			} else { 
				$return .= 	"<td class='order_invoice'><table align='center'>";
				if (!empty($invoice)) {
					$return .= 	"<tr><td class='invoice'><span>Invoice Number:</span><br>".
							"<a href='#' onclick=\"call('/".WEBROOT_DIR."/invoices/getIt/".$invoice."',2);\">".
							$invoice."</a></td></tr>".
							"<tr><td class='print'><a href='#' onclick=\"call('/".WEBROOT_DIR."/invoices/getIt/".$invoice."',2);\">Go To Print Invoice</a></td></tr>";
				}
				if (!empty($orderId)) {
					$return .= "<tr><td class='print'><a href='#' onclick=\"call('/".WEBROOT_DIR."/orders/getIt/".$orderId."');\">Go To Print Order</a></td></tr>";
				}
				$return .= "</table></td>";
			} 
			$return .= "</tr></table>";
		}
		return $return;
	}
	
	function getPaginationBar($paginator, $parentUrlParams, $title = 'Items') {
		$product_counts = $paginator->counter(array('format' => '%count%'));
		$page_counts = $paginator->counter(array('format' => '%pages%'));
		
		$pgBar = '<div class="page_info">Total <span>' . $product_counts . '</span> ' . $title . '</div>
        		  <div class="page_bar">' . 
        		'	<ul>
						<li class="page_title">Pages:</li>
						<li class="page_prev" alt="Previous Page" title="Previous Page">' . 
							$paginator->prev('&nbsp;', array('tag' => 'div', 'escape' => false, 
												'url'=>array('action'=>'view')), null, null) . 
				 '		</li>' .
				 			$paginator->numbers(array('tag' => 'li', 'separator' => '', 
												'url'=>array('action'=>'view' . $parentUrlParams))) .
											 
				 '		<li class="page_next" alt="Next Page" title="Next Page">' . 
				 			$paginator->next('&nbsp;', array('tag' => 'div', 'escape' => false, 
												'url'=>array('action'=>'view')), null, null) . 
				 '		</li>
				 	</ul> 
				  </div>';
		
		return $pgBar;
	}
	function getPaginationSort($paginator, $columns_config, $thisItem, $defaultSort = 'Product.created', $defaultSortMode = 'desc') {
		$currLimit = "";
		
		if (isset($thisItem) && is_array($thisItem)) {
			$currPage = isset($paginator->options['url']['page']) ? $paginator->options['url']['page'] : 1;
			$currSort = isset($paginator->options['url']['sort']) ? $paginator->options['url']['sort'] : $defaultSort;
			$currDir  = isset($paginator->options['url']['direction']) ? $paginator->options['url']['direction'] : $defaultSortMode;
			$currDir = $currDir == 'desc' ? 'asc' : 'desc';
			$currSortDir = $currSort . ':' . $currDir;
		
			if (isset($paginator->options['url']['limit'])) {
				$currLimit = '/limit:' . $paginator->options['url']['limit'];
			}
		}
		
		$columns_string = '';
		
		foreach($columns_config as $col) {
			$class = $width = '';
			if (isset($col['width'])) $width = 'width="' . $col['width'] . '"';
			$columns_string .= '<td ' . $width . '>';
			if (isset($col['field'])) {
				$pgBaseUrl = $paginator->sort(
						$col['name'],
						$col['field'],
						array('direction'=>$currDir, 'url'=>array('action'=>'view'))
				);
				$columns_string .= $pgBaseUrl;
			} else {
				$columns_string .= $col['name'];
			}
			$columns_string .= '</td>';
		}
		
		return $columns_string;
	}
	
	function addImageListUploader($btnAddId, $listWrapId, $arrOptions = array()) {
		$title = isset($arrOptions['title']) ? $arrOptions['title'] : 'Images';
		$htmlList = "<tr class='image_uploader_box' bgcolor='#FFD5FF'>
						<td valign='top'>" . $title . "</td>
						<td colspan='2'>
							<table cellpadding='0' cellspacing='0' id='" . $listWrapId . "'>
								<tr>
									<td>
										<button id='" . $btnAddId . "'>Add</button>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<script language='javascript' type='text/javascript'>
						var opts = {
							" . (isset($arrOptions['media_id_name']) ? "media_id_name:'" . $arrOptions['media_id_name'] . "'," : '') 
							  . (isset($arrOptions['media_url_name']) ? "media_url_name:'" . $arrOptions['media_url_name'] . "'," : '')
							  . (isset($arrOptions['media_link_name']) ? "media_link_name:'" . $arrOptions['media_link_name'] . "'," : '')
							  . (isset($arrOptions['only_one_image']) ? "only_one_image:'" . $arrOptions['only_one_image'] . "'," : '')
							  . (isset($arrOptions['medias']) ? "medias:'" . json_encode($arrOptions['medias']) . "'," : '')
							  . (isset($arrOptions['info']) ? "info:'" . $arrOptions['info'] . "'," : '')
							  . (isset($arrOptions['width']) ? "width:'" . $arrOptions['width'] . "'," : '') 
							  . (isset($arrOptions['height']) ? "height:'" . $arrOptions['height'] . "'," : '') . 
							" id: 'empty'
						};
						setImageAddEvent('" . $btnAddId . "', '" . $listWrapId . "', opts);
					</script>
				";
				
		return $htmlList;
	}
	
	function formatMediaData($thisItem) {
		$arrProdImages = array();

		if (isset($thisItem['Media']) && count($thisItem['Media']) > 0) {
			foreach($thisItem['Media'] as $media) {
				$path = IMAGE_URL_ROOT . str_replace("\\", '/', $media['dir']);
				$url = $path . '/' . $media['file_name'];
				$arrProdImages[] = array('media_id' => $media['id'], 'media_url' => $url);
			}
		}
		
		return $arrProdImages;
	}
	
	function calculatePath($pathRoot, $imgId, $deep = 6) {
		$ttlNumber = $deep;
		$strId = '' . $imgId;
		$count = strlen($imgId);
	
		$path = '';
		$offset = $ttlNumber - $count;
		for($i=0; $i<$offset; $i++) $path .= '0';
	
		$path .= $imgId;
	
		$folderCount = $ttlNumber / 2;
		$folder = $pathRoot;
		for($j=0; $j<$folderCount; $j++) {
			$start = $j * 2;
			$folder .= DS . substr($path, $start, 2);
		}
	
		return $folder;
	}
	function getProductImageUrl($pathRoot, $imgId, $imgPrefix, $imgType = 'small', $key = 0, $ext='jpg', $pathDeep = 6) {
		$dir = $this->calculatePath($pathRoot, $imgId, $pathDeep);
		$path = str_replace("\\", '/', $dir);
		$url = $path . '/' . $imgPrefix . '_' . $key . '_' . $imgType . "." . $ext;
		
		return $url;
	}
	function getFrontEndImage($medias, $pathRoot, $imgId, $imgPrefix, $imgType = 'small', $pathDeep = 6) {
		$arrProdImages = array();
		
		if (isset($medias) && count($medias) > 0) {
			foreach($medias as $key => $media) {
				$arrFile = explode('.', $media['file_name']);
				$ext = $arrFile[count($arrFile) - 1];
				$dir = $this->calculatePath($pathRoot, $imgId, $pathDeep);
				$path = str_replace("\\", '/', $dir);
				$url = $path . '/' . $imgPrefix . '_' . $key . '_' . $imgType . "." . $ext;
				$arrProdImages[] = $url;
			}
		}
		
		return $arrProdImages;
	}
	function getFrontEndImageInfo($medias, $pathRoot, $imgId, $imgPrefix, $pathDeep = 6) {
		$arrProdImages = array();
	
		if (isset($medias) && count($medias) > 0) {
			foreach($medias as $key => $media) {
				$arrFile = explode('.', $media['file_name']);
				$ext = $arrFile[count($arrFile) - 1];
				$dir = $this->calculatePath($pathRoot, $imgId, $pathDeep);
				$path = str_replace("\\", '/', $dir);
				$url = $path . '/' . $imgPrefix . '_' . $key;
				$arrProdImages[] = array('img_base' => $url, 'img_ext' => $ext, 'img_delimiter' => '_');
			}
		}
	
		return $arrProdImages;
	}
	
	function breadcrumbs($categories, $currentCategoryId, $productName = '') {
		$catLinks = $breadcrumbs = '';
		$returns = array();
		if (isset($categories) && count($categories) > 0) {
			foreach($categories as $key => $cat) {
				if (!empty($productName)) {
					$catLinks .= '<li class="category"><a href="/category/' . $cat['CategoryDetail']['category_alias'] . '">'
							  . 	$cat['CategoryDetail']['name'] . '</a><span>></span></li>';
				} else {
					if ($currentCategoryId == $cat['CategoryDetail']['category_id']) {
						$returns['cat_name'] = $cat['CategoryDetail']['name'];
						$returns['cat_comment'] = $cat['CategoryDetail']['comment'];
					
						$catLinks .= '<li class="category"><strong>'
								  . 	$cat['CategoryDetail']['name'] . '</strong></li>';
					} else {
						$catLinks .= '<li class="category"><a href="/category/' . $cat['CategoryDetail']['category_alias'] . '">'
								  . 	$cat['CategoryDetail']['name'] . '</a><span>></span></li>';
					}
				}
			}
			
			$breadcrumbs = '<div class="breadcrumbs"><ul>
								<li class="home">
									<a href="/" title="Go to Home Page">Home</a>
									<span>></span>
								</li>' . $catLinks;
			
			if (!empty($productName)) {
				$breadcrumbs .= '<li class="product">
									<strong>' . $productName . '</strong>
								</li>';
			}
			
			$breadcrumbs .= '</ul></div>';
		}

		$returns['breadcrumbs'] = $breadcrumbs;
		
		return $returns;
	}
	
	function showSubCategories($cat, $navIndex, $level=0) {
		$html = '';
		$levelIndex = $level > 0 ? $level : '';
		if (isset($cat['children'])) {
			$counts = count($cat['children']);
			if (!$counts ) $counts = '';
			$html = '<div class="level-top">' .
					'	<ul class="level' . $levelIndex . ' column' . $counts . '">' .
					'   	<ul class="level' . $levelIndex. '">' .
					'			<div class="catagory_children">';
				
			$level ++;
			$j = 1;
			foreach($cat['children'] as $subCat) {
				$counts = 0;
				if (isset($subCat['children'])) $counts = count($subCat['children']);
				$class = $counts > 0 ? 'parent' : '';
				if ($j == 1) $class = 'first '. $class;
	
				$newNavIndex = $navIndex . '-' . $j;
				$html .= '<li class="level' . $level . ' nav-' . $newNavIndex . ' ' . $class .
				' item no-level-thumbnail">' .
				'		<a class="catagory-level' . $level . '" href="/category/' . $subCat['category_alias'] . '">' .
				'			<div class="thumbnail"></div>' .
				'			<span>' . $subCat['name'] . '</span>' .
				'		</a>';
					
				$html .= $this->showSubCategories($subCat, $newNavIndex, $level);
	
				$html .= '</li>';
	
				$j ++;
			}
			$html .= '</div></ul></ul></div>';
				
		}
	
		return $html;
	}
	function showMobileSubCategories($cat, $navIndex, $level=0) {
		$html = '';
		$levelIndex = $level;
		if (isset($cat['children'])) {
			$html = '<ul class="level' . $levelIndex . '">';
				
			$level ++;
			$j = 1;
			foreach($cat['children'] as $subCat) {
				$counts = 0;
				if (isset($subCat['children'])) $counts = count($subCat['children']);
				$class = $counts > 0 ? 'parent' : '';
				if ($j == 1) $class = 'first '. $class;
	
				$newNavIndex = $navIndex . '-' . $j;
				$html .= '<li class="level' . $level . ' nav-' . $newNavIndex . ' ' . $class . '">' .
						'<a class="catagory-level' . $level . '" href="/category/' . $subCat['category_alias'] . '">' .
						'<span>' . $subCat['name'] . '</span>' .
						'</a>';
					
				$html .= $this->showMobileSubCategories($subCat, $newNavIndex, $level);
	
				$html .= '</li>';
	
				$j ++;
			}
			$html .= '</ul>';
				
		}
	
		return $html;
	}
}
?>
