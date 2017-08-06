<?php
class ImagesController extends AppController 
{
	var $uses = array('Image', 'Media');
	var $components = array('ImagesUploader', 'RequestHandler');
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('get', 'view');
	}
	
	private function getImageDim($mod, $imgType='') {
		$dimensions = array();
		switch (strtolower($mod)) {
			/*Note: $dimensions key 4 is for the image slide viewer in CMS*/
			case 'product' :
				$dimensions = array(
					1=>array('width'=>600, 'height'=>450),
					2=>array('width'=>456, 'height'=>342),
					3=>array('width'=>140, 'height'=>105),
					4=>array('width'=>100, 'height'=>75)
				);
				break;
			case 'page' : 
				$dimensions = array(
					1=>array('width'=>900, 'height'=>285),
					4=>array('width'=>100, 'height'=>100)
				);
				break;
			
			case 'supplier' : 
				$dimensions = array(4=>array('width'=>100, 'height'=>100));
				if ($imgType == IMAGE_SUPPLIER_LOGO) {
					$dimensions[1] = array('width'=>167, 'height'=>167);
				}
				$dimensions[1] = array('width'=>209, 'height'=>209);
				break;
		}
		
		return $dimensions;
	}
	
	private function delDir($dir) {
		if ($handle = opendir($dir)) {
			$is_dir_empty = true;
			while (false !== ($file = readdir($handle))) {
				if (is_file($dir . DS . $file)) {
					if (!unlink($dir . DS . $file)) {
						$is_dir_empty = false;
					}
				} else if ($file != '.' && $file != '..') {
					$is_dir_empty = false;
				}
			}
				
			closedir($handle);
				
			if ($is_dir_empty === true) {
				if (rmdir($dir)) return true;
			}
		}
		return false;
	}
	
	function get($mod, $pid) {
		if ($this->RequestHandler->isAjax()) {
			$params = array(
				'conditions' => array('model'=>$mod, 'model_id'=>$pid),
				'order' => array('Image.id'),
				'recursive' => -1,
				'fields' => array('Image.id', 'Image.extension', 'model_id', 'model', 
													'link_model', 'link_model_id', 'is_default', 'image_type')
			);
			$images = $this->Image->find('all', $params);
			
			$this->autoRender = false;
			
			echo json_encode($images);
		}
	}
	
	function admin_delete($mod, $pid = 0, $imgId = 0) {
		if ($this->RequestHandler->isAjax()) {
			$returns['success'] = false;
			if ($this->Image->delete($imgId)) {
				$this->delDir(IMAGE_PATH_ROOT . DS . $mod . DS . $imgId);
					
				$param1 = array(
					'conditions' => array('model_id' => $pid, 'model' => $mod),
					'fields' => array('id'),
					'recursive' => -1
				);
				$item = $this->Image->find('first', $param1);
				$newDefaultId = isset($item['Image']['id']) ? $item['Image']['id'] : 0;
				
				$param2 = array(
					'conditions' => array('model_id' => $pid, 'model' => $mod, 'is_default' => 1),
					'recursive' => -1
				);
				$imgCount = $this->Image->find('count', $param2); 
				if ($imgCount <= 0 && $newDefaultId > 0) {
					$this->Image->id = $newDefaultId;
					$this->Image->saveField('is_default', 1);
				}
			
				$returns['success'] = true;
			}
			
			$this->autoRender = false;
			
			echo json_encode($returns);
		}
	}
	
	function admin_set($mod, $pid = 0, $imgId = 0) {
		if ($this->RequestHandler->isAjax()) {
			$returns['success'] = false;
			
			//remove old default image
			$conditions = array('model_id' => $pid, 'model' => $mod);
			$this->Image->updateAll(array('is_default' => 0), $conditions);
		
			//set new default image
			$this->Image->id = $imgId;
			if ($this->Image->saveField('is_default', 1)) {
				$returns['success'] = true;
			}
			
			$this->autoRender = false;
			
			echo json_encode($returns);
		}
	}
	
	function admin_upload($mod, $pid = 0) {
		$upload_path = IMAGE_PATH_ROOT . DS . $mod;
		$this->ImagesUploader->relatedModelName = $mod;
		$this->ImagesUploader->relatedModelId	= $pid;
		$this->ImagesUploader->extraData = isset($this->data['extra']) ? $this->data['extra'] : array();
		$this->ImagesUploader->dimensions = $this->getImageDim($mod);
		$this->ImagesUploader->upload('upload_image', $upload_path);
		
		$error = $this->ImagesUploader->getError();
		
		echo json_encode($error);
		
		$this->render('admin_upload', 'ajax');
	}
	
	function view($mediaId = '') {
		if (!empty($mediaId)) {
			if (is_numeric($mediaId)) {
				$m = $this->Media->findById($mediaId, array('file_name', 'dir'));
				$imagePath = IMAGE_PATH_ROOT . $m['Media']['dir'] . DS . $m['Media']['file_name'];
			} else {
				//remove '/' from WWW_ROOT
				$imagePath = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1) . base64_decode($mediaId);
			}

			$size = filesize($imagePath);
			/*header("HTTP/1.1 200 OK");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");*/
			header("Cache-Control: private, max-age=10800, pre-check=10800");
			header("Pragma: private");
			header("Content-Type: image/jpg");
			header("Content-Length: $size");
			
			readfile($imagePath);
			
			die();
		}
	}
}
?>