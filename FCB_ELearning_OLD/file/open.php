<?php 
use classes\url;
use classes\topiccategories as Category;
use classes\TopicData;
require "../___autoload.php";
$route = isset($_GET["route"]) ? $_GET["route"] : "";

switch ($route) {
  case "cat":
    $cat = new Category();
    $id = isset($_GET["id"]) ? $_GET["id"] : "";
    $cat->id = $id;
    $cat->file();  
  break;
  case "sub-topic-cover":
    header("Content-Type:image/png");
    $id = url::get("id");
    $tp = new TopicData();
    $tp->id = $id;
    $cover = $tp->getFileCover();
    $b = count($cover) > 0  ? $cover[0]["cover"] : "";
    if (empty($b)) {
      // default logo
      print file_get_contents("../assets/images/feed-logo.png");
    } else {
      print base64_decode($b);
    }
  break;
}