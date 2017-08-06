<?php
/* SVN FILE: $Id: missing_model.thtml 4409 2007-02-02 13:20:59Z phpnut $ */
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
<h1>Missing Model</h1>
<p class="error">No class found for the <em><?php echo $model;?></em> model</p>
<p><span class="notice"><strong>Notice:</strong> If you want to customize this error message, create <?php echo APP_DIR.DS."views/errors/missing_model.thtml"; ?>.</span></p>
<p><span class="notice"><strong>Fatal</strong>: Create the class below in file : <?php echo "app".DS."models".DS.Inflector::underscore($model).".php"; ?></p>
<p>&lt;?php<br />
class <?php echo $model;?> extends AppModel {<br />
&nbsp;&nbsp;&nbsp;var $name = '<?php echo $model;?>';<br />
}<br />
?&gt;<br />
</p>