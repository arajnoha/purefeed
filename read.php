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
    <link rel="stylesheet" type="text/css" href="core/neon.css?c=alois">
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
    <div id="feed" class="reader loop<?php if (isset($_POST["type"]) && $_POST["type"] !== "") {echo " ".$_POST["type"];} ?>">

    <?php if ($ins === 1) { ?>
        <div class="choose-type">
            <a href="index.php"><?=$loc_choose_content_add;?></a>
            <a href="read.php"class="active"><?=$loc_choose_content_read;?></a>
      </div>
    <?php } ?>

        <?php

            if (file_exists("core/sources")) {

            // parse all posts (because it's the initial view mode)
            $URLItems = array_filter(explode("\n", file_get_contents('core/sources')));
            $totalPosts = array();

            foreach ($URLItems as $source) {
               $feeds = simplexml_load_file($source);
               $sourceName = $feeds->channel->title;
               $sourceLink = $feeds->channel->link;

                foreach($feeds->channel->item as $item) {
                    $type = (string) $item->category;
                    $title = (string) $item->title;
                    $content = $item->description;
                    $link = (string) $item->link;
                    $time = $item->pubDate;
                    $timestamp = strftime("%Y-%m-%d %H:%M:%S", strtotime($time));
                    $comments = $item->comments;
                    array_push($totalPosts, array("type" => $type,"sourceLink" => $sourceLink, "title" => $title, "description" => $content, "time" => $timestamp, "link" => $link, "source" => $sourceName, "comments" => $comments));
                }
            }



            // Comparison function
            function date_compare($element1, $element2) {
                $datetime1 = strtotime($element1['time']);
                $datetime2 = strtotime($element2['time']);
                return $datetime2 - $datetime1;
            }

            usort($totalPosts, 'date_compare');


            $totalPostCount = count($totalPosts);



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


            for ($loopStart = $loopStart;$loopStart < $loopLimit; $loopStart++) {

                $single = $totalPosts[$loopStart];

                // original DOM populator
                if ($single["type"] === "status") {
                    echo '<div class="post post-type-status"><div class="post-content"><p>'.$single["content"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=status">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/status.php?edit='.$single["timestamp"].'">'.$loc_loop_edit.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div><a href="p/'.$single["timestamp"].'?focus=autofocus" class="link"><div class="post-comments"><span>+ '.$loc_add_comment.'</span><span>'.$single["comments"].'</span></div></a></div>';
                } else if ($single["type"] === "image") {
                    echo '<div class="post post-type-image"><div class="post-content"><img src="'.$single["sourceLink"].'p/'.$single["timestamp"].'/600_1.jpg" alt="">';
                    if ($single["location"] && $single["location"] !== "") {
                        echo "<a class='location' href='https://mapy.cz?q=".$single["location"]."'>".$single["location"]."</a>";
                    }
                    echo '<p>'.$single["description"].'</p></div><div class="post-meta"><a href="p/'.$single["timestamp"].'/full_1.jpg" class="download-original" download></a><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=image">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/edit_image_description.php?edit='.$single["timestamp"].'">'.$loc_loop_editDescription.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div><a href="p/'.$single["timestamp"].'?focus=autofocus" class="link"><div class="post-comments"><span>+ '.$loc_add_comment.'</span><span>'.$single["comments"].'</span></div></a></div>';
                } else if ($single["type"] === "video") {
                    echo '<div class="post post-type-video"><div class="post-content"><video controls><source src="p/'.$single["timestamp"].'/full.mp4" type="video/mp4"></video>';
                    if ($single["location"] && $single["location"] !== "") {
                        echo "<a class='location' href='https://mapy.cz?q=".$single["location"]."'>".$single["location"]."</a>";
                    }
                    echo '<p>'.$single["description"].'</p></div><div class="post-meta"><a href="p/'.$single["timestamp"].'/full.mp4" class="download-original" download></a><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=video">'.$loc_loop_deleteConfirm.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div><a href="p/'.$single["timestamp"].'?focus=autofocus" class="link"><div class="post-comments"><span>+ '.$loc_add_comment.'</span><span>'.$single["comments"].'</span></div></a></div>';
                } else if ($single["type"] === "article") {
                    echo '<div class="post post-type-article"><div class="post-content"><a href="p/'.$single["timestamp"].'"><h3>'.$single["title"].'</h3></a><p>'.$single["perex"].'</p></div><div class="post-meta"><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=article">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/article.php?edit='.$single["timestamp"].'">'.$loc_loop_edit.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div><a href="p/'.$single["timestamp"].'?focus=autofocus" class="link"><div class="post-comments"><span>+ '.$loc_add_comment.'</span><span>'.$single["comments"].'</span></div></a></div>';
                } else if ($single["type"] === "gallery") {
                    echo '<div class="post post-type-gallery"><div class="post-content"><div>';

                    for($i=0;$i<$single['count'];$i++){


                        if ($i === 0) {
                            echo '<input id="'.$single["timestamp"].'in'.($i+1).'" type="radio" name="'.$single["timestamp"].'" checked>';
                            echo '<div class="post-slide" data-count="'.($i+1).'/'.$single["count"].'">';
                            echo '<img src="'.$single["sourceLink"].'p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                            echo '<label for="'.$single["timestamp"].'in'.($i+2).'" class="label-more"></label>';
                            echo '</div>';
                        } else if ($i+1 < $single['count']) {
                            echo '<input id="'.$single["timestamp"].'in'.($i+1).'" type="radio" name="'.$single["timestamp"].'">';
                            echo '<div class="post-slide" data-count="'.($i+1).'/'.$single["count"].'">';
                            echo '<label for="'.$single["timestamp"].'in'.($i).'" class="label-less"></label>';
                            echo '<img src="'.$single["sourceLink"].'p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                            echo '<label for="'.$single["timestamp"].'in'.($i+2).'" class="label-more"></label>';
                            echo '</div>';
                        } else {
                            echo '<input id="'.$single["timestamp"].'in'.($i+1).'" type="radio" name="'.$single["timestamp"].'">';
                            echo '<div class="post-slide" data-count="'.($i+1).'/'.$single["count"].'">';
                            echo '<label for="'.$single["timestamp"].'in'.($i).'" class="label-less"></label>';
                            echo '<img src="'.$single["sourceLink"].'p/'.$single["timestamp"].'/600_'.($i+1).'.jpg" alt="">';
                            echo '</div>';
                        }

                    }

                    echo '</div>';
                    if ($single["location"] && $single["location"] !== "") {
                        echo "<a class='location' href='https://mapy.cz?q=".$single["location"]."'>".$single["location"]."</a>";
                    }
                    echo '<p>'.$single["description"].'</p></div><div class="post-meta"><a href="p/'.$single["timestamp"].'/photos.zip" class="download-original" download></a><input type="checkbox" id="del_'.$single["timestamp"].'"><label for="del_'.$single["timestamp"].'" data-cancel="'.$loc_loop_deleteCancel.'">'.$loc_loop_delete.'</label><a class="operations operations-delete" href="core/delete.php?id='.$single["timestamp"].'&type=image">'.$loc_loop_deleteConfirm.'</a><a class="operations operations-edit" href="core/add/edit_image_description.php?edit='.$single["timestamp"].'">'.$loc_loop_editDescription.'</a><a href="p/'.$single["timestamp"].'" class="link">'.date('d/m/Y H:i', $single["timestamp"]).'<span class="timestamp"></span></a></div><a href="p/'.$single["timestamp"].'?focus=autofocus" class="link"><div class="post-comments"><span>+ '.$loc_add_comment.'</span><span>'.$single["comments"].'</span></div></a></div>';
                }

                // populate DOM
                echo '<div class="post post-type-article">';
                echo  '<div class="post-content">';
                echo   '<a href="'.$single["link"].'"><h3>'.$single["title"].'</h3></a>';
                echo   '<p>'.$single["description"].'</p>';
                echo  '</div>';
                echo  '<div class="post-meta">';
                echo   '<span class="source">'.$single["source"].'</span>'.date('d/m/Y H:i',strtotime($single["time"]));
                echo  '</div>';
                echo '</div>';

            }



            if ($postCount > $loopLimit) {
                echo '<form action="" method="post" class="load-more"><input type="text" value="'.$loadMoreLink.'" id="pullmore" name="pullmore"><label for="submit-next" class="read-more">'.$loc_loop_loadMore.'</label><input type="submit" id="submit-next"></form>';
            }

        }
        ?>

    </div>
</body>
</html>
