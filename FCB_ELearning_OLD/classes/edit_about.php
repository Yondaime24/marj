<?php 
namespace classes;
use sql;

class Edit_about {

   public function getAboutContent() {
    $st =  sql::con1()->prepare("SELECT * FROM feed_about");
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }
}