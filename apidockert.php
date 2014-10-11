<?php

    include("src/DocParser.php");
    $files = array();

    $files = array_merge($files,glob("examples/*.php"));
    $dock = new DocParser();
    foreach ($files as $key => $value) {
        $data = file_get_contents($value);
        preg_match_all("/\\/\\*\\*(.*?)\\*?\\*(\\/)/is", $data, $matches,PREG_SET_ORDER);
        $dock->nextFile($value);
        foreach ($matches as $key => $doc) {
            $dock->nextDoc($doc);
        }
    }

    include("src/DocShowApi.php");
    $a = new DocShowApi($dock->getStorage());
    echo $a->render();



