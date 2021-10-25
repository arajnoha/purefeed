<?php
session_start();
include("data.php");
include("l10n/".$siteLanguage.".php");


if (isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../");
}

$msg = '';

if (isset($_POST['login'])) {
	if ($_POST['login'] === $sitePassword) {
		$_SESSION["in"] = 1;
		header("Location: ../");
	} else {
		$msg = $loc_login_badPassword;
	}
}
?>
<!doctype html>
<html lang="cs">
<head>
    <style>html{background: #f3ceb2}body{visibility:hidden}/*FOUC*/</style>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="neon.css?c=alois">
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
        <form action="login.php" method="post" class="login">
        <label for="login"><?=$loc_login_label?></label>
        <input type="password" id="login" name="login" autofocus>
        <input type="submit" name="submit" value="<?=$loc_login_submit?>">
	<p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



