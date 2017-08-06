<?php
class DocumentsController extends AppController 
{
	var $uses = array('Document');
	var $components = array('DocumentsUploader', 'RequestHandler');
	
	function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->allow('get');
	}
	
	private function delFile($dir, $fileName) {
		$filePath = $dir . DS . $fileName;
		if (is_file($filePath)) {
			if (unlink($filePath)) {
				if ($handle = opendir($dir)) {
					$is_dir_empty = true;
					while (false !== ($file = readdir($handle))) {
						if ($file != '.' && $file != '..') {
							if (is_file($dir . DS . $file)) {
								$is_dir_empty = false;
								break;
							}
						}
					}
					closedir($handle);
					
					if ($is_dir_empty === true) {
						if (rmdir($dir)) return true;
					}
				}
				return true;
			}
		}
		return false;
	}
	
	function get($mod, $pid) {
		if ($this->RequestHandler->isAjax()) {
			$params = array(
				'conditions' => array('model'=>$mod, 'model_id'=>$pid),
				'order' => array('Document.id'),
				'recursive' => -1,
				'fields' => array('Document.id', 'file_name', 'file_size', 'extension', 'is_default')
			);
			$files = $this->Document->find('all', $params);
			
			$this->autoRender = false;
			
			echo json_encode($files);
		}
	}
	
	function admin_delete($mod, $pid = 0, $fileId = 0) {
		if ($this->RequestHandler->isAjax()) {
			$returns['success'] = false;
			$doc = $this->Document->findById($fileId, array('file_name'));
			if ($this->Document->delete($fileId)) {
				$this->delFile(FILE_PATH_ROOT . DS . $mod . DS . $pid, $doc['Document']['file_name']);
					
				$param1 = array(
					'conditions' => array('model_id' => $pid, 'model' => $mod),
					'fields' => array('id'),
					'recursive' => -1
				);
				$item = $this->Document->find('first', $param1);
				$newDefaultId = isset($item['Document']['id']) ? $item['Document']['id'] : 0;
				
				$param2 = array(
					'conditions' => array('model_id' => $pid, 'model' => $mod, 'is_default' => 1),
					'recursive' => -1
				);
				$fileCount = $this->Document->find('count', $param2); 
				if ($fileCount <= 0 && $newDefaultId > 0) {
					$this->Document->id = $newDefaultId;
					$this->Document->saveField('is_default', 1);
				}
			
				$returns['success'] = true;
			}
			
			$this->autoRender = false;
			
			echo json_encode($returns);
		}
	}
	
	function admin_set($mod, $pid = 0, $fileId = 0) {
		if ($this->RequestHandler->isAjax()) {
			$returns['success'] = false;
			
			//remove old default Document
			$conditions = array('model_id' => $pid, 'model' => $mod);
			$this->Document->updateAll(array('is_default' => 0), $conditions);
		
			//set new default Document
			$this->Document->id = $fileId;
			if ($this->Document->saveField('is_default', 1)) {
				$returns['success'] = true;
			}
			
			$this->autoRender = false;
			
			echo json_encode($returns);
		}
	}
	
	function admin_upload($mod, $pid = 0) {
		$upload_path = FILE_PATH_ROOT . DS . $mod;
		$this->DocumentsUploader->relatedModelName = $mod;
		$this->DocumentsUploader->relatedModelId	= $pid;
		$this->DocumentsUploader->upload('upload_file', $upload_path);
		
		$error = $this->DocumentsUploader->getError();
		
		$this->autoRender = false;
		echo json_encode($error);
		
		$this->render('admin_upload', 'ajax');
	}
}
?>