<?php
//Lists all error names and their descriptions.

 require "errors.php";
 $xspError = new Errors();
 $error = $xspError->err;
 $i = 0;
 foreach($error as $e){
	$errorStr = $xspError->errgetstr($e);
	echo $i.".) <strong>".$e."</strong>: ".$errorStr."<br/>";
	$i++;
 }
?>