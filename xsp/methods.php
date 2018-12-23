<?php
/*
* XSP Methods
*/

	function initXSP(){
		return (new XSP());
	}
	
	function xParse($xspObj,$str){
		return ($xspObj->Parse($str));
	}
?>