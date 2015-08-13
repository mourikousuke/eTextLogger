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

    <body>
    <h1>ePub Uploader</h1>
        <div id="main">
          <div id="wrapper">
          	<p>Only the compressed epubs (any .epub file) can be accepted.<br></p>
          	<form action="epub_unzipper.php" method="post" enctype="multipart/form-data">
            	<input type="file" name="epub" size="30" />
                <input type="submit" value="upload" />
            </form>
          </div>
        </div>
    </body>
</html>
