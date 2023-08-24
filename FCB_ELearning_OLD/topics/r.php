<?php 
use classes\Q;
use classes\url;
use classes\pptx;
use classes\Question\Group;
use classes\QuizPrepare;
use classes\user;
use classes\Date as Date2;
use classes\notification;
use classes\topics;
require_once '../___autoload.php';
class App {
  private $route = '';
  public function __construct() {
    $this->route = isset($_GET['r']) ? $_GET['r'] : '';
  }
  public function Run() {
    switch ($this->route) {
      case 'get-topic':
        $id = url::post('id');
        $type = url::post('type');
        $q = new Q();
        $ppt = new pptx();
        $ppt->status = '1';
        $ppt->subTopicId = $id;
        $data = $ppt->get();
        $q->type = $type;
        $q->objId = $id;
        print json_encode(['head' => $q->get(), 'res' => $data]);
      break;
      default:
      case 'pptx':
        $id = url::post('id');
        $type = url::post('type');
        $ppt = new pptx();
        $ppt->status = '1';
        $ppt->subTopicId = $id;
        $data = $ppt->get();
        print json_encode($data);
      break;
      case 'upload-ppsx':
        $id = url::post('id');
        $ppt = new pptx();
        $ppt->subTopicId = $id;
        $ppt->status = '1';
        $info = $ppt->get();

        $top = new topics();
        $fdata = $top->getSubData($id);
        if (count($fdata) <= 0)
          exit;
        if (count($info) > 0) {
          if (isset($_FILES['file']['name']) || !empty($_FILES['file']['name'])) {
            if ($_FILES['file']['type'] == 'application/vnd.openxmlformats-officedocument.presentationml.slideshow') {
              $name = $info[0]['name'].'.ppsx';
              if (copy($_FILES['file']['tmp_name'], '../_res/ppts/'.$name)) {
                /*
                ** Upload success
                */
                print 'uploaded';
                $notif = new notification();
                $notif->write([
                  "msg" => "New topics added \"" . $fdata[0]["sub_title"] . "\" in ".$fdata[0]["cat_title"]." category",
                  "code" => "TOPIC",
                  "id" => [
                    "main_id" => $fdata[0]["main_id"],
                    "cat_id" => $fdata[0]["cat_id"],
                    "sub_id" => $fdata[0]["sub_id"]
                  ]
                ]);
              }
            }
          }
        }
      break;
      case 'upload-ppsx-cover':
        $id = url::post('id');
        $ppt = new pptx();
        $user = new user();
        $ppt->subTopicId = $id;
        $ppt->status = '1';
        $info = $ppt->get();
        // form form
        $finalname = "";
        $flag = false;
        $n = '';
        if (isset($_FILES['file']['name']) || !empty($_FILES['file']['name'])) {
          if (in_array($_FILES['file']['type'], ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])) {
            $n = $_FILES['file']['name'];
            $n = explode('.', $n);
            $name = date('YmdHis').mt_rand(1000,9999).mt_rand(1000, 9999).time().mt_rand(1000000, 9999999).mt_rand(10000,99999);
            $finalname = $name.'.'.end($n);
            $flag = true;
          }
        }
        if (!$flag)exit;
        $original_name = $_FILES['file']['name'];
        ////////////////from form
        if (count($info) > 0) {
          $finalname = $info[0]['name'];
        } else {
          // create a new background cover
          $ppt = new pptx();
          $ppt->subTopicId = $id;
          $ppt->status = '1';
          $ppt->name = $finalname;
          $ppt->uid = $user->idno;
          $ppt->dt = Date2::getDate();
          $ppt->originalName = $original_name;
          $ppt->save();
        }
        if (copy($_FILES['file']['tmp_name'], '../_res/pptcover/'.$finalname)) {
          /*
          ** Upload success
          */
          print 'uploaded';
        }
      break;
      case 'Quiz/List':
        $obj = url::post('objid');
        $type = url::post('type');
        $group = new Group();
        $group->ObjId = $obj;
        $group->Status = '1';
        $group->QCCode = $type;
        $data = $group->getGroup();
        $len = count($data);
        $qp = new QuizPrepare(); // object of Quiz Preparation
        $gdata = [
          'group' => []
        ];
        for ($a = 0; $a < $len; $a++) {
          $id = $data[$a]['QGId'];
          $gdata['group'][$a]['id'] = $data[$a]['QGId'];
          $gdata['group'][$a]['code'] = $data[$a]['QCCode'];
          $gdata['group'][$a]['title'] = $data[$a]['Title'];
          $gdata['group'][$a]['objid'] = $data[$a]['ObjId'];
          $gdata['group'][$a]['dt'] = date('F d, Y H:i',strtotime($data[$a]['DateCreated']));
          $gdata['group'][$a]['item'] = [];
          // retrieving the data
          $qp->status = '1';
          $qp->group_id = $id;
          $resp = $qp->get();
          $rlen = count($resp);
          for ($b = 0; $b < $rlen; $b++) {
            $gdata['group'][$a]['item'][$b]['id'] = $resp[$b]['id'];
            $gdata['group'][$a]['item'][$b]['title'] = $resp[$b]['title'];
            $gdata['group'][$a]['item'][$b]['itemno'] = $resp[$b]['itemno'];
            $gdata['group'][$a]['item'][$b]['timelimit'] = $resp[$b]['timelimit'];
          }
          print json_encode($gdata);
        }
      break;
    }
  }
}
$app = new App();
$app->Run();