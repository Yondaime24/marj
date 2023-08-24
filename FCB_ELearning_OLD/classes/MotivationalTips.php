<?php 
namespace classes;
use sql;
use classes\user;

class MotivationalTips {

    public function __construct() {
        $this->user = user::getInstance();
    } 

    public function getDisplayedTips(){
        $st = sql::con1()->prepare("select * from feed_quote where status = 'Displayed'");
        $st->execute();
        $data = $st->fetchAll();
        return $data; 
    }

    public function getNotDisplayedTips(){
        $st = sql::con1()->prepare("select * from feed_quote where status = 'Not Shown' order by quote_id desc");
        $st->execute();
        $data = $st->fetchAll();
        return $data; 
    }

}