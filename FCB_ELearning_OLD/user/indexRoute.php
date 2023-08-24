<?php 
use classes\DailyReads;
use classes\MotivationalTips;
use classes\user;
use classes\auth;
use classes\url;

require_once "../___autoload.php";
auth::isLogin();

class app {
    public function __construct() {
        $this->route = isset($_GET["route"]) ? $_GET["route"] : "";
      }
      public function run() {
        switch($this->route) {

            case "getDisplayedReads":
                $dailyreads = new DailyReads();
                $data = $dailyreads->getDisplayedReads();
                print json_encode($data);
             break;
             case "getDisplayedTips":
                $motivationaltips = new MotivationalTips();
                $data = $motivationaltips->getDisplayedTips();
                print json_encode($data);
             break;
             case "geNotDisplayedReads":
                $dailyreads = new DailyReads();
                $data = $dailyreads->getNotDisplayedReads();
                $data_len = count($data);
                for ($i = 0; $i < $data_len; $i++) {
                    $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_inserted"]));  
                  }
                print json_encode($data);
             break;
             case "geNotDisplayedTips":
                $motivationaltips = new MotivationalTips();
                $data = $motivationaltips->getNotDisplayedTips();
                $data_len = count($data);
                for ($i = 0; $i < $data_len; $i++) {
                    $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_uploaded"]));  
                  }
                print json_encode($data);
             break;
              // case "getOnline":
              //   $userOnline = new user();
              //   $data = [
              //     'online'  => $userOnline->getOnline(),
              //     'offline' => $userOnline->getOffline()
              //   ];
              //   print json_encode($data);
              // break;
              case "getOnline":
                $userOnline = new user();
                $data = $userOnline->getOnline();
                print json_encode($data);
              break;
              case "getOffline":
                $userOffline = new user();
                $data = $userOffline->getOffline();
                print json_encode($data);
              break;
              // case "displaySearch":
              //   $displaysearch = new user();
              //   $displaysearch->search = url::post("search");
              //   $data = $displaysearch->searchChatBox();
              //   print json_encode($data);
              // break;
        }
    }
}
$app = new app();
$app->run();