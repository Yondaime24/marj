<?php 
namespace classes;
use classes\UserQuiz;
use classes\user;
use classes\topics;

class usertask {
  public function __construct(string $uid = null) {
    $this->user = user::getInstance();
    $this->uid = $uid != null ? $uid : $this->user->idno;
  }
  public function quiz_completed() {
    $ua = new UserQuiz();
    $user = user::getInstance();
    $ua->uid = $user->idno;
    $ua->done = 1;
    $data = $ua->taskall();
    return $data;
  }
  public function topic_completed(): int {
    $topic = new topics($this->uid);
    $data = $topic->completed();
    return count($data);
  }
  public function week_time_spent() {
    return 0;
  }
}