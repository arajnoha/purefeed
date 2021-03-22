<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../../");
}

$msg = '';

if (isset($_POST["submit"]) && $_SESSION["in"] === 1) {

    $count = count($_FILES['video']['name']);

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
        file_put_contents("../indexes/video",$folder."|",FILE_APPEND);
        file_put_contents("../indexes/global",$folder."|",FILE_APPEND);

        for($i=0;$i<$count;$i++){
            $filename = $_FILES['video']['name'][$i];
            $videotemp = $_FILES['video']['tmp_name'][$i];

            $fileArray = array('type' => "video", 'description' => $description, 'location' => $location, 'timestamp' => $folder);
            copy("video_page.php", "../../p/".$folder."/index.php");
        
            file_put_contents("../../p/".$folder."/meta.json", json_encode($fileArray));
            file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);           


            if(is_uploaded_file($videotemp)) {

				if(move_uploaded_file($videotemp, "../../p/".$folder."/full.mp4")) {



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
        <form action="video.php" method="post" class="add" enctype="multipart/form-data">
        <label for="addvideo"><?=$loc_addPage_video_label?></label>
        <input type="file" id="video" name="video[]" accept="video/*">
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



