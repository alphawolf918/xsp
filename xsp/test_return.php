<?php
require 'xsp.php';
$x = new XSP();
$rStr = $x->Parse("var return name");
echo $rStr;
?>