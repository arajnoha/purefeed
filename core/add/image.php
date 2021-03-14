<?php
session_start();
include("../data.php");
include("../l10n/".$siteLanguage.".php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../../");
}

$msg = '';

if (isset($_POST["submit"]) && $_SESSION["in"] === 1) {

    $count = count($_FILES['image']['name']);

    if ($count > 0) {

        // initiate /p/ folder if it doesn't exist
        if (!file_exists('../../p/')) {
            mkdir('../../p/', 0777, true);
        }

        $description = $_POST["adddescription"];
        $verbatimContent = $_POST["adddescription"];

        if (isset($_POST["allowmarkdown"])) {
            include("../Parsedown.php");
            $Parsedown = new Parsedown();
            $description = $Parsedown->text($description);
        }

        $folder = strtotime("now");
        mkdir("../../p/".$folder);

        for($i=0;$i<$count;$i++){
            $filename = $_FILES['image']['name'][$i];
            $imagetemp = $_FILES['image']['tmp_name'][$i];

            if ($count === 1) {
                $fileArray = array('type' => "image", 'description' => $description, 'timestamp' => $folder);
                copy("image_page.php", "../../p/".$folder."/index.php");
            } else {
                $fileArray = array('type' => "gallery", 'description' => $description, 'timestamp' => $folder, 'count' => $count);
                copy("gallery_page.php", "../../p/".$folder."/index.php");
            }

            file_put_contents("../../p/".$folder."/meta.json", json_encode($fileArray));
            file_put_contents("../../p/".$folder."/verbatim",$verbatimContent);           


            if(is_uploaded_file($imagetemp)) {

				if(move_uploaded_file($imagetemp, "../../p/".$folder."/full_".($i+1).".jpg")) {

                    
					// trim, scale down and optionally add white background to fill the 600x600 area
					$im = new Imagick("../../p/".$folder."/full_".($i+1).".jpg");
					$im->trimImage(20000);
					$im->resizeImage(600, 600,Imagick::FILTER_LANCZOS,1, TRUE);
					$im->setImageBackgroundColor("white");
					$w = $im->getImageWidth();
					$h = $im->getImageHeight();
					$off_top=0;
					$off_left=0;
					if($w > $h){
						$off_top = ((600-$h)/2) * -1;
					}else{
						$off_left = ((600-$w)/2) * -1;
					}
					$im->extentImage(600,600, $off_left, $off_top);
					$im->writeImage("../../p/".$folder."/600_".($i+1).".jpg");

					header("Location: ../../");
				}
			}
        }

        if ($count > 1) {
            $zip = new ZipArchive;
            if ($zip->open("../../p/".$folder."/photos.zip", ZipArchive::CREATE) === TRUE) {
                for ($img = 0;$img < $count;$img++) {
                    $zip->addFile("../../p/".$folder."/full_".($img+1).".jpg");
                }
                $zip->close();
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
    <style>html{background: #f8c4c4}body{visibility:hidden}/*FOUC*/</style>
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
        <form action="image.php" method="post" class="add" enctype="multipart/form-data">
        <label for="addimage"><?=$loc_addPage_image_label?></label>
        <input type="file" id="image" name="image[]" accept="image/*" multiple>
        <label for="adddescription"><?=$loc_addPage_image_description?></label>
        <textarea id="adddescription" name="adddescription"></textarea>
        <input type="checkbox" id="allowmarkdown" name="allowmarkdown">
        <label for="allowmarkdown"><?=$loc_addPage_status_allowMarkdown?><span class="help" title="<?=$loc_addPage_help?>"></span></label>
        <input type="submit" name="submit" value="<?=$loc_addPage_publish?>">
	    <p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



