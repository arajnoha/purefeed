<?php
session_start();
include("data.php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../");
}

$msg = '';

if (isset($_POST["submit"])) {
    $file = fopen("data.php","w");
    $newvalues = '<?php $siteName = "'.$_POST["sitename"].'";$siteDescription = "'.$_POST["sitedescription"].'"; $sitePassword = "'.$_POST["sitepassword"].'"; $siteColor = "'.$_POST["sitecolor"].'"; ?>';
    fwrite($file, $newvalues);
    fclose($file);
    header("Location: settings.php");
}
?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="neon.css">
    <link rel="icon" type="image/png" href="i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
    <style>:root{--dominant-color:<?=$siteColor;?>}</style>
</head>
	    <body>
		<main>
        <header>
        <div>
            <h2><a href="../"><?=$siteName;?></a></h2>
            <p><?=$siteDescription;?></p>
            <a href="../">< Back to Feed</a>
        </div>
    </header>
        <form action="settings.php" method="post" class="login">
        <label for="sitename">Website's name:</label>
        <input type="text" id="sitename" name="sitename" value="<?=$siteName;?>">
        <label for="sitedescription">Website's description:</label>
        <textarea id="sitedescription" name="sitedescription"><?=$siteDescription;?></textarea>
        <label for="sitepassword">Website's password:</label>
        <input type="password" id="sitepassword" name="sitepassword" value="<?=$sitePassword;?>">
        <label for="sitecolor">Website's dominant color:</label>
        <input type="color" id="sitecolor" name="sitecolor" value="<?=$siteColor;?>">
        <input type="submit" name="submit" value="Save changes">
	<p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



