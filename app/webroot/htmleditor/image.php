<?php
require_once ( 'Utility.php' );

DEFINE ( 'PATH_PAGE_IMAGES', 'data' );
DEFINE ( 'PATH_ROOT_IMAGES', 'htmleditor/data');
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(dirname(__FILE__))));
}

$companyId = Utility::getValue ( 'com', 1 );
$action = Utility::getValue ( 'action' );
$prefix = 'insert';
$fileField = 'img_file';

if ( $action == 'upload_img' && isset ( $_FILES[$fileField]["name"] ) &&
          !empty ( $_FILES[$fileField]["name"] ) ) {

  $strPath = PATH_PAGE_IMAGES."/".$companyId."/";
  $name = $_FILES[$fileField]["name"];
  //$path = "/".WEBROOT_DIR."/".PATH_ROOT_IMAGES."/".$companyId."/".$prefix."_".$name;
  $path = "/".PATH_ROOT_IMAGES."/".$companyId."/".$prefix."_".$name;

  Utility::createDir ( $strPath );
  Utility::uploadImage ( $fileField, $strPath, true, $prefix );
  echo "
         <script>
         parent.document.getElementById('url').value='".$path."';
         parent.document.getElementById('img_file').disabled=true;
         </script>
       ";
}
else {
  echo 'waiting for uploading...';
}

?>
