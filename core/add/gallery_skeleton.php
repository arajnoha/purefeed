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
    <style>:root{--dominant-color:<?=$siteColor;?>}</style>
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
    <div class="post post-type-gallery">
            <div class="post-content">
                <div>
                    <?php

                    for($i=0;$i<$data['count'];$i++){

                        if ($data['count'] === 2) {

                            if ($i === 0) {
                                echo '<div class="post-slide" id="ss'.($i+1).'"><img src="600_'.($i+1).'.jpg" alt="">';
                                echo '<a href="#ss'.($i+2).'" class="arrow-next">&gt;</a></div>';
                            } else {
                                echo '<div class="post-slide" id="ss'.($i+1).'"><a href="#ss'.($i).'" class="arrow-previous">&lt;</a>';
                                echo '<img src="600_'.($i+1).'.jpg" alt=""></div>';
                            }

                        // if there are more than 2 images
                        } else {

                            if ($i === 0) {
                                echo '<div class="post-slide" id="ss'.($i+1).'"><img src="600_'.($i+1).'.jpg" alt="">';
                                echo '<a href="#ss'.($i+2).'" class="arrow-next">&gt;</a></div>';
                            } else if ($i+1 < $data['count']) {
                                echo '<div class="post-slide" id="ss'.($i+1).'"><a href="#ss'.($i).'" class="arrow-previous">&lt;</a>';
                                echo '<img src="600_'.($i+1).'.jpg" alt="">';
                                echo '<a href="#ss'.($i+2).'" class="arrow-next">&gt;</a></div>';
                            } else {
                                echo '<div class="post-slide" id="ss'.($i+1).'"><a href="#ss'.($i).'" class="arrow-previous">&lt;</a>';
                                echo '<img src="600_'.($i+1).'.jpg" alt=""></div>';
                            }

                        }
                    }


                    ?>
                </div>
                <p><?=$data["description"];?></p>
            </div>
            <div class="post-meta">
                <a href="" class="link"><?=$date;?><span class="timestamp"></span></a>
            </div>   
        </div>
</body>
</html>
