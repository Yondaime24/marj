<?php 
namespace classes;
use sql;
use classes\user;

class DailyReads {

    public function __construct() {
        $this->user = new user();
    } 

    public function getDisplayedReads(){
        $st = sql::con1()->prepare("select * from feed_about where status = 'Displayed'");
        $st->execute();
        $data = $st->fetchAll();
        return $data; 
    }
    public function getNotDisplayedReads(){
        $st = sql::con1()->prepare("select * from feed_about where status = 'Not Shown' order by about_id desc");
        $st->execute();
        $data = $st->fetchAll();
        return $data; 
    }

}