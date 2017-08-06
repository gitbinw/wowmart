<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
 
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/webpage/*', array('controller' => 'pages', 'action' => 'display'));
	
	Router::connect('/admin', array('controller' => 'console', 'action' => 'index', 'admin' => true));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */

Router::connect('/checkout/*', array('controller' => 'orders', 'action' => 'checkout'));
Router::connect('/account/*', array('controller' => 'customers', 'action' => 'account'));
Router::connect('/register/*', array('controller' => 'customers', 'action' => 'register'));
Router::connect('/login/*', array('controller' => 'customers', 'action' => 'login'));
Router::connect('/logout/*', array('controller' => 'customers', 'action' => 'logout'));
Router::connect('/forgot/*', array('controller' => 'customers', 'action' => 'forgot'));

Router::connect('/search/*', array('controller' => 'products', 'action' => 'search'));
Router::connect('/category/*', array('controller' => 'categories', 'action' => 'view'));
Router::connect('/product/*', array('controller' => 'products', 'action' => 'view'));
Router::connect('/cart/*', array('controller' => 'carts', 'action' => 'view'));

Router::connect('/orders/ordersheet.pdf', array('controller' => 'orders', 'action' => 'output', 'pdf'));

/* Paypal IPN plugin */
  Router::connect('/paypal_ipn/process', array('plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'process'));
  
  /* Optional Routes, but nice for administration */
  Router::connect('/paypal_ipn/edit/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'edit'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/view/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'view'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/delete/:id', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'delete'), array('id' => '[a-zA-Z0-9\-]+', 'pass' => array('id')));
  Router::connect('/paypal_ipn/add', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'edit'));
  Router::connect('/paypal_ipn', array('admin' => true, 'plugin' => 'paypal_ipn', 'controller' => 'instant_payment_notifications', 'action' => 'index'));/*
  /* End Paypal IPN plugin */ 