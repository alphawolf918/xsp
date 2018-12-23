<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="lib.js"></script>
		<script type="text/javascript" src="xscript.js"></script>
		<style type="text/css">
		<!--
		 body {
			font-family: "Verdana";
			font-size: 12px;
		 }
		-->
		</style>
		<title>Script Page</title>
	</head>
	<body>
		<div id="xsp"></div>
		<xscript type="xsp">
		clr -a;
		var set name = "Daisy Johnson";
		var set country = "USA";
		var get name;
		out ", ";
		var get country;
		</xscript>
		<script type="text/javascript" src="xspml.js"></script>
	</body>
</html>