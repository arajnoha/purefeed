<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");

if (!isset($_SESSION["in"]) || $_SESSION["in"] !== 1) {
	header("Location: ../../");
        exit;
}

$msg = '';

if (isset($_POST["submit"]) && $_SESSION["in"] === 1) {

    $count = count($_FILES['audio']['name']);

    if ($count > 0) {

        // initiate /p/ folder if it doesn't exist
        if (!file_exists('../../p/')) {
            mkdir('../../p/', 0777, true);
        }

        $description = $_POST["adddescription"];
        $verbatimContent = $_POST["adddescription"];
        $location = "";
        if (isset($_POST["addlocation"])) {$location = $_POST["addlocation"];}

        if (isset($_POST["allowmarkdown"])) {
            include("../Parsedown.php");
            $Parsedown = new Parsedown();
            $description = $Parsedown->text($description);
        }

        $folder = strtotime("now");
        mkdir("../../p/".$folder);

        if (!file_exists('../indexes/')) {
			mkdir('../indexes/', 0777, true);
        }
        file_put_contents("../indexes/audio",$folder."|",FILE_APPEND);
        file_put_contents("../indexes/global",$folder."|",FILE_APPEND);

        for($i=0;$i<$count;$i++){
            $filename = $_FILES['audio']['name'][$i];
            $audiotemp = $_FILES['audio']['tmp_name'][$i];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            $fileArray = array('type' => "audio", 'description' => $description, 'location' => $location, 'timestamp' => $folder, 'extension' => $ext,'comments' => 0, "love" => 0, "comments_array" => []);
            copy("audio_page.php", "../../p/".$folder."/index.php");

            file_put_contents("../../p/".$folder."/meta.json", json_encode($fileArray));
            file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);


            if(is_uploaded_file($audiotemp)) {

				if(move_uploaded_file($audiotemp, "../../p/".$folder."/audio.".$ext)) {
					header("Location: ../../");
				}
			}
        }

    } else {
        $msg = $loc_addPage_image_error;
    }
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
        <form action="audio.php" method="post" class="add" enctype="multipart/form-data">
        <label for="addaduio"><?=$loc_addPage_audio_label?></label>
        <input type="file" id="audio" name="audio[]" accept="audio/*">
        <label for="adddescription"><?=$loc_addPage_image_description?></label>
        <textarea id="adddescription" name="adddescription"></textarea>
        <input type="checkbox" id="allowmarkdown" name="allowmarkdown">
        <label for="allowmarkdown"><?=$loc_addPage_status_allowMarkdown?><span class="help" title="<?=$loc_addPage_help?>"></span></label>
        <label for="addlocation"><?=$loc_addPage_image_location?></label>
        <input type="text" id="addlocation" name="addlocation">
        <input type="submit" name="submit" value="<?=$loc_addPage_publish?>">
	    <p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



