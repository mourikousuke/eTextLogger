<!DOCTYPE html>
<HTML>
<HEAD>
<TITLE>uploaded</TITLE>
<meta http-equiv="refresh" content="10;URL=../index.php">
</HEAD>
<BODY>

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


	// upload
	echo "uploading \" $uploadedFileName \" ...<br> ";
	if(extOut($uploadedFileName) != "epub"){
		echo "Uploading is canceled.<br>You didn't choose any file or the file you selected is not an epub file.<br>";
		//header("Location: ../index.php");
	}else{
		if (is_uploaded_file($uploadedTmpName)) {
			if(file_exists($epubUploadPath))
				echo "There is already a same name file.<br> The file is overwritten.<br>";
        	if (move_uploaded_file($uploadedTmpName, $epubUploadPath)) {
            	//chmod("" . $_FILES["epub"]["name"], 0777);
            	copy($epubUploadPath, $zipUploadPath);

            	echo "The file is uploaded correctly.<br>";

            	//unzip
    			if(file_exists("../books/".$_FILES["epub"]["name"]))
    				removeDir($unzipUploadPath);
    			mkdir($unzipUploadPath);
				unzip($zipUploadPath, $unzipUploadPath);

        	} else {
            	echo "The file could not be uploaded correctly.<br>";
        	}
   		} else {
        	echo "No file is selected.<br>";
    	}
    }

    echo "You will be redirected to the index page in 10 seconds.<br>";
	echo "<a href=\"../index.php\">You can back to the index page soon by clicking here.</a><br>";

?>

</BODY>
</HTML>