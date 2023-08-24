<?php 
  use classes\url;
  use classes\mynotification as notif;
  use classes\user;
  use classes\lib\sql;
  require_once "../___autoload.php";
  class app {
    public function __construct() {
      $this->route = isset($_GET["route"]) ? $_GET["route"] : "";
      $this->con = sql::getInstance();
    }
    public function run() {
      switch ($this->route) {
        case "check":
          $n = new notif();
          $user = new user();
          $user->sql = $this->con;
          $user->online();
          print count($n->check());
        break;
        case "get":
          $n = new notif();
          print json_encode($n->get());
        break;
        default:
          echo "Invalid url";
        break;
      }
    }
  }
  $app = new app();
  $app->run();
?>