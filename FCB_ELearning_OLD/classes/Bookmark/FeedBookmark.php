<?php 
namespace classes\Bookmark;
use classes\lib\sql;
use classes\user;
use classes\Date as Date2; 

class FeedBookmark {
  public function __construct() {
    $this->types = [
      'cat',
      'maintopic',
      'subtopic',
      'quiz',
      'user',
      'memo'
    ];
    //sample
    /**
     * $feed = new FeedBookmark();
     * $feed->title = '';
     * $feed->objid = '';
     * $feed->type = '';// topic, quiz, 
     */
    $this->feed = sql::getInstance();
    $this->user = user::getInstance();
    $this->title = '';
    $this->objid = '';
    $this->type = '';
    $this->status = '1';
    $this->uid = $this->user->idno;
    $this->dt = Date2::getDate();
    //if ($this->type)
  }
  public function getBadge() {
    $data = $this->feed->exec('select count(objid) as total from bookmark where uid = :uid', [$this->uid]);
    return isset($data[0]['total']) ? $data[0]['total'] : '';
  }
  private function _exist() {
    $data = $this->feed->exec('select top 1 objid from bookmark where objid = :a and type = :type and uid = :uid', [$this->objid, $this->type, $this->uid]);
    return count($data) > 0 ? true : false;
  }
  public function get() {
    $data = $this->feed->exec('select * from bookmark where uid = :uid', [$this->uid]);
    $data_len = count($data);
    for ($i = 0; $i < $data_len; $i++) {
      $data[$i]['ago'] = Date2::Ago($data[$i]['dateadded']);
    }
    return $data;
  }
  public function delete() {
    if (!$this->__checkType())
      return ['ok' => 0, 'msg' =>'Something went wrong!'];
    $this->feed->exec('delete from bookmark where uid = :uid and type = :type and objid = :objid', [$this->uid, $this->type, $this->objid]);
    return ['ok' => 1, 'msg' =>'Removed!'];
  }
  private function __checkType () {
    return in_array($this->type, $this->types);
  }
  public function add() {
    if (!$this->__checkType())
      return ['ok' => 0, 'msg' => 'Something went wrong!'];
    if (!$this->_exist()) {
      $this->feed->exec('insert into bookmark(objid, type, uid, dateadded, title)
        values(:objid, :type, :uid, :dt, :title);
      ', [
        $this->objid,
        $this->type,
        $this->uid,
        $this->dt,
        $this->title
      ]); 
      return ['ok' => 1, 'msg' => 'Added!'];
    } else
      return ['ok' => 0, 'msg' => 'Already bookmark!'];
  }
}