<?php 
use classes\topiccategories as Category;
use classes\auth;
use classes\access;
use classes\url;
use classes\Image;
use classes\topics as MainTopic;
use classes\Date as Date2;
use classes\subtopics as SubTopic;
use classes\TopicData;
use classes\TimeSpent;
use classes\Task\User as Task;
use classes\notification;
use classes\topics;
require_once "../___autoload.php";
auth::isLogin();

class route {
  public function __construct() {
    $this->route = isset($_GET["r"]) ? $_GET["r"] : "";
    $this->category = new Category();
    $this->access = new access();
    $this->is_admin = $this->access->check();
  }
  public function run() {
    switch($this->route) {
      case "Category/Save":
        if ($this->is_admin) {
          $image = new Image();
          $this->category->id = url::post("cat_id");
          $this->category->title = url::post("title");
          $this->category->des = url::post("des");
          $file = isset($_FILES["file"]) ? $_FILES["file"] : [];
          if (!empty($file)) {
            $type = $_FILES["file"]["type"];
            if (in_array($type, ["image/jpeg", "image/png", "image/jpg"])) {
              $file_tmp = "";
              $temp_filename = "../tmp/".mt_rand(1000, 9999).time().mt_rand(1000000, 9999999).".png";
              $image->Scale(file_get_contents($_FILES["file"]["tmp_name"]), 480, $temp_filename);              
              $data = file_get_contents($temp_filename);
              $this->category->cover = base64_encode($data);
              unlink($temp_filename);
            }
          }
          print json_encode($this->category->submit());
        }
      break;
      case "category/list":
        $cat = new Category();
        $cat->status = '1';
        $cat->search = url::post("search");
        $data = $cat->search();
        print json_encode($data);
      break;
      case "Main/topic/list":
        $mainTopic = new MainTopic();
        $mainTopic->catid = url::post("catid");
        $data = $mainTopic->get();
        $data_len = count($data);
        $i = 0;
        while ($i < $data_len) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["createdon"]);
          $i++;
        }
        print json_encode($data);
      break;
      case "Sub/topic/list":
        $subTopic = new SubTopic();
        $subTopic->topic_id = url::post("topic_id");
        $data = $subTopic->get();
        $data_len = count($data);
        $i = 0;
        while ($i < $data_len) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["createdon"]);
          $data[$i]["createdon"] = date("Y-m-d H:i", strtotime($data[$i]["createdon"]));
          $i++;
        }
        $task = new Task();
        $task->taskMain(url::post('topic_id'))->done();
        print json_encode($data);
      break;
      case "SubTopic/load/ppsx":
        $tp = new TopicData();
        $tp->objid = url::post("id");
        $tp->topic_type = url::post("topic_type");
        $data = $tp->get();
        print json_encode($data);
      break;
      case "subtopic/upload/ppsx":
        if (!$this->is_admin) return;
        $notif = new notification();
        $id = url::post("id");
        $title = url::post("title");
        $type = url::post("type");
        $topic_type = url::post("topic_type");
        $data = "";
        $objid = url::post("objid");
        $tp = new TopicData();
        
        if ($type == "ppsx") {
          if (isset($_FILES["ppsx"]['name'])) {
            if (in_array($_FILES["ppsx"]["type"], ['application/vnd.openxmlformats-officedocument.presentationml.slideshow'])) {
              $data = base64_encode(file_get_contents($_FILES["ppsx"]["tmp_name"]));       
              // add new data
              $tp->id = $id;
              $tp->title = $title;
              $tp->objid = $objid;
              $tp->type = $type;
              $tp->data = $data;
              $tp->typic_type = $topic_type;
              print json_encode($tp->submit());
              $notif->write([
                'msg' => 'New topic presentation uploaded!',
                'code' => 'ST',
                'id' => $id
              ]);
            }
          }

        } else if ($type == "html") {

        }
      break;
      case "get/subtopic/data":
        $type = url::post('topic_type');
        $objid = url::post("objid");
        $task = new Task();
        $tp = new TopicData();
        $tp->topic_type = $type;
        $tp->objid = $objid;
        if ($type == 'sub') {
          $task->taskSub($objid)->done();
        }
        print json_encode($tp->get());
      break;
      case "sub/cover/pic/upload":
        if($this->is_admin) {
          $id = url::post("id");
          $tp = new TopicData();
          if (isset($_FILES["file"]["name"])) {
            if (in_array($_FILES["file"]["type"], ["image/png", "image/jpg", "image/jpeg"])) {
              $path = "../tmp/".mt_rand(1000, 9999).time().".png";
              $img = new Image();
              $img->Scale(file_get_contents($_FILES["file"]["tmp_name"]), 480, $path);
              $file_data = file_get_contents($path);
              unlink($path);
              $tp->id = $id;
              $tp->cover = base64_encode($file_data);
              $tp->updateCover();
            }
          }
        }
      break;
      case "topic/data/delete";
        if ($this->is_admin) {
          $tp = new TopicData();
          $tp->id = url::post("id");
          $tp->status = '0';
          print json_encode($tp->trash());
        }
      break;
      case "category/trash":
        if (!$this->is_admin) return;
        $c = new Category();
        $c->id = url::post("id");
        print json_encode($c->trash());
      break;  
      case "main/topic/save":
        if (!$this->is_admin) return;
        $mainTopic = new MainTopic();
        $id = url::post("id");
        $title = url::post("title");
        $des = url::post("des");
        $cat_id = url::post("cat_id");
        $mainTopic->id = $id;
        $mainTopic->title = $title;
        $mainTopic->des = $des;
        $mainTopic->catid = $cat_id;
        print json_encode($mainTopic->submit());
      break;
      case "main/topic/trash":
        if (!$this->is_admin) return;
        $mainTopic = new MainTopic();
        $mainTopic->id = url::post("id");
        $mainTopic->status = '0';
        print json_encode($mainTopic->trash());
      break;
      case "sub/topic/save":
        if (!$this->is_admin) return;
        $sub = new SubTopic();
        $sub->id = url::post("id");
        $sub->des = url::post("des");
        $sub->title = url::post("title");
        $sub->topic_id = url::post("topic_id");
        print json_encode($sub->submit());
      break;
      case "sub/topic/trash":
        if (!$this->is_admin) return;
        $sub = new SubTopic();
        $sub->id = url::post("id");
        $sub->status = '0';
        print json_encode($sub->trash());
      break;
      case "topic/interval/update":
        $ts = new TimeSpent();
        $ts->type = 'st';
        $ts->start(); 
        $ts->close();
      break;
      default:
        print "Invalid URL";
      break;
    }
  }
}
$route = new route();
$route->run();