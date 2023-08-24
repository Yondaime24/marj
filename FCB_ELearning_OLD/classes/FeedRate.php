<?php 
namespace classes;
use sql;
use classes\user;
class FeedRate {
  public function __construct() {
    $this->user = user::getInstance();
  }
  public function getLastRate() {
    $st = sql::con1()->prepare("SELECT TOP 1 * FROM  rate_us WHERE user_id = :uid ORDER BY rate_id DESC");
    $st->bindParam(":uid", $this->user->idno);
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getAllLastRate() {
    $st = sql::con1()->prepare("SELECT avg(rate_value) as average FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY rate_id DESC)");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getAllRateCount() {
    $st = sql::con1()->prepare("SELECT count(*) as rate_count FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY rate_id DESC)");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function get5StarRate() {
    $st = sql::con1()->prepare("SELECT count(rate_value) as count FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY rate_id DESC) AND rate_value = '5'");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function get4StarRate() {
    $st = sql::con1()->prepare("SELECT count(rate_value) as count FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY rate_id DESC) AND rate_value = '4'");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
    public function get3StarRate() {
    $st = sql::con1()->prepare("SELECT count(rate_value) as count FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY rate_id DESC) AND rate_value = '3'");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
    public function get2StarRate() {
    $st = sql::con1()->prepare("SELECT count(rate_value) as count FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY rate_id DESC) AND rate_value = '2'");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
    public function get1StarRate() {
    $st = sql::con1()->prepare("SELECT count(rate_value) as count FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY rate_id DESC) AND rate_value = '1'");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getHistory() {
    $st = sql::con1()->prepare("select * from rate_us where user_id = :uid order by rate_id desc");
    $st->bindParam(":uid", $this->user->idno);
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getRecentCmt() {
    $st = sql::con1()->prepare("SELECT * FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY date_rated DESC) ORDER BY date_rated DESC");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getRecentCmt5() {
    $st = sql::con1()->prepare("SELECT * FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY date_rated DESC) AND rate_value = '5' ORDER BY date_rated DESC");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getRecentCmt4() {
    $st = sql::con1()->prepare("SELECT * FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY date_rated DESC) AND rate_value = '4' ORDER BY date_rated DESC");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getRecentCmt3() {
    $st = sql::con1()->prepare("SELECT * FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY date_rated DESC) AND rate_value = '3' ORDER BY date_rated DESC");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getRecentCmt2() {
    $st = sql::con1()->prepare("SELECT * FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY date_rated DESC) AND rate_value = '2' ORDER BY date_rated DESC");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
  public function getRecentCmt1() {
    $st = sql::con1()->prepare("SELECT * FROM rate_us AS r1 WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = r1.user_id ORDER BY date_rated DESC) AND rate_value = '1' ORDER BY date_rated DESC");
    $st->execute();
    $data = $st->fetchAll();
    return $data;
  }
 
}