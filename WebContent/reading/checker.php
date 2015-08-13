<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ePub Viewer</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <meta name="apple-mobile-web-app-capable" content="yes">
    </head>

	<?php
		$dir = "../books/epub";

		$keyword = $_GET["keyword"];
		$filelist = getFileList($dir);

		echo "FILE LIST : <br>";
		$i=1;
		foreach($filelist as $val){
			$name = stringcut($val,$dir."/",".");
			echo "[$i] : $name<br>";
			$i++;
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


    <body>
    	<div id="main">
			<div id="wrapper">
        		<div id="area">
					<a href="../index.php"><br>Click here to back to index page.</a>
				</div>
			</div>
		</div>
	</body>
</html>
