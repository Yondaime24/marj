<?php 
namespace classes;
use classes\key;
class auth {
  public static function isLogin() {
    $key = new key();
    $type = strtolower($_SERVER["REQUEST_METHOD"]);
    if (!isset($_COOKIE['PHPSESSID'])) {
      if ($type == "get") {
        print 'You are not logged In! Go to <a href="'.NETLINKZ_URL.'">Netlinkz</a>';
      } else if ($type == "post") {
        print '{"ok" : 0, "msg" => "You are not signed in!"}';
      }
      exit;
    }
    if ($_COOKIE['PHPSESSID'] != $key->session) {
      if ($type == "get") {
        print 'You are not logged In! Go to <a href="'.NETLINKZ_URL.'">Netlinkz</a>';
      } else if ($type == "post") {
        print '{"ok" : 0, "msg" => "You are not signed in!"}';
      }
      exit;
    }
    if (empty($key->idno) && empty($key->fname) && empty($key->lname) && empty($ulevel) && empty($branch_no) && empty($branch) && empty($key->expire)) {
      if ($type == "get") {
        print 'You are not logged In! Go to <a href="'.NETLINKZ_URL.'">Netlinkz</a>';
      } else if ($type == "post") {
        print '{"ok" : 0, "msg" => "You are not signed in!"}';
      }
      exit;
    }
  }
}