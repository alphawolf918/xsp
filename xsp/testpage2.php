<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<style type="text/css">
		<!--
		 body {
			 font-family: Verdana;
			 font-size: 12px;
		 }
		-->
		</style>
		<title>Test Page</title>
	</head>
	<body>
		<?php
			require 'xsp.php';
			$xsp = new XSP();
			$arr = $xsp->Parse("return //name,//country,//age,//city,//state from customers2.xml");
			foreach($arr as $key=>$val){
				$k = str_replace("info_","",$key);
				$k = ucfirst($k);
				echo "
				<strong>".$k."</strong>: ".$arr[$key]."
				<br />";
			}
		?>
	</body>
</html>