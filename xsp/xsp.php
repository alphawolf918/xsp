<?php
 //error_reporting(0); //Comment out if you wish to see all notices, errors, warnings and strict messages - currently set to only show errors.
 require 'errors.php';
 /* 
 ************************
 	 Name:	    Extensive Server Path (XSP) v2.0 (an extension of PHP and XPath)
     Copyright: (C) 2012 - Present (2017)
     Programmer: Paul T. Shannon Jr. / Zollern Wolf
 
     This program is free software: you can redistribute it and/or modify
     it under the terms of the GNU General Public License as published by
     the Free Software Foundation, either version 3 of the License, or
     (at your option) any later version.
 
     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.
 
     You should have received a copy of the GNU General Public License
     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 
 Beta Version
 Configuration settings in xsp.ini
 For questions or comments, e-mail admin@zollernverse.org
 
 ** Please do not claim this as your own, as I have screenshots and the exact date when I started the project. I will file charges if credit is taken for my work. This license may not change, and this copyright cannot be removed. If either of these are disputed, then this copy is no longer a legal one.

 ** IF YOU MAKE ANY CHANGES TO THE CODE AND WISH TO UPLOAD IT SEPARATELY, then please reference my original project via hyperlink. Do not claim yours as the "official" version, as that is what mine is. If I happen to see a change I wish to implement into the final version, then I will attempt to contact you to discuss it, so please leave some way for me to contact you as well. This is for your benefit, as I believe in teamwork very heavily. I do not, however, believe in theft. Thank you.
 *************************
 */
 
 @include 'methods.php';

 class XSP {
 	public $itemNum = 0;
	public $version = "2.8";
 
	//Parser function, interprets the passed code.
 	public function Parse($str){
		if($str != ""){
		//	echo " CMND: ".$str."<br /><br/>";
		}
 		$toggle = 1;
 		$breakDown = explode(" ",strtolower($str));
 		$ln = (count($breakDown)-1);
		
		require 'modules.php';
		$this->setPredefinedVars();
				
 		switch(strtolower($breakDown[0])){
 			default:
 				$this->xspError(0);
 			break;
 			case 'create':
 				$m = "";
				$fileName = $breakDown[1];
 				if($breakDown[2] != "with") $this->xspError(3);
 				if(!preg_match("/root\((.*)\)/i",$breakDown[3],$m)){
 					$this->xspError(4);
 				}else{
 					$f = fopen($fileName,"w+") OR $this->xspError(2);
 					fwrite($f,"<"."?xml version=\"".$this->getXMLVersion()."\" encoding=\"".$this->getXMLEncoding()."\" ?".">\r\n") OR $this->xspError(41);
 					fwrite($f,"<".$m[1].">\r\n</".$m[1].">") OR $this->xspError(41);
 				}
 			break;
 			case 'delete':
 			case 'del':
				$R = explode(",",$breakDown[1]);
				foreach($R as $X){
					$t = str_replace(";","",$X);
					if(file_exists($t)){
						unlink($t);
					}else{
						$this->xspError(27);
					}
				}
 			break;
			case 'comment':
				switch($breakDown[1]){
					default:
						$this->xspError(15);
					break;
					case 'add':
						if(preg_match('/"(.*)"/',$str,$m)){
							$element = $breakDown[$ln-2];
							$dom = $this->xmlLoad($breakDown[$ln]);
							$selectedItem = $this->XQuery($dom,$element);
							$this->DOMInstance($selectedItem);
							$selectedItem->appendChild($dom->createComment($m[1]));
							$this->xmlSave($dom,$breakDown[$ln]);
						}
					break;
				}
			break;
 			case 'select':
			case 'sel':
			case 'get':
				$srch = explode(",",$breakDown[1]);
				$h=0;
				foreach($srch as $key){
					$h++;
					$dom = $this->xmlLoad($breakDown[3]);
					$selectedItem = $this->XQuery($dom,$key);
					$this->DOMInstance($selectedItem);
					if($breakDown[2] != "from"){
						$this->xspError(5);
					}
					if($breakDown[4] == "where"){
						if($breakDown[5] != ""){
							if(preg_match("/where (.*) (=|:) \"(.*)\"/i",$str,$m)){
								$valueToCheck = $m[3];
								$this->XSearchQuery($dom,$breakDown[1],$breakDown[5],$this->itemNum,$m[3]);
							}else{
								$this->xspError(6);
							}
						}else{
							$this->xspError(6);
						}
					}else{
						echo $selectedItem->nodeValue;
						if($h < count($srch)){
							echo ",";
						}
						echo " ";
					}
				}
 			break;
			case 'db':
			case 'database':
				$subCommand = $breakDown[1];
				switch($subCommand){
					default:
						$this->xspError(15);
					break;
					case 'create':
						$subCommand2 = $breakDown[2];
						switch($subCommand2){
							default:
								$this->xspError(15);
							break;
							case 'db':
							case 'database':
								if($breakDown[3] == "" OR $breakDown[3] == null){
									$this->xspError(9);
								}
								$this->Parse('fc '.$breakDown[3].".xml with root(database)");
								$this->Parse('attrset @name="'.$breakDown[3].'" to /database in '.$breakDown[3].'.xml');
							break;
							case 'table':
								if($breakDown[3] == "" OR $breakDown[3] == null){
									$this->xspError(9);
								}
								$info = explode(".", $breakDown[3]);
								$database = $info[0];
								$table = $info[1];
								$this->Parse('append element table to /database in '.$database.'.xml');
								$this->Parse('attrset @name="'.$table.'" to //table[last()] in '.$database.'.xml');
							break;
						}
					break;
					case 'add':
						$subCommand2 = $breakDown[2];
						switch($subCommand2){
							default:
								$this->xspError(15);
							break;
							case 'field':
								if($breakDown[3] == "" OR $breakDown[3] == null){
									$this->xspError(9);
								}
									$info = explode(".", $breakDown[5]);
									$database = $info[0];
									$table = $info[1];
									$this->Parse('append element row to //table[@name="'.$table.'"] in '.$database.'.xml');
									$this->Parse('append element field to //table[@name="'.$table.'"]/row[last()] in '.$database.'.xml');
									$this->Parse('attrset @name="'.$breakDown[3].'" to //field[last()] in '.$database.'.xml');
							break;
							case 'row':
								if($breakDown[3] == "" OR $breakDown[3] == null){
									$this->xspError(9);
								}
								$info = explode(".", $breakDown[5]);
								$database = $info[0];
								$table = $info[1];
								$fields = explode(",", $breakDown[3]);
								foreach($fields as $field){
									$val = explode("=", $field);
									$f = $val[0]; //Field
									$v = $val[1]; //Value
									$this->Parse('db add field '.$f.' to '.$database.'.'.$table);
									$getTable = $this->Parse('return //field[@name="'.$f.'"]/parent::*/parent::*/@name from '.$database.'.xml');
									$tableName = $getTable["fieldname".$f."_parent*_parent*_name"];
									if($tableName == $table){
										$this->Parse("addn text '".$v."' to //table[@name=\"".$table."\"]/row/field[@name=\"".$f."\"][last()] in ".$database.'.xml');
									}
								}
							break;
						}
					break;
					case 'select':
						//db select username from xdata.users where id = 1
						if($breakDown[2] == "" OR $breakDown[2] == null){
							$this->xspError(9);
						}
						$info = explode(".", $breakDown[4]);
						$database = $info[0];
						$table = $info[1];
						$xspQuery = 'select //table[@name="'.$table.'"]/row/field[@name="'.$breakDown[2].'"] from '.$database.'.xml';
						if($breakDown[5] == "where"){
							$xspQuery .= ' where //table[@name="'.$table.'"]/row/field[@name="'.$breakDown[6].'"] = "'.$breakDown[8].'"';
						}
						$this->Parse($xspQuery);
					break;
				}
			break;
			case 'fetch':
				$srch = explode(",", $breakDown[1]);
				$h = 0;
				foreach($srch as $key){
					$h++;
					$dom = $this->xmlLoad($breakDown[3]);
					$key = str_replace(".", "/", $key);
					$key = "//".$key;
					$selectedItem = $this->XQuery($dom, $key);
					$this->DOMInstance($selectedItem);
					if($breakDown[4] == "where"){
						if($breakDown[5] != ""){
							if(preg_match("/where (.*?) (=|:) \"(.*?)\"/i", $str, $m)){
								$conditionToCheck = $m[1];
								$valueToCheck = $m[3];
								$conditionToCheck = "//".$conditionToCheck;
								$conditionToCheck = str_replace(".", "/", $conditionToCheck);
								$conditionToCheck = str_replace("this", "", $conditionToCheck);
								$this->XSearchQuery($dom, $key, $conditionToCheck, $this->itemNum, $valueToCheck);
							}
						}
					}else{
						echo $selectedItem->nodeValue;
						if($h < count($srch)){
							echo ",";
						}
						echo " ";
					}
				}
			break;
			case 'with':
				if($breakDown[2] == "select" OR $breakDown[2] == "fetch" OR $breakDown[2] == "return"){
					$toSearch = str_replace(".", "/", $breakDown[3]);
					$srch = explode(",", $toSearch);
					$h = 0;
					foreach($srch as $key){
						$h++;
						$dom = $this->xmlLoad($breakDown[5]);
						$preKey = str_replace(".", "/", $breakDown[1]);
						$key = "//".$preKey."/".$key;
						$selectedItem = $this->XQuery($dom, $key);
						$this->DOMInstance($selectedItem);
						if($breakDown[2] != "return"){
							echo $selectedItem->nodeValue;
							if($h < count($srch)){
								echo ", ";
							}
							echo " ";
						}else{
							return $selectedItem->nodeValue;
						}
					}
				}
			break;
 			case 'return':
			case 'ret':
				$srch = explode(",",$breakDown[1]);
				$results = array();
				foreach($srch as $key=>$val){
					$dom = $this->xmlLoad($breakDown[3]);
					$selectedItem = $this->XQuery($dom,$val);
					$this->DOMInstance($selectedItem);
					if($breakDown[2] != "from"){
						$this->xspError(5);
					}
					$keyname = $this->xspFormatKey($val);
					if($breakDown[4] == "where"){
						if($breakDown[5] != ""){
							if(preg_match("/where (.*) (=|:) \"(.*)\"/",$str,$m)){
								$val = $this->XSearchQuery($dom,$val,$breakDown[5],$this->itemNum,$m[3],"return");
								if($i > 1){
									$result[$keyname] = $val;
								}else{
									$result = $val;
								}
							}else{
								$this->xspError(6);
							}
						}else{
							$this->xspError(6);
						}
					}else{
						$result[$keyname] = $selectedItem->nodeValue;
					}
				}
				$result = ($val != NULL) ? $result : "";
				return $result;
			break;
 			case 'set':
				if($breakDown[1] == 'attr' OR $breakDown[1] == 'attribute'){
 					$dom = $this->xmlLoad($breakDown[$ln]);
 					$XQuery = $this->XQuery($dom,$breakDown[$ln-2]);
 					$this->DOMInstance($XQuery);
 					if(preg_match("/@(.*)=\"(.*)\"/",$str,$m)){
 						$XQuery->setAttribute($m[1],$m[2]);
 					}
 					$this->xmlSave($dom,$breakDown[$ln]);
 				}else{
					$this->xspError(10);
				}
 			break;
			case 'for':
			case 'loop':
				$loopType = $breakDown[1];
				if($loopType == "until"){
					$cond = $breakDown[2];
					$x = explode(",",$cond);
					$xUntil = $x[1];
					$xu = explode("=",$xUntil);
					$xEnd = $x[0];
					$xe = explode("=",$xEnd);
					$act = (preg_match("/\{(.*)\}/i",$str,$m)) ? $m[1] : "say Error";
					if($breakDown[3] != "do"){
						$this->xspError(9);
					}
					while($xe[1] < $xu[1]){
						$i = $xe[1];
						$mr = preg_replace("/\\$".$xe[0]."/",$i,$m[1]);
						$this->Parse($mr);
						$xe[1]++;
					}
				}else if($loopType == 'through' OR $loopType == 'thru' OR $loopType == 'in'){
					$file = $breakDown[2];
					$f = str_replace("#!xsp;","",file_get_contents($file));
					$x = explode("\n",$f);
					foreach($x as $Line){
						if($Line == " ") continue;
						$Line = str_replace(";","",$Line);
						$this->Parse($Line);
					}
				}else{
					$this->xspError(15);
				}
			break;
			case 'call':
			case 'goto':
				$files = explode(",",$breakDown[1]);
				foreach($files as $F){
					if(!file_exists($F.".xsp")){
						$this->xspError(27);
					}
					$this->Parse("parse ".$F.".xsp");
				}
			break;
			case 'cparse':
			case 'crun':
			case 'cp':
				$file = $breakDown[1];
				$this->Parse("decompile ".$file);
				$this->Parse("parse ".$file);
				$this->Parse("compile ".$file);
			break;
			case 'attrset':
				$dom = $this->xmlLoad($breakDown[$ln]);
				if($breakDown[1] != "multi"){
					$R = explode(",",$breakDown[1]);
					foreach($R as $X){
						$T = explode("=",$X);
						$attr = $T[0];
						$val = $T[1];
						$Y = $breakDown[$ln-2];
						$Z = $breakDown[$ln];
						$parseAttr = "set attr ".$X." to ".$Y." in ".$Z;
						$this->Parse($parseAttr);
					}
				}else{
					$R = explode(",",$breakDown[2]);
					foreach($R as $X1){
						$E = explode(":",$X1);
						$xAttr = $E[0];
						$xFile = $E[2];
						$xPath = $E[1];
						$dom = $this->xmlLoad($xFile);
						$parseAttr = "set attr ".$xAttr." to ".$xPath." in ".$xFile;
						$this->Parse($parseAttr);
					}
				}
			break;
			case 'add':
 			case 'append':
 				switch($breakDown[1]){
 					case 'element':
					case 'tag':
						$newElem = $breakDown[2];
						$attr = explode("@",$newElem);
						$newElem = $attr[0];
						$oldElem = $breakDown[4];
						$filename = $breakDown[6];
 						$dom = $this->xmlLoad($filename);
 						$XQuery = $this->XQuery($dom,$oldElem);
 						$this->DOMInstance($XQuery);
 							$domElem = $XQuery->appendChild(
 									$dom->createElement($newElem)
 								);
 						$this->xmlSave($dom,$filename);
						foreach($attr as $a){
							if($a == $newElem) continue;
							$this->Parse("set attr @".$a." to //".$newElem."[last()] in ".$breakDown[6]);
						}
					break;
					case 'text':
					case 'string':
						$dom = $this->xmlLoad($breakDown[$ln]);
						$XQuery = $this->XQuery($dom,$breakDown[$ln-2]);
						$this->DOMInstance($XQuery);
						if(preg_match("/\"(.*)\"/",$str,$m)){
							if($XQuery->nodeValue != $m[1]){
								$XQuery->appendChild(
									$dom->createCDATASection($m[1])
								);
							}
						}
					$this->xmlSave($dom,$breakDown[$ln]);
					break;
 				}
 		break;
		case 'update':
			if($breakDown[2] == "set"){
				$R = explode(",",$breakDown[3]);
				$h = 0;
				foreach($R as $t5){
					$dom = $this->xmlLoad($breakDown[1]);
					$t5 = str_replace(":", "=", $t5);
					$v = explode("=",$t5);
					$val = $v[1];
					$el = $v[0];
					$finalVal = $val;
					$XQuery = $this->XQuery($dom,$el);
					$this->DOMInstance($XQuery);
					$fq[$h];
					$vq = "return ".$el." from ".$breakDown[1];
					$vc = $this->Parse($vq);
					$le = $this->xspFormatKey($el);
					if($vc[$le] == ""){
						$fq[$h] = 'add text '.$finalVal.' to '.$el.' in '.$breakDown[1];
					}else{
						$fq[$h] = 'change nodeValue of '.$el.' to '.$finalVal.' in '.$breakDown[1];
					}
					$this->Parse($fq[$h]);
					$h++;
				}
			}else if($breakDown[2] == "add"){
				$h = 0;
				$f = $breakDown[1];
				$R = explode(",",$breakDown[3]);
				$dom = $this->xmlLoad($f);
				$bl = $breakDown[$ln];
				foreach($R as $t5){
					$v = $t5;
					if(preg_match('/(.*)\[(.*)\]/',$v,$m)){
						$el = $m[1];
						$val = $m[2];
						$rt = "append element ".$this->xspFormatKey($el)." to ".$breakDown[5]." in ".$f;
						$this->Parse($rt);
						$val = str_replace("_"," ",$val);
						$tr = 'add text '.$val.' to '.$bl."/".$el.'[last()] in '.$f;
						$this->Parse($tr);
					}
					$h++;
				}
			}else{
				$this->xspError(9);
			}
		break;
			case 'addn':
				$dom = $this->xmlLoad($breakDown[$ln]);
				$XQuery = $this->XQuery($dom,$breakDown[$ln-2]);
				$this->DOMInstance($XQuery);
				if(preg_match("/'(.*)'/",$str,$m)){
					if($XQuery->nodeValue != $m[1]){
						$XQuery->appendChild(
							$dom->createCDATASection($m[1])
						);
					}
				}
				$this->xmlSave($dom,$breakDown[$ln]);
			break;
			case 'updaten':
			if($breakDown[2] == "set"){
				$R = explode(",",$breakDown[3]);
				$h = 0;
				foreach($R as $t5){
					$dom = $this->xmlLoad($breakDown[1]);
					$v = explode(":",$t5);
					$val = $v[1];
					$el = $v[0];
					$finalVal = $val;
					$XQuery = $this->XQuery($dom,$el);
					$this->DOMInstance($XQuery);
					$fq[$h];
					$vq = "return ".$el." from ".$breakDown[1];
					$vc = $this->Parse($vq);
					$le = $this->xspFormatKey($el);
					if($vc[$le] == ""){
						$fq[$h] = 'addn text \''.$finalVal.'\' to '.$el.' in '.$breakDown[1];
					}else{
						$fq[$h] = "changen nodeValue of ".$el." to '".$finalVal."' in ".$breakDown[1];
					}
					$this->Parse($fq[$h]);
					$h++;
				}
			}else if($breakDown[2] == "add"){
				$h = 0;
				$f = $breakDown[1];
				$R = explode(",",$breakDown[3]);
				$dom = $this->xmlLoad($f);
				$bl = $breakDown[$ln];
				foreach($R as $t5){
					$v = $t5;
					if(preg_match('/(.*)\[(.*)\]/',$v,$m)){
						$el = $m[1];
						$val = $m[2];
						$rt = "append element ".$this->xspFormatKey($el)." to ".$breakDown[5]." in ".$f;
						$this->Parse($rt);
						$val = str_replace("_"," ",$val);
						$tr = 'add text '.$val.' to '.$bl."/".$el.'[last()] in '.$f;
						$this->Parse($tr);
					}
					$h++;
				}
			}else{
				$this->xspError(9);
			}
		break;
 			case 'if':
 				if(preg_match("/exists\((.*)\)/i",$breakDown[1],$f)){
 					if(preg_match("/then/i",$breakDown[2])){
 						if(preg_match("/\{(.*)\}/",$str,$p)){
 							if(file_exists($f[1])){
 								$this->Parse($p[1]);
 							}else{
 								if(preg_match("/else \{(.*)\}/i",$str,$p)){
 									$this->Parse($p[1]);
 								}
 							}
 						}else{
 							$this->xspError(13);
 						}
 					}else{
 						$this->xspError(11);
 					}
 				}
 			break;
 			case 'parse':
			case 'include':
			case 'run':
 				if(preg_match("/(.*)\.xsp/i",$breakDown[1],$f)){
 					$this->xsp_file($f[0]);
 				}else{
 					$this->xspError(9);
 				}
 			break;
 			case 'clear()':
 			break;
			case 'clearvars()':
				$this->clearVars();
			break;
 			case 'clearerr()':
 				$this->clearErrors();
 			break;
 			case 'clearall()':
 				$this->clearErrors();
 				if($breakDown[1] == "-v"){
 					$this->clearVars();
 				}
 			break;
 			case 'clear':
			case 'clr':
 				switch($breakDown[1]){
 					default:
 						$this->xspError(15);
 					break;
 					case 'variables':
 					case 'vars':
 					case '-v':
 						$this->clearVars();
 					break;
 					case 'errors':
 					case 'err':
 					case '-e':
 						$this->clearErrors();
 					break;
 						$this->clearVars();
 					break;
 					case '-ev':
 						$this->clearErrors();
 						$this->clearVars();
 					break;
 					case '-ve':
 					case 'all':
 					case '-a':
 						$this->clearAll();
 					break;
 				}
 			break;
 			case 'say':
 			case 'out':
			case 'echo':
			case 'print':
				$xStr = str_replace("print ","",$str);
				$xStr = str_replace("echo ","",$str);
				$xStr = str_replace("out ","",$str);
				$xStr = str_replace("say ","",$str);
				$xStr = str_replace("\"","",$xStr);
				$xStr = str_replace("'","",$xStr);
				$R = explode("&",$str);
				foreach($R as $X){
					$X = str_replace("print ","",$X);
					$X = str_replace("echo ","",$X);
					$X = str_replace("out ","",$X);
					$X = str_replace("say","",$X);
					$X = str_replace("\"","",$X);
					$X = str_replace("\'","",$X);
					echo $X;
				}
 			break;
 			case 'sayr':
 			case 'outr':
			case 'echor':
			case 'getr':
				$xStr = str_replace("getr ","",$str);
				$xStr = str_replace("echor ","",$str);
				$xStr = str_replace("outr ","",$str);
				$xStr = str_replace("sayr ","",$str);
				$xStr = str_replace("\"","",$xStr);
				$xStr = str_replace("'","",$xStr);
				$R = explode("&",$str);
				foreach($R as $X){
					$X = str_replace("getr ","",$X);
					$X = str_replace("echor ","",$X);
					$X = str_replace("outr ","",$X);
					$X = str_replace("sayr","",$X);
					$X = str_replace("\"","",$X);
					$X = str_replace("\'","",$X);
					return $X;
				}
 			break;
 			case 'change':
 				$dom = $this->xmlLoad($breakDown[$ln]);
 				$XQuery = $this->XQuery($dom,$breakDown[3]);
				if(strtolower($breakDown[1]) == "nodevalue"){
					if(strtolower($breakDown[2]) == "of"){
						if(strtolower($XQuery instanceof DOMNode)){
							if($breakDown[4] == "to"){
								if($breakDown[$ln-1] == "in"){
									if(preg_match("/\"(.*)\"/i",$str,$m)){
										$this->ReplaceValue("/".$XQuery->nodeValue."/i",$m[1],$breakDown[$ln]);
									}else{
										$this->xspError(13);
									}
								}else{
									$this->xspError(22);
								}
							}else{
								$this->xspError(18);
							}
						}else{
							$this->xspError(34);
						}
					}else{
						$this->xspError(9);
					}
				}else{
					$this->xspError(9);
				}
 			break;
 			case 'changen':
 				$dom = $this->xmlLoad($breakDown[$ln]);
 				$XQuery = $this->XQuery($dom,$breakDown[3]);
				if(strtolower($breakDown[1]) == "nodevalue"){
					if(strtolower($breakDown[2]) == "of"){
						if(strtolower($XQuery instanceof DOMNode)){
							if($breakDown[4] == "to"){
								if($breakDown[$ln-1] == "in"){
									if(preg_match("/'(.*)'/i",$str,$m)){
										$this->ReplaceValue("/".$XQuery->nodeValue."/i",$m[1],$breakDown[$ln]);
									}else{
										$this->xspError(13);
									}
								}else{
									$this->xspError(22);
								}
							}else{
								$this->xspError(18);
							}
						}else{
							$this->xspError(34);
						}
					}else{
						$this->xspError(9);
					}
				}else{
					$this->xspError(9);
				}
 			break;
 			case 'drop':
 				$dom = $this->xmlLoad($breakDown[$ln]);
				$R = explode(",",$breakDown[1]);
				foreach($R as $X){
					$selectedItem = $this->XQuery($dom,$X);
					$this->DOMInstance($selectedItem);
					if($breakDown[2] != "from"){
						$this->xspError(5);
					}
 					$this->deleteNode($selectedItem);
 					$this->xmlSave($dom,$breakDown[$ln]);
				}
 			break;
			case 'xs':
				if($breakDown[1] == "" OR !file_exists($breakDown[1].".xs")){
					$this->xspError(30);
				}
				$fileName = $breakDown[1].".xs";
				$this->XScript($fileName);
			break;
 			case 'move':
 				$dom1 = $this->xmlLoad($breakDown[3]);
 				$selectedItem1 = $this->XQuery($dom1,$breakDown[1]);
 				$this->DOMInstance($selectedItem1);
 				if($breakDown[2] == "from"){
 					$data = $this->Parse("return ".$breakDown[1]." from ".$breakDown[3]);
 					$this->Parse("change nodeValue of ".$breakDown[1]." to empty() in ".$breakDown[3]);
 					if($breakDown[4] == "to"){
 						$dom2 = $this->xmlLoad($breakDown[$ln]);
 						$selectedItem2 = $this->XQuery($dom2,$breakDown[5]);
 						$this->DOMInstance($selectedItem2);
 						if($breakDown[6] == "in"){
 							$this->Parse("append text \"".$data."\" to ".$breakDown[5]." in ".$breakDown[$ln]);
 						}else{
 							$this->xspError(22);
 						}
 					}else{
 						$this->xspError(18);
 					}
 				}else{
 					$this->xspError(5);
 				}
 			break;
 			case 'copy':
 				$dom1 = $this->xmlLoad($breakDown[3]);
 				$selectedItem1 = $this->XQuery($dom1,$breakDown[1]);
 				$this->DOMInstance($selectedItem1);
 				if($breakDown[2] == "from"){
 					$data = $this->Parse("return ".$breakDown[1]." from ".$breakDown[3]);
 					if($breakDown[4] == "to"){
 						$dom2 = $this->xmlLoad($breakDown[$ln]);
 						$selectedItem2 = $this->XQuery($dom2,$breakDown[5]);
 						$this->DOMInstance($selectedItem2);
 						if($breakDown[6] == "in"){
 							$this->Parse("append text \"".$data."\" to ".$breakDown[5]." in ".$breakDown[$ln]);
 						}else{
 							$this->xspError(22);
 						}
 					}else{
 						$this->xspError(18);
 					}
 				}else{
 					$this->xspError(5);
 				}
 			break;
 			case 'dir':
 			case 'directory':
 				switch($breakDown[1]){
 					default:
 						$this->xspError(15);
 					break;
 					case 'mk':
 					case 'make':
					case 'mkdir':
					case 'makedirectory':
						$R = explode(",",$breakDown[2]);
						foreach($R as $x){
							mkdir($x,0700,true);
						}
 					break;
 					case 'rm':
 					case 'remove':
 					case 'del':
 					case 'delete':
 						if($breakDown[2] != ""){
 							if(!rmdir($breakDown[2])){
 								$this->xspError(24);
 							}
 						}else{
 							$this->xspError(23);
 						}
 					break;
 					case 'rn':
 					case 'rename':
						$R = explode(",",$breakDown[2]);
						foreach($R as $X){
							$dirs = explode("=",$X);
							$oldName = $dirs[0];
							$newName = $dirs[1];
							if(is_dir($oldName) AND $oldName != "" AND $newName != ""){
								rename($oldName,$newName);
							}else{
								$this->xspError(23);
							}
						}
 					break;
 					case 'list':
 					case 'ls':
 					if($breakDown[2] != ""){
 						$dir = $_ENV["DOCUMENT_ROOT"].$breakDown[2];
 						if(is_dir($dir)){
 							if($od = opendir($dir)){
 								while(($file = readdir($od)) !== false){
 									if($file == "." OR $file == ".."){ 
 										continue;
 									}
 									echo $file."<br />";
 								}
 								closedir($od);
 							}else{
 								$this->xspError(25);
 							}
 						}else{
 							$this->xspError(23);
 						}
 					}else{
 						$this->xspError(24);
 					}
 					break;
 					case 'empty':
 					case 'clear':
 						if($breakDown[2] != ""){
 							$dir = $_ENV["DOCUMENT_ROOT"].$breakDown[2];
 							if(is_dir($dir)){
 								if($od = opendir($dir)){
 									while(($file = readdir($od)) !== false){
 										if($file == "." OR $file == ".."){
 											continue;
 										}
 										$fileVar = $dir."/".$file;
 										if(file_exists($fileVar)){
 											unlink($fileVar);
 										}else{
 											$this->xspError(27);
 										}
 									}
 									closedir($dir);
 								}else{
 									$this->xspError(25);
 								}
 							}else{
 								$this->xspError(23);
 							}
 						}else{
 							$this->xspError(24);
 						}
 					break;
 					case 'chmod':
 						if($breakDown[2] != ""){
 							$dir = $_ENV["DOCUMENT_ROOT"].$breakDown[2];
 							if(is_dir($dir) OR file_exists($dir)){
 								if($breakDown[3] != ""){
 									chmod($breakDown[2],$breakDown[3]);
 								}else{
 									$this->xspError(28);
 								}
 							}else{
 								$this->xspError(23);
 							}
 						}else{
 							$this->xspError(24);
 						}
 					break;
 					case 'chown':
 						if($breakDown[2] != ""){
 							$dir = $_ENV["DOCUMENT_ROOT"].$breakDown[2];
 							if(is_dir($dir) OR file_exists($dir)){
 								if($breakDown[3] != ""){
 									chown($breakDown[2],$breakDown[3]);
 								}else{
 									$this->xspError(29);
 								}
 							}
 						}else{
 							$this->xspError(24);
 						}
 					break;
					case 'move':
					case 'mv':
						$x = explode(",",$breakDown[2]);
						foreach($x as $cmd){
							$r = explode(":",$cmd);
							$this->Parse("dir rename ".$r[0]."=".$r[1]."/".$r[0]);
						}
					break;
 				}
 			break;
 			case 'rename':
			case 'rn':
					$strFiles = $breakDown[1];
					$R = explode(",",$strFiles);
					foreach($R as $X){
						$fileSplit = explode("=",$X);
						$oldName = $fileSplit[0];
						$newName = $fileSplit[1];
						rename($oldName,$newName);
					}
 			break;
 			case 'variable':
 			case 'var':
 				$this->varCheck();
 				switch($breakDown[1]){
 					default:
 						$this->xspError(15);
 					break;
 					case 'set':
 					case 'define':
 					case 'def':
 						if($breakDown[3] == "="){
 							if($breakDown[2] != ""){
 								if($breakDown[4] != ""){
 									if(preg_match("/var(iable)? set (.*) = \"(.*)\"/i",$str,$m)){
										$this->varCheck();
 										if(preg_match("/<variable name=\"".$m[2]."\">/i",$this->LoadVars())){
											$this->Parse("var drop ".$m[2]);
											$this->Parse("var set ".$m[2]." = \"".$m[3]."\"");
 										}else{
 											$this->Parse("append element variable@name=\"".$m[2]."\" to /vars in bin/variables.xml");
 											$this->Parse("append text \"".$m[3]."\" to //variable[last()] in bin/variables.xml");
 										}
 									}else if (preg_match("/var(iable)? set (.*) = eval\((.*)\)/i",$str,$n)){
 											if(preg_match("/<variable name=\"".$n[2]."\">/i",$this->LoadVars())){
 												$repVar = $this->Parse($n[3]);
												$this->Parse("var drop ".$n[2]);
												$this->Parse("var set ".$n[2]." = \"".$repVar."\"");
 											}else{
 												$this->Parse("append element variable@name=\"".$n[2]."\" to /vars in bin/variables.xml");
 												$repVar = $this->Parse($n[3]);
 												$newVal = preg_replace("/eval\((.*)\)/",$repVar,$str);
 												$newVal = str_replace(",",";",$newVal);
 												$this->Parse("append text \"".$repVar."\" to //variable[last()] in bin/variables.xml");
 											}
 									}else{
 										$this->xspError(38);
 									}
 								}else{    
 									$this->xspError(31);
 								}
 							}else{
 								$this->xspError(32);
 							}
 						}else{
 							$this->xspError(33);
 						}
 					break;
 					case 'delete':
 					case 'drop':
 						if($breakDown[2] != ""){
 							$varData = $this->LoadVars();
 							if(preg_match("/<variable name=\"".$breakDown[2]."\">/i",$varData)){
 								$this->ReplaceValue("/<variable name=\"".$breakDown[2]."\">(.*)<\/variable>/i","","bin/variables.xml");
 							}else{
 								$this->xspError(42);
 							}
 						}else{
 							$this->xspError(32);
 						}
 					break;
 					case 'get':
 					case 'sel':
 					case 'select':
 						$varGet = $breakDown[2];
 						if($varGet != ""){
 							$this->Parse("select //variable[@name=\"".$varGet."\"] from bin/variables.xml");
 						}else{
 							$this->xspError(32);
 						}
 					break;
 					case 'return':
 						$varGet = $breakDown[2];
 						if($varGet != ""){
							$rv = "return //variable[@name=\"".$varGet."\"] from bin/variables.xml";
							$returnedVar = $this->Parse($rv);
 							return $returnedVar[$varGet];
 						}else{
 							$this->xspError(32);
 						}
 					break;
 					case 'clear':
 					case 'clr':
 						$this->clearVars();
 					break;
					case 'list':
					case 'ls':
						$dom = $this->xmlLoad("bin/variables.xml");
						$node = $dom->firstChild;
						$childNodes = $node->childNodes->length;
						$i = 1;
						echo "<strong>Variables:</strong>
						<div style='font-weight: normal !important;'>";
						while($i <= $childNodes){
							$varName = $this->Parse("return //variable[".$i."]/@name from bin/variables.xml");
							echo " &nbsp; &nbsp; <strong>".$varName["variable_name"]."</strong> = ".($this->Parse("var return ".$varName["variable_name"]))."<br />";
							$i++;
						}
						echo '</div>';
					break;
					case 'reset':
						if(preg_match("/var reset (.*) = \"(.*)\"/i",$str,$m)){
							$this->Parse("var drop ".$m[1]);
							$this->Parse("var set ".$m[1]." = \"".$m[2]."\"");
						}
					break;
 				}
 			break;
 			case 'compile':
 				if($breakDown[1] != ""){
 					$this->compileFile($breakDown[1]);
 				}else{
 					$this->xspError(27);
 				}
 			break;
			case 'fcheck':
			case 'fc':
				$f = $breakDown[1];
				$this->Parse("if exists(".$f.") then {delete ".$f."}");	
					if($breakDown[$ln-1] != "with"){
						$this->xspError(3);
					}
					if(!preg_match("/root\((.*)\)/",$breakDown[$ln],$m)){
						$this->xspError(4);
					}else{
						$this->Parse("create ".$f." with root(".$m[1].")");
					}
			break;
 			case 'file':
 				switch($breakDown[1]){
 					default:
 						$this->xspError(15);
 					break;
 					case 'read':
 						if($breakDown[2] == "from"){
 							if($breakDown[3] != ""){
 								return $this->readFromFile($breakDown[3]);
 							}else{
 								$this->xspError(27);
 							}
 						}else{
 							$this->xspError(5);
 						}
 					break;
 					case 'print':
 					case 'echo':
 						if($breakDown[2] == "from"){
 							if($breakDown[3] != "" AND file_exists($breakDown[3])){
 								$this->printFromFile($breakDown[3]);
 							}else{
 								$this->xspError(30);
 							}
 						}else{
 							$this->xspError(5);
 						}
 					break;
					case 'lock':
						if($breakDown[2] != "" AND file_exists($breakDown[3])){
							flock(($f = fopen($breakDown[2],"a+")),LOCK_EX);
						}else{
							$this->xspError(30);
						}
					break;
					case 'unlock':
						if($breakDown[2] != ""){
							flock(($f = fopen($breakDown[2],"a+")),LOCK_UN);
						}
					break;
 				}
 			break;
 			case 'decompile':
 				if($breakDown[1] != ""){
 					$this->decompileFile($breakDown[1]);
 				}else{
 					$this->xspError(27);
 				}
 			break;
			case 'stop':
			case 'break':
			case 'end':
			case 'exit':
				eval("exit();");
			break;
 			case 'help':
			case '?':
 				$this->Parse("file print from Documentation.txt");
 			break;
 			case '#':
 				//Allows comments..
 			case '':
 				//Ignores empty XSP commands..
 			break;
 		}
 		
 		if(preg_match("/;/",$str)){
 			$statements = explode("; ",$str);
 			$cx;
 			$i=0;
 			while($i<count($statements)){
 				$cx = $statements[$i];
				echo "Parsing: ".$cx."<br /><br />";
 				$this->Parse($cx);
 				$i++;
 			}
 		}
 	}
	
	public function xspFormatKey($val){
		$keyname = str_replace("//","",$val);
		$keyname = str_replace("/","_",$keyname);
		$keyname = str_replace("[","",$keyname);
		$keyname = str_replace("]","",$keyname);
		$keyname = str_replace("@","",$keyname);
		$keyname = str_replace("(","",$keyname);
		$keyname = str_replace(")","",$keyname);
		$keyname = str_replace(":","",$keyname);
		$keyname = str_replace('"',"",$keyname);
		$keyname = str_replace("'","",$keyname);
		$keyname = str_replace("=","",$keyname);
		$keyname = str_replace("variablename","",$keyname);
		$keyname = str_replace("variable_","",$keyname);
		$keyname = preg_replace("/(\d+?)/","",$keyname);
		return $keyname;
	}
 
 	public function xspError($errcode){
 		$xerr = new Errors();
 		$errname = $xerr->errgetname($errcode);
 		$message = $xerr->errgetstr($errname);
 		if($this->XMLErrorLoggingEnabled()){
 			if(!file_exists($this->XMLErrorLogName())){
 				$f = fopen($this->XMLErrorLogName(),"a+") OR exit("Could not create error log.");
 				fwrite($f,'<'.'?xml version="'.$this->getXMLVersion().'" encoding="'.$this->getXMLEncoding().'" ?'.'>
 					<logs>
 					</logs>');
 			}
 			$this->Parse("append element error@type=\"".$errname."\"@code=\"".$errcode."\"@timestamp=\"".time()."\" to //logs in ".$this->XMLErrorLogName());
 			$this->Parse("append text \"".$message."\" to //error[last()] in ".$this->XMLErrorLogName());
 		}
 		exit("<div style='color: #f00;'><strong>".$errname."Error:</strong> ".$message."</div>");
 	}
 
 	public function varCheck(){
		if(!is_dir("bin") OR !file_exists("bin/variables.xml")){
			mkdir("bin",0777);
			$this->Parse("create bin/variables.xml with root(vars)");
		}
 	}
 
 	public function DOMInstance($domInst){
 		if(!$domInst instanceof DOMNode)
 			$this->xspError(34);
 	}
 
 	public function ReplaceValue($oldPattern,$newPattern,$file){
 		$f = file_get_contents($file);
 		$x = preg_replace($oldPattern,$newPattern,$f);
 		file_put_contents($file,$x);
 	}
 	
 	public function LoadVars(){
 		return file_get_contents("bin/variables.xml");
 	}
 
 	public function XQuery($dom,$str,$in=0){
 		if($in >= $this->getXMLMaxSearch()){
 			$this->xspError(35);
 		}
 		$XPath = new DOMXPath($dom);
 		return ($XPath->query($str)->item($in));
 	}
 
 	public function XSearchQuery($dom,$xpath1,$xpath5,$in=0,$testVal,$mode="select"){
		/*echo $xpath1."<br />";
		echo $xpath5."<br />";
		echo $testVal."<br />";
		echo "select ".$xpath1." from file where ".$xpath5." = \"".$testVal."\"<br />";*/
		//exit;
 		$result = $this->XQuery($dom,$xpath5,$in);
 		$this->DOMInstance($result);
 		$fv = $result->nodeValue;
 		if($fv == $testVal){
 			$selectedItem2 = $this->XQuery($dom,$xpath1,$in);
 			if($mode == "select"){
 				echo $selectedItem2->nodeValue;
 			}else{
 				return $selectedItem2->nodeValue;
 			}
 			$this->itemNum = 0;
 			$in = 0;
 		}else{
 			$in++;
 			if($in < $this->getXMLMaxSearch()){
 				$this->XSearchQuery($dom,$xpath1,$xpath5,$in,$testVal);
 			}else{
 				$this->itemNum = 0;
 				$in = 0;
 				$this->xspError(35);
 			}
 		}
 	}
 
 	public function xmlLoad($file){
 		if($this->itemNum >= $this->getXMLMaxSearch()){
 			$this->xspError(35);
 		}
 		$dom = new DOMDocument();
 		$dom->formatOutput = true;
 		$dom->preserveWhiteSpace = false;
 		$f = str_replace(";","",$file);
 		$dom->load($f);
 		return $dom;
 	}
 
 	public function xmlSave($dom,$file){
 		$dom->normalizeDocument();
 		$dom->save($file);
 	}
 
 	public function xsp_file($f1,$file_type="xsp"){
 		$x = file_get_contents($f1);
 		$x = preg_replace("/\r|\t/","",$x);
		$x = str_replace(";","",$x);
 		$xp = explode("\n",$x);
 		$ind = 0;
 		$hex;
 		if($file_type == "xsp"){
 			foreach($xp as $command){
 				if($ind == 0){
 					if($command !== "#!xsp"){
 						$this->xspError(36);
 					} else if($command == "#!xsp-compiled"){
 						$hex = true;
 					}
 				}
 				$ind++;
 				if(substr($command, 0, 1) == "#" OR $command == "") continue;
				echo " &nbsp; &raquo; ".$command."<br />";
 				$this->Parse($command);
 			}
 		}else if($file_type == "xsp-module"){
 			foreach($xp as $command){
 				if($ind == 0){
 					if($command !== "#!xsp-module"){
 						$this->xspError(37);
 					}
 				}
 				$ind++;
 				if(substr($command, 0, 1) == "#") continue;
 				$this->Parse($command);
 			}
 		}
 	}
 	
 	public function compileFile($f1){
 		$x = file_get_contents($f1);
 		$xp = explode(";",$x);
 		$y;
 		foreach($xp as $command){
 			$y .= bin2hex($command);
 		}
 		file_put_contents($f1,$y);
 	}
 	
 	public function printFromFile($f1){
 		$f = fopen($f1,"r+");
 		while(($buffer = fgets($f)) !== false){
 			echo $buffer."<br />";
 		}
 		fclose($f);
 	}
 	
 	public function readFromFile($f1){
 		$lines = [];
 		$f = fopen($f1,"r+");
 		$i=0;
 		while(($buffer = fgets($f)) !== false){
 			$lines[$i] = $buffer."<br />";
 		}
 		fclose($f);
 		return $lines;
 	}
 	
 	public function decompileFile($f1){
 		$x = file_get_contents($f1);
 		$unhex = $this->hex2bin($x);
 		$unhex = str_replace("\r","\r",$unhex);
 		$unhex = str_replace(substr(0,0,$unhex),"",$unhex);
 		file_put_contents($f1,$unhex);
 	}
 	
 	/**
 	* Below function credited to chaos79 of php.net.
 	**/
 	public function hex2bin($h) {
 		if (!is_string($h)) return null;
 		$r='';
 		for ($a=0; $a<strlen($h); $a+=2) { 
 			$r.=chr(hexdec($h{$a}.$h{($a+1)}));
 		}
 	return $r;
 	}
 
 	/*
 	* Following two functions credited to 
 	* Justin Sheckler of php.net.
 	*/
 
 	public function deleteNode($node) {
 		$this->deleteChildren($node);
 		$parent = $node->parentNode;
 		$oldnode = $parent->removeChild($node);
 	}
 
 	public function deleteChildren($node) {
 		while (isset($node->firstChild)) {
 			$this->deleteChildren($node->firstChild);
 			$node->removeChild($node->firstChild);
 		}
 	}
	
	public function getPreferredMathRound($mathStatement){
		$iniFile = parse_ini_file("xsp.ini");
		$roundStyle = $iniFile["ceil_or_floor_opt"];
		switch($roundStyle){
			default:
				$this->xspError(43);
			break;
			case 'ceil':
				return ceil($mathStatement);
			break;
			case 'floor':
				return floor($mathStatement);
			break;
		}
	}
	
	public function getXSPDateFormat(){
		$iniFile = parse_ini_file("xsp.ini");
		return $iniFile["date_format"];
	}
 
 	public function getXMLEncoding(){
 		$iniFile = parse_ini_file("xsp.ini");
 		return $iniFile["xml_encoding"];
 	}
 
 	public function getXMLVersion(){
 		$iniFile = parse_ini_file("xsp.ini");
 		return $iniFile["xml_version"];
 	}
 
 	public function XMLErrorLoggingEnabled(){
 		$iniFile = parse_ini_file("xsp.ini");
 		return $iniFile["enable_logging"];
 	}
 
 	public function getXMLMaxSearch(){
 		$iniFile = parse_ini_file("xsp.ini");
 		return $iniFile["max_search"];
 	}
 
 	public function XMLErrorLogName(){
 		$iniFile = parse_ini_file("xsp.ini");
 		return $iniFile["log_name"];
 	}
 
 	public function clearErrors(){
 			/*$f = fopen($this->XMLErrorLogName(),"w+");
 			fwrite($f,'<'.'?xml version="'.$this->getXMLVersion().'" encoding="'.$this->getXMLEncoding().'"?'.'>
 				<logs></logs>');*/
			$this->Parse("fc ".($this->XMLErrorLogName())." with root(logs)");
 	}
 	
 	public function clearVars(){
		unlink("bin/variables.xml");
		rmdir("bin");
		$this->varCheck();
 	}
 	
 	public function clearAll(){
 		$this->clearVars();
 		$this->clearErrors();
 	}
	
	public function XScript($str){
		require 'xscript.php';
		return (new XScript)->Parse(file_get_contents($str));
	}
	public function setPredefinedVars(){
		$pcName = $_SERVER["COMPUTERNAME"];
		$pcOS = $_SERVER["OS"];
		$sysDrive = $_SERVER["SystemDrive"];
		$username = $_SERVER["USERNAME"];
		$userDomain = $_SERVER["USERDOMAIN"];
		$serverName = $_SERVER["SERVER_NAME"];
		$serverPort = $_SERVER["SERVER_PORT"];
		$serverAddr = $_SERVER["REMOTE_ADDR"];
		$f = fopen("bin/system_vars.txt","w");
$str = '[System Variables]

version='.$this->version.'
serverName='.$pcName.'
sysDrive='.$sysDrive.'
username='.$username.'
userDomain='.$userDomain.'
serverName='.$serverName.'
serverOS='.$pcOS.'
serverPort='.$serverPort.'
serverAddr='.$serverAddr;
		file_put_contents("bin/system_vars.txt",$str);
	}
 }
 ?>