<?php

    include("src/DocParser.php");
    $files = array();
    if(empty($argv) || count($argv) < 2){
        $files = glob(__DIR__."/examples/*.php");
    }else{
        // cli
        $own = array_shift($argv);
        $files = array_merge($files,$argv);
        
    }
    $dock = new DocParser();
    foreach ($files as $key => $value) {
        $data = file_get_contents($value);
        preg_match_all("/\\/\\*\\*(.*?)\\*?\\*(\\/)/is", $data, $matches,PREG_SET_ORDER);
        $dock->nextFile($value);
        foreach ($matches as $key => $doc) {
            $dock->nextDoc($doc,array("file"=>$value));
        }
    }

    include("src/DocShowApi.php");
    $a = new DocShowApi($dock->getStorage());
    echo $a->render();



