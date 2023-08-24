<?php
namespace classes;
use sql;
use classes\user;
use classes\Date as Date2;
class notification {
  public function __construct() {
    $this->dt = Date2::getDate();
    $this->status = "1";
    $this->user = user::getInstance();
    if (empty($this->user->idno)) {
      print 'I think you are not logged In yet!';
      exit;
    }
  }
  public function getlastid() {
    $st = sql::con1()->prepare("select max(id) as last_id from notification");
    $st->execute();
    $data = $st->fetchAll();
    return count($data)  > 0 ? ($data[0]["last_id"] + 1) : 1; 
  }
  public function write($msg) {
    $id = $this->getlastid();
    $msg = json_encode($msg);
    $st = sql::con1()->prepare("insert into notification
    (id, notif_data, dt, status, uid)
    values(:id, :data, :dt, :status, :uid)
    ");
    $st->bindParam(":id", $id);
    $st->bindParam(":data", $msg);
    $st->bindParam(":dt", $this->dt);
    $st->bindParam(":status", $this->status);
    $st->bindParam(":uid", $this->user->idno);
    $st->execute();
  }
}