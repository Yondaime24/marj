<?php
namespace classes\Task;
use classes\user as user1;
use classes\Date as Date2;
use classes\lib\sql;
use classes\topics;
use classes\subtopics;
use classes\TopicData;
use classes\QuizFull;

class User {
  public function __construct() {
    $this->u = new user1();
    $this->feed = sql::getInstance();

    $this->uid = $this->u->idno;
    $this->objid = '';
    $this->objtype = ''; // cat, main, sub, data
    $this->type = ''; // quiz, topic
    $this->dt = Date2::getDate();
  }
  private function isExist() {
    $data = $this->feed->exec('select top 1 uid from usertask where 
      uid = :uid and
      objid = :objid and
      objtype = :objtype and 
      type = :type
    ', [
      $this->uid,
      $this->objid,
      $this->objtype,
      $this->type
    ]);
    return count($data) > 0 ? true : false;
  }
  private function submitTaskQuiz() {

  }
  private function submitTaskTopic() {
    $this->type = 'topic';
    if (!$this->isExist()) {
      $data = $this->feed->exec('insert into usertask(uid, objid, objtype, type, dtstart) values(:a, :b, :c, :d, :e)', [
        $this->uid, 
        $this->objid,
        $this->objtype,
        $this->type,
        $this->dt
      ]);
    }
  }
  public function done() {
    $this->feed->exec('update usertask set status = :s, dtfinish = :d where 
      uid = :uid and
      objid = :objid and
      objtype = :objtype and 
      type = :type
    ', [
      1, 
      $this->dt,
      $this->uid,
      $this->objid,
      $this->objtype,
      $this->type
    ]);
  }
  public function taskCat($cat_id) {
    $this->objtype = 'cat';
    $this->objid = $cat_id;
    $this->submitTaskTopic();
    return $this;
  }
  public function taskMain($main_id) {
    $this->objtype = 'main';
    $this->objid = $main_id;
    $this->submitTaskTopic();
    return $this;
  }
  public function taskSub($sub_id) {
    $this->objtype = 'sub';
    $this->objid = $sub_id;
    $this->submitTaskTopic();
    return $this;
  }
  public function taskData($data_id) {
    $this->objtype = 'data';
    $this->objid = $data_id;
    $this->submitTaskTopic();
    return $this;
  }
  public function isDone($objid) {
    $data = $this->feed->exec('select * from usertask where 
      uid = :uid and
      objid = :objid and
      objtype = :objtype and 
      type = :type
    ', [
      $this->uid,
      $objid,
      $this->objtype,
      $this->type
    ]);
    if (count($data) > 0) {
      if ($data[0]['status'] == '1')
        return true;
    } 
    return false;
  }
  public function taskQuiz() {

  }
  public function isQuizComplete($cat_id) {
    //checking if all quizzes is completed
    $quiz = new QuizFull();
    return $quiz->isCompleteAll($cat_id);
  }
  public function isTopicComplete($cat_id) {
    $topic_obj = new topics();
    $sub_obj = new subtopics();
    $td = new TopicData();
    $topic_obj->catid = $cat_id;
    $topic_data  = $topic_obj->get();
    $topic_data_len = count($topic_data);
    if ($topic_data_len > 0) {
      $this->objtype = 'cat';
      $this->type = 'topic';
      if (!$this->isDone($cat_id)) return false;
      for ($i = 0; $i < $topic_data_len; $i++) {
        // main
        $this->objtype = 'main';
        if (!$this->isDone($topic_data[$i]['id'])) return false;
        $sub_obj->topic_id = $topic_data[$i]['id'];
        $sub_data = $sub_obj->get();
        $sub_data_len = count($sub_data);
        for ($j = 0; $j < $sub_data_len; $j++) {
          $this->objtype = 'sub';
          if (!$this->isDone($sub_data[$j]['id'])) return false;
          $td->objid = $sub_data[$j]['id'];
          $td->topic_type = 'sub';
          $sub_pps_data = $td->get();
          $sub_pps_data_len = count($sub_pps_data);
          for ($k = 0; $k < $sub_pps_data_len; $k++) {
            $this->objtype = 'data';
            if (!$this->isDone($sub_pps_data[$k]['id'])) return false;
          }
        }
      }
    } 
    else 
    return false;
    return true;
  }
  public function getTaskTopic() {
  }
  public function getTaskQuiz() {
  }
}