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
    <div id="feed" class="loop<?php if (isset($_POST["type"]) && $_POST["type"] !== "") {echo " ".$_POST["type"];} ?>">

    <?php if ($ins === 1) { ?>
        <div class="choose-type">
            <a href="index.php"><?=$loc_choose_content_add;?></a>
            <a href="read.php"class="active"><?=$loc_choose_content_read;?></a>
      </div>
    <?php } ?>

        <?php

            if (file_exists("core/sources")) {

            // parse all posts (because it's the initial view mode)
            $URLItems = array_reverse(array_filter(explode("\n", file_get_contents('core/sources'))));
            $totalPosts = [];

            foreach ($URLItems as $source) {
                $feeds = simplexml_load_file($source);
                foreach($feeds->channel->item as $item) {
                    $title = (string) $item->title;
                    $description = (string) $item->description;
                    $time = date('d/m/Y H:i',strtotime($item->pubDate));
                    $totalPosts[] = array($title,$description,$time);
                }
            }

            function timecomp($a,$b) {
                return strtotime($b[2])-strtotime($a[2]);
            }
            uasort($totalPosts,'timecomp');


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


                // populate DOM
                echo '<div class="post post-type-article"><div class="post-content"><h3>'.$single[0].'</h3><p>'.$single[1].'</p></div><div class="post-meta">'.$single[2].'</a></div></div>';

            }



            if ($postCount > $loopLimit) {
                echo '<form action="" method="post" class="load-more"><input type="text" value="'.$loadMoreLink.'" id="pullmore" name="pullmore"><label for="submit-next" class="read-more">'.$loc_loop_loadMore.'</label><input type="submit" id="submit-next"></form>';
            }

        }
        ?>

    </div>
</body>
</html>
