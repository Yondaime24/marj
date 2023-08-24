<?php 
/* Multiple choice, Enumeration, essay..etc  */
namespace classes\Question;
use sql;

class ItemsChoices {
  
  public $QICId = '';
  public $Status = '';
  public $Type = '';
  public $QIId = '';
  public $Points = '';
  public $IsAnsKey = '';
  public $Des = ''; 
  public $CreatedBy = '';
  public $DateCreated = '';

  public $dt;
  public $uid;

  // choices id
  public $id = '';
  //question item id
  public $q_id = '';
  
  //use to unset all the choices within the question item
  private function _unSet() {
    $st = sql::con1()->prepare('update QuestionItemsChoices set IsAnsKey = \'\' where QIId = :q_id and status =:status');
    $st->bindParam(':q_id',$this->q_id);
    $st->bindParam(':status', $this->Status);
    $st->execute();
  }
  public function setKey() {
    $this->_unSet();
    $st = sql::con1()->prepare('update QuestionItemsChoices set IsAnsKey = \'1\' where Status = :status and QICId = :id');
    $st->bindParam(':id', $this->id);
    $st->bindParam(':status', $this->Status);
    $st->execute();
  }
  public function __construct() {
    $this->key = '1';  
  }
  
  private function __LastId() {
    $st = sql::con1()->prepare('select top 1 QICId as id from QuestionItemsChoices where Status = :status order by QICId desc');
    $st->bindParam(':status', $this->Status);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return count($data) > 0 ? $data[0]['id'] : '';
  }

  public function Save() {
    $st = sql::con1()->prepare('insert into QuestionItemsChoices(Type, Status, QIId, Points, IsAnsKey, Des, CreatedBy, DateCreated) values(:type, :status, :id, :points, :iskey, :des, :creator, :datecreated)');
    $st->bindParam(':type', $this->Type);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':id', $this->QIId);
    $st->bindParam(':points', $this->Points);
    $st->bindParam(':iskey', $this->IsAnsKey);
    $st->bindParam(':des', $this->Des);
    $st->bindParam(':creator', $this->CreatedBy);
    $st->bindParam(':datecreated', $this->DateCreated);
    $st->execute();
    return '{"ok": "1", "msg" : "New Item Added!", "LastId": "' . $this->__LastId() . '"}';
  }

  public function Update() {
    $st = sql::con1()->prepare('UPDATE QuestionItemsChoices SET Des = :Des WHERE QICId = :id and QIId = :qid ');
    $st->bindParam(':Des', $this->Des);
    $st->bindParam(':id', $this->QICId);
    $st->bindParam(':qid', $this->QIId);
    $st->execute();
    return '{"ok": "1", "msg" : "Item Updated!!", "LastId": "' . $this->QICId . '"}';
  }

  public function Add() {
    if (empty($this->QICId)) {
      // add new Items
      return $this->Save();
    } else {
      // Update the Itesm
      return $this->Update();
    }
  }
  public function privGet() {
    $st = sql::con1()->prepare('select Des, QICId, Type, QIId from QuestionItemsChoices where QIId = :QIId and Status = :status');
    $st->bindParam(':QIId', $this->QIId);
    $st->bindParam(':status', $this->Status);
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function Get() {
    $st = sql::con1()->prepare('select * from QuestionItemsChoices where QIId = :QIId and Status = :status');
    $st->bindParam(':QIId', $this->QIId);
    $st->bindParam(':status', $this->Status);
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function GetKey() {
    $st = sql::con1()->prepare('select * from QuestionItemsChoices where QIId = :QIId and Status = :status and IsAnsKey = :key');
    $st->bindParam(':QIId', $this->QIId);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':key', $this->key);
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }

  public function GetAll() {

  }
  public function iskey($id) {
    // return true if it is a key answer
    $st = $st = sql::con1()->prepare("select QICId from QuestionItemsChoices as qic where qic.QICId = :id and qic.Status = :status and IsAnsKey = '1'");
    $st->bindParam(":id", $id);
    $st->bindParam(":status", $this->Status);
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;
  }
  public function Trash() {
    $st = sql::con1()->prepare('UPDATE QuestionItemsChoices SET Status = :status, RemovedBy = :uid, DateRemoved = :dr  WHERE QICId = :id');
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':id', $this->QICId);
    $st->bindParam(':uid', $this->uid);
    $st->bindParam(':dr', $this->dt);
    $st->execute();
  }
}