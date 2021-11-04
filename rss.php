<?php
include("core/data.php");
include("core/l10n/".$siteLanguage.".php");
$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$url .= $_SERVER['SERVER_NAME'];
$url .= htmlspecialchars($_SERVER['REQUEST_URI']);
$blogURL = (dirname($url));

 header( "Content-type: text/xml");
 
 echo "<?xml version='1.0' encoding='UTF-8'?>
 <rss version='2.0'>
 <channel>
 <title>".$siteName."</title>
 <link>".$blogURL."</link>
 <description>".$siteDescription."</description>";
 
$postArray = array_reverse(array_filter(explode("|",file_get_contents("core/indexes/global"))));

$globalArray = [];
foreach ($postArray as $single) {
    $singleArray = json_decode(file_get_contents("p/".$single."/meta.json"), true);
    array_push($globalArray, $singleArray);
}

foreach($globalArray as $single) {
    echo "<item>";
    if ($single["type"] === "status") {
        echo "<title>".$loc_rss_newPost."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
        echo "<description>".$single['content']."</description>";

    } else if ($single["type"] === "image") {
        echo "<title>".$loc_rss_newPhoto.$siteName."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";

    } else if ($single["type"] === "article") {
        echo "<title>".$single['title']."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
        echo "<description>".$single['perex']."</description>";

    } else if ($single["type"] === "gallery") {
        echo "<title>".$loc_rss_newGallery.$siteName."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
    }
    else if ($single["type"] === "video") {
        echo "<title>".$loc_rss_newVideo.$siteName."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
    }
    else if ($single["type"] === "audio") {
        echo "<title>".$loc_rss_newAudio.$siteName."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
    }
    echo "</item>";
}
 echo "</channel></rss>";
?>