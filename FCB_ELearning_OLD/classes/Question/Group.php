<?php 
namespace classes\Question;
use sql;

class Group {
  
  public $QGId = '';
  public $Title = '';
  public $Description = '';
  public $Status = '';
  public $TimeLimit = '';
  public $QCCode = '';
  public $CreatedBy = '';
  public $DateCreated  = '';
  public $ObjId = '';

  public $dt = '';
  public $uid = '';

  private function _LastId() {
    $st = sql::con1()->prepare('select top 1 QGId as id from QuestionGroup order by QGId desc');
    $st->execute();
    $data = $st->fetch();
    return $data['id'];
  }
  public function getGroup() {
    $st = sql::con1()->prepare('select * from QuestionGroup as qg where ObjId = :id and Status = :status and QCCode = :type');
    $st->bindParam(':id', $this->ObjId);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':type', $this->QCCode);
    $st->execute();
    return $st->fetchAll();
  }
  public function Save() {
    $st = sql::con1()->prepare('insert into QuestionGroup(ObjId, QCCode, Title, Description, Status, TimeLimit, DateCreated, CreatedBy) values(:objId, :code, :title, :des, :status, :timeLimit, :dateCreated, :createdBy)');
    $st->bindParam(':code', $this->QCCode);
    $st->bindParam(':title', $this->Title);
    $st->bindParam(':des', $this->Description);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':timeLimit', $this->TimeLimit);
    $st->bindParam(':dateCreated', $this->dt);
    $st->bindParam(':createdBy', $this->uid);
    $st->bindParam(':objId', $this->ObjId);
    $st->execute();
    return '{"ok": "1", "msg" : "New Questionaire Added", "GroupId": "' . $this->_LastId() . '"}';
  }
  public function Get() {
    $st = sql::con1()->prepare('select * from QuestionGroup where QGId = :id and status = :status');
    $st->bindParam(':id', $this->QGId);
    $st->bindParam(':status', $this->Status);
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function GetAll() {
    $st = sql::con1()->prepare('select * from QuestionGroup where Status = :status and QCCode = :code and ObjId = :objId order by QGId desc');
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':code', $this->QCCode);
    $st->bindParam(':objId', $this->ObjId);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return $data;
  }
  
  public function Update() {
    $st = sql::con1()->prepare('update QuestionGroup set QCCode = :code, Title = :title, Description = :des, Status = :status, TimeLimit = :timeLimit, DateCreated = :dateCreated, CreatedBy = :createdBy');
    $st->bindParam(':code', $this->QCCode);
    $st->bindParam(':title', $this->Title);
    $st->bindParam(':des', $this->Description);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':timeLimit', $this->TimeLimit);
    $st->bindParam(':dateCreated', $this->DateCreated);
    $st->bindParam(':createdBy', $this->CreatedBy);
    $st->execute();
    return '{"ok": "1", "msg" : "Questionaire Updated"}';
  }
  public function Trash() {
    $st = sql::con1()->prepare('update QuestionGroup set Status = :status, date_removed = :dt, removed_by = :uid  where QGId = :id');
    $st->bindParam(':id', $this->QGId);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':dt', $this->dt);
    $st->bindParam(':uid', $this->uid);
    $st->execute();

    return '{"ok" : "1", "msg" : "Removed Successfully"}';
  }
  public function Add() {
    if (empty($this->QGId)) {
      //insert operation
      return $this->Save();
    } else {
      //update operation
    }
  }
} 