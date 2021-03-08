<?php
session_start();
include("../data.php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../../");
}

$msg = '';

if (isset($_POST["submit"]) && ($_POST["title"] !== "")) {
    
    // initialise the default blog folder
		if (!file_exists('../../p/')) {
			mkdir('../../p/', 0777, true);
		}		

        $title = $_POST["title"];
        $content = $_POST["article"];
        $folder = strtotime("now");

        include("../Parsedown.php");
		$Parsedown = new Parsedown();
        $content = $Parsedown->text($content);
        $perex = mb_strimwidth(strip_tags($content), 0, 180, "...");

		mkdir("../../p/".$folder);

		$file = fopen("../../p/".$folder."/meta.json","w");
		$fileArray = array('type' => "article", 'title' => $title, 'content' => $content, 'perex' => $perex, 'timestamp' => $folder);
		fwrite($file, json_encode($fileArray));
		fclose($file);

        copy("article_page.php", "../../p/".$folder."/index.php");

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
		<main class="grow">
        <header>
        <div>
            <h2><a href="../../"><?=$siteName;?></a></h2>
            <p><?=$siteDescription;?></p>
            <a href="../../">< Discard & Back to Feed</a>
        </div>
    </header>
        <form action="article.php" method="post" class="add">
        <label for="title">Article's title:</label>
        <input type="text" id="title" name="title">
        <label for="article">Article's content (Use Markdown for syntax)<span class="help" title="Markdown uses special characters to style the text, use it like this:&#10; _italic text_&#10;**bold text**&#10;[text of a link](URL of a link)&#10;>citation&#10;![image description](image URL)&#10;  (two spaces for the new line break)&#10;###small title&#10;"></span>:</label>
        <textarea id="article" name="article"></textarea>
        <input type="submit" name="submit" value="Publish">
	<p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



