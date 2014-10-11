<?php


class DocParserStorage{
	var $remember = array("group","version","created","file");
	var $tmp = array();
	var $insertid = 1;
	var $collection = array();
	function addItem($name,$value){
		$name = trim($name);
		$value = trim($value);
		if($name == 'input_var' || $name == 'get_params'){
			if(preg_match("/^(\w+) (.*?)/", $value,$regx)){
				if(!isset($this->tmp[$name]))
					$this->tmp[$name] = array();
				$this->tmp[$name][$regx[1]] = $value;
				return;
			}
		}

		$this->tmp[$name] = $value;
	}

	function nextItem(){
		$this->storeTmp();
		$this->clearTmp();
	}

	function lastItem(){
		$this->storeTmp();
		$this->tmp = array();
	}
	function storeTmp(){
		$count = 0;
		foreach ($this->tmp as $key => $value)
			if(!in_array($key, $this->remember))
				$count++;
		
		$this->tmp['_id'] = $this->insertid++;
		if($count > 0){
			$this->collection[] = $this->tmp;
		}
	}

	function clearTmp(){
		$newTmp = array();
		foreach ($this->remember as $key) {
			if(isset($this->tmp[$key])){
				$newTmp[$key] = $this->tmp[$key];
			}
		}
		$this->tmp = $newTmp;
	}
	function getCollection(){
		return $this->collection;

	}
	function getPer($type='group',$alt='no-group'){
		$out = array();
		foreach ($this->collection as $key => $value) {
			$group = !empty($value[$type]) ? $value[$type] : $alt;
			if(!isset($out[$group]))
				$out[$group] = array();

			$out[$group][] = $value;
			# code...
		}
		ksort($out);
		return $out;

	}

}