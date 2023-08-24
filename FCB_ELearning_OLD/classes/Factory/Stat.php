<?php 
namespace classes\Factory;
use classes\user;
use classes\subtopics;
use classes\QuizPrepare;
use classes\Factory\Statistics;

class Stat {
  public static function create($type): Statistics {
    if ($type == "user") {
      return user::getInstance();
    } else if ($type == "subtopic") {
      $t =  new subtopics();
      $t->status = 1;
      return $t;
    } else if ($type == "QuizPrepare") {
      return new QuizPrepare();
    } else {
      throw new \Exception("Not Supported Type", 1);
    }
  }
}