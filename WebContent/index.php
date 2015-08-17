<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ePub Viewer</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <meta name="apple-mobile-web-app-capable" content="yes">

        <style type="text/css">

          body {
            overflow: auto;
            background: #eee;
          }

          #wrapper {
            width: 480px;
            height: 640px;
            overflow: hidden;
            border: 1px solid #ccc;
            margin: 28px auto;
            background: #fff;
            border-radius: 0 5px 5px 0;
          }

          #area {
            width: 480px;
            height: 660px;
            margin: -30px auto;
            -moz-box-shadow:      inset 10px 0 20px rgba(0,0,0,.1);
            -webkit-box-shadow:   inset 10px 0 20px rgba(0,0,0,.1);
            box-shadow:           inset 10px 0 20px rgba(0,0,0,.1);
          }

          #area iframe {
            padding: 40px 40px;
          }

          body {
            font-size: 1em;
            line-height: 1.33em;
            font-family: serif;

          }
          h1 {
            text-align: center
            font-size: 1.5em;
            line-height: 1.33em;
            text-align: center;
            padding-bottom: 0em;
            text-align: center;
            text-transform: uppercase;
            font-weight: normal;
            letter-spacing: 4px;
            padding-top: 60px;
          }

          ol {
            width: 350px;
            margin: 50px auto;
            /*font-family: sans-serif;*/
          }

          a {
            font-size: 1.2em;
            line-height: 1.33em;
            color: #000;
          }
        </style>
    </head>

	<?php
		header("Content-Type: text/html; charset=UTF-8");
		$dir = "books/epub";

		$keyword = $_GET["keyword"];
		$filelist = getFileList($dir);

		foreach($filelist as $val){
			$name = stringcut($val,$dir."/",".");
			//$namelist[] = $name;
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


    <body>
    	<div id="main">
			<div id="wrapper">
        		<div id="area">
              		<h1>ePub Viewer</h1>
              		<form method="get" action="index.php">
              			<p align="center">
              				<input type="search" name="keyword" size="20" >
              				<input type="submit" value="Search">
              			</p>
              		</form>

					<?php
						$user_agent = $_SERVER['HTTP_USER_AGENT'];
						if (strstr($user_agent, 'Trident') || strstr($user_agent, 'MSIE')) {
							echo "<p align=\"center\">We are sorry that this system dose not support Internet Explorer.<br>File uploading function and file checking function are available.</p>";
						}else{
							if($keyword != ""){
								if(count($resultlist)){
									echo "<form style=\"text-align:center\" method=\"get\" action=\"reading/PDFbasedviewer.html\">";
									echo "<select name=\"book-selection\">";
									echo "<option>Select a Book</option>";
									foreach($resultlist as $title){ echo "<option value=\"$title\">".$title."</option>";}
									echo "</select>";
									echo "<input type=\"submit\" id=\"viewEPUB\" value=\"Read\"></form>";
								} else { echo "<p align=\"center\">we did not find results for : $keyword.</p>"; }
							} else { echo "<p align=\"center\">Enter a keyword and click \"Search\".</p>"; }
						}
					?>
					<p align="center">
						<a href="reading/uploader.php">click here to upload an epub file</a>
        	  		</p>
        	  		<p align="center">
						<a href="reading/checker.php">click here to check epub files</a>
        	  		</p>
				</div>
			</div>
		</div>
	</body>
</html>
