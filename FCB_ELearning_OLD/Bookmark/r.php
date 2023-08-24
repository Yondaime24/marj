<?php 
require_once '../___autoload.php';
class app {
  public function __construct() {
    $this->route = isset($_GET["route"]) ? $_GET["route"] : "";
  }
  public function run() {
    switch($this->route) {
      case "add":
        print "aad";
      break;
    }
  }
}
$app = new app();
$app->run();