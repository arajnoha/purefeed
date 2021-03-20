<?php
session_start();
include("core/data.php");
include("core/l10n/".$siteLanguage.".php");

$ins = 0;
if (isset($_SESSION["in"]) && $_SESSION["in"] === 1) {
    $ins = 1;
}
?>
<!doctype html>
<html lang="cs">
<head>
    <style>html{background: #f3ceb2}body{visibility:hidden}/*FOUC*/</style>
    <meta charset="utf-8">
    <title><?=$siteName;?></title>
    <link rel="stylesheet" type="text/css" href="core/neon.css?c=1">
    <link rel="icon" type="image/png" href="core/i/favicon.png">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="<?=$siteName;?> - <?=$siteDescription;?>">
</head>
<body <?php if ($ins === 1) {echo "class='admin'";} ?>>
    <header>
        <div>
            <h2><?=$siteName;?></h2>
            <p><?=$siteDescription;?></p>
        </div>
        <nav>
        <?php if ($ins === 1) { ?>
            <a href="core/logout.php"><img src="core/i/logout.svg" alt=""></a>
            <a href="core/settings.php"><img src="core/i/settings.svg" alt=""></a>
        <?php } else { ?>
            <a href="core/login.php"><img src="core/i/user.svg" alt=""></a>
            <a href="rss.php"><img src="core/i/rss.svg" alt=""></a>
        <?php } ?>
        <a href="https://github.com/arajnoha/purefeed"><img src="core/i/code.svg" alt="purefeed project - github"></a>       
        </nav>
    </header>
    <div id="feed" class="loop">

    <?php if ($ins === 1) { ?>
        <div class="add-post">
            <a class="add-post-item add-post-status" href="core/add/status.php">
                <img src="core/i/status.svg">
                <?=$loc_addPost_text?>
             </a>
            <a class="add-post-item add-post-article" href="core/add/article.php">
                <img src="core/i/blogpost.svg">
                <?=$loc_addPost_article?>
            </a>
            <a class="add-post-item add-post-image" href="core/add/image.php">
                <img src="core/i/image.svg">
                <?=$loc_addPost_image?>
            </a>
        </div>
    <?php } ?>

        <?php

            if (file_exists("core/indexes/global")) {

            // parse all posts (because it's the initial view mode)
            $totalPosts = array_reverse(array_filter(explode("|",file_get_contents("core/indexes/global"))));
            $totalPostCount = count($totalPosts);

           


            // count the post types to decide whether show the post filter or not
            $limitCounter = 0;
            $files = glob("core/indexes/" . "*");
            if ($files){
            $limitCounter = count($files);
            }
            
            if ($limitCounter > 2) {
                echo "<div class='post-filter nobar'>";
                echo "<form action='' method='post' class='post-filter-form'><input type='text' name='type' value=''><input type='submit' id='submit-global'><label for='submit-global'>";
                echo "<div class='post-filter-item global selected' data-count='".$totalPostCount."'>".$loc_homepage_All."</div>";
                echo "</label></form>";


                if (file_exists("core/indexes/image")) {
                    $limitCounter++;
                    echo "<form action='' method='post' class='post-filter-form'><input type='text' name='type' value='image'><input type='submit' id='submit-image'><label for='submit-image'>";
                    echo "<div class='post-filter-item image' data-count='".count(array_filter(explode("|",file_get_contents("core/indexes/image"))))."'>".$loc_homepage_image."</div>";
                    echo "</label></form>";
                }
                if (file_exists("core/indexes/article")) {
                    $limitCounter++;
                    echo "<form action='' method='post' class='post-filter-form'><input type='text' name='type' value='article'><input type='submit' id='submit-article'><label for='submit-article'>";
                    echo "<div class='post-filter-item article' data-count='".count(array_filter(explode("|",file_get_contents("core/indexes/article"))))."'>".$loc_homepage_article."</div>";
                    echo "</label></form>";
                }
                if (file_exists("core/indexes/status")) {
                    $limitCounter++;
                    echo "<form action='' method='post' class='post-filter-form'><input type='text' name='type' value='status'><input type='submit' id='submit-status'><label for='submit-status'>";
                    echo "<div class='post-filter-item status' data-count='".count(array_filter(explode("|",file_get_contents("core/indexes/status"))))."'>".$loc_homepage_status."</div>";
                    echo "</label></form>";
                }
                echo "</div>";
            }

            // check for post filter requests and alter the loop source
            if (isset($_POST["type"]) && $_POST["type"] !== "") {
                $totalPosts = array_reverse(array_filter(explode("|",file_get_contents("core/indexes/".$_POST["type"]))));
                $totalPostCount = count($totalPosts);
            }

            $request = 0; // in case there aren't any requests
            if (isset($_POST["pullmore"])) {$request = $_POST["pullmore"];}
            if (isset($_POST["pullless"])) {$request = $_POST["pullless"];}

            $loopStart = 0 + $request;

            // check if request isnt more than total post (but after Start increment)
            if (($request + 10) > ($totalPostCount)) {
                $remainer = ($totalPostCount) - $request;
                $loopLimit = $request + $remainer;
            } else {
                $loopLimit = 10 + $request;
            }

            
            $threshold = 10;

            $postCount = $totalPostCount;

            // if there aren't even 10 posts, show them all
            if ($postCount < $threshold) {
                $loopLimit = $postCount;

            // if there are more, show the link for 10 more (or less)
            } else if ($postCount > $threshold) {
                $loadMoreLink = $threshold + $request;
                $loadLessLink = $request - $threshold;
            }


            // potential Previous button
            if ($loopStart !== 0) {
                echo '<form action="" method="post" class="load-more"><input type="text" value="'.$loadLessLink.'" id="pullless" name="pullless"><label for="submit-prev" class="read-more">'.$loc_loop_loadLess.'</label><input type="submit" id="submit-prev"></form>';
            }


            $globalArray = [];
            foreach ($totalPosts as $single) {
                $singleArray = json_decode(file_get_contents("p/".$single."/meta.json"), true);
                array_push($globalArray, $singleArray);
            }

            for ($loopStart = $loopStart;$loopStart < $loopLimit; $loopStart++) {
                
                $single = $globalArray[$loopStart];

                // populate DOM based on post types read from jsons
                if ($single["type"] === "status") {
                    echo '<div class="post post-type-status"><div class="post-content"><p>'.$single["content"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=status">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/status.php?edit='.$single["timestamp"].'">'.$loc_loop_edit.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                } else if ($single["type"] === "image") {
                    echo '<div class="post post-type-image"><div class="post-content"><img src="p/'.$single["timestamp"].'/600_1.jpg" alt="">';
                    if ($single["location"] && $single["location"] !== "") {
                        echo "<a class='location' href='https://mapy.cz?q=".$single["location"]."'>".$single["location"]."</a>";
                    }
                    echo '<p>'.$single["description"].'</p></div><div class="post-meta"><a href="p/'.$single["timestamp"].'/full_1.jpg" class="download-original" download></a><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=image">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/edit_image_description.php?edit='.$single["timestamp"].'">'.$loc_loop_editDescription.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                } else if ($single["type"] === "article") {
                    echo '<div class="post post-type-article"><div class="post-content"><a href="p/'.$single["timestamp"].'"><h3>'.$single["title"].'</h3></a><p>'.$single["perex"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=article">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/article.php?edit='.$single["timestamp"].'">'.$loc_loop_edit.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                } else if ($single["type"] === "gallery") {
                    echo '<div class="post post-type-gallery"><div class="post-content"><div>';
                    
                    for($i=0;$i<$single['count'];$i++){
                        

                        if ($i === 0) {
                            echo '<input id="'.$single["timestamp"].'in'.($i+1).'" type="radio" name="'.$single["timestamp"].'" checked>';
                            echo '<div class="post-slide" data-count="'.($i+1).'/'.$single["count"].'">';
                            echo '<img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                            echo '<label for="'.$single["timestamp"].'in'.($i+2).'" class="label-more"></label>';
                            echo '</div>';
                        } else if ($i+1 < $single['count']) {
                            echo '<input id="'.$single["timestamp"].'in'.($i+1).'" type="radio" name="'.$single["timestamp"].'">';
                            echo '<div class="post-slide" data-count="'.($i+1).'/'.$single["count"].'">';
                            echo '<label for="'.$single["timestamp"].'in'.($i).'" class="label-less"></label>';
                            echo '<img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                            echo '<label for="'.$single["timestamp"].'in'.($i+2).'" class="label-more"></label>';
                            echo '</div>';
                        } else {
                            echo '<input id="'.$single["timestamp"].'in'.($i+1).'" type="radio" name="'.$single["timestamp"].'">';
                            echo '<div class="post-slide" data-count="'.($i+1).'/'.$single["count"].'">';
                            echo '<label for="'.$single["timestamp"].'in'.($i).'" class="label-less"></label>';
                            echo '<img src="p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                            echo '</div>';
                        }

                    }

                    echo '</div>';
                    if ($single["location"] && $single["location"] !== "") {
                        echo "<a class='location' href='https://mapy.cz?q=".$single["location"]."'>".$single["location"]."</a>";
                    }
                    echo '<p>'.$single["description"].'</p></div><div class="post-meta"><a href="p/'.$single["timestamp"].'/photos.zip" class="download-original" download></a><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=image">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/edit_image_description.php?edit='.$single["timestamp"].'">'.$loc_loop_editDescription.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div></div>';
                }
            }
            


            if ($postCount > $loopLimit) {
                echo '<form action="" method="post" class="load-more"><input type="text" value="'.$loadMoreLink.'" id="pullmore" name="pullmore"><label for="submit-next" class="read-more">'.$loc_loop_loadMore.'</label><input type="submit" id="submit-next"></form>';
            }

        }
        ?>

    </div>
</body>
</html>
