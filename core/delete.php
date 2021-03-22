<?php
session_start();

if (isset($_SESSION["in"]) && $_SESSION["in"] === 1 && $_GET["id"] !== "" && $_GET["type"] !== "") { 
    include("data.php");
    $dirname = $_GET["id"];
    $type = $_GET["type"];
    array_map('unlink', glob("../p/".$dirname."/*"));
    $oldGlobal = array_filter(explode("|",file_get_contents("indexes/global")));
    $newGlobal = implode("|",array_diff($oldGlobal, [$_GET["id"]]));
    file_put_contents("indexes/global",$newGlobal."|");
    $oldType = array_filter(explode("|",file_get_contents("indexes/".$type)));
    $newType = implode("|",array_diff($oldType, [$_GET["id"]]));
    if ($newType === "") {
        array_map('unlink', glob("indexes/".$type));
    } else {
        file_put_contents("indexes/".$type,$newType."|");
    }
    
    rmdir("../p/".$dirname);
 } 

header("Location: ../");
?>
