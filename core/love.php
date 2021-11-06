<?php
  if (isset($_GET['post'])) {
      $id = $_GET['post'];
      $json = file_get_contents("../p/".$id."/meta.json");
      $data = json_decode($json, true);

    if (!isset($_COOKIE["love".$id])) {
        setcookie("love".$id, 1, time() + (86400 * 30 * 365 * 99), "/");
        $addingLove = $data;
        $loving = (int) $addingLove["love"];
        $loving++;
        $addingLove["love"] = $loving;
        file_put_contents("../p/".$id."/meta.json", json_encode($addingLove));
    } else if (isset($_COOKIE["love".$id])) {
        setcookie("love".$id, "", time() - 3600, "/");
        $addingLove = $data;
        $loving = (int) $addingLove["love"];
        $loving--;
        $addingLove["love"] = $loving;
        file_put_contents("../p/".$id."/meta.json", json_encode($addingLove));
    }
  }

  header('Content-type: image/png');
echo gzinflate(base64_decode('6wzwc+flkuJiYGDg9fRwCQLSjCDMwQQkJ5QH3wNSbCVBfsEMYJC3jH0ikOLxdHEMqZiTnJCQAOSxMDB+E7cIBcl7uvq5rHNKaAIA'));
?>
