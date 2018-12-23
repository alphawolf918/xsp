<!doctype html>
<html lang="en">
 	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="favicon.ico" />
 		<script type="text/javascript" src="jquery.js"></script>
 		<script type="text/javascript" src="lib.js"></script>
		<script type="text/javascript" src="modal.js"></script>
		<link rel="stylesheet" type="text/css" href="styles.css" media="all" />
 		<title>XSP Console</title>
	</head>
	<body>
		<div class="dialog"></div>
		<div class="container">
			<div class="title topGrad">&raquo; Extensive Server Path &laquo;</div>
				<div class="brl"></div>
				<div class="brl"></div>
				<div class="brl"></div>
				<div id="cmd"></div>
				<div class="brl"></div>
			<div class="navButtons">
				<button onclick="Xsp.clearScreen();" class="aquaButton"><div class="glare"></div>Clear Screen</button>
				&nbsp;
				<button onclick="Xsp.lastCmd();" class="aquaButton"><div class="glare"></div>Last Command</button>
				&nbsp;
				<button onclick="Xsp.help();" class="aquaButton"><div class="glare"></div>Help</button>
				&nbsp;
				<button onclick="Xsp.clearNow('vars');" class="aquaButton"><div class="glare"></div>Clear Variables</button>
				&nbsp;
				<button onclick="Xsp.clearNow('errors');" class="aquaButton"><div class="glare"></div>Clear Errors</button>
				&nbsp;
				<button onclick="Xsp.clearNow('all');" class="aquaButton"><div class="glare"></div>Clear All</button>
				&nbsp;
			</div>
			<div class="brl"></div>
			<div class="brl"></div>
			<div id="msgText">
				Command:
			</div>
			<form action="javascript:Xsp.xspSystem();" method="post">
				 &nbsp; &nbsp; <input type="text" size="100" name="cin" placeholder="Enter command" id="cin" />
				 &nbsp; &nbsp; 
				<button name="submit" class="emeraldButton" id="run"><div class="glare"></div>Run Query</button>
				&nbsp; &nbsp;
				<button onclick="Xsp.clearNow('cin');" class="emeraldButton"><div class="glare"></div>Clear Field</button>
			</form>
			<div class="brl"></div>
			<div class="title bottomGrad">&nbsp;</div>
		</div>
	</body>
</html>