<?php
namespace classes;
use sql;
use classes\notification;
use classes\user;
use classes\Date as Date2;
class mynotification extends notification {
  public function __construct() {
    $this->user = user::getInstance();
    $this->dt = Date2::getDate();
  }
  public function check() {
    // check if there is a notificatio 
    $st = sql::con1()->prepare("
    select
    * 
    from notification as notif
    left join my_notification as mynotif
    on mynotif.notif_id = notif.id and mynotif.uid = :uid
    where mynotif.notif_id is null or mynotif.notif_id = ''
    ");
    $st->bindParam(":uid", $this->user->idno);
    $st->execute();
    $data = $st->fetchAll();
    $len = count($data);
    for ($i = 0; $i < $len; $i++) {
      $msg = $data[$i]["notif_data"];
      $date = $data[$i]["dt"];
      $id = $data[$i]["id"];
      //$this->set($id);
    }
    return $data;
  }
  public function exist($id) {
    $st = sql::con1()->prepare("select * from my_notification where notif_id = :id and uid = :uid");
    $st->bindParam(":id", $id);
    $st->bindParam(":uid", $this->user->idno);
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;
  }
  public function set($notif_id) {
    // this function is used to set the new incoming nofication from the notication table 
    if (!$this->exist($notif_id)) {
    $st = sql::con1()->prepare("
      insert into my_notification
      (notif_id, uid, date_received)
      values(:id, :uid, :dt)
      ");
      $st->bindParam(":id", $notif_id);
      $st->bindParam(":uid", $this->user->idno);
      $st->bindparam(":dt", $this->dt);
      $st->execute();
    }
  }
  public function get($off = 1) {
    // get the notification if there is a notification
    $data = $this->check();
    $len = count($data);
    for ($i = 0; $i < $len; $i++) {
      $msg = $data[$i]["notif_data"];
      $date = $data[$i]["dt"];
      $id = $data[$i]["id"];
      $this->set($id);
    }
    //////////
    $off = $off < 1 ? 1 : (int)$off;
    if ($off > 1)
      $off = $off * 10;
    $st = sql::con1()->prepare("
    select
    top 10 start at :off 
    * 
    from notification as notif
    left join my_notification as mynotif
    on mynotif.notif_id = notif.id and mynotif.uid = :uid
    where mynotif.notif_id is not null or not mynotif.notif_id = ''
    order by dt desc
    ");
    $st->bindParam(":uid", $this->user->idno);
    $st->bindParam(":off", $off);
    $st->execute();
    $data = $st->fetchAll();
    $len = count($data);
    $d = [];
    for ($i = 0; $i < $len; $i++) {
      $d[$i]["msg"] = $data[$i]["notif_data"];
      $d[$i]["dt"] = $data[$i]["dt"];
      $d[$i]["dt_ago"] = Date2::Ago($data[$i]["dt"]);
      $d[$i]["dt_recv"] = $data[$i]["date_received"];
      $d[$i]["dt_recv_ago"] = Date2::Ago($data[$i]["date_received"]);
    } 
    return $d;
  }
}