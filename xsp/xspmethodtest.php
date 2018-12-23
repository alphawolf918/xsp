<?php
require 'xsp.php';
$x = initXSP();
$str = xParse($x,"var return name");
?><!doctype html>
<html lang="en-US">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<style type="text/css">
		<!--
		body {
			font-family: Verdana;
			font-size: 12px;
		}
		-->
		</style>
		<title>XSP Methods</title>
	</head>
	<body>
		<?php
			echo $str;
		?>
	</body>
</html>