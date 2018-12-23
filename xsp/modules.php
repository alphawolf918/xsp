<?php
		if(preg_match("/var\((.*)\)/i",$str,$m)){
			$l = "var return ".$m[1];
			$v = $this->Parse($l);
			$str = preg_replace("/var\(".$m[1]."\)/i","\"".$v."\"",$str);
		}
		
		if(preg_match("/%(.*)%/i",$str,$m)){
			$l = "var return ".$m[1];
			$v = $this->Parse($l);
			$str = preg_replace("/%(".$m[1].")%/i",'"'.$v.'"',$str);
		}
			
		if(preg_match("/valueOf\((.*),(.*)\)/i",$str,$m)){
			$v = $this->Parse("return ".$m[1]." from ".$m[2]);
			$str = preg_replace("/valueOf\(".$m[1].",".$m[2]."\)/i","\"".$v."\"",$str);
		}
		
		if(preg_match("/add\((\d*),(\d*)\)/i",$str,$m)){
			$x = str_replace("add(","",$str);
			$x = str_replace(")","",$x);
			$x = str_replace(",","",$x);
			$res = $m[1]+$m[2];
			$res = $this->getPreferredMathRound($res);
			$val = "\"".$res."\"";
			$str = preg_replace("/".$m[1].$m[2]."/i",$val,$x);
		}
		
		if(preg_match("/sum\((.*),(.*)\)/i",$str,$m)){
			$x = $m[1];
			$y = $m[2];
			$xVar = $this->Parse("var return ".$x);
			$yVar = $this->Parse("var return ".$y);
			$str = preg_replace("/sum\((".$x.",".$y."\)/i",($xVar+$yVar),$str);
		}
		
		if(preg_match("/diff\((.*),(.*)\)/i",$str,$m)){
			$x = $m[1];
			$y = $m[2];
			$xVar = $this->Parse("var return ".$x);
			$yVar = $this->Parse("var return ".$y);
			(int) $val = ($xVar-$yVar);
			$str = preg_replace("/diff\(".$x.",".$y."\)/i",$val,$str);
		}
		
		if(preg_match("/sub\((\d*),(\d*)\)/i",$str,$m)){
			$x = str_replace("sub(","",$str);
			$x = str_replace(")","",$x);
			$x = str_replace(",","",$x);
			$res = $m[1]-$m[2];
			$res = $this->getPreferredMathRound($res);
			$val = "\"".$res."\"";
			$str = preg_replace("/".$m[1].$m[2]."/i",$val,$x);
		}
		
		if(preg_match("/getIP()/i",$str,$m)){
			$res = '"'.$_SERVER["REMOTE_ADDR"].'"';
			$str = str_replace("getIP()",$res,$str);
		}
		
		if(preg_match("/date\(\)/i",$str)){
				$strDate = date($this->getXSPDateFormat());
				$str = str_replace("date()",'"'.$strDate.'"',$str);
		}
		
		if(preg_match("/ucfirst\((.*)\)/i",$str,$m)){
			$p = str_replace('"',"",$m[1]);
			$o = ucfirst($p);
			$str = preg_replace("/ucfirst\(".$m[1]."\)/i",'"'.$o.'"',$str);
		}
		
		if(preg_match("/ucwords\((.*)\)/i",$str,$m)){
			$p = str_replace('"',"",$m[1]);
			$o = ucwords($p);
			$str = preg_replace("/ucwords\(".$m[1]."\)/i",'"'.$o.'"',$str);
		}
		
		if(preg_match("/replace\((.*),(.*),(.*)\)/i",$str,$m)){
			$dom = $this->xmlLoad($m[3]);
			$selectedItem = $this->XQuery($dom,$m[1]);
			$this->DOMInstance($selectedItem);
			$this->Parse("change nodeValue of ".$m[1]." to ".$m[2]." in ".$m[3]);
			$val = $this->Parse("return ".$m[1]." from ".$m[3]);
			$xKey = $this->xspFormatKey($m[1]);
			$str = preg_replace("/replace\(".$m[1].",".$m[2].",".$m[3]."\)/i",'"'.$val[$xKey].'"',$str);
		}
		
		if(preg_match("/md5\((.*)\)/i",$str,$m)){
			$p = str_replace('"',"",$m[1]);
			$o = md5($p);
			$str = preg_replace("/md5\(".$m[1]."\)/i",'"'.$o.'"',$str);
		}
		
		if(preg_match("/strtoupper\((.*)\)/i",$str,$m)){
			$p = str_replace('"',"",$m[1]);
			$o = strtoupper($p);
			$str = preg_replace("/strtoupper\(".$m[1]."\)/i",'"'.$o.'"',$str);
		}
		
		if(preg_match("/strtolower\((.*)\)/i",$str,$m)){
			$p = str_replace('"',"",$m[1]);
			$o = strtolower($p);
			$str = preg_replace("/strtolower\(".$m[1]."\)/i",'"'.$o.'"',$str);
		}
		
		if(preg_match("/empty\(\)/i",$str)){
			$str = str_replace("empty()",'" "',$str);
		}
		
	/*	if(preg_match("/first\((.*?)\)/i",$str,$m)){
			$xml = simplexml_load_file($m[1]);
			$result = $xml->xpath("/node()");
			echo '<pre>';
				var_dump($result);
				echo $result[0];
			echo '</pre>';
			$str = preg_replace("/first\((.*)\)/i",$result,$str);
		}*/
?>