<?php 
class ImagesUploaderComponent extends Object {

	// You may choose two options array and db. When you use db, then file variables are saved into the database using dbModel.
	private $handlerType = 'db'; 

	private $count = 0;
	
	// Set your model here
	private $dbModel = 'Image';
	
	// You can modify the array keys and values below in case you want save file variables into the database.
	var $relatedModelName = 'Product';
	var $relatedModelId	  = 0;
	var $extraData = array(); //include link_model, link_model_id, alt_text, image_type
	var $dbFields = array(
				'dir' => 'dir', // The directory the file was uploaded to
				'file_name'	=> 'file_name', // The file name it was saved with
				'mime_type'	=> 'mime_type', // The mime type of the file
				'extension' => 'extension', // The extension of the file
				'file_size'	=> 'file_size', // The size of the file
				'width'			=> 'width',
				'height'		=> 'height',
				'model_id'	=> 'model_id',
				'model'			=> 'model',
				'link_model' => 'link_model', //this is for image click through (link to a model)
				'link_model_id' => 'link_model_id',
				'alt_text' => 'alt_text', // alt text for image description
				'image_type' => 'image_type' // image type: logo, photo etc.
	);

	var $allowedMime = array( 
				  'image/jpeg', // images
				  'image/pjpeg', 
				  'image/png', 
				  'image/gif'
		);


		var $maxFileSize = 5000; //5M
		
		var $dimensions = array(
			1=>array('width'=>500, 'height'=>500),
			2=>array('width'=>300, 'height'=>300),
			3=>array('width'=>200, 'height'=>200),
			4=>array('width'=>100, 'height'=>100)
		);

		private $errorMsg = array();
		private $isError = false;
		private $lastUploadData;
		private $logMsg = '';		// using this variable is generating upload log
		private $dir = ''; 		// server directory where uploaded files will be save
		private $uploadedFiles = array();
		public $files2upload = 0; 	// number of files which should be send
		public $uploadedNum = 0; 	// number of sent files
		
		/**
		* constructor all written by Chris Partridge
		*/
		function __construct() {

			if (!in_array($this->handlerType, array('db', 'array'))) {
				$this->setError('The specified handler type is invalid.');
			}

			if ($this->handlerType == 'db') {
				if (App::import('Model', $this->dbModel)) {
					// create the model 
					$this->{$this->dbModel} = & new $this->dbModel;
				} else {
					$this->setError('The specified model does not exist.');
				}
				
				if (!is_subclass_of($this->{$this->dbModel}, 'AppModel')) {
					unset($this->{$this->dbModel});
					$this->setError('The specified model is not a cake model.');
				}
			}
				
			parent::__construct();
		}

		/**
		* Method keeps errors
		* @param $error - the error message
		*/
		private function setError($error, $fieldId = null) {
			$this->isError = true;
			if (isset($fieldId)) {
				$this->errorMsg[$fieldId]   = $error;
			} else { 
				$this->errorMsg['global'] = $error;	
			}
			$this->setLog($error);
		}
			
		public function getError() {
			$returns['uploaded'] = $this->getUploadedFiles();
			if (true === $this->isError) {
				$returns['success'] = false;
				$returns['message'] = $this->errorMsg;
				return $returns;
			} else {
				$returns['success'] = true;
				$returns['message'] = 'No Error';
				return $returns;
			}
		}

		/**
		* Method generates upload log
		* @param $logmsg - the log message
		*/
		private function setLog($logMsg) {
			$this->logMsg .= $logMsg;
		}
		
		private function setUploadedLog($imgId) {
			$this->uploadedFiles[] = $imgId;
		}

		public function getUploadedFiles() {
			return $this->uploadedFiles;	
		}
		
		public function getLog() {
			return $this->logMsg;
		}

		public function getMime($file) {
			if (!function_exists('mime_content_type')) {
				return system(trim('file -bi ' . escapeshellarg ($file)));
			} else {
				return mime_content_type($file);
			}
		}

		/**
		* If any files were uploaded returns last upload info
		*/
		public function getLastUploadInfo() {
			if(!is_array($this->lastUploadData)) {
				$this->setError('No upload detected.');
			} else {
				return $this->lastUploadData;
			}
		}

		/**
		* Like a name - method try to upload one file
		* @param $field - name of form field
		* @param $dir - server path where files will be save
		*/
		public function upload($field, $dir) {

			if ($_FILES[$field]) {
				$filesCount = sizeof($_FILES[$field]['name']);
				$this->files2upload = $filesCount;

				$logMsg = '=============== UPLOAD LOG ===============<br />';
				$logMsg .= 'Upload folder: ' . $dir . '<br />';
				$logMsg .= 'Files to send: ' . $filesCount . '<br />';
				$logMsg .= '---------------------------------------------------------------<br />';
				$this->setLog($logMsg);

				for ($i = 0; $i < $filesCount; $i++) {
					if (isset($_FILES[$field]['tmp_name'][$i]) && !empty($_FILES[$field]['tmp_name'][$i])) {
						if ($this->tryUpload($field, $dir, $i)) {
							$this->setUploadedLog($i);
							$this->setLog('File was successfully uploaded.');
							$this->uploadedNum++;
						} else {
							$this->setError(' File wasn\'t uploaded.');
						}
						$this->setLog('<br /><br />');
					}
				}

				$logMsg = '---------------------------------------------------------------';
				$logMsg .= '<br />Files ' . $this->uploadedNum . ' of ' . $filesCount . ' were successfully uploaded.<br /><br />';
				$this->setLog($logMsg);
			} else {
				$this->setError('No files supplied.');
			}

		}

		/**
		* Method almost all written by Chris Partridge, original name: upload
		* Handle the upload process
		* @param $field - form field
		* @param $dir - directory where file will be copy
		* @param $Id - position in array
		*/
		private function tryUpload($field, $dir, $fileId) {

			$logMsg = 'File number: ' . ($fileId + 1) . '<br />';
			$logMsg .= 'name: ' . $_FILES[$field]['name'][$fileId] . '<br />';
			$logMsg .= 'temporary name: ' . $_FILES[$field]['tmp_name'][$fileId] . '<br />';
			$logMsg .= 'type: ' . $_FILES[$field]['type'][$fileId] . '<br />';
			$logMsg .= 'error number: ' . $_FILES[$field]['error'][$fileId] . '<br />';
			$logMsg .= 'size: ' . $_FILES[$field]['size'][$fileId] . '<br />';
			$this->setLog($logMsg);

			// Check that the two method variables are set
			if (empty($field) || empty($dir)) {
				$this->setError('Field name and a directory are required.', $fileId);
				return false;
			}
		
			// Check that the upload file field exists
			if (!isset($_FILES[$field]['name'][$fileId])) {
				$this->setError('No file supplied.', $fileId);
				return false;
			}
	
			// Check that the file upload was not errornous
			if ($_FILES[$field]['error'][$fileId] != 0) {				
				switch($_FILES[$field]['error'][$fileId]) {
					case 1:
						$this->setError('The file is too large (server).', $fileId);
					break;
					
					case 2:
						$this->setError('The file is too large (form).', $fileId);
					break;
					
					case 3:
						$this->setError('The file was only partially uploaded.', $fileId);
					break;
					
					case 4:
						$this->setError('No file was uploaded.', $fileId);
					break;
					
					case 5:
						$this->setError('The servers temporary folder is missing.', $fileId);
					break;
					
					case 6:
						$this->setError('Failed to write to the temporary folder.', $fileId);
					break;
				}
				
				return false;
			}
	
			// Check that the supplied dir ends with a DS
			if ($dir[(strlen($dir)-1)] != DS) {
				$dir .= DS;
			}

			// Check that the given dir is writable
			if (!is_dir($dir) || !is_writable($dir)) {
				$this->setError('No such directory ' . $dir . ' or not writable', $fileId);
				return false;
			}

			// Check that the file is of a legal mime type
			if (!in_array($_FILES[$field]['type'][$fileId], $this->allowedMime)) {
				$this->setError('Image type(' . $_FILES[$field]['type'][$fileId] . ') is not valid.', $fileId);
				return false;
			}
			
			// Get file size
			$file_size = $_FILES[$field]['size'][$fileId] / 1024;
			
			// Check that the file is smaller than the maximum filesize.
			if ($file_size > $this->maxFileSize) {
				$this->setError('File size(' . $file_size . 'K) is too big.', $fileId);
				return false;
			}
			
			// Get the mime type for the file
			$mime_type = $_FILES[$field]['type'][$fileId];
			$file_ext  = $this->getFileExtension($mime_type);
			
			// Get dimensions of the image.
			list($width, $height) = getimagesize($_FILES[$field]['tmp_name'][$fileId]);
			
			// Update the database is using db
			if ($this->handlerType == 'db') {
				// Create database update array
				$linkModelName = isset($this->extraData['link_model'][$fileId]) && !empty($this->extraData['link_model'][$fileId]) ? $this->extraData['link_model'][$fileId] : '';
				$linkModelId   = isset($this->extraData['link_model_id'][$fileId]) && !empty($this->extraData['link_model_id'][$fileId]) ? $this->extraData['link_model_id'][$fileId] : 0;
				$linkAltText   = isset($this->extraData['alt_text'][$fileId]) && !empty($this->extraData['alt_text'][$fileId]) ? $this->extraData['alt_text'][$fileId] : '';
				$linkImgType   = isset($this->extraData['image_type'][$fileId]) && !empty($this->extraData['image_type'][$fileId]) ? $this->extraData['image_type'][$fileId] : 0;
				$file_details = array(
						$this->dbModel => array( 
								$this->dbFields['dir'] => $dir,
								$this->dbFields['file_name'] => basename($_FILES[$field]['name'][$fileId]),
								$this->dbFields['mime_type'] => $mime_type,
								$this->dbFields['extension'] => $file_ext,
								$this->dbFields['file_size'] => $file_size,
								$this->dbFields['width']	 => $width,
								$this->dbFields['height']	 => $height,
								$this->dbFields['model_id']	 => $this->relatedModelId,
								$this->dbFields['model'] 	 => $this->relatedModelName,
								$this->dbFields['link_model'] 	 => $linkModelName,
								$this->dbFields['link_model_id'] => $linkModelId,
								$this->dbFields['alt_text'] => $linkAltText,
								$this->dbFields['image_type'] => $linkImgType
						)
				);
				
				if (!$this->hasDefaultImage()) {
					$file_details[$this->dbModel]['is_default'] = 1;
				}
				
				// Update database, set error on failure		
				$this->{$this->dbModel}->create();			  
				if (!$this->{$this->dbModel}->save($file_details, false)) {
					$this->setError('There was a database error.', $fileId);
					return false;
				} else {					
					$this->setLog('File record added to the database.<br />');
				}
				
				// Get the database id
				$file_id = $this->{$this->dbModel}->getLastInsertId();
				
				$this->dir = $dir . $file_id . DS;
			}
		
			// Generate dir name if using handler type of array or db - doesn't matter
			if ($this->handlerType == 'array' || $this->handlerType == 'db') {
				if ($this->dir == '')
					$this->dir = $dir;//. uniqid('') . DS;		
			}

			// Check if dir exists
			if (!is_dir($this->dir)) {
				// Create a folder for the file, on failure delete db record and set error
				if (!mkdir($this->dir)) {

					// Remove db record if using db
					if ($this->handlerType == 'db') {
						$this->{$this->dbModel}->del($file_id);
						$this->setLog('Removed file record from the database.<br />');
					}
				
					// Set the error and return false
					$this->setError('The folder could not be created.', $fileId);
					return false;
				}
			}

			// Move the uploaded file to the new directory
			if (!move_uploaded_file($_FILES[$field]['tmp_name'][$fileId], $this->dir . basename($_FILES[$field]['name'][$fileId]))) {
				// Remove db record if using db
				if($this->handlerType == 'db')	{
					$this->{$this->dbModel}->del($file_id);
					$this->setLog('Removed file record from the database.<br />');
				}
				
				// Set the error and return false
				$this->setError('Cannot move file to created directory', $fileId);
				return false;
			} else {
				$this->generateImages($file_id, basename($_FILES[$field]['name'][$fileId]), $file_ext);
			}
			
			// Set the data for the lastUploadData variable
			$this->lastUploadData = array( 'dir' => $this->dir,
							'file_name' => basename($_FILES[$field]['name'][$fileId]),
							'mime_type' => $mime_type,
							'file_size' => $file_size
						);
			
			// Add the id if using db
			if($this->handlerType == 'db') {
				$this->_lastUploadData['id'] = $file_id;
			}
			
			// Return true
			return true;
		}
		
		private function generateOneImage ($source,$target,$img_w,$img_h) {
			$size = getimagesize ($source);
			switch ( $size[2] ) {
				case 1:		
         			$objImg = imagecreatefromgif($source);
         			break;
        		case 2:
            		$objImg = imagecreatefromjpeg($source);
            		break;
       			case 3:
            		$objImg = imagecreatefrompng($source);
            		break;
			}
			if ( isset ($objImg) ) {
				$rateOrig = $size[0] / $size[1];
				$rateNew  = $img_w / $img_h;
				
				if ($size[0] < $img_w && $size[1] < $img_h) {
					$img_w = $size[0];
					$img_h = $size[1];
				} else if ($rateNew > $rateOrig) {
					$img_w = $size[0] * $img_h / $size[1];
				} else if ($rateNew < $rateOrig) {
					$img_h = $size[1] * $img_w / $size[0];
				}
				
				$objImg_p = imagecreatetruecolor($img_w, $img_h);
				imagecopyresampled($objImg_p, $objImg, 0, 0, 0, 0, $img_w, $img_h, $size[0], $size[1]);
				imagejpeg($objImg_p, $target, 80);
			} else {
				copy( $source, $img_path );
			}
		}
		
		private function hasDefaultImage() {
			$params = array(
				'conditions' => array('model_id' => $this->relatedModelId, 'model' => $this->relatedModelName, 'is_default' => 1),
				'recursive' => -1
			);
			$imgCount = $this->{$this->dbModel}->find('count', $params);
			if ($imgCount > 0) {
				return true;
			}
			
			return false;
		}
		
		private function generateImages ($imgId, $imgName, $extension) {
			$source = $this->dir . $imgName;
			foreach($this->dimensions as $key => $dim) {
				$target = $this->dir . $this->generateImageName($imgId, $key, $extension);
				$this->generateOneImage($source, $target, $dim['width'], $dim['height']); 
			}
			unlink($source);
		}
		
		private function generateImageName($imgId, $dimensionKey, $extension) {
			$name = $imgId . 'a' . $dimensionKey . $extension;
			
			return $name;
		}
		
		private function getFileExtension($mimeType) {
			$ext = '';
			switch (trim(strtolower($mimeType))) {
				case 'image/jpeg'  :
				case 'image/pjpeg' :
					$ext = '.jpg';
					break;
				case 'image/png'   :
					$ext = '.png';
					break;
				case 'image/gif'   :
					$ext = '.gif';
					break;
			}
			
			return $ext;
		}
		
}

?>