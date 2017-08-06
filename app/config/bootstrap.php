<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php
 *
 * This is an application wide file to load any function that is not used within a class
 * define. You can also use this to include or require any files in your application.
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * App::build(array(
 *     'plugins' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'models' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'views' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'controllers' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'datasources' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'behaviors' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'components' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'helpers' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'vendors' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'shells' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'locales' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * As of 1.3, additional rules for the inflector are added below
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

define('STORE_FULLNAME', 'Wowmart Online Shop');
define('STORE_NAME', 'WOWMART'); 
define('USER_ID_PREFIX', 'WOWMART');
define('SITE_URL', 'http://dev.wowmart.com:8080');
define('SITE_DOMAIN', 'wowmart.com.au');

define('COOKIE_WOWMART', 'WM');

define('APP_VENDORS', ROOT . DS . APP_DIR . DS . 'vendors');
define('APP_VENDORS_PAYPAL_REST', APP_VENDORS . DS . 'paypal_rest');

/*1 is the ID in sys_suppliers table. we don't need Supplier for this website,
 * so, just created a supplier which is wowmart, all products are linked to 
 * this supplier.
 */
define('SUPPLIER_DEFAULT_ID', 1); 

define('SITE_URL_ROOT', '');

define('WEBPAGE_ROOT', SITE_URL_ROOT . '/page/');

//Default meta data for SEO
define('DEFAULT_PAGE_TITLE', "Wowmart Online Shop");
define('DEFAULT_META_DESC', "Wowmart Online Shop");
define('DEFAULT_META_KEYWORDS', "Wowmart Online Shop");

//Default user group, use for user register from front-end and admin register a user from back-end
define('USER_GROUP_CUSTOMER', 3);
define('USER_GROUP_SUPPLIER', 4);

//Admin menus constants - need recursive tree submenus
define('ADMIN_MENU_CATEGORY', 3);
define('ADMIN_MENU_GROUP', 1);

//Image Path Root
define('MEDIA_PATH_ROOT', WWW_ROOT . 'medias');
define('MEDIA_PATH_TEMP', WWW_ROOT . 'medias' . DS . 'tmp');
define('MEDIA_URL_TEMP', '/medias/tmp'); 
define('IMAGE_PATH_ROOT', WWW_ROOT . 'medias' . DS . 'images');
define('IMAGE_URL_ROOT', '/medias/images');
define('PRODUCT_IMAGE_PATH_ROOT', WWW_ROOT . 'medias' . DS . 'products');
define('PRODUCT_IMAGE_URL_ROOT', '/medias/products');

define('IMAGE_PREFIX_BANNER', 'banner_');
define('IMAGE_PREFIX_HOMEBANNER', 'home_banner_');

//Page Banners Types
define('PAGE_BANNER_TYPE_TOP', 'top_banner');
define('PAGE_BANNER_TYPE_FEATURE', 'feature_banner');

//Page Templates
define('PAGE_TEMPLATE_DEFAULT', 1);
define('PAGE_TEMPLATE_WITHOUTBANNER', 2);
define('PAGE_TEMPLATE_SERVICE', 3);

//File Path Root
define('FILE_PATH_ROOT', WWW_ROOT . 'files');

//Product Images path
define('IMAGE_PATH_PRODUCT', WWW_ROOT . 'medias' . DS . 'product');

//Image Type
define('IMAGE_SUPPLIER_LOGO', 1);
define('IMAGE_SUPPLIER_PHOTO', 2);

//Product Files path
define('FILE_PATH_PRODUCT', WWW_ROOT . 'files' . DS . 'product');

//Error Code
define('ERROR_CODE_VALIDATION', '1001');

//Product Types
define('TYPE_NEW_ARRIVALS', 1);
define('TYPE_DISCOUNT', 2);
define('TYPE_HOT_SALE', 3);
define('TYPE_CLEARANCE', 4);

//Order Types
define('TYPE_ORDER_NOT_PAID', 1);
define('TYPE_ORDER_PAY_REVIEW', 2);
define('TYPE_ORDER_PAID', 3);
define('TYPE_ORDER_PENDING', 4);
define('TYPE_ORDER_DELIVERED', 5);
define('TYPE_ORDER_COMPLETED', 6);
define('TYPE_ORDER_RETURN', 7);

//Payment Method
define('PAYMENT_METHOD_CASH', 'cash');

//Business Code
define('BUSINESS_BRASA_DELIVERY', 2);
define('BUSINESS_VIRTUAL_FARMER', 1);

//Delivery Type
define('DELIVERY_TYPE_LETTER', 1);
define('DELIVERY_TYPE_PARCEL', 2);

//Freshla emails
define('EMAIL_TECH', 'develop.binw@gmail.com');
define('EMAIL_BCC', 'develop.binw@gmail.com,vargashospitality@gmail.com');
define('EMAIL_SALES_TO', 'sales@freshla.com.au');
define('EMAIL_SALES_REPLY', 'sales@freshla.com.au');
define('EMAIL_SALES_FROM', 'Freshla <' . EMAIL_SALES_REPLY . '>');

define('EMAIL_SUPPLIERS_TO', 'suppliers@freshla.com.au');
define('EMAIL_SUPPLIERS_REPLY', 'suppliers@freshla.com.au');
define('EMAIL_SUPPLIERS_FROM', 'Freshla <' . EMAIL_SUPPLIERS_REPLY . '>');

//User defined shipping
define('SHIPPING_USERDEFINED_BASIC', 9.8);
define('SHIPPING_USERDEFINED_MAX', 5);
define('SHIPPING_USERDEFINED_PERITEM', 2);