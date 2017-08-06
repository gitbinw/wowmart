<!--
/**********************************************************************
	Version: FreeRichTextEditor.com Version 1.00.
	License: http://creativecommons.org/licenses/by/2.5/
	Description: Example of how to preload content into freeRTE using PHP.
	Author: Copyright (C) 2006  Steven Ewing
**********************************************************************/
-->
<?php
$action=isset($_GET['action'])?$_GET['action']:1;
?>
<html>
<head>
<title>Medtel Australia - Html Editor</title>
<meta http-equiv="content-type" content="text/html;charset='utf-8'">
<meta http-Equiv="Cache-Control" Content="no-cache">
<meta http-Equiv="Pragma" Content="no-cache">
<meta http-Equiv="Expires" Content="0">
</head>
<body>
<form method="get" onsubmit="return false;">
<!-- Include the Free Rich Text Editor Runtime -->
<script src="js/richtext.js" type="text/javascript" language="javascript"></script>
<!-- Include the Free Rich Text Editor Variables Page -->
<script src="js/config.js" type="text/javascript" language="javascript"></script>
<!-- Initialise the editor -->
<script>
var option_id = <?php if ($action==1) echo "opener.cust_option_id";else echo "opener.cust_board_id";?>;
var content = opener.getCleanCustomizedContent(option_id);
initRTE(content,'');
function saveHtmlContent(){
  var rteContent = getXHTML(trim(document.getElementById(rteName).contentWindow.document.body.innerHTML));
  opener.$("#menu_line li[@id='"+option_id+"'] div").html(rteContent);
  window.close();
}
</script>
<input type="button" value='Save Content' onclick='saveHtmlContent();'>
</form>
</body>
</html>
