<?php 
use classes\url;
use classes\Bookmark\FeedBookmark;
use classes\auth;

require_once '../___autoload.php';
auth::isLogin();
class route {
  public function __construct() {
    $this->route = url::post('r');
  }
  public function run() {
    switch($this->route) {
      case 'add':
        $bm = new FeedBookmark();
        $bm->type = url::post('type');
        $bm->objid = url::post('id');
        $bm->title = url::post('title');
        print json_encode($bm->add());
      break;
      case 'my-bookmark':
        $bm = new FeedBookmark();
        print json_encode($bm->get());
      break;
      case 'delete':
        $bm = new FeedBookmark();
        $bm->type = url::post('type');
        $bm->objid = url::post('id');
        print json_encode($bm->delete());
      break;
    }
  }
}
$route = new route();
$route->run();