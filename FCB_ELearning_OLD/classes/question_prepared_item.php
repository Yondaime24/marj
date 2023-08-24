<?php 
namespace classes;
use classes\lib\sql;

class question_prepared_item {
  public function __construct() {
    $this->feed = null;
    $this->prepared_id = '';
    $this->feed = sql::getInstance();
  }
  public function init() {
    
  }
  public function add($no, $question_item_id) {
    $this->feed->exec('insert into question_prepared_item(no, prepared_id, question_item_id) values(:n, :a, :b)', [
      $no,
      $this->prepared_id,
      $question_item_id
    ]);
  }
  public function getItem() {
    return $this->feed->exec('select * from question_prepared_item where prepared_id = :id', [$this->prepared_id]); 
  }
  public function randomItem() {
    $data = $this->getItem();
    $num = count($data);
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
    return $final_data;
  }
}