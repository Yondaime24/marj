<?php 
use classes\Task;

class Route {
  
  public $url = null;

  public function run() {
    switch ($this->url) {
      case 'check':
        $task = new Task();
        print json_encode($task->active());
      break;
      case '':
      break;
      default:
        print 'Invalid URL';
      break;
    }
  }
}
require '../___autoload.php';

$route = new Route();
$route->url = isset($_GET['r']) ? $_GET['r'] : '';
$route->run();
