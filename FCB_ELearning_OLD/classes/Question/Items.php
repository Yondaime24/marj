<?php 
/*
 * Classes for adding a Question list 
 *  
 */
namespace classes\Question;
use sql;
use classes\Date as Date2;
use classes\ItemsChoices;

class Items {
  public $idno = '';

  public $Question = '';
  public $AnsKey = '';
  public $Status = '';
  public $isDynamicQuestion = '';
  public $QCCode = '';
  public $QIId = ''; // The Unique Id of the Question Item
  public $QGId = '';
  public $Type = '';
  public $DateCreated = '';
  public $points = '1';

  public $uid = '';
  public $dt = '';

  public function __construct() {
    
  }
  private function __LastId() {
    $st = sql::con1()->prepare('select top 1 QIId from QuestionItems where status = :status order by QIId desc');
    $st->bindParam(":status", $this->Status);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return count($data) > 0 ? $data[0]['QIId'] : '';
  }

  public function Save() {
    $st  = sql::con1()->prepare('insert into QuestionItems(points, Question, AnsKey, Status, isDynamicQuestion, QCCode, QGId, DateCreated, CreatedBy, type) values(:points, :question, :ansKey, :status, :dynamic, :code, :qGId, :DateCreated, :CreatedBy, :type)');
    $st->bindParam(':question', $this->Question);
    $st->bindParam(':ansKey', $this->AnsKey);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':dynamic', $this->isDynamicQuestion);
    $st->bindParam(':code', $this->QCCode);
    $st->bindParam(':qGId', $this->QGId);
    $st->bindParam(':DateCreated', $this->DateCreated);
    $st->bindParam(':type', $this->Type);
    $st->bindParam(':CreatedBy', $this->idno);
    $st->bindParam(':points', $this->points);
    $st->execute();
    return '{"ok": "1", "msg": "New Question Added", "LastId": "' . $this->__LastId() . '"}';
  }
  public function Update() {
    $st = sql::con1()->prepare('update QuestionItems set points = :points,Question = :question, AnsKey = :ansKey, Status = :status, isDynamicQuestion = :isDynamicQuestion, QCCode = :QCCode, QGId = :QGId where QIId = :id');
    $st->bindParam(':question', $this->Question);
    $st->bindParam(':ansKey', $this->AnsKey);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':isDynamicQuestion', $this->isDynamicQuestion);
    $st->bindParam(':QCCode', $this->QCCode);
    $st->bindParam(':QGId', $this->QGId);
    $st->bindParam(':id', $this->QIId);
    $st->bindParam(':points', $this->points);
    $st->execute();
    return '{"ok": "1", "msg": "Question Updated!", "LastId": "' . $this->QIId . '"}';
  }
  public function Add() {
    if (empty($this->QIId)) {
      // insert operation
      return $this->Save();
    } else {
      return $this->Update();
      // update operation
    }
  }
  public function Get() {
    $st = sql::con1()->prepare('select * from QuestionItems where Status = :status and QGId = :gid');
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':gid', $this->QGId);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return $data;
  }
  public function trash() {
    $st = sql::con1()->prepare('update QuestionItems set status = :status, removedby = :uid, date_removed = :dt where QIId = :id');
    $st->bindParam(':id', $this->QIId);
    $st->bindParam(':status', $this->Status);
    $st->bindParam(':uid', $this->uid);
    $st->bindParam(':dt', $this->dt);
    $st->execute();    
  }
  public function GetAll() {

  }
  public function getTotal() {
    $st = sql::con1()->prepare('select count(*) as total from QuestionItems where QGId = :id and Status = :status');
    $st->bindParam(':id', $this->QGId);
    $st->bindParam(':status', $this->Status);
    $st->execute();
    $data = $st->fetchAll();
    return isset($data[0]['total']) ? $data[0]['total'] : 0;
  }
  public function random($num) {
    $st = sql::con1()->prepare('select * from QuestionItems where QGId = :id and Status = :status');
    $st->bindParam(':id', $this->QGId);
    $st->bindParam(':status', $this->Status);
    $st->execute();
    $data = $st->fetchAll();
    $total = count($data);
    // for ($i = 0; $i < $total; $i++) {
    //   print $data[]['Question'].'<br />';
    // }
    // shuffle the question
    // shuffle algoritm
    $i = $num - 1;
    $final_data = [];
    while ($i > -1) {
      $index_random = mt_rand(0, $i);
      $final_data[] = $data[$index_random];
      for ($a = 0; $a <= $i; $a++) {
        if ($a != $index_random) {
          $tmp[] = $data[$a];
        }
      }
      $data = $tmp;
      $tmp = []; // tmp reset
      $i--;
    }
    // end shuffle algorithm
    return $final_data;
  }
}