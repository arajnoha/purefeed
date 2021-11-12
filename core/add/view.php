<?php

if (!isset($_SESSION["visit"])) {
    $_SESSION["visit"] = 1;
    $viewFile = [];
    if (file_exists("../../view.json")) {
        $viewFile = json_decode(file_get_contents("../../view.json"), true);
    }
    $viewDate = date('Y-m-d');
    $viewFile[$viewDate] += 1;
    file_put_contents("../../view.json", json_encode($viewFile));
}

?>
