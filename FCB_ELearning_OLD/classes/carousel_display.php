<?php 
namespace classes;
use sql;

class Carousel_display {

   public function getImages() {
    $st =  sql::con1()->prepare("SELECT * FROM carousel_image WHERE status = 'displayed' ORDER BY sequence DESC");
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }

  public function getSingleImage($id) {
    $st =  sql::con1()->prepare("SELECT * FROM carousel_image WHERE status = 'displayed' AND id = :id ORDER BY sequence DESC");
    $st->bindParam(":id", $id);
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }

  
}