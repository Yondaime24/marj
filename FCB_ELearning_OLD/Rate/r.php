<?php 
use classes\FeedRate;
use classes\Date as Date2;
require_once "../___autoload.php";
class app {
  public function __construct() {
    $this->route = isset($_GET["route"]) ? $_GET["route"] : "";
  }
  public function run() {
    switch($this->route) {
      case "history":
        $resp = [
          "current" => [],
          "history" => []
        ];
        $feedRate = new FeedRate();
        $resp["current"] = $feedRate->getLastRate();
        $data = $feedRate->getHistory();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["date_rated"]);
          $data[$i]["dt"] = date("F d, Y H:i:s", strtotime($data[$i]["date_rated"]));  
        }
        $resp["history"] = $data;
        print json_encode($resp);
      break;
      case "average":
        $feedRate = new FeedRate();
        $data = $feedRate->getAllLastRate();
        print json_encode($data);
      break;
      case "count":
        $feedRate = new FeedRate();
        $data = $feedRate->getAllRateCount();
        print json_encode($data);
      break;
      case "5star":
        $feedRate = new FeedRate();
        $data = $feedRate->get5StarRate();
        print json_encode($data);
      break;
      case "4star":
        $feedRate = new FeedRate();
        $data = $feedRate->get4StarRate();
        print json_encode($data);
      break;
      case "3star":
        $feedRate = new FeedRate();
        $data = $feedRate->get3StarRate();
        print json_encode($data);
      break;
      case "2star":
        $feedRate = new FeedRate();
        $data = $feedRate->get2StarRate();
        print json_encode($data);
      break;
      case "1star":
        $feedRate = new FeedRate();
        $data = $feedRate->get1StarRate();
        print json_encode($data);
      break;
      case "recentCmt":
        $resp = [
          "recentCmt" => []
        ];
        $feedRate = new FeedRate();
        $data = $feedRate->getRecentCmt();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["date_rated"]);
          $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_rated"]));  
        }
        $resp["recentCmt"] = $data;
        print json_encode($resp);
      break;
      case "recentCmt5":
        $resp = [
          "recentCmt5" => []
        ];
        $feedRate = new FeedRate();
        $data = $feedRate->getRecentCmt5();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["date_rated"]);
          $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_rated"]));  
        }
        $resp["recentCmt5"] = $data;
        print json_encode($resp);
      break;
      case "recentCmt4":
        $resp = [
          "recentCmt4" => []
        ];
        $feedRate = new FeedRate();
        $data = $feedRate->getRecentCmt4();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["date_rated"]);
          $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_rated"]));  
        }
        $resp["recentCmt4"] = $data;
        print json_encode($resp);
      break;
      case "recentCmt3":
        $resp = [
          "recentCmt3" => []
        ];
        $feedRate = new FeedRate();
        $data = $feedRate->getRecentCmt3();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["date_rated"]);
          $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_rated"]));  
        }
        $resp["recentCmt3"] = $data;
        print json_encode($resp);
      break;
      case "recentCmt2":
        $resp = [
          "recentCmt2" => []
        ];
        $feedRate = new FeedRate();
        $data = $feedRate->getRecentCmt2();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["date_rated"]);
          $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_rated"]));  
        }
        $resp["recentCmt2"] = $data;
        print json_encode($resp);
      break;
      case "recentCmt1":
        $resp = [
          "recentCmt1" => []
        ];
        $feedRate = new FeedRate();
        $data = $feedRate->getRecentCmt1();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]["ago"] = Date2::Ago($data[$i]["date_rated"]);
          $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_rated"]));  
        }
        $resp["recentCmt1"] = $data;
        print json_encode($resp);
      break;
    }
  }
}
$app = new app();
$app->run();