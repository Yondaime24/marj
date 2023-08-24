<?php 
/**
 * Time Interval algoritm 
 */
namespace classes;
use sql;
use classes\user;
use classes\Date as Date2;
class TimeSpent {
  public function __construct(string $uid = "") {
    $this->user = user::getInstance();
    $this->uid = empty($uid) ? $this->user->idno : $uid;
    $this->dt = Date2::Date();
    $this->type = "";
    $this->time = time();
  }
  public function allTime() {
    $st = sql::con1()->prepare("select sum(total_spent) as totalSpent from user_time_spent where uid = :uid and type = :type");
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":type", $this->type);
    $st->execute();
    $st->setFetchMode(sql::assoc());
    $data = $st->fetchAll();
    return Date2::Spent($data[0]["totalSpent"]);
  }
  public function thisWeek(): string {
    $st = sql::con1()->prepare("select sum(total_spent) as totalSpent from user_time_spent where uid = :uid and type = :type");
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":type", $this->type);
    $st->execute();
    $st->setFetchMode(sql::assoc());
    $data = $st->fetchAll();
    return Date2::Spent($data[0]["totalSpent"]);
  }
  private function today() {
    $st = sql::con1()->prepare("select * from user_time_spent where uid = :uid and dt = :dt and type = :type");
    $st->bindParam(":dt", $this->dt);
    $st->bindParam(":type", $this->type);
    $st->bindParam(":uid", $this->uid);
    $st->execute();
    $data = $st->fetchAll();
    $st = null;
    return $data;
  }
  private function update($total_spent) {
    $st = sql::con1()->prepare("update user_time_spent set total_spent = :ts, lasttime = :lt where uid = :uid and dt = :dt and type = :type");
    $st->bindParam(":dt", $this->dt);
    $st->bindParam(":type", $this->type);
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":lt", $this->time);
    $st->bindParam(":ts", $total_spent);
    $st->execute();
  }
  public function getByDate() {
    $st = sql::con1()->prepare("select total_spent from user_time_spent where uid = :uid and dt = :dt and type = :type");
    $st->bindParam(":dt", $this->dt);
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":type", $this->type);
    $st->execute();
    $data = $st->fetchAll();
    return isset($data[0]["total_spent"]) ? $data[0]["total_spent"] : 0;
  }
  public function close() {
    if (isset($_SESSION[$this->type])) { 
      unset($_SESSION[$this->type]);
    }
  }
  public function is_close() {
    return !isset($_SESSION[$this->type]) ? true : false;
  }
  public function start() {
    $today_spent = 0;
    $today_data = $this->today();
    $c_time = 0;
    if (count($today_data) > 0) {
      $last_time = $today_data[0]["lasttime"];
      if (!isset($_SESSION[$this->type])) { 
        // overwite the tlast time if session is not started
        $last_time = $this->time - 1;
        $_SESSION[$this->type] = '1';
      }
      $total_spent = $today_data[0]["total_spent"];
      $c_time = $this->time - $last_time;
      $total_spent += $c_time;
      $this->update($total_spent);
    } else {
      // create a new interval every day and once the user is loggedin
      $st = sql::con1()->prepare("insert into user_time_spent
        (dt, type, lasttime, total_spent, uid) values 
        (:dt, :type, :lt, :ts, :uid)
      ");
      $st->bindParam(":dt", $this->dt);
      $st->bindParam(":type", $this->type);
      $st->bindParam(":lt", $this->time);
      $st->bindParam(":ts", $today_spent);
      $st->bindParam(":uid", $this->uid);    
      $st->execute();
    }
  }
  public function get() {

  }
}