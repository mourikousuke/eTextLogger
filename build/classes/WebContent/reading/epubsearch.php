<?php
	header("Content-Type: text/html; charset=UTF-8");
	$dir = "../books";
	
	$keyword = $_GET["keyword"];
	$filelist = getFileList($dir);
	
	foreach($filelist as $val){
		$name = stringcut($val,"books/",".");
		$namelist[] = $name;
		if(strpos($name, $keyword) === false){
			// any process
		}else{
			$resultlist[] = $name;
		}
	}
	unset($val);
	
	//return file list
	function getFileList($dir) {
    	$files = scandir($dir);
    	$files = array_filter($files, function ($file) {
        	return !in_array($file, array('.', '..'));
    	});
    	$list = array();
    	foreach ($files as $file) {
        	$fullpath = rtrim($dir, '/') . '/' . $file;
        	if (is_file($fullpath)) {
            	$list[] = $fullpath;
        	}
        	/*
        	if (is_dir($fullpath)) {
            	$list = array_merge($list, getFileList($fullpath));
       		}
       		*/
    	}
    	return $list;
	}
	
	//return a string between $a and $b($a is a string and $b is a character)
	function stringcut($str,$a,$b){
        
        $ap=strpos($str,$a);
        for($i=0;$i<strlen($str)-$ap-1;$i++){
            if($str[$ap+strlen($a)+$i]!=$b)
                $result=$result.$str[$ap+strlen($a)+$i];
            else
                break;
        }
        return $result;
    }
?>