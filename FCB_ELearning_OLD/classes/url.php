<?php
namespace classes;

class url {
  public static function get($key) {
    if (isset($_GET[$key])) return $_GET[$key]; exit;
  }
  public static function post($key) {
    if (isset($_POST[$key])) return $_POST[$key]; exit;
  }
}