<?php 
/*
located at home in netlinkz
$data = [
  "branch_no" => isset($_COOKIE["branch_no"]) ? $_COOKIE["branch_no"] : "",
  "ulevel" => isset($_COOKIE["ulevel"]) ? $_COOKIE["ulevel"]: "",
  "fname" => isset($_COOKIE["fname"]) ? $_COOKIE["fname"]: "",
  "lname" => isset($_COOKIE["lname"]) ? $_COOKIE["lname"]: "",
  "idno" => isset($_COOKIE["idno"]) ? $_COOKIE["idno"] : "",
  "branch" => isset($_COOKIE["branch"]) ? $_COOKIE["branch"] : ""
] ;
$auth = json_encode($data);
$len = strlen($auth);
for ($i = 0; $i < $len; $i++) {
  $tmp = ord($auth[$i]) + 5;
  $auth[$i] = chr($tmp);

  $tmp = ~ord($auth[$i]);
  $auth[$i] = chr($tmp);
}
for ($i = 0; $i < 2; $i++)
  $auth = base64_encode($auth);
*/
namespace classes;
class key {
  public $branch_no = '';
  public $ulevel = '';
  public $fname = '';
  public $lname = '';
  public $idno = '';
  public $branch = '';
  public $expire = '';
  public $session = '';
  private function __decode($key) {
    for ($i = 0; $i < 2; $i++)
      $key = base64_decode($key);
    $len = strlen($key);
    for ($i = 0; $i < $len; $i++) {
      $tmp = ~ord($key[$i]);
      $key[$i] = chr($tmp);
      $tmp = ord($key[$i]) - 5;
      $key[$i] = chr($tmp);
    }
    return $key;
  }
  public function __construct() {
    //get all the tokens from the url GET s
    if (!isset($_GET['s'])) {
      $key = isset($_COOKIE['FEED17']) ? $_COOKIE['FEED17'] : "";
    } else {
      $key = $_GET['s'];
      //update the cookie
      setcookie("FEED17", $key, [
        "path" => "/"
      ]);
      unset($_GET['s']);
      header("location:index.php");
      exit;
    }
    $key = $this->__decode($key);
    $key = json_decode($key);
    $this->branch_no = isset($key->branch_no) ? $key->branch_no : "";
    $this->ulevel = isset($key->ulevel) ? $key->ulevel : "";
    $this->fname = isset($key->fname) ? $key->fname : "";  
    $this->lname = isset($key->lname) ? $key->lname : "";
    $this->idno = isset($key->idno) ? $key->idno : "";
    $this->branch = isset($key->branch) ? $key->branch : "";
    $this->expire = isset($key->expire) ? $key->expire : "";
    $this->session = isset($key->session) ? $key->session: '';
  }
  public static function init() {
      $data = [
        "branch_no" => "HED",
        "ulevel" => "PR",
        "fname" => "MARJON",
        "lname" => "CAJOCON",
        "idno" => "1533",
        "branch" => "HED",
        "expire" => date("Y-m-d h:i:s", time() + 28800),
        'session' => isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : '' 
      ] ;
      $auth = json_encode($data);
      $len = strlen($auth);
      for ($i = 0; $i < $len; $i++) {
        $tmp = ord($auth[$i]) + 5;
        $auth[$i] = chr($tmp);
        $tmp = ~ord($auth[$i]);
        $auth[$i] = chr($tmp);
      }
      for ($i = 0; $i < 2; $i++)
        $auth = base64_encode($auth);
      echo $auth;
  }
}