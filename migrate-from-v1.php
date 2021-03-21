<?php

if (!file_exists('core/indexes/')) {
    mkdir('core/indexes/', 0777, true);
}

$postPath = "p/*";
$postArray = glob($postPath, GLOB_ONLYDIR);

foreach ($postArray as $single) {
    $type = json_decode(file_get_contents($single."/meta.json"), true)["type"];
    $single = explode("p/",$single)[1];
    if ($type === "status") {
        file_put_contents("core/indexes/status",$single."|",FILE_APPEND);
    } else if ($type === "article") {
        file_put_contents("core/indexes/article",$single."|",FILE_APPEND);
    } else if ($type === "image" || $type === "gallery") {
        file_put_contents("core/indexes/image",$single."|",FILE_APPEND);

    }
    file_put_contents("core/indexes/global",$single."|",FILE_APPEND);
}

?>