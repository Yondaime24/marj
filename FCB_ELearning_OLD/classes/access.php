<?php 
namespace classes;
use sql;
use classes\user;
class access {
  private static $obj = null;

  private $ac = [];
  private function _is_admin_level() {
    $st = sql::con1()->prepare("select user_id from admin_level where user_id = :uid and status = 'active'");
    $st->bindParam(":uid", $this->user_id);
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;
  }
  public static function getInstance() {
    if (self::$obj == null)
      self::$obj = new access();
     return self::$obj;
  }
  public function __construct($acc = []) { 
    $this->user = user::getInstance(); 
    $this->user_id = $this->user->idno;
    $this->is_admin = $this->_is_admin_level();
  }
  public function check($myaccess = "") {
    $c = count($this->ac);
    /**
     * The Administrator has always acces in the system
     * Only the administrator can add a personel as admin list
     */
    if ($this->user->ulevel == "AD") 
      return true;
    // it priority the administrator level
    // another layer for the user type
    return $this->is_admin;
  }
}