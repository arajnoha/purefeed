<?php
session_start();

if (isset($_SESSION["in"]) && $_SESSION["in"] === 1 && $_GET["id"] !== "") { 
    include("data.php");
    $dirname = $_GET["id"];
    array_map('unlink', glob("../p/".$dirname."/*"));
    rmdir("../p/".$dirname);

 } 

header("Location: ../");
?>
