<?php
class FilesHandlerComponent extends Object {
	var $image_types = array ( 'image/jpeg', 'image/png', 'image/gif', 'image/pjpeg' );
	var $file_types  = array ( '');
	var $image_model = 'Image';
	/*
	 *** value 1: only one image for main image of product ***
	 *** value 2: can be more than one images for product ***
	 *** value 3: the small image for product list, this is actually the same image as value 1 ***
	*/
	var $image_sizes = array ( 
		1=>array('w'=>450,'h'=>450),
		2=>array('w'=>320,'h'=>320),
		3=>array('w'=>200,'h'=>200),
		4=>array('w'=>110, 'h'=>110)
	);
	
	function __construct() {
		if (App::import('Model', $this->image_model)) {
			$this->{$this->image_model} = & new $this->image_model;
		}
	}
	
	function uploadImage ( $strFieldName, $path='',$prefix='') {
		if( !isset ( $_FILES[$strFieldName] ) ) return false;
		$file_name = $_FILES[$strFieldName]["name"];
  		$file_type = $_FILES[$strFieldName]["type"];
  		$file_temp = $_FILES[$strFieldName]["tmp_name"];
  		if ( isset ( $file_name ) && !empty ( $file_name ) ) {
    		if(!in_array($file_type, $this->image_types)) return false;
    		$fname = $path.uniqid('').$file_name;
    		$fullPath = $this->image_root.$fname;
    		if(move_uploaded_file($file_temp, $fullPath)) {
      			return $fname;
    		}
  		}
  		return false;
	}
	
	function saveImage ( $prodId, $source, $index ) {
		$target = 'images/products/'.$prodId."/";
		$dir = $this->image_root.$target;
		if ( !is_dir ( $dir ) && !mkdir( $dir ) ) return false;
		$source = $this->image_root.$source;		 
		$img = getimagesize($source);
		$img_width = $img [ 0 ];
		$img_height = $img [ 1 ];
		$img_type = $img [ 'mime' ];
		$img_id = '';
		$img_ext = $this->getImageExtension(@$img [ 2 ]);
		$img_prefix = 'prod_'.$prodId."_".$index."_";
		if ( $index == 2 ) {
			$arrRes = $this->{$this->image_model}->findAll ( 'img_index='.$index.' AND product_id='.$prodId, 
															 'COUNT(*)+1 AS num',null,null,null,-1 );
			$img_subfix = $arrRes[0][0]['num'];		
		} else {
			$img_subfix = '1';
			$arrIds= $this->{$this->image_model}->find ( 'img_index=1 AND product_id='.$prodId, 'id', null, -1 );
			if ( isset ( $arrIds ['Image'][ 'id'] ) && !empty ( $arrIds ['Image'][ 'id'] ) ) {
				$img_id = $arrIds ['Image'][ 'id'];
			}		
		}
														  		
		$img_name = $img_prefix.$img_subfix.$img_ext;
		$img_path = $this->image_root.$target.$img_name;
		
		$data = array ("Image"=>array ( 
										'dir'=>$target,'file_name'=>$img_name,
										'width'=>$img_width,'height'=>$img_height,'mime_type'=>$img_type,
										'img_index'=>$index,'product_id'=>$prodId,
										'id'=>$img_id 
									  ) 
					  );
		if ( $this->{$this->image_model}->save ($data) ) {
			if ( copy( $source, $img_path ) ) {
				return unlink ( $source );
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function saveImages ( $source, $prodId, $index ) {
		$target = 'images/products/'.$prodId."/";
		$dir = $this->image_root.$target;
		if ( !is_dir ( $dir ) && !mkdir( $dir ) ) return false;
		
		if ( $index == 1 ) {
			$arrIndex = array ( 1, 3 );
		} else {
			$arrIndex = array ( $index );
		}
		
		$source = $this->image_root.$source;
		$size = getimagesize ($source);
		$img_type = $size [ 'mime' ];
		$img_id = '';
		
		switch ( $size[2] ) {
			case 1:		
         		$objImg = imagecreatefromgif($source);
         		$img_ext = '.gif';
         		break;
            case 2:
                $objImg = imagecreatefromjpeg($source);
                $img_ext = '.jpg';
                break;
            case 3:
                $objImg = imagecreatefrompng($source);
                $img_ext = '.png';
                break;
		}
		foreach ( $arrIndex as $index ) {
			$new_size = $this->adjustImageSize(	$size[0],$size[1],
											   	$this->image_sizes [$index]['w'],$this->image_sizes [$index]['h'] );
			$img_w = $new_size ['w'];
			$img_h = $new_size ['h'];
			$img_prefix = 'prod_'.$prodId."_".$index."_";
			
			if ( $index == 2 ) {
				$arrRes = $this->{$this->image_model}->findAll ( 'img_index='.$index.' AND product_id='.$prodId, 
															 	 'COUNT(*)+1 AS num',null,null,null,-1 );
				$img_subfix = $arrRes[0][0]['num'];		
			} else {
				$img_subfix = '1';							//means only one image
				$arrIds= $this->{$this->image_model}->find('img_index='.$index.' AND product_id='.$prodId,'id',null,-1);
				if ( isset ( $arrIds ['Image'][ 'id'] ) && !empty ( $arrIds ['Image'][ 'id'] ) ) {
					$img_id = $arrIds ['Image'][ 'id'];
				}		
			}
			
			$img_name = $img_prefix.$img_subfix.$img_ext;
			$img_path = $this->image_root.$target.$img_name;
			$data = array ( "Image"=>array ( 
												'dir'=>$target,'file_name'=>$img_name,
												'width'=>$img_w,'height'=>$img_h,'mime_type'=>$img_type,
												'img_index'=>$index,'product_id'=>$prodId,'id'=>$img_id 
									  	   ) 
					  	  );
			if ( $this->{$this->image_model}->save ($data) ) {
				if ( isset ($objImg) ) {	
					$objImg_p = imagecreatetruecolor($img_w,$img_h);
					imagecopyresampled($objImg_p, $objImg, 0, 0, 0, 0, $img_w, $img_h, $size[0], $size[1]);
					imagejpeg($objImg_p, $img_path);
				} else {
					copy( $source, $img_path );
				}
			}
		}
		unlink ( $source );
	}
	
	function copyImage ($source,$target,$img_w,$img_h) {
		$size = getimagesize ($source);
		$objImg_p = imagecreatetruecolor($img_w,$img_h);
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
			imagecopyresampled($objImg_p, $objImg, 0, 0, 0, 0, $img_w, $img_h, $size[0], $size[1]);
			imagejpeg($objImg_p, $target, 100);
		} else {
			copy( $source, $img_path );
		}
		unlink($source);
	}
	
	function adjustImageSize ($width, $height, $new_width, $new_height) {
    	$h_factor = $new_height / $height;
    	$w_factor = $new_width / $width;
    	if ($h_factor <= $w_factor) {
    		$new_size ['h'] = $new_height;
    		$new_size ['w'] = $width * $h_factor;
    	} else {
    		$new_size ['w'] = $new_width;
    		$new_size ['h'] = $height * $w_factor;
    	}
        
    	return $new_size;
  	}
	
	function deleteImage ( $path ) {
		$path = $this->image_root.$path;
		if ( is_file( $path ) ) {
			return unlink( $path );
		}
	}
	
	function getImageExtension ( $mime_type_id ) {
		switch ( $mime_type_id ) {
			case 1:
         		return '.gif';
            case 2:
                return '.jpg';
            case 3:
                return '.png';
            case 4:
                return '.swf';
            case 5:
                return '.psd';
            case 6:
                return '.bmp';
            case 7:
                return '.tiff';
            case 8:
                return '.tiff';
            case 9:
                return '.jpc';
            case 10:
                return '.jp2';
            case 11:
                return '.jpx';
            case 12:
                return '.jb2';
            case 13:
                return '.swc';
            case 14:
                return '.iff';
            case 15:
                return '.wbmp';
            case 16:
                return '.xbm';
            default:
                return '';
		}
	}
	
	function get_image_extension ( $filename ) {
		if ( function_exists ( 'exif_imagetype' ) ) {
			switch ( exif_imagetype ( $filename ) ) {
				case 1:
         			return 'gif';
            	case 2:
                	return 'jpg';
            	case 3:
                	return 'png';
            	case 4:
                	return 'swf';
            	case 5:
                	return 'psd';
            	case 6:
                	return 'bmp';
            	case 7:
                	return 'tiff';
            	case 8:
                	return 'tiff';
            	case 9:
                	return 'jpc';
            	case 10:
                	return 'jp2';
            	case 11:
                	return 'jpx';
            	case 12:
                	return 'jb2';
            	case 13:
                	return 'swc';
            	case 14:
                	return 'iff';
            	case 15:
                	return 'wbmp';
            	case 16:
                	return 'xbm';
            	default:
                	return false;
			}
		} else {
			return false;
		}
	} 
}
?>