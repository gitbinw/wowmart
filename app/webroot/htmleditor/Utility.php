<?php

class Utility {
 
  private static $image_types = array("bmp","jpeg","gif","png","jpg");
 
  public static function getValue($varName, $default = "") {
    $value = $default ;
    if(isset($_GET[$varName])) 
      $value = $_GET[$varName];
    else if(isset($_POST[$varName])) 
      $value = $_POST[$varName];

    return $value;
  }
  
  public function formatValue($value, $default = '') {
    $returnVal = $default;
    if(isset($value)) $returnVal = $value;
  }

  public function redirectPage ($url) {
    echo "
           <script language='javascript' type='text/JavaScript'>
             document.location.href='".$url."'
           </script>
         ";
  }
  
  public static function clearArray($arr) {
    $arr = array();
  }
  
  public static function arrayStrToLower(&$arr) {
    $temp = array();
    foreach($arr as $key => $a) {
      if(!is_array($a))
        $temp[$key] = strtolower($a);
    }
    $arr = $temp;
  }
  
  public static function getIntPart($fltNumber) {
    $num = explode(".", $fltNumber);
    return $num[0];
  }

  public static function debugLog($str) {
    $fp = fopen("data/log.txt","a+");
    $today = date("H:i:s D F j, Y");
    fwrite($fp,"********$today********\r\n\r\n$str\r\n\r\n");
    fclose($fp);
  }
  
  public static function uploadFile($strFieldName, $path=''){
    $file_name = $_FILES[$strFieldName]["name"];
    $file_temp = $_FILES[$strFieldName]["tmp_name"];
    $target_path = $path;

    if(isset($file_name) && !empty($file_name)) {
      $ltime = date("YmdHis");
      $target_path = $target_path.$ltime.basename($file_name);

      if(move_uploaded_file($file_temp, $target_path)) {
        return $target_path;
      }
    }
    return false;
  }

  public static function uploadImage($strFieldName, $path='', $prefix = true, $prefix_name = '' ) {
    if(!isset($_FILES[$strFieldName])) return false;

    $file_name = $_FILES[$strFieldName]["name"];
    $file_temp = $_FILES[$strFieldName]["tmp_name"];
    $target_path = $path;

    if(isset($file_name) && !empty($file_name)) {
      $str = explode(".",$file_name);
      if(!in_array(strtolower($str[1]), Utility::$image_types)) return false;

      if ( $prefix === true ) {
        $ltime = date("YmdHis");
        if ( !empty ( $prefix_name ) ) {
          $ltime = $prefix_name."_";
        }
        $target_path = $target_path.$ltime.basename($file_name);
      }
      else $target_path = $target_path.basename($file_name);
      if(move_uploaded_file($file_temp, $target_path)) {
        return $target_path;
      }
    }
    return false;
  }
 
  public function delFile ( $filePath ) {
    if ( file_exists ( $filePath ) && is_file ( $filePath ) ) {
      return unlink ( $filePath );
    }
    return false;
  }

  public function createDir ( $strDir ) {
    if ( file_exists ( $strDir ) && is_dir ( $strDir ) ) return $strDir;
    else {
      mkdir ( $strDir );
      chmod ( $strDir, 0744 ); 
      return $strDir;
    }
  }
 
  public function delDir ( $strDir ) {
    if ( file_exists ( $strDir ) && is_dir ( $strDir ) ) {
      if ( $handle = opendir($strDir) ) {
        while ( false !== ( $file = readdir ( $handle ) ) ) {
          if ( $file != "." && $file != ".." ) {
            $spath = $strDir.$file;
            if ( is_file($spath) ) {
              unlink ( $spath );
            }
          }
        }
      }
      rmdir ( $strDir );
    }
  }
  
  public static function getAllImages($folder) {
    $arrImages = array();
    if ($handle = opendir($folder)) {
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
          $spath = $folder.$file;
          if(is_file($spath)) {
            $temp["name"] = $file;
            $temp["path"] = $spath;
            $arrImages[] = $temp;
          }
        }
      }
      closedir($handle);
    }
    return $arrImages;
  }
  
  public function getJpgPhotoPath ($jpeg_data, $maxWidth = 300) {
    $today = date('Ymd');
    $baseName = basename(tempnam('.', $today));
    $jpeg_filename = $this->photoRoot.$baseName;
    $outJpeg = fopen($jpeg_filename, "wb");
    fwrite($outJpeg, $jpeg_data);
    fclose($outJpeg);

    $jpeg_dimensions = getimagesize ($jpeg_filename);
    $width = $jpeg_dimensions[0];
    $height = $jpeg_dimensions[1];
    if( $width > $maxWidth ) {
      $scale_factor = $maxWidth / $width;
      $img_width = $maxWidth;
      $img_height = $height * $scale_factor;
    }
    else {
      $img_width = $width;
      $img_height = $height;
    }
    $photo = array('path'=>$jpeg_filename, 'width'=>$img_width, 'height'=>$img_height);

    return $jpeg_filename;
  }

  public function adjustImageSize ($img_filename, $max, $type=1) {
    $jpeg_dimensions = getimagesize ($img_filename);
    $width = $jpeg_dimensions[0];
    $height = $jpeg_dimensions[1];

    $img_width = $width;
    $img_height = $height;
    if($type == 1) {
      if( $width > $max ) {
        $scale_factor = $max / $width;
        $img_width = $max;
        $img_height = $height * $scale_factor;
      }
    }
    else if($type == 2) {
      if( $height > $max ) {
        $scale_factor = $max / $height;
        $img_height = $max;
        $img_width = $width * $scale_factor;
      }
    }
    
    $imgSize = array('w'=>$img_width, 'h'=>$img_height);
    return $imgSize;
  }

  public function deleteFile($rel_path) {
    $del_success = false;
    if(file_exists($rel_path)) { //delte image file from folder
      if(unlink($rel_path)) $del_success = true;
    }
    else $del_success=true;
    return $del_success;
  }

}

?>
