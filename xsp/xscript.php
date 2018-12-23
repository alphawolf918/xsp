<?php
	class XScript {
		public $dict = array("create","append","delete","select","print","out");
		public function Parse($str){
			$bd = explode(" ",$str);
			if(!in_array($bd["0"],$this->dict)){
				exit("Undefined keyword, designation '".$bd["0"]."'. Program terminated.");
			}
			switch($bd["0"]){
				case 'print':
				case 'out':
					$str = str_replace($bd["0"],"",$str);
					if(preg_match('/"(.+?)"/',$str,$x)){
						echo $x[1];
					}
				break;
			}
		}
	}
?>