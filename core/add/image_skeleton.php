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
if (isset($_GET["focus"])) {
    $focus = $_GET["focus"];
}

if (isset($_POST["commentor"]) && isset($_POST["comment"])) {
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
    <div class="post post-type-image">
            <div class="post-content">
                <img src="600_1.jpg" alt="">
                <?php
                    if ($data["location"] && $data["location"] !== "") {
                        echo "<a class='location' href='https://mapy.cz?q=".$data["location"]."'>".$data["location"]."</a>";
                    }
                ?>
                <p><?=$data["description"];?></p>
            </div>
            <div class="post-meta">
            <a href="full_1.jpg" class="download-original" download></a>
            <input type="checkbox" id="del_<?=$data["timestamp"]?>" data-cancel="<?=$loc_loop_deleteCancel?>"><label for="del_<?=$data["timestamp"]?>" data-cancel="<?=$loc_loop_deleteCancel?>"><?=$loc_loop_delete?></label><a class="operations operations-delete" href="../../core/delete.php?id=<?=$data["timestamp"]?>&type=image"><?=$loc_loop_deleteConfirm?></a>
            <a class="operations operations-edit" href="../../core/add/edit_image_description.php?edit=<?=$data["timestamp"]?>"><?=$loc_loop_edit?></a>
            <a href="" class="link"><?=$date;?><span class="timestamp"></span></a>
            </div>
            <div class="post-comments">
                <label for="open-comment-section"></label>
                <span>+ <?=$loc_add_comment;?></span>
                <span><?=$data["comments"];?></span>
            </div>
            <input type="checkbox" id="open-comment-section" name="open-comment-section" <?php if($focus) {echo "checked='checked'";} ?> >
            <div class="post-comments" id="hook_comments">
                <form action="" method="post">
                    <label for="commentor"><?=$loc_single_commentor;?></label>
                    <input type="text" id="commentor" name="commentor" <?=$focus;?>>
                    <label for="comment"><?=$loc_single_comment;?></label>
                    <textarea name="comment" id="comment"></textarea>
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
    </div>
</body>
</html>
