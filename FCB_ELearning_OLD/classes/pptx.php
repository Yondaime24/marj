<?php 
namespace classes;
use sql;
use classes\Date as Date2;
use classes\user;
class pptx {
  public $id = "";
  public $topicId = "";
  public $subTopicId = "";
  public $name = "";
  public $caption = "";
  public $des = "";
  public $originalName = "";
  public $status = "";
  public function __construct() {
    $this->user = user::getInstance();
    $this->dt = Date2::getDate();
    $this->status = '1';
    $this->uid = $this->user->idno;
  }
  public function get() {
    $st = sql::con1()->prepare('select * from pptx WHERE sub_topics_id = :tid and status = :status');
    $st->bindParam(':tid', $this->subTopicId);
    $st->bindParam(":status", $this->status);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return $data;
  }
  public function getInfo() {
    $st = sql::con1()->prepare("select * from pptx where id = :id");
    $st->bindParam(":id", $this->id);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return $data;
  }
  public function update() {
    
  }
  public function save() {
    $st = sql::con1()->prepare("insert into pptx(name, uid, dt, sub_topics_id, topic_id, caption, des, original_name, status) values(:name, :uid, :dt, :subTopicId, :topicId, :caption, :des, :originalName, :status)");
    $st->bindParam(":name", $this->name);
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":dt", $this->dt);
    $st->bindParam(":subTopicId", $this->subTopicId);
    $st->bindParam(":topicId", $this->topicId);
    $st->bindParam(":caption", $this->caption);
    $st->bindParam(":des", $this->des);
    $st->bindParam(":originalName", $this->originalName);
    $st->bindParam(":status", $this->status);
    $st->execute();
    return true;
  }
  public function trash() {
    $info = $this->getinfo();
    if (count($info) > 0) {
      $st = sql::con1()->prepare("update pptx set status = :status where id = :id");
      $st->bindParam(":status", $this->status);
      $st->bindParam(":id", $this->id);
      $st->execute();
      /* removing the actual file */
      unlink('../data/'.$info[0]["name"]);
    }
    return true;
  }
  public function submit() {
  }
}