<?php
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
?>