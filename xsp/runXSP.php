<?php
require "xsp.php";
$xsp = new XSP();
if(!isset($_POST["m"])){
	$coms = explode(";",$_POST["q"]);
	$lines = 0;
	foreach($coms as $cmd){
		if($cmd == "") continue;
		if(!isset($_POST["n"])){
			$syntaxCmd = syntaxHighlight($cmd);
			echo "<div style='height: 15px;'></div>
				<div style='color: #444;'>&raquo; ".$syntaxCmd."</div>
				<div style='height: 10px;'></div>";
		}
		$lines++;
		$xsp->Parse($cmd);
	}
}

function syntaxHighlight($str){
	$strx = $str;
	$strx = preg_replace("/(help|\?|create|delete|fcheck|fc|comment|select|return|fetch|with|ret|sel|get|attrset|print|rename|rn|dir|directory|var|variable|append|drop|update|move|copy|if|parse|run|crun|cparse|call|goto|change|file|for|loop|class|increase|decrease|increment|decrement|mod|compile|decompile|include)/i", '<span style="color: #f00;">$1</span>', $strx);
	$strx = preg_replace("/ (element|from|attr|attribute|text|nodevalue|by|def|define|remove|rm|add|mk|make|mkdir|del|set|empty|clr|lock|unlock|read|makedirectory|from|multi|thru|through|until|unt) /i", ' <span style="color: #00f;">$1</span> ', $strx);
	$strx = preg_replace("/ (in|where) /i", ' <span style="color: #d0f;">$1</span> ', $strx);
	$strx = str_replace("this", '<span style="color: #a0e;">this</span>', $strx);
	$strx = preg_replace("/ (new|then|else|do) /i", ' <span style="color: #0d0;">$1</span> ', $strx);
	$strx = preg_replace("/ (member|to|by|of) /i", ' <span style="color: #0fd;">$1</span> ', $strx);
	$strx = preg_replace("/ (clear|out|root|exists|say) /i", ' <span style="color: #c00; font-weight: bold;">$1</span> ', $strx);
	$strx = preg_replace("/(stop|end|break|exit)/i", '<span style="color: #000; font-weight: bold;">$1</span>', $strx);
	$strx = preg_replace("/(\.|\/\/)/", '<span style="color: #00f;">$1</span>', $strx);
	$strx = str_replace("@", '<span style="color: #0b0;">@</span>', $strx);
	$strx = str_replace(" = ", ' <span style="color: #c00;">=</span> ', $strx);
	return $strx;
}
?>