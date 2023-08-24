<?php 
namespace classes;
use sql;
use classes\user;

// Avoid user from multitasking

class Task {

  // user id
  public $uid = '';
  // type of the Topics, either category, Main topics or sub topics
  public $type = '';
  // Date Transaction
  public $dt = '';
  // 
  public $isDone = '';
  //
  public $objid = '';
  //
  public $status = '';
  //
  private $userObj = '';
  public function __construct() {
    $this->userObj = user::getInstance();
  }
  // Checker if he or che has an read topics 

  public function active() {
    if (empty($this->uid)) {
      // once the user id is empty then, directly detect the login user id
      $this->uid = $this->userObj->idno;
    }
    return [
      // list of the task of the users
      'profile' => [
         'id' => $this->uid
       ], 
      'task' => [
        'topics' => $this->topics(),
        'quiz' => $this->quiz()
      ]
    ];
  }
  
  public function topics() {
    $this->isDone = '0';
    $this->status = '1';
    $this->type = 'topics';
    return $this->getAll();
  }

  public function quiz() {
    $this->isDone = '0';
    $this->status = '1';
    $this->type = 'quiz';

    return $this->getAll();
  }

  // chekcking the user quiz to avoid from cheating
  // this function also use for checking if user has existing quiz or not
  // once the user has an existing quiz he/she can't view the topics or any topics, the user must 
  // have to check the "finish button" 
  public function getAll() {
    $st = sql::con1()->prepare('select 
      id,
      name, 
      isFinish, 
      remark, 
      removedby, 
      status, 
      tbl_id, 
      type
      from user_task
      where status = :status and isFinish = :finish and uid = :uid and type = :type
    ');
    $st->bindParam(':finish', $this->isDone);
    $st->bindParam(':status', $this->status);
    $st->bindParam(':uid', $this->uid);
    $st->bindParam(':type', $this->type);
    $st->execute();
    return $st->fetchAll();
  }
  public function get() {
      $st = sql::con1()->prepare('select 
      id,
      name, 
      isFinish, 
      remark, 
      removedby, 
      status, 
      tbl_id, 
      type
      from user_task
      where status = :status and isFinish = :finish and uid = :uid and type = :type
    ');
    $st->bindParam(':finish', $this->isDone);
    $st->bindParam(':status', $this->status);
    $st->bindParam(':uid', $this->uid);
    $st->bindParam(':type', $this->type);
    $st->execute();
    return $st->fetchAll();
  }
  public function save() {

  }  

  // once the button read topics then it is set to is_done to zero 
  // Use for setting user current topics reading or task
  public function create() {

  }

  public function finish() {

  }
}