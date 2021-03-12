<?php
session_start();
include("data.php");
include("l10n/".$siteLanguage.".php");

if (!isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../");
}

$msg = '';

if (isset($_POST["submit"])) {
    $file = fopen("data.php","w");
    $newvalues = '<?php $siteName = "'.$_POST["sitename"].'";$siteDescription = "'.$_POST["sitedescription"].'"; $sitePassword = "'.$_POST["sitepassword"].'"; $siteLanguage = "'.$_POST["siteLanguage"].'"; ?>';
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
</head>
	    <body>
		<main>
        <header>
        <div>
            <h2><a href="../"><?=$siteName;?></a></h2>
            <p><?=$siteDescription;?></p>
            <a href="../"><?=$loc_single_backToFeed?></a>
        </div>
    </header>
        <form action="settings.php" method="post" class="login">
        <label for="sitename"><?=$loc_settings_labelName?></label>
        <input type="text" id="sitename" name="sitename" value="<?=$siteName;?>">
        <label for="sitedescription"><?=$loc_settings_labelDescription?></label>
        <textarea id="sitedescription" name="sitedescription"><?=$siteDescription;?></textarea>
        <label for="sitepassword"><?=$loc_settings_labelPassword?></label>
        <input type="password" id="sitepassword" name="sitepassword" value="<?=$sitePassword;?>">
        <label for="cars"><?=$loc_settings_labelLanguage?></label>

        <?php
        $options = array(
            'english' => 'English',
            'czech' => 'Čeština'
        );
        echo '<select name="siteLanguage" id="siteLanguage">';
        foreach($options as $value => $display){
            if($value == $siteLanguage){
               echo '<option value="'.$value.'" selected>'.$display.'</option>';
            }else{
               echo '<option value="'.$value.'">'.$display.'</option>';
            }
        }
        echo '</select>';

        ?>

        <input type="submit" name="submit" value="<?=$loc_settings_save?>">
	<p><?=$msg;?></p>
        </form>
        </main>
    </body>
</html>



