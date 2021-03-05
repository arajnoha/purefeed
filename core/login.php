<?php
session_start();
include("data.php");

if (isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
	header("Location: ../");
}

$msg = '';

if (isset($_POST['login'])) {
	if ($_POST['login'] === $sitePassword) {
		$_SESSION["in"] = 1;
		header("Location: ../");
	} else {
		$msg = "Wrong password, try it again please.";
	}	
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
        <form action="login.php" method="post" class="login">
        <label for="login">Fill in your password:</label>
        <input type="password" id="login" name="login" autofocus>
        <input type="submit" name="submit" value="Log in">
	<p><?=$msg;?></p>
        </form>
        </section>
        </main>
    </body>
</html>



