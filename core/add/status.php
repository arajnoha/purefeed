<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");

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
        <form action="status.php" method="post" class="add">
        <label for="addstatus"><?=$loc_addPage_status_label?></label>
        <textarea id="addstatus" name="addstatus"></textarea>
        <input type="checkbox" id="allowmarkdown" name="allowmarkdown">
        <label for="allowmarkdown"><?=$loc_addPage_status_allowMarkdown?><span class="help" title="<?=$loc_addPage_help?>"></span></label>
        <input type="submit" name="submit" value="<?=$loc_addPage_publish?>">
    	<p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



