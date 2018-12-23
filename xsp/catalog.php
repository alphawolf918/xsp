<!doctype html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<style type="text/css">
		<!--
		 * {
			 font-family: "Verdana";
			 font-size: 12px;
		 }
		-->
		</style>
		<title>Export Test</title>
	</head>
	<body>
<?php
	$x = new DOMDocument();
	$x->load("catalog.xml");
	$xpath = new DOMXPath($x);
	$nodes = $xpath->query('//*');
	$nodeNames = array();
	$i=0;
	foreach($nodes as $node){
		echo $node->nodeName.' = '.$node->nodeValue.'<br />';
	}
?>
	</body>
</html>