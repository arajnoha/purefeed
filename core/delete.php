<?php
session_start();

if (isset($_SESSION["in"]) && $_SESSION["in"] === 1 && $_GET["id"] !== "") { 
    include("data.php");
    $dirname = $_GET["id"];
    array_map('unlink', glob("../p/".$dirname."/*"));
    rmdir("../p/".$dirname);
    header("Location: ../");

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
                <form action="delete.php?id=<? echo($_GET['id']); ?>" method="post" class="login">
                <p>Do you really want to delete that post?</p>
                <input type="submit" name="submit" value="Delete">
                <a href="../">Cancel & return</a>
            <p><?=$msg;?></p>
                </form>
                </section>
                </main>
            </body>
        </html>


<?php } else {
	header("Location: ../");
}
?>
