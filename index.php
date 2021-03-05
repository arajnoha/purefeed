<?php
session_start();
include("core/data.php");

$ins = 0;
if (isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
    $ins = 1;
}
?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="core/neon.css">
    <link rel="icon" type="image/png" href="core/i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
    <style>:root{--dominant-color:<?=$siteColor;?>}</style>
</head>
<body <?php if ($ins === 1) {echo "class='admin'";} ?>>
    <header>
        <div>
            <h2><?=$siteName;?></h2>
            <p><?=$siteDescription;?></p>
        </div>
        <nav>
        <?php if ($ins === 1) { ?>
            <a href="core/logout.php"><img src="core/i/logout.svg" alt="Log out"></a>
            <a href="core/settings.php"><img src="core/i/settings.svg" alt="Settings"></a>
        <?php } else { ?>
            <a href="core/login.php"><img src="core/i/user.svg" alt="Log in"></a>
            <a href="rss.php"><img src="core/i/rss.svg" alt="RSS"></a>
        <?php } ?>           
        </nav>
    </header>
    <div id="feed">

    <?php if ($ins === 1) { ?>
        <div class="add-post">
            <a class="add-post-item add-post-status" href="core/add/status.php">
                <img src="core/i/status.svg">
                Text post
             </a>
            <a class="add-post-item add-post-article" href="core/add/article.php">
                <img src="core/i/blogpost.svg">
                Article
            </a>
            <a class="add-post-item add-post-image" href="core/add/image.php">
                <img src="core/i/image.svg">
                Photo(s)
            </a>
        </div>
    <?php } ?>

        <?php
            // sorting post function
            function datesortdesc(array $b, array $a) {
                if ($a['timestamp'] < $b['timestamp']) {
                    return -1;
                } else if ($a['timestamp'] > $b['timestamp']) {
                    return 1;
                } else {
                    return 0;
                }
            }

            // Reconstruct all posts
            $postPath = "p/*";
            $postArray = glob($postPath, GLOB_ONLYDIR);

            $globalArray = [];
            foreach ($postArray as $single) {
                $singleArray = json_decode(file_get_contents($single."/meta.json"), true);
                array_push($globalArray, $singleArray);
            }
            usort($globalArray, 'datesortdesc');

            foreach($globalArray as $single) {
                
                // populate DOM based on post types read from jsons
                if ($single["type"] === "status") {
                    echo '<div class="post post-type-status"><div class="post-content"><p>'.$single["content"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'">Delete</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'">Confirm deletion</a><a href="p/'.$single["timestamp"].'" class="link">'.date('m/d/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                } else if ($single["type"] === "image") {
                    echo '<div class="post post-type-image"><div class="post-content"><img src="p/'.$single["timestamp"].'/600_1.jpg" alt=""><p>'.$single["description"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'">Delete</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'">Confirm deletion</a><a href="p/'.$single["timestamp"].'" class="link">'.date('m/d/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                } else if ($single["type"] === "article") {
                    echo '<div class="post post-type-article"><div class="post-content"><a href="p/'.$single["timestamp"].'"><h3>'.$single["title"].'</h3></a><p>'.$single["perex"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'">Delete</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'">Confirm deletion</a><a href="p/'.$single["timestamp"].'" class="link">'.date('m/d/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                } else if ($single["type"] === "gallery") {
                    echo '<div class="post post-type-gallery"><div class="post-content"><div>';
                    
                    for($i=0;$i<$single['count'];$i++){
                        if ($single['count'] === 2) {

                            if ($i === 0) {
                                echo '<div class="post-slide" id="'.$single["timestamp"].'ss'.($i+1).'"><img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                                echo '<a href="#'.$single["timestamp"].'ss'.($i+2).'" class="arrow-next">&gt;</a></div>';
                            } else {
                                echo '<div class="post-slide" id="'.$single["timestamp"].'ss'.($i+1).'"><a href="#'.$single["timestamp"].'ss'.($i).'" class="arrow-previous">&lt;</a>';
                                echo '<img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt=""></div>';
                            }

                        // if there are more than 2 images
                        } else {

                            if ($i === 0) {
                                echo '<div class="post-slide" id="'.$single["timestamp"].'ss'.($i+1).'"><img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                                echo '<a href="#'.$single["timestamp"].'ss'.($i+2).'" class="arrow-next">&gt;</a></div>';
                            } else if ($i+1 < $single['count']) {
                                echo '<div class="post-slide" id="'.$single["timestamp"].'ss'.($i+1).'"><a href="#'.$single["timestamp"].'ss'.($i).'" class="arrow-previous">&lt;</a>';
                                echo '<img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                                echo '<a href="#'.$single["timestamp"].'ss'.($i+2).'" class="arrow-next">&gt;</a></div>';
                            } else {
                                echo '<div class="post-slide" id="'.$single["timestamp"].'ss'.($i+1).'"><a href="#'.$single["timestamp"].'ss'.($i).'" class="arrow-previous">&lt;</a>';
                                echo '<img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt=""></div>';
                            }

                        }
                    }

                    echo '</div><p>'.$data["description"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'">Delete</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'">Confirm deletion</a><a href="p/'.$single["timestamp"].'" class="link">'.date('m/d/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                }
            }
        ?>

    </div>
</body>
</html>
