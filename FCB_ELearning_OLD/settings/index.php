<?php 
use classes\topiccategories;
use classes\url;

require '../___autoload.php';
$route = isset($_GET["route"]) ? $_GET["route"] : "";
if ($route == 'submitCat') {
  $s = new topiccategories();
  $s->title = url::post('title');
  $s->des = url::post('des');
  $s->status = 1;
  $s->createdon = date('Y-m-d h:i:s');
  $s->createdby = '1';
  $s->id = url::post('id');
  if (empty($s->title)) {
    print json_encode(['ok' => 1, 'msg' => 'Name is required']);
    exit;
  }
  print json_encode($s->submit());
} else if ($route == 'getCat') {
  $s = new topiccategories();
  print json_encode($s->getall());
} else if ($route == 'trash') {
  $s = new topiccategories();
  $s->status = 0;
  $s->id = url::post('id');
  print json_encode($s->status());
}
