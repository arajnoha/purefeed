<?php
session_start();
include("../../core/data.php");
include("../../core/l10n/".$siteLanguage.".php");
$json = file_get_contents("meta.json");
$data = json_decode($json, true);
$date = date('d/m/Y H:i', $meta["timestamp"]);

$ins = 0;
if (isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
    $ins = 1;
}
?>
<!doctype html>
<html lang="cs">
<head>
    <style>html{background: #f8c4c4}body{visibility:hidden}/*FOUC*/</style>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="../../core/neon.css">
    <link rel="icon" type="image/png" href="../../core/i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
</head>
<body <?php if ($ins === 1) {echo "class='admin'";} ?>>
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


                            if ($i === 0) {
                                echo '<input id="in'.($i+1).'" type="radio" name="'.$data["timestamp"].'" checked>';
                                echo '<div class="post-slide" data-count="'.($i+1).'/'.$data["count"].'">';
                                echo '<img src="600_'.($i+1).'.jpg" alt="">';
                                echo '<label for="in'.($i+2).'" class="label-more"></label>';
                                echo '</div>';
                            } else if ($i+1 < $data['count']) {
                                echo '<input id="in'.($i+1).'" type="radio" name="'.$data["timestamp"].'">';
                                echo '<div class="post-slide" data-count="'.($i+1).'/'.$data["count"].'">';
                                echo '<label for="in'.($i).'" class="label-less"></label>';
                                echo '<img src="600_'.($i+1).'.jpg" alt="">';
                                echo '<label for="in'.($i+2).'" class="label-more"></label>';
                                echo '</div>';
                            } else {
                                echo '<input id="in'.($i+1).'" type="radio" name="'.$data["timestamp"].'">';
                                echo '<div class="post-slide" data-count="'.($i+1).'/'.$data["count"].'">';
                                echo '<label for="in'.($i).'" class="label-less"></label>';
                                echo '<img src="600_'.($i+1).'.jpg" alt="">';
                                echo '</div>';
                            }
                    }

                    ?>
                </div>
                <?php
                    if ($data["location"] && $data["location"] !== "") {
                        echo "<a class='location' href='https://mapy.cz?q=".$data["location"]."'>".$data["location"]."</a>";
                    }
                ?>
                <p><?=$data["description"];?></p>
            </div>
            <div class="post-meta">
                <a href="photos.zip" class="download-original" download></a>
                <input type="checkbox" id="del_<?=$data["timestamp"]?>" data-cancel="<?=$loc_loop_deleteCancel?>"><label for="del_<?=$data["timestamp"]?>" data-cancel="<?=$loc_loop_deleteCancel?>"><?=$loc_loop_delete?></label><a class="operations operations-delete" href="../../core/delete.php?id=<?=$data["timestamp"]?>"><?=$loc_loop_deleteConfirm?></a>
                <a class="operations operations-edit" href="../../core/add/edit_image_description.php?edit=<?=$data["timestamp"]?>"><?=$loc_loop_edit?></a>
                <a href="" class="link"><?=$date;?><span class="timestamp"></span></a>
            </div>   
        </div>
</body>
</html>
