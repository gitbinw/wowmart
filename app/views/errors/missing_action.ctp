<?php
/* SVN FILE: $Id: missing_action.thtml 4409 2007-02-02 13:20:59Z phpnut $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.libs.view.templates.errors
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 4409 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2007-02-02 07:20:59 -0600 (Fri, 02 Feb 2007) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<h1>Missing Method in <?php echo $controller;?></h1>
<p class="error">You are seeing this error because the action <em><?php echo $action;?></em> is not defined in controller <em><?php echo $controller;?></em></p>
<p><span class="notice">If you want to customize this error message, create <?php echo APP_DIR.DS."views/errors/missing_action.thtml"; ?>.</span></p>
<p><span class="notice"><strong>Fatal</strong>: Confirm you have created the <?php echo $controller;?>::<?php echo $action;?>() in file : <?php echo APP_DIR.DS."controllers".DS.Inflector::underscore($controller).".php"; ?></p>
<p>&lt;?php<br />
class <?php echo $controller;?> extends AppController {<br />
&nbsp;&nbsp;&nbsp;<strong>function <?php echo $action;?>() {<br />
&nbsp;&nbsp;&nbsp;}</strong><br />
}<br />
?&gt;<br />
</p>