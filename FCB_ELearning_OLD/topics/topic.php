<?php 
use classes\topics;
use classes\pptx;
use classes\url;
use classes\subtopics;
use classes\auth;

require '../___autoload.php';
auth::isLogin();

$route = isset($_GET["route"]) ? $_GET["route"] : "";
function app($route) {
  if ($route == "ListTopics") {
    $topicobj = new topics();
    $data = $topicobj->get();
    print json_encode($data);
  } else if ($route == 'powerpoint') {
    $topic_id = url::post('topic_id');
    $pptxo = new pptx();
    $pptxo->topic_id = $topic_id;
    $data = $pptxo->get();
    print json_encode($data);
  } else if ($route == 'save') {
    $t = new topics();
    $t->catid = url::post('catid');
    $t->id = url::post('id');
    $t->title = url::post('title');
    $t->des = url::post('des');
    $t->createdby = 1;
    $t->createdon = date('Y-m-d h:i:s');
    $t->status = 1;
    if (empty(trim($t->title))) {
      print json_encode(["ok" => false, "msg" => "Title Must Not Empty"]);
      exit;
    }
    print json_encode($t->submit());
  } else if ($route == 'trash') {
    $t = new topics();
    $t->id = url::post("id");
    $t->status = 0;
    print json_encode($t->trash());
  } else if ($route == "topicpanel") {
    $t = new topics();
    $t->catid = url::post("catid");
    $t->status = 1;
    print json_encode($t->get());
  } else if ($route == "sub_topic_save") {
    $sub = new subtopics();
    $sub->id = url::post("id");
    $sub->title = url::post("title");
    $sub->des = url::post("des");
    $sub->status = 1;
    $sub->createdby = 1;
    $sub->topic_id = url::post("topic_id");
    $sub->createdon = date("Y-m-d h:i:s");
    print json_encode($sub->submit()); 
  } else if ($route == "subtopicdata") {
    $sub = new subtopics();
    $sub->status = 1;
    $sub->topic_id = url::post("topic_id");
    print json_encode($sub->get());
  } else if ($route == "sub_topic_trash") {
    $sub = new subtopics();
    $sub->id = url::post("id");
    $sub->status = 0;
    print json_encode($sub->trash());
  } else if ($route == "uploadPPTX") {
    if (!isset($_FILES["pptx"]["name"]))
      return print "Invalid";
    if (empty($_FILES["pptx"]["name"]))
      return print "Name is empty";
    if ($_FILES["pptx"]["type"] != 'application/vnd.openxmlformats-officedocument.presentationml.presentation')
      return print "File is Invalid";
    $fname = explode(".", $_FILES["pptx"]["name"]);
    $ext = end($fname);
    $newname = md5($_FILES["pptx"]["name"].mt_rand(1000000, 9999999).time());
    $newname = $newname.".".$ext;
    if(copy($_FILES["pptx"]["tmp_name"], "../data/".$newname)) {
      $ppt = new pptx();
      $ppt->name = $newname;
      $ppt->uid = ""; //username 
      $ppt->dt = date("Y-m-d h:i:s");
      $ppt->originalName = $_FILES["pptx"]["name"];
      $ppt->status = 1;
      $ppt->subTopicId = url::post("subTopicId");
      $ppt->save();
      return print "File Uploaded successfully";  
    }
  } else if ($route == "pptxList") {
    $ppt = new pptx();
    $ppt->status = 1;
    $ppt->subTopicId = url::post("subTopicId");
    print json_encode($ppt->get());
  } else if ($route == "trashPptxFile") {
    $ppt = new pptx();
    $ppt->status = '0';
    $ppt->id = url::post("pptxId");
    if ($ppt->trash()) 
      return print "Removed Successfully";
  } else if ($route == 'getSubTopics') {
    $topicId = url::post('topicId');
    $st = new subtopics();
    $ppt = new pptx();
    $st->status = '1';
    $st->topic_id = $topicId;

    $data = $st->get();
    $len = count($data);

    $gdata = [];
    $ppt->status = '1';

    for ($i = 0; $i < $len; $i++) {
      $ppt->subTopicId = $data[$i]['id'];
      $gdata[$i] = [$data[$i], $ppt->get()];
    }
    print json_encode($gdata);
  }
}

app($route);