<?php 
use classes\topics as MainTopic;
use classes\pptx;
use classes\url;
use classes\subtopics as SubTopic;
use classes\auth;
use classes\topiccategories as Category;

require '../___autoload.php';
auth::isLogin();

$route = isset($_GET["route"]) ? $_GET["route"] : "";
function App($route) {
  switch ($route) {
    case "Category":
      $category = new Category();
      $category->status = '1';
      $catData = $category->getall();
      print json_encode($catData);
      break;
    case "MainTopic":
      $mainTopic = new MainTopic();
      $mainTopic->catid = url::post("catId");
      $mainTopic->status = '1';
      print json_encode($mainTopic->get());
      break;
    case "SubTopic":
      $subTopic = new SubTopic();
      $subTopic->status = '1';
      $subTopic->topic_id = url::post("mainTopicId");
      print json_encode($subTopic->get());
    break;
    default:
      # code...
      break;
  }
}
App($route);