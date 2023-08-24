<?php
use classes\TimeSpent; 
@session_start();
/**
 * Configuration
 */
require_once "config/config.inc.php";
/**
 * End Configuration
 */
function classloader($class) {
  require_once $class.".php";
}
spl_autoload_register("classloader");

$script = isset($_SERVER["SCRIPT_NAME"]) ? $_SERVER["SCRIPT_NAME"] : "";
$script = explode("/", $script);
$script = end($script);
if (in_array($script, ["Topics.php"])) {
  $ts = new TimeSpent();
  $ts->type = "st";
  $ts->start();
} else {
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $ts = new TimeSpent();
    if (!$ts->is_close()) {
      $ts->type = "st";
      $st->start();
      $ts->close();
    }
  }
}