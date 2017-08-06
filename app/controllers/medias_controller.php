<?php
class MediasController extends AppController {
	var $uses = array ('User', 'Media');
	var $components = array('Session', 'RequestHandler', 'MediasManager');
	var $response = array('status' => 0, 'errorCode' => 0, 'errorMsg' => '', 'data' => '');
	var $helpers = array ('utility','combobox','javascript');
	
	var $strListName = 'thisItem';
	
	var $allowedMime = array( 
		'image/jpeg', // images
		'image/pjpeg', 
		'image/png', 
		'image/gif'
	);
	var $maxFileSize = 5000; //2M
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('profile', 'item', 'get');
	}
	
	private function moveMedia($src, $target) {
		$source = MEDIA_PATH_TEMP . DS . $src;

		copy( $source, $target );
		unlink($source);
	}
	
	/*Admin methods*/
	function admin_upload() {
		$errors = '';
		
		if (isset($_FILES['uploaded_file']) && $this->loginId) {
			$imageTempPath = MEDIA_PATH_TEMP;
			$imageTempId = $this->loginId;
			
			$mime_type = isset($_FILES['uploaded_file']['type']) ? $_FILES['uploaded_file']['type'] : '';
			$file_size = isset($_FILES['uploaded_file']['size']) ? $_FILES['uploaded_file']['size'] / 1024 : '';
			
			if (!$mime_type) {
				$errors = 'Image mime type is empty.';
			} else if (!$file_size) {
				$errors = 'Image size is zero.';
			} else if (!in_array($mime_type, $this->allowedMime)) {
				$errors = 'Image type is not valid. It must be .jpg, .jpeg, .png or .gif';
			} else if ($file_size > $this->maxFileSize) {
				$errors = 'Image size(' . $file_size . 'K) is too big.';
			}

			if (!$errors) {
				$ext = $this->MediasManager->getFileExtension($mime_type);
				$tempImgName = $imageTempId . $ext;
				$orginSource = $imageTempPath . DS . $tempImgName;
				if (move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $orginSource)) {
					$this->response['data']['name'] = $_FILES['uploaded_file']['name'];
					$this->response['data']['file'] = $tempImgName;
					$this->response['data']['url'] = MEDIA_URL_TEMP . '/' . $tempImgName;
					$this->response['status'] = 1;
				} 
			} else {
				$this->response['status'] = 0;
				$this->response['errorCode'] = ERROR_CODE_VALIDATION;
				$this->response['errorMsg'] = $errors;
			}
		} else {
			$this->response['status'] = 0;
			$this->response['errorCode'] = ERROR_CODE_VALIDATION;
			$this->response['errorMsg'] = 'Uploaded file is empty OR permission denied.';
		}
		
		$this->autoRender = false;
			
		echo json_encode($this->response);
	}
	
	function admin_new () {	
		$this->render ('admin_edit', 'ajax');	
	}
	
	private function admin_dataList($parentId=0, $keywords='') {
		$keywords = !empty($this->params['form']['keywords']) ? $this->params['form']['keywords'] : $keywords;
		$this->paginate = array(
			'Media' => array(
				'page' => 1,
				'recursive' => 2,
				'order' => array('Media.created' => 'desc'),
				'limit' => $this->limit,
				'fields' => array('Media.id', 'Media.dir','Media.media_name','Media.file_name', 
								  'Media.file_size', 'Media.external_url', 'Media.created'),
				'conditions' => array(
					'OR' => array(
						array('Media.media_name LIKE' => '%' . $keywords . '%'),
						array('Media.file_name LIKE' => '%' . $keywords . '%'),
						array('Media.external_url LIKE' => '%' . $keywords . '%'),  
						array('Media.dir LIKE' => '%' . $keywords . '%'),
						array('Media.file_size LIKE' => '%' . $keywords . '%')
					)
				)
			)
		);

		$arrItems = $this->paginate('Media');

		$this->set ( $this->strListName,  $arrItems );
	}
	function admin_view ($parentId=0, $keywords='') {	
		$this->admin_dataList($parentId=0, $keywords='');
		$this->render ('admin_view', 'ajax');
	}
	
	function admin_list($parentId=0, $keywords='') {
		//$this->layout = 'admin_frame';
		$this->admin_dataList($parentId=0, $keywords='');
		//if ($this->RequestHandler->isAjax()) {
			$this->render ('admin_list', 'ajax');
		//}
	}
	
	function admin_save ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (!empty ($this->data)) {
				$tempSrc = '';
				if (!empty($this->params['form']['file_src'])) {
					$tempSrc = $this->params['form']['file_src'];
					$tempPath = MEDIA_PATH_TEMP . DS . $tempSrc;
					$this->data['Media']['file_size'] = filesize($tempPath) / 1024;
				}
				if ($this->Media->saveAll($this->data, array('validate'=>'first'))) {
					$mediaId = $this->Media->id;
					
					if (!empty($tempSrc)) {
						$arrSrc = explode('.', $tempSrc);
						$lastIndex = count($arrSrc) - 1;
						$newSrc = $mediaId . '.' . $arrSrc[$lastIndex];
						$mediaTarget = $this->MediasManager->calculatePath(IMAGE_PATH_ROOT, $mediaId, false, true);
						$this->moveMedia($this->params['form']['file_src'], $mediaTarget . DS . $newSrc);
						$this->Media->saveField('file_name', $newSrc);
						$this->Media->saveField('dir', $this->MediasManager->getRelativePath($mediaId));
					}
					
					$keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
					$this->admin_view($parentId, $keywords);
				} else {
					$this->set($this->strListName, $this->data);
					$this->set('errors', $this->validateErrors($this->Media));
					
					$this->render ('admin_edit', 'ajax');	
				}
			}
		}
	}
	
	function admin_saveMedia () {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['media_id']) && !empty($this->params['form']['media_id'])) {
				$this->Media->recursive = -1;
				$media = $this->Media->findById($this->params['form']['media_id']);
				$path = $media['Media']['dir'];
				$name = $media['Media']['file_name'];
				$path = IMAGE_URL_ROOT . str_replace("\\", '/', $path);
				$url = $path . '/' . $name;
				
				$this->response['status'] = 1;
				$this->response['data']['url'] = $url;
			} else {
				$data['Media'] = $this->params['form'];
				if (!empty($this->params['form']['file_src'])) {
					$tempSrc = $this->params['form']['file_src'];
					$tempPath = MEDIA_PATH_TEMP . DS . $tempSrc;
					$data['Media']['file_size'] = filesize($tempPath) / 1024;
				}
				if ($this->Media->saveAll($data, array('validate'=>'first'))) {
					$mediaId = $this->Media->id;
					
					if (!empty($tempSrc)) {
						$arrSrc = explode('.', $tempSrc);
						$lastIndex = count($arrSrc) - 1;
						$newSrc = $mediaId . '.' . $arrSrc[$lastIndex];
						$mediaTarget = $this->MediasManager->calculatePath(IMAGE_PATH_ROOT, $mediaId, false, true);
						$this->moveMedia($this->params['form']['file_src'], $mediaTarget . DS . $newSrc);
						$this->Media->saveField('file_name', $newSrc);
						$path = $this->MediasManager->getRelativePath($mediaId);
						$this->Media->saveField('dir', $path);
						
						$path = IMAGE_URL_ROOT . str_replace("\\", '/', $path);
						$url = $path . '/' . $newSrc;
						
						$this->response['status'] = 1;
						$this->response['data']['url'] = $url;
						$this->response['data']['media_id'] = $mediaId;
					}
				} else {
					
				}
			}
			
			$this->autoRender = false;
			
			echo json_encode($this->response);
		}
	}
	
	function admin_edit ($parentId=0,$strItems='') {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
			  	$data = $this->Media->findById($items[0]['id']);
			 
				$this->set($this->strListName, $data);
			}
			
			$this->render ('admin_edit', 'ajax');	
		}	
	}
	
	function admin_delete ($parentId=0) {
		if ($this->RequestHandler->isAjax()) {
			if (isset($this->params['form']['selItems']) && !empty($this->params['form']['selItems'])){
				$items = unserialize(stripslashes($this->params['form']['selItems']));
				foreach ( $items as $item ) {
					$this->Media->recursive = -1;
					$media = $this->Media->findById($item[ 'id' ]);
					$src = $this->MediasManager->calculatePath(IMAGE_PATH_ROOT, $item[ 'id' ]);
					$src .= DS . $media['Media']['file_name'];
					
					$this->Media->delete($item[ 'id' ]);
					unlink($src);
				}
			}
			$keywords = isset($this->params['form']['keywords']) ? $this->params['form']['keywords'] : '';
			$this->admin_view($parentId, $keywords);
		}
	}
}
?>