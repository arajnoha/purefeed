<?php
session_start();
include("../data.php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../../");
}

$msg = '';

if (isset($_POST["addstatus"]) && ($_POST["addstatus"] !== "")) {
    
    // initialise the default blog folder
		if (!file_exists('../../p/')) {
			mkdir('../../p/', 0777, true);
		}		

        $content = $_POST["addstatus"];
        $folder = strtotime("now");

        if (isset($_POST["allowmarkdown"])) {
            include("../Parsedown.php");
            $Parsedown = new Parsedown();
            $content = $Parsedown->text($content);
        }
		

		mkdir("../../p/".$folder);

		$file = fopen("../../p/".$folder."/meta.json","w");
		$fileArray = array('type' => "status", 'content' => $content, 'timestamp' => $folder);
		fwrite($file, json_encode($fileArray));
		fclose($file);

        copy("status_page.php", "../../p/".$folder."/index.php");

		header("Location: ../../");
}
?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="../neon.css">
    <link rel="icon" type="image/png" href="../i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
    <style>:root{--dominant-color:<?=$siteColor;?>}</style>
</head>
	    <body>
		<main>
        <header>
        <div>
            <h2><a href="../../"><?=$siteName;?></a></h2>
            <p><?=$siteDescription;?></p>
            <a href="../../">< Discard & Back to Feed</a>
        </div>
    </header>
        <form action="status.php" method="post" class="add">
        <label for="addstatus">Your new text post:</label>
        <textarea id="addstatus" name="addstatus"></textarea>
        <input type="checkbox" id="allowmarkdown" name="allowmarkdown">
        <label for="allowmarkdown">Allow markdown syntax<span class="help" title="Markdown uses special characters to style the text, use it like this:&#10; _italic text_&#10;**bold text**&#10;[text of a link](URL of a link)&#10;>citation&#10;![image description](image URL)&#10;  (two spaces for the new line break)&#10;###small title&#10;"></span></label>
        <input type="submit" name="submit" value="Publish">
    	<p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



