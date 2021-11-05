<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../../");
}

$msg = '';

if (isset($_POST["addstatus"]) && ($_POST["addstatus"] !== "") && $_SESSION["in"] === 1) {

		if (!file_exists('../../p/')) {
			mkdir('../../p/', 0777, true);
		}

        $content = $_POST["addstatus"];
        $verbatimContent = $_POST["addstatus"];
        $folder = strtotime("now");

        if (isset($_POST["allowmarkdown"])) {
            include("../Parsedown.php");
            $Parsedown = new Parsedown();
            $content = $Parsedown->text($content);
        }


        mkdir("../../p/".$folder);


        if (!file_exists('../indexes/')) {
			mkdir('../indexes/', 0777, true);
        }
        file_put_contents("../indexes/status",$folder."|",FILE_APPEND);
        file_put_contents("../indexes/global",$folder."|",FILE_APPEND);

		$fileArray = array('type' => "status", 'content' => $content, 'timestamp' => $folder, "love" => 0, 'comments' => 0, "comments_array" => []);
        file_put_contents("../../p/".$folder."/meta.json", json_encode($fileArray));
        file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);

        copy("status_page.php", "../../p/".$folder."/index.php");

		header("Location: ../../");
}

if (isset($_POST["editstatus"]) && isset($_GET["edit"]) && ($_POST["editstatus"] !== "") && $_SESSION["in"] === 1) {


        $content = $_POST["editstatus"];
        $verbatimContent = $_POST["editstatus"];
        $folder = $_GET["edit"];

        if (isset($_POST["allowmarkdown2"])) {
            include("../Parsedown.php");
            $Parsedown = new Parsedown();
            $content = $Parsedown->text($content);
        }

        $oldFile = json_decode(file_get_contents("../../p/".$folder."/meta.json"), true);
        $newFile = $oldFile;
        $newFile["content"] = $content;
        file_put_contents("../../p/".$folder."/meta.json", json_encode($newFile));
        file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);
		header("Location: ../../");
}

$editContent = "";
if (isset($_GET["edit"])) {
    $editContent = file_get_contents("../../p/".$_GET['edit']."/verbatim");
}
?>
<!doctype html>
<html lang="cs">
<head>
    <style>html{background: #f3ceb2}body{visibility:hidden}/*FOUC*/</style>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="../neon.css?c=alois">
    <link rel="icon" type="image/png" href="../i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
</head>
	    <body>
		<main>
        <header>
        <div>
            <h2><a href="../../"><?=$siteName;?></a></h2>
            <p><?=$siteDescription;?></p>
            <a href="../../"><?=$loc_single_discardBackToFeed?></a>
        </div>
    </header>

         <?php if (isset($_GET["edit"])) { ?>

        <form action="status.php?edit=<?=$_GET['edit']?>" method="post" class="add">
        <label for="editstatus"><?=$loc_addPage_status_label_edit?></label>
        <textarea id="editstatus" name="editstatus" autofocus><?=$editContent?></textarea>
        <input type="checkbox" id="allowmarkdown2" name="allowmarkdown2">
        <label for="allowmarkdown2"><?=$loc_addPage_status_allowMarkdown?><span class="help" title="<?=$loc_addPage_help?>"></span></label>
        <input type="submit" name="submit" value="<?=$loc_addPage_edit?>">
    	<p><?=$msg;?></p>
        </form>


        <?php } else { ?>

        <form action="status.php" method="post" class="add">
        <label for="addstatus"><?=$loc_addPage_status_label?></label>
        <textarea id="addstatus" name="addstatus" autofocus></textarea>
        <input type="checkbox" id="allowmarkdown" name="allowmarkdown">
        <label for="allowmarkdown"><?=$loc_addPage_status_allowMarkdown?><span class="help" title="<?=$loc_addPage_help?>"></span></label>
        <input type="submit" name="submit" value="<?=$loc_addPage_publish?>">
    	<p><?=$msg;?></p>
        </form>

        <?php } ?>
        </section>
        </main>
    </body>
</html>



