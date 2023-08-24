<?php 
namespace classes;

class Html {
  public static function escape(&$str) {
    $str = htmlspecialchars($str);
  }
  public static function escape_r($str) {
    return htmlspecialchars($str);
  }
}