<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../../");
}

$msg = '';

if (isset($_POST["editdescription"]) && isset($_GET["edit"]) && $_SESSION["in"] === 1) {
	
        $content = $_POST["editdescription"];
        $verbatimContent = $_POST["editdescription"];
        $folder = $_GET["edit"];

        if (isset($_POST["allowmarkdown"])) {
            include("../Parsedown.php");
            $Parsedown = new Parsedown();
            $content = $Parsedown->text($content);
        }
		
        $oldFile = json_decode(file_get_contents("../../p/".$folder."/meta.json"), true);
        $newFile = $oldFile;
        $newFile["description"] = $content;      
        file_put_contents("../../p/".$folder."/meta.json", json_encode($newFile));
        file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);
		header("Location: ../../");
}

if (isset($_GET["edit"])) {
    $editContent = file_get_contents("../../p/".$_GET['edit']."/verbatim");
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

        <form action="edit_image_description.php?edit=<?=$_GET['edit']?>" method="post" class="add">
        <label for="editdescription"><?=$loc_addPage_status_label_edit?></label>
        <textarea id="editdescription" name="editdescription"><?=$editContent?></textarea>
        <input type="checkbox" id="allowmarkdown" name="allowmarkdown">
        <label for="allowmarkdown"><?=$loc_addPage_status_allowMarkdown?><span class="help" title="<?=$loc_addPage_help?>"></span></label>
        <input type="submit" name="submit" value="<?=$loc_addPage_edit?>">
    	<p><?=$msg;?></p>
        </form>

        </section>
        </main>
    </body>
</html>



