<?php 
namespace classes;
use sql;

class Info_about {

   public function getAllInfoContent() {
    $st =  sql::con1()->prepare('SELECT * FROM info');
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }

  public function getFeedAbout() {
    $st =  sql::con1()->prepare("SELECT * FROM info WHERE info_id='1'");
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }

  public function getFcbAbout() {
    $st =  sql::con1()->prepare("SELECT * FROM info WHERE info_id='2'");
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }
  
}