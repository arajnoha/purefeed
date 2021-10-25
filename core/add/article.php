<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");


if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../../");
}

$msg = '';

if (isset($_POST["submit"]) && ($_POST["title"] !== "") && $_SESSION["in"] === 1) {

    // initialise the default blog folder
		if (!file_exists('../../p/')) {
			mkdir('../../p/', 0777, true);
		}

        $title = $_POST["title"];
        $content = $_POST["article"];
        $verbatimContent = $_POST["article"];
        $folder = strtotime("now");

        include("../Parsedown.php");
		$Parsedown = new Parsedown();
        $content = $Parsedown->text($content);
        $perex = mb_strimwidth(strip_tags($content), 0, 180, "...");

        mkdir("../../p/".$folder);

        if (!file_exists('../indexes/')) {
			mkdir('../indexes/', 0777, true);
        }
        file_put_contents("../indexes/article",$folder."|",FILE_APPEND);
        file_put_contents("../indexes/global",$folder."|",FILE_APPEND);

		$fileArray = array('type' => "article", 'title' => $title, 'content' => $content, 'perex' => $perex, 'timestamp' => $folder, 'comments' => 0, "comments_array" => []);
        file_put_contents("../../p/".$folder."/meta.json", json_encode($fileArray));
        file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);
        copy("article_page.php", "../../p/".$folder."/index.php");

		header("Location: ../../");
}

if (isset($_POST["submit2"]) && ($_POST["title2"] !== "") && $_SESSION["in"] === 1) {

        $title = $_POST["title2"];
        $content = $_POST["article2"];
        $verbatimContent = $_POST["article2"];
        $folder = $_GET["edit"];

        include("../Parsedown.php");
		$Parsedown = new Parsedown();
        $content = $Parsedown->text($content);
        $perex = mb_strimwidth(strip_tags($content), 0, 180, "...");

        $oldFile = json_decode(file_get_contents("../../p/".$folder."/meta.json"), true);
        $newFile = $oldFile;
        $newFile["title"] = $title;
        $newFile["content"] = $content;
        $newFile["perex"] = $perex;
        file_put_contents("../../p/".$folder."/meta.json", json_encode($newFile));
        file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);
        copy("article_page.php", "../../p/".$folder."/index.php");

		header("Location: ../../");
}

$editTitle = "";
$editContent = "";
if (isset($_GET["edit"])) {
    $editTitle = json_decode(file_get_contents("../../p/".$_GET['edit']."/meta.json"), true)["title"];
    $editContent = file_get_contents("../../p/".$_GET['edit']."/verbatim");
}
?>
<!doctype html>
<html lang="cs">
<head>
    <style>html{background: #f3ceb2}body{visibility:hidden}/*FOUC*/</style>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="../neon.css">
    <link rel="icon" type="image/png" href="../i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
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

    <?php if (isset($_GET["edit"])) { ?>

        <form action="article.php?edit=<?=$_GET["edit"]?>" method="post" class="add">
        <label for="title2"><?=$loc_addPage_article_titleLabel?></label>
        <input type="text" id="title2" name="title2" value="<?=$editTitle?>">
        <label for="article2"><?=$loc_addPage_article_contentLabel?><span class="help" title="<?=$loc_addPage_help?>"></span>:</label>
        <textarea id="article2" name="article2"><?=$editContent?></textarea>
        <input type="submit" name="submit2" value="<?=$loc_addPage_edit?>">
	    <p><?=$msg;?></p>
        </form>

        <?php } else { ?>

        <form action="article.php" method="post" class="add">
        <label for="title"><?=$loc_addPage_article_titleLabel?></label>
        <input type="text" id="title" name="title">
        <label for="article"><?=$loc_addPage_article_contentLabel?><span class="help" title="<?=$loc_addPage_help?>"></span>:</label>
        <textarea id="article" name="article"></textarea>
        <input type="submit" name="submit" value="<?=$loc_addPage_publish?>">
	    <p><?=$msg;?></p>
        </form>

        <?php } ?>

        </section>
        </main>
    </body>
</html>



