<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");


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
            <a href="../../"><?=$loc_single_discardBackToFeed?></a>
        </div>
    </header>
        <form action="article.php" method="post" class="add">
        <label for="title"><?=$loc_addPage_article_titleLabel?></label>
        <input type="text" id="title" name="title">
        <label for="article"><?=$loc_addPage_article_contentLabel?><span class="help" title="<?=$loc_addPage_help?>"></span>:</label>
        <textarea id="article" name="article"></textarea>
        <input type="submit" name="submit" value="<?=$loc_addPage_publish?>">
	<p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



