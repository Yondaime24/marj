<?php 
namespace classes\lib;
use classes\lib\sql;
class sqlfunc {
  public function __construct() {
    $this->feed = sql::getInstance();
    $this->table = '';
    $this->col = '';
  }
  public function lastId() {
    $data = $this->feed->exec("select max(".$this->col.") as last_id from ".$this->table); 
    return count($data)  > 0 ? ($data[0]["last_id"] + 1) : 1; 
  }
}