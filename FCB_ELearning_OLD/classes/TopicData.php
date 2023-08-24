<?php 
namespace classes;
use sql;
use classes\user;
use classes\Date as Date2;
use classes\Html;
use classes\lib\sql as sql2;

class TopicData {
  public function __construct() {
    $this->feed = sql2::getInstance();

    $this->user = user::getInstance();

    $this->dt = Date2::getDate();
    $this->uid = $this->user->idno;
    
    $this->type = ""; 
    $this->title = "";
    $this->id = "";
    $this->data = "";
    $this->cover = "";
    $this->status = "";
    $this->objid = "";
    $this->topic_type = "";
    $this->status = '1';
    /**
     * @this->type (ppsx, html)
     * ppsx the powerpoint presentation display
     * html create a custom story
     */
  }
  private function lastId() {
    $st = sql::con1()->prepare("select max(id) as last from topic_data");
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? ($data[0]['last'] + 1) : 1;
  }
  private function existOnSave() {
    $st = sql::con1()->prepare("select top 1 id from topic_data where title = :title and status = 1"); //EDITED
    $st->bindParam(":title", $this->title);
     $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;
  }
  private function existOnUpdate() {
    $st = sql::con1()->prepare("select top 1 id from topic_data where title = :title and not id = :id");
    $st->bindParam(":id", $this->id);
    $st->bindParam(":title", $this->title);
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;
  }
  public function trash() {
    $st = sql::con1()->prepare("update topic_data set status = :status where id = :id");
    $st->bindParam(":status", $this->status);
    $st->bindParam(":id", $this->id);
    $st->execute();
    return ["ok" => "1", "msg" => "Removed Successfully!"];
  }
  public function updateCover() {
    $st = sql::con1()->prepare("update topic_data set cover = :cover where id = :id and status = :status");
    $st->bindParam(":id", $this->id);
    $st->bindParam(":status", $this->status);
    $st->bindParam(":cover", $this->cover);
    $st->execute();
    return ["ok" => 1, "msg" => "Cover updated!"];
  }
  public function getFileCover() {
    $st = sql::con1()->prepare("select cover from topic_data where id = :id");
    $st->bindParam(":id", $this->id);
    $st->execute();
    $st->setFetchMode(sql::assoc());
    $data = $st->fetchAll();
    $st = null;
    return $data;
  }
  public function getFileData() {
    $st = sql::con1()->prepare("select data from topic_data where id = :id");
    $st->bindParam(":id", $this->id);
    $st->execute();
    $st->setFetchMode(sql::assoc());
    $data = $st->fetchAll();
    $st = null;
    return $data;
  }
  public function get() {
    $data = $this->feed->exec('select id, title, datecreated, uid, status, topic_type, type from topic_data where objid = :objid and status = :status and topic_type = :topic_type', [
      $this->objid,
      $this->status,
      $this->topic_type
    ]);
    $data_len = count($data);
    for ($i = 0; $i < $data_len; $i++) {
      Html::escape($data[$i]["title"]);
    }
    return $data;
  }
  public function save() {
    $st = sql::con1()->prepare("insert into topic_data
    (
      id, title, data, type, datecreated, cover, uid, status, objid, topic_type
    ) values
    (
      :id, :title, :data, :type, :dt, :cover, :uid, :status, :objid, :topic_type
    )
    ");
    $id = $this->lastId();
    $st->bindParam(":id", $id);
    $st->bindParam(":title", $this->title);
    $st->bindParam(":data", $this->data);
    $st->bindParam(":type", $this->type);
    $st->bindParam(":dt", $this->dt);
    $st->bindParam(":cover", $this->cover);
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":status", $this->status);
    $st->bindParam(":objid", $this->objid);
    $st->bindParam(":topic_type", $this->typic_type);
    $st->execute();
    return ["ok" => 1, "msg" => "New Subtopic Data added"];
  }
  public function update() {
    $data_col = "";
    $cover_col = "";
    $data = '';
    if (!empty($this->type)) {
      $data = 'data = :data,
      type = :type,';
    }
    if (!empty($this->cover)) {
      $cover_col = ',cover = :cover';
    }
    $st = sql::con1()->prepare("
      update topic_data set
      title = :title,
      " . $data . "
      " . $data_col  . "
      
      datecreated = :dt
      " . $cover_col  . "
      where id = :id
    ");
    $st->bindParam(":title", $this->title);

    if (!empty($this->type)) {
      $st->bindParam(":data", $this->data);
      $st->bindParam(":type", $this->type);
    }
    $st->bindParam(":dt", $this->dt);
    if (!empty($this->cover))
      $st->bindParam(":cover", $this->cover);
    $st->bindParam(":id", $this->id);
    $st->execute();
    return ["ok" => 1, "msg" => "Topic updated!"];
  }
  public function submit() {
    if (empty($this->id)) {
    //   // save
    //   if (!$this->existOnSave())
    //     return $this->save();
      
    // } else {
    //   // update
    //   if (!$this->existOnUpdate())
    //     return $this->update();
    // }

    //EDITED
 // save
    if ($this->existOnSave()) return ["type" => "insert", "ok" => 0, "msg" => "Subtopic Already Existed"];
    return $this->save();
  } else {
    // update
    if ($this->existOnUpdate()) return ["type" => "update", "ok" => 0, "msg" => "Subtopic Already Exists in other table"];
    return $this->update();
  }


  }
}