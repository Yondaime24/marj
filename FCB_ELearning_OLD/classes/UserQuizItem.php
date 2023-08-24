<?php 
namespace classes;
use classes\lib\sql;
class UserQuizItem {
  public function __construct() {
    $this->feed = null;
    $this->uid = '';
    $this->question_prepared_id = '';
    $this->feed = sql::getInstance();
  }
  public function init() {
    
  }
  public function add($i, $question_item) {
    $data = $this->feed->exec('select no from user_quiz_item where question_item_id = :d and question_prepared_id = :pr and uid = :u', [
      $question_item,
      $this->question_prepared_id,
      $this->uid
    ]);
    if (count($data) >= 0) {
      $data = $this->feed->exec('delete from user_quiz_item where question_item_id = :d and question_prepared_id = :pr and uid = :u', [
        $question_item,
        $this->question_prepared_id,
        $this->uid
      ]);
    }
    $this->feed->exec('insert into user_quiz_item(question_item_id, question_prepared_id, uid, no) values(:a, :b,:c, :no)', [
      $question_item,
      $this->question_prepared_id,
      $this->uid,
      $i
    ]);
  }
}