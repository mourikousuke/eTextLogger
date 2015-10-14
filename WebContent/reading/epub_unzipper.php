<!DOCTYPE html>
<HTML>
<HEAD>
<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>ePub Uploader</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
</HEAD>
<BODY>
<h1>ePub Uploader</h1>
<?php
	// path and file name
	$uploadedFileName = $_FILES["epub"]["name"];
	$uploadedTmpName = $_FILES["epub"]["tmp_name"];
	$epubUploadPath = "../books/epub/".$uploadedFileName;
	$zipUploadPath = str_replace(".epub",".zip","../books/zipped/".$uploadedFileName);
	$unzipUploadPath = "../books/unzipped/".str_replace(".epub","",$uploadedFileName);


	function extOut($str){
        return substr($str,strrpos($str,'.')+1);
    }

    function unzip( $zip_path, $dir_path ){
		$zip = new ZipArchive();
		if( $zip->open($zip_path) === true ){
			$zip->extractTo($dir_path);
			$zip->close();
		}else{
			throw new Exception('It does not open a zip file');
		}
	}

	function removeDir($path){
    	foreach(glob($path . "/*") as $file){
        	if(is_dir($file)){
            	removeDir($file);
        	}else{
           		unlink($file);
        	}
    	}
    	rmdir($path);
	}

	echo "---------------------------------------------------------<br>";
	// upload
	echo "uploading \" $uploadedFileName \" ...<br> ";
	if(extOut($uploadedFileName) != "epub"){
		echo "<font color=\"red\">Error. Check the following points :<br></font>";
		echo "[1] file size (up to 4MB)<br>[2] extension (must be .epub)<br> [3] Did you choose any file?<br>";
		//header("Location: ../index.php");
	}else{
		if (is_uploaded_file($uploadedTmpName)) {
			if(file_exists($epubUploadPath))
				echo "There is already a same name file.<br> The file is overwritten.<br>";
        	if (move_uploaded_file($uploadedTmpName, $epubUploadPath)) {
            	//chmod("" . $_FILES["epub"]["name"], 0777);
            	copy($epubUploadPath, $zipUploadPath);

            	echo "<font color=\"green\">File uploading is finished successfully.<br></font>";

            	//unzip
    			if(file_exists("../books/".$_FILES["epub"]["name"]))
    				removeDir($unzipUploadPath);
    			mkdir($unzipUploadPath);
				unzip($zipUploadPath, $unzipUploadPath);

        	} else {
            	echo "The file <font color=\"red\">could not</font> be uploaded correctly.<br>Check permissions.<br>";
        	}
   		} else {
        	echo "No file is selected.<br>";
    	}
    }

	echo "---------------------------------------------------------<br>";
	echo "<a href=\"../index.php\">[1] Click here to go back to index page.</a><br>";
	echo "<a href=\"uploader.php\">[2] Click here to upload another epub file.</a><br>";
	echo "<a href=\"checker.php\">[3] Click here to check an epub index.</a><br>";

?>

</BODY>
</HTML>