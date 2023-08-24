<?php 
use classes\url;
use classes\userlevel;
use classes\user;
use classes\Date as Date2;
require_once "../___autoload.php";
class app {
  public function __construct() {
    $this->route = isset($_GET["route"]) ? $_GET["route"] : "";
  }
  public function run() {
    switch($this->route) {
      case "search":
        $search = url::post("search");
        $user = new user();
        $user->fname = "%" . $search . "%";
        $user->lname = "%" . $search . "%";
        $user->idno = $search;
        echo json_encode($user->search());
      break;
      case "add":
        $user = new user();
        $ul = new userlevel();
        $ul->user_id = url::post("idno");
        $ul->status = 'active';
        $ul->dt = Date2::getDate();
        $ul->uid = $user->idno;
        $ul->submit();
      break;
      case "deactive":
        $user = new user();
        $ul = new userlevel();
        $ul->user_id = url::post("idno");
        $ul->status = "deactive";
        $ul->uid = $user->idno;
        $ul->dt = Date2::getDate();
        $ul->set_status();
      break;
      default:
      break;
    }
  }
}
$app = new app();
$app->run();