<?php
/* SVN FILE: $Id: missing_view.thtml 4409 2007-02-02 13:20:59Z phpnut $ */
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
<h1>Missing view</h1>
<p class="error">You are seeing this error because the view for <em><?php echo $controller.'Controller';?>::<?php echo $action;?>()</em> could not be found.</p>
<p><span class="notice">If you want to customize this error message, create <?php echo APP_DIR.DS."views/errors/missing_view.thtml"; ?>.</span></p>
<p><span class="notice"><strong>Fatal</strong>: Confirm you have created the file : <?php echo $file;?></p>