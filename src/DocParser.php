<?php

include("DocParserStorage.php");

class DocParser{
	var $storage = null;
	function __construct(){
		$this->storage = new DocParserStorage();
	}
	function nextFile($filename){
		$this->storage->addItem("file",$filename);
	}
	function nextDoc($doc_array,$extra_attr=array()){
		list($full,$doc,$code) = $doc_array;

		$attr = $this->getAttributes($doc);
		$attr = array_merge($extra_attr,$attr);
		foreach ($attr as $key => $value) {
			$this->storage->addItem($value[0],$value[1]);
		}
		// $this->storage->addItem('source_code',$code);
		$this->storage->nextItem();
	}

	function getAttributes($doc){
		preg_match_all('/\s*\*[ \t]*\@(\w+)\s*(?::|=|)\s*?(\S[^\n\r]+)/is', $doc, $lines_raw,PREG_SET_ORDER);
		$lines = array();
		foreach ( $lines_raw as $key => $value) {
			$lines[] = array($value[1], $value[2]);
		}
		return $lines;
	}

	function getStorage(){
		return $this->storage;
	}


}