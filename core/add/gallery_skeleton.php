<?php
session_start();
include("../../core/data.php");
include("../../core/l10n/".$siteLanguage.".php");
$json = file_get_contents("meta.json");
$data = json_decode($json, true);
$date = date('d/m/Y H:i', $meta["timestamp"]);
$metaContent = strip_tags($data["description"]);

$ins = 0;
if (isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
    $ins = 1;
}
if (isset($_GET["focus"])) {
    $focus = $_GET["focus"];
}

$love = "";

if (isset($_GET["love"])) {
    if (!isset($_COOKIE["love".$data["timestamp"]])) {
        setcookie("love".$data["timestamp"], 1, time() + (86400 * 30 * 365 * 99), "/");
        $addingLove = $data;
        $loving = (int) $addingLove["love"];
        $loving++;
        $addingLove["love"] = $loving;
        file_put_contents("meta.json", json_encode($addingLove));
    } else if (isset($_COOKIE["love".$data["timestamp"]])) {
        setcookie("love".$data["timestamp"], "", time() - 3600, "/");
        $addingLove = $data;
        $loving = (int) $addingLove["love"];
        $loving--;
        $addingLove["love"] = $loving;
        file_put_contents("meta.json", json_encode($addingLove));
    }
    header("Location: ?");
}

// has to happen after first love catcher because of its reload
if (isset($_COOKIE["love".$data["timestamp"]])) {
    $love = "loved";
}

if (isset($_POST["commentor"]) && isset($_POST["comment"])) {
    if ($_POST["commentor"] !== "" && $_POST["comment"] !== "") {
        $name = htmlspecialchars(strip_tags($_POST["commentor"]));
        $content = htmlspecialchars(strip_tags($_POST["comment"]));
        $timestamp = strtotime("now");

        $newFile = $data;
        $counter = (int) $newFile["comments"];
        $counter++;
        $newFile["comments"] = $counter;
        $oldArray = (array) $newFile["comments_array"];
        array_push($oldArray, compact('name', 'content', 'timestamp'));
        $newFile["comments_array"] = $oldArray;
        file_put_contents("meta.json", json_encode($newFile));
        header("Location: ");
    }

}
?>
<!doctype html>
<html lang="cs">
<head>
    <style>html{background: #f3ceb2}body{visibility:hidden}/*FOUC*/</style>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="../../core/neon.css?c=alois">
    <link rel="icon" type="image/png" href="../../core/i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$metaContent;?>">
    <meta property="og:image" content="600_0.jpg">

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
                <a href="?love=1" class="love <?=$love;?>"><?=$data["love"];?></a>
                <input type="checkbox" id="del_<?=$data["timestamp"]?>" data-cancel="<?=$loc_loop_deleteCancel?>"><label for="del_<?=$data["timestamp"]?>" data-cancel="<?=$loc_loop_deleteCancel?>"><?=$loc_loop_delete?></label><a class="operations operations-delete" href="../../core/delete.php?id=<?=$data["timestamp"]?>&type=image"><?=$loc_loop_deleteConfirm?></a>
                <a class="operations operations-edit" href="../../core/add/edit_image_description.php?edit=<?=$data["timestamp"]?>"><?=$loc_loop_edit?></a>
                <a href="" class="link"><?=$date;?><span class="timestamp"></span></a>
            </div>
            <div class="post-comments">
                <label for="open-comment-section"></label>
                <span>+ <?=$loc_add_comment;?></span>
                <span><?=$data["comments"];?></span>
            </div>
            <input type="checkbox" id="open-comment-section" name="open-comment-section" <?php if(isset($focus)) {echo 'checked="checked"';} ?>>
            <div class="post-comments" id="hook_comments">
                <form action="" method="post">
                    <label for="commentor"><?=$loc_single_commentor;?></label>
                    <input type="text" id="commentor" name="commentor" required>
                    <label for="comment"><?=$loc_single_comment;?></label>
                    <textarea name="comment" id="comment"></textarea required>
                    <input type="submit" value="<?=$loc_single_save_comment;?>">
                </form>
            </div>
            <?php
                for ($i = 0; $i < count($data["comments_array"]); $i++)  {
                    echo "<div class='post-single-comment'>";
                    echo "<span>".$data["comments_array"][$i]["name"]."</span><span>".date('H:i d/m/Y', $data["comments_array"][$i]["timestamp"])."</span>";
                    echo "<span>".$data["comments_array"][$i]["content"]."</span>";
                    echo "</div>";
                }
            ?>
        </div>
</body>
</html>
