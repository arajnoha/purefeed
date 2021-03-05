<?php
include("core/data.php");
$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$url .= $_SERVER['SERVER_NAME'];
$url .= htmlspecialchars($_SERVER['REQUEST_URI']);
$blogURL = (dirname($url));

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

 header( "Content-type: text/xml");
 
 echo "<?xml version='1.0' encoding='UTF-8'?>
 <rss version='2.0'>
 <channel>
 <title>".$siteName."</title>
 <link>".$blogURL."</link>
 <description>".$siteDescription."</description>";
 
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
    echo "<item>";
    if ($single["type"] === "status") {
        echo "<title>New text post:</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
        echo "<description>".$single['content']."</description>";

    } else if ($single["type"] === "image") {
        echo "<title>New photo from ".$siteName."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";

    } else if ($single["type"] === "article") {
        echo "<title>".$single['title']."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
        echo "<description>".$single['perex']."</description>";

    } else if ($single["type"] === "gallery") {
        echo "<title>New gallery of photos from ".$siteName."</title>";
        echo "<link>".$blogURL."/p/".$single['timestamp']."</link>";
    }
    echo "</item>";
}
 echo "</channel></rss>";
?>