<?php
	require 'xsp.php';
	$xsp = new XSP();
	$xsp->Parse('fetch field[@name="'.$xsp->Parse('with database.table return field.@name from xspbase.xml').'"] from xspbase.xml');
?>