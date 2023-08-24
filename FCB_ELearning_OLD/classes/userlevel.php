<?php 
namespace classes;
use sql;
class userlevel {
  public function __construct() {
    $this->user_id = "";
    $this->status = "";
    $this->dt = "";
    $this->uid = "";
  }
  public function is_exist() {
    $st = sql::con1()->prepare("select user_id from admin_level where user_id = :idno");
    $st->bindParam(":idno", $this->user_id);
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;    
  }
  public function save() {
    $st = sql::con1()->prepare("insert into 
      admin_level(user_id, addedby, status, datecreated)
      values(:user_id, :creator, :status, :dt)
    ");
    $st->bindParam(":user_id", $this->user_id);
    $st->bindParam(":creator", $this->uid);
    $st->bindParam(":status", $this->status);
    $st->bindParam(":dt", $this->dt);
    $st->execute();
  }
  public function update() {
    $st = sql::con1()->prepare("update admin_level set status = :status where user_id = :uid");
    $st->bindParam(":status", $this->status);
    $st->bindParam(":uid", $this->user_id);    
    $st->execute();
  }
  public function submit() {
    if ($this->is_exist()) {
      // update
      $this->status = "active";
      $this->update();
    } else {
      // insert
      $this->save();
    }
  }
  public function set($code) {
    
  }
  public function set_status() {
    $st = sql::con1()->prepare("update admin_level set status = :status, removedby = :uid, dateremoved = :dt where user_id = :user_id");
    $st->bindParam(":status", $this->status);
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":dt", $this->dt);
    $st->bindParam(":user_id", $this->user_id);
    $st->execute();
  }
  public function net_search() {
    $st = sql::netlinkz()->prepare("select top 10 
      b.des, 
      u.ulevel, 
      u.branch, 
      u.fname, 
      u.lname,
      al.des as level 
      from users as u 
      inner join branches as b on b.code = u.branch 
      inner join access_level as al on al.ulevel = u.ulevel
      where fname ");
  }
  public function netlinkz_user($user_id) {
    $st = sql::netlinkz()->prepare("select 
      b.des, 
      u.ulevel, 
      u.branch, 
      u.fname, 
      u.lname,
      al.des as level 
      from users as u 
      inner join branches as b on b.code = u.branch 
      inner join access_level as al on al.ulevel = u.ulevel
      where idno = :id");
    $st->bindParam(":id", $user_id);  
    $st->execute();
    return $st->fetchAll();
  }
  public function getall() {
    $st = sql::con1()->prepare("select user_id from admin_level where status = :status");
    $st->bindParam(":status", $this->status);
    $st->execute();
    $arr = [];
    $data = $st->fetchAll();
    $len = count($data);
    for ($i = 0; $i < $len; $i++) {
      $user_data = $this->netlinkz_user($data[$i]["user_id"]);
      $arr[$i]["user_id"] = $data[$i]["user_id"];
      $arr[$i]["fname"] = isset($user_data[0]["fname"]) ? $user_data[0]["fname"] : "";
      $arr[$i]["lname"] = isset($user_data[0]["lname"]) ? $user_data[0]["lname"] : "";
      $arr[$i]["branch"] = isset($user_data[0]["branch"]) ? $user_data[0]["branch"] : "";
      $arr[$i]["des"] = isset($user_data[0]["des"]) ? $user_data[0]["des"] : "";
      $arr[$i]["level"] = isset($user_data[0]["level"]) ? $user_data[0]["level"] : "";
      $arr[$i]["ulevel"] = isset($user_data[0]["ulevel"]) ? $user_data[0]["ulevel"] : "";
    }
    return $arr;
  }
}