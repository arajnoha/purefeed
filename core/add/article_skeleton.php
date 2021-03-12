<?php
session_start();
include("../../core/data.php");
include("../../core/l10n/".$siteLanguage.".php");
$json = file_get_contents("meta.json");
$data = json_decode($json, true);
$date = date('d/m/Y H:i', $meta["timestamp"]);
?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="../../core/neon.css">
    <link rel="icon" type="image/png" href="../../core/i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
</head>
<body>
    <header>
        <div>
            <h2><?=$siteName;?></h2>
            <p><?=$siteDescription;?></p>
            <a href="../../"><?=$loc_single_backToFeed?></a>
        </div>
    </header>
    <div id="feed">
    <div class="post post-type-article">
            <div class="post-content">
                <h1><?=$data["title"];?></h1>
                <div><?=$data["content"];?></div>
            </div>
            <div class="post-meta">
            <a href="" class="link"><?=$date;?><span class="timestamp"></span></a>
            </div>        
        </div>
    </div>
</body>
</html>
