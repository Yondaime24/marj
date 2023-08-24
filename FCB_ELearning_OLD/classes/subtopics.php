<?php 
namespace classes;
use sql;
use classes\Date as Date2;
use classes\user;
use classes\Html;
use classes\lib\sql as sql2;
use classes\Factory\Statistics;

class subtopics implements Statistics{
  public $id = "";
  public $topic_id = "";
  public $title = "";
  public $des = "";
  public $createdby = "";
  public $createdon = "";

  public function __construct() {
    $this->feed = sql2::getInstance();
    
    $this->user = user::getInstance();
    $this->status = '1';
    $this->dt = Date2::getDate();
    $this->uid = $this->user->idno;
  }
  public function save() {
    $st = sql::con1()->prepare("insert into sub_topics(title, des, createdby, createdon, topic_id, status) values(:title,:des,:createdby, :createdon, :topic_id, :status)");
    $st->bindParam(":title", $this->title);
    $st->bindParam(":des", $this->des);
    $st->bindParam(":createdby", $this->uid);
    $st->bindParam("createdon", $this->dt);
    $st->bindParam(":topic_id", $this->topic_id);
    $st->bindParam(":status", $this->status);
    $st->execute();
    return ["type" => "insert", "ok" => 1, "msg" => "New Subtopics Added"];
  }
  public function existOnSave() {
    $st = sql::con1()->prepare("select id from sub_topics where topic_id = :topic_id and title = :title and status = 1"); //EDITED
    $st->bindParam(":title", $this->title);
    $st->bindParam(":topic_id", $this->topic_id);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return count($data) > 0 ? true : false;
  }
  public function existOnUpdate() {
    $st = sql::con1()->prepare("select id from sub_topics where topic_id = :topic_id and title = :title and not id = :id");
    $st->bindParam(":topic_id", $this->topic_id);
    $st->bindParam(":title", $this->title);
    $st->bindParam(":id", $this->id);
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;
  }
  public function update() {
    $st = sql::con1()->prepare("update sub_topics set title = :title, des = :des, topic_id = :topic_id where id = :id and status = :status");
    $st->bindParam(":title", $this->title);
    $st->bindParam(":des", $this->des);
    $st->bindParam(":topic_id", $this->topic_id);
    $st->bindParam(":id", $this->id);
    $st->bindParam(":status", $this->status);
    $st->execute();
    return ["type" => "update", "ok" => 1, "msg" => "Updated!"];
  }
  public function getInfo() {
    $st = sql::con1()->prepare("select * from sub_topics where id = :id and status = :status");
    $st->bindParam(":id", $this->id);
    $st->bindParam(":status", $this->status);
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function get() { 
    $data = $this->feed->exec('select * from sub_topics where topic_id = :topic_id and status = :status', [$this->topic_id, $this->status]);
    $data_len = count($data);
    for ($i = 0; $i < $data_len; $i++) {
      Html::escape($data[$i]["title"]);
    }
    return $data;
  }
  public function count() {
    $data = $this->feed->exec("select count(id) as total from sub_topics where status = :status", [$this->status]);
    return isset($data[0]["total"]) ? $data[0]["total"] : "";
  }
  //form submitted
  public function submit() {
    if (empty($this->id)) {
      //insert record
      if ($this->existOnSave()) return ["type" => "insert", "ok" => 0, "msg" => "Subtopics Already Exists"];
      return $this->save();
    } else {
      //update record
      if ($this->existOnUpdate()) return ["type" => "update", "ok" => 0, "msg" => "Subtopics Already Exists in other table"];
      return $this->update();
    }
  }
  public function trash() {
    $st = sql::con1()->prepare("update sub_topics set status = :status where id = :id");
    $st->bindParam(":status", $this->status);
    $st->bindParam(":id", $this->id);
    $st->execute();
    return ["type" => "delete", 'ok' => true, 'msg' => 'Removed!'];
  }

  public function getFinSub(){
    $st =  sql::con1()->prepare('select td.id, td.uid from topic_data as td left join usertask as ut on ut.objid=td.id where td.uid=:uid and td.objid=(select objid from usertask where objid=td.objid and objtype=\'sub\')');
    $st->bindParam(":uid", $this->user->idno);
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;

    $st2 =  sql::con1()->prepare('select td.id, td.uid from topic_data as td right join usertask as ut on ut.objid=td.id where td.uid=:uid');
    $st2->bindParam(":uid", $this->user->idno);
    $st2->execute();
    $data2 = $st2->fetchall(sql::assoc());
    $st2 = null;

    if($data === $data2){
      return('equal');
    }
  }

}