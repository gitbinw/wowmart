<?php 
class MediasManagerComponent extends Object {
	
	private $totalPathDeep = 6;
	private $imageTmp = array('width' => 800, 'height' => 800);
	private $imageSizeConfig = array(
		'xsmall' => array('width' => 97, 'height' => 97),
		'small' => array('width' => 268, 'height' => 268),
		'medium' => array('width' => 308, 'height' => 308),
		'xmedium' => array('width' => 450, 'height' => 450),
		'large' => array('width' => 600, 'height' => 600),
		'xlarge' => array('width' => 800, 'height' => 800)
	);
	private $dbModel = 'Media';
		
	/**
	* constructor all written by Chris Partridge
	*/
	function __construct() {
		if (App::import('Model', $this->dbModel)) {
			// create the model
			$this->{$this->dbModel} = & new $this->dbModel;
		}
		
		parent::__construct();
	}
	
	public function generateProductImages($medias, $prodAlias, $prodId) {
		$targetPath = $this->calculatePath(PRODUCT_IMAGE_PATH_ROOT, $prodId, false, true);
		$this->generateImages($medias, $prodAlias, $targetPath);
	}
	public function generateImages($medias, $imgName, $targetPath = '') {
		if (isset($medias)) {
			$arrMedia = $this->{$this->dbModel}->find('all', array(
							'recursive' => -1,
							'conditions' => array(
								'Media.id' => $medias
							)
						));

			foreach($arrMedia as $index => $m) {
				$mediaPath = $this->calculatePath(IMAGE_PATH_ROOT, $m['Media']['id']);
				$mediaSrc = $mediaPath . DS . $m['Media']['file_name'];
				$targetRoot = !empty($targetPath) ? $targetPath : $mediaPath;
				$targetBase = $targetRoot . DS . $imgName . '_' . $index; 
				
				$tmpImg = $targetBase . '_tmp' . $this->getExtFromFile($m['Media']['file_name']);
				$this->generateOneImage($mediaSrc, $tmpImg, $this->imageTmp['width'], $this->imageTmp['height']);
					
				foreach($this->imageSizeConfig as $key => $size) {
					$target = $targetBase . '_' . $key . $this->getExtFromFile($m['Media']['file_name']);
						
					$this->generateOneImage($tmpImg, $target, $size['width'], $size['height']);
				}
				
				unlink($tmpImg);
				if ($targetRoot != $mediaPath) {
					$origin = $targetBase . '_origin' . $this->getExtFromFile($m['Media']['file_name']);
					copy($mediaSrc, $origin);
				}
			} 
			
		}
	}
	
	public function getFileExtension($mimeType = '') {
		$ext = '.jpg';
		/*switch (trim(strtolower($mimeType))) {
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
			}*/
	
		return $ext;
	}
	
	private function getExtFromFile($filename) {
		$arrSrc = explode('.', $filename);
		$lastIndex = count($arrSrc) - 1;
		$ext = '.' . $arrSrc[$lastIndex];
		
		return $ext;
	}
	
	private function cropOneImage ($source, $target, $size) {
		$this->generateOneImage ($source,$target,$size,$size,$size);
	}
	
	/**
	* Method generate an image with different dimensions 
	* @param $error - the error message
	*/
	private function generateOneImage ($source,$target,$img_w,$img_h,$crop='') {
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
			
			$srcX = $srcY = 0;
			$srcW = $size[0];
			$srcH = $size[1];
			if(!empty($crop)) {
				$cropInfo = $this->getCropDimension($size, $crop);
			}
			if (isset($cropInfo) && $cropInfo !== false) {
				$srcX = $cropInfo['srcX'];
				$srcY = $cropInfo['srcY'];
				$srcW = $cropInfo['srcW'];
				$srcH = $cropInfo['srcH'];
				$img_w = $cropInfo['dstW'];
				$img_h = $cropInfo['dstH'];
			}
			$objImg_p = imagecreatetruecolor($img_w, $img_h);
			imagecopyresampled($objImg_p, $objImg, 0, 0, $srcX, $srcY, $img_w, $img_h, $srcW, $srcH);
			imagejpeg($objImg_p, $target, 80);
		} else {
			copy( $source, $target );
		}
	}
	
	private function getCropDimension($size, $crop) {
		$rate = $size[0] / $size[1];
		if ($rate > 1) {
			$src_x = 0 + (($size[0] - $size[1])/2);
			$src_y = 0;
			$src_width = $size[1];
			$src_height = $size[1];
		} else {
			$src_x = 0;
			$src_y = 0 + (($size[1] - $size[0])/2);
			$src_height = $size[0];
			$src_width = $size[0];
		}
	
		$return = false;
		if ($crop) {
			if (!is_array($crop)) {
				$dst_width = $crop;
				$dst_height = $crop;
			} else {
				$dst_width = $crop['width'];
				$dst_height = $crop['height'];
			}
			$return = array ('srcX' => $src_x,
					'srcY' => $src_y,
					'srcW' => $src_width,
					'srcH' => $src_height,
					'dstW' => $dst_width,
					'dstH' => $dst_height);
		}
		return $return;
	}
	
	public function calculatePath($pathRoot, $imgId, $isTmp = false, $createNew = false) {
		$ttlNumber = $this->totalPathDeep;
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
			if ( $createNew === true && !is_dir( $folder ) ) mkdir( $folder );
		}
	
		if ($isTmp === true) {
			$folder .= DS . 'tmp';
			if ( $createNew === true && !is_dir( $folder ) ) mkdir( $folder );
		}
	
		return $folder;
	}
	public function getRelativePath($imgId) {
		$ttlNumber = $this->totalPathDeep;
		$strId = '' . $imgId;
		$count = strlen($imgId);
	
		$path = '';
		$offset = $ttlNumber - $count;
		for($i=0; $i<$offset; $i++) $path .= '0';
	
		$path .= $imgId;
	
		$folderCount = $ttlNumber / 2;
		$folder = '';
		for($j=0; $j<$folderCount; $j++) {
			$start = $j * 2;
			$folder .= DS . substr($path, $start, 2);
		}
	
		return $folder;
	}
	
	public function rrmdir($dir) {
		if (is_dir ($dir) ) {
			if($fp = opendir($dir)) {
				while( ( $file = readdir($fp) ) !== false ) {
					$path = $dir . DS . $file;
					if ($file != '.' && $file != '..') {
						if (is_dir($path)) {
							$this->rrmdir($path);
						} else  {
							unlink($path);
						}
					}
				}
				closedir($fp);
			}
	
			rmdir($dir);
		}
	}
		
}

?>