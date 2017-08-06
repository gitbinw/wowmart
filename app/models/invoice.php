<?php
class Invoice extends AppModel {
	var $name = 'Invoice';
	var $hasMany = 'Order';
	var $recursive = 2;
}
?>