<?php 
namespace classes;
use sql;

class Img_folder {

   public function getAllFolderName() {
    $st =  sql::con1()->prepare('SELECT * FROM img_folder');
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }

  
}