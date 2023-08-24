<?php 
namespace classes;
use classes\key;
use sql;
use classes\Date as Date2;
use classes\lib\sql as sql2;
use classes\Factory\Statistics;

class user implements Statistics {
  private static $flag = null;

  private $key = null;
  public $branch_no = '';
  public $ulevel = '';
  public $fname = '';
  public $lname = '';
  public $idno = '';
  public $branch = '';
  public $position = '';
  public $branchDes = '';
  public $branchCode = '';
  private $online_time = 60;
  public static function getInstance() {
    if (!isset(self::$flag))
      self::$flag = new user();
    return self::$flag;
  }

  public function __construct() {
    $this->key = new key();
    $this->branch_no = $this->key->branch_no;
    $this->ulevel = $this->key->ulevel;
    $this->fname = $this->key->fname;  
    $this->lname = $this->key->lname;
    $this->idno = $this->key->idno;
    $this->branch = $this->key->branch;
    $this->myIp = $_SERVER['REMOTE_ADDR'];
    $this->sql = sql2::getInstance();
  }
  private function __accessLevel() {
    $st = sql::netlinkz()->prepare('select des from access_level where ulevel = :level');
    $st->bindParam(':level', $this->ulevel);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    if (count($data)) {
      $this->position = $data[0]['des'];
    }
  }
  private function __branches() {
    $st = sql::netlinkz()->prepare('select des, code from branches where code = :c');
    $st->bindParam(':c', $this->branch);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    if (count($data)) {
      $this->branchDes = $data[0]['des'];
      $this->branchCode = $data[0]['code'];
    } 
  }
  private function __users() {
    $st = sql::netlinkz()->prepare('select des, code from branches where code = :c');
    $st->bindParam(':c', $this->branch);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    if (count($data)) {
      $this->branchDes = $data[0]['des'];
      $this->branchCode = $data[0]['code'];
    } 
  }
  /* 
    if you need a description of the position and branchess call the method load
    example: 
    $userObj = new user();
    $userObj->load(); //method call
    print($userObj->position);
    print($userObj->branchDes);
  */
  public function count() {
    $data = $this->sql->exec("select count(uid) as total from user_online");
    return isset($data[0]["total"]) ? $data[0]["total"] : "";
  }
  public function getOnline() {
    $time = time() - $this->online_time;
    $data = $this->sql->exec('select * from user_online where (dt > :dt) and uid != :uid order by dt desc', [$time, $this->idno]);
    return $data;
  }
  public function getOffline() {
    $time = time() - $this->online_time;
    $data = $this->sql->exec('select * from user_online where (dt < :dt) and uid != :uid order by dt desc', [$time, $this->idno]);
    return $data;
  }
  public function searchOnline(string $search = ""):array {
    $data = $this->sql->exec("select uo.uid, uo.dt, uo.name,uo.branch from user_online as uo 
      where 
        uo.name like :name or 
        uo.uid like :id 
      order by uo.dt desc", [
      "%" . $search . "%",
      $search
    ]);
    $data_len = count($data);
    $time = time() - $this->online_time;;
    for ($i = 0; $i < $data_len; $i++) {
      $data[$i]["status"] = $data[$i]["dt"] > $time ? 1 : 0;
    }
    return $data; 
  }
  public function online() {
    $data = $this->sql->exec('select * from user_online where uid = :uid', [$this->idno]);
    if (count($data) > 0) {
      // update
      date_default_timezone_set("Asia/Manila");
      $data = $this->sql->exec('update user_online set name = :name, branch = :branch, dt = :dt where uid = :uid', [$this->fname.' '.$this->lname, $this->branch, time(), $this->idno]);
    }else {
      // insert new online
      $this->sql->exec('insert into user_online(uid, dt, name, branch) values(:uid, :dt, :name, :branch)', [$this->idno, time(), $this->fname.' '.$this->lname, $this->branch]);
    }
  }
  public function load() {
    $this->__accessLevel();
    $this->__branches();
  }
  public function search() {
    if (!is_numeric($this->idno)) {
      $st = sql::netlinkz()->prepare("select top 20 b.des, u.idno, u.fname, u.lname, u.branch  from users as u
      inner join branches as b on u.branch = b.code
      where u.fname like :fname or u.lname like :lname");
      $st->bindParam(":fname", $this->fname);
      $st->bindParam(":lname", $this->lname);
      $st->execute();
    } else {
      $st = sql::netlinkz()->prepare("select top 20 b.des, u.idno, u.fname, u.lname, u.branch  from users as u 
      inner join branches as b on u.branch = b.code
      where u.idno = :idno");
      $st->bindParam(":idno", $this->idno);
      $st->execute();
    }
    return $st->fetchAll();
  }

  // public function searchChatBox() {
  //   $search = "%".$this->search."%";
  //   $st =  sql::con1()->prepare('select name from user_online where (name like :name) order by dt desc');
  //   $st->bindParam(":name", $search);
  //   $st->execute();
  //   $data = $st->fetchall(sql::assoc());
  //   $data_len = count($data);
  //   $st = null;
  //   return $data;
  // }

}