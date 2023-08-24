<?php 
namespace classes;
use sql;
use classes\lib\sql as sql2;
use classes\user;
use classes\Date as Date2;

class chatBox {
  public $id = '';
  public $uid = '';
  public $myId = '';
  public $from = '';
  public $to = '';
  public $dateSend = '';
  public $dateSeen = '';
  public $status = '';

  public $limit = 5;
  public $offset = 0;
  private $online_time = 60;

  /*  
    Status represent for 1 is availble and 0 is removed 
  */
  /*
   * Message Contents
   */
  public $dataRecv = [];
  public $msg = '';
  public $isRead = '';
  public $type = '';
  /*
   * Sending chat to user or to the admin or personel
   */
  public $search = '';
  public function __construct() {
    $this->feed = sql2::getInstance();
    $this->user = user::getInstance();
  }
  public function getLatestChatId() {
    return $this->feed->exec("SELECT top 1 ffrom, uo.name from chatbox as c 
      inner join user_online as uo on uo.uid = c.ffrom
    where c.tto = 'admin' and c.isRead = '0' order by c.dateSend DESC"); 
  }
  public function send() {
    $st = sql::con1()->prepare("insert into chatBox(uid, ffrom, tto, msg, isRead, status, dateSend)values(:uid, :from, :to, :msg, :isRead, :status, :dateSend)");
    $st->bindParam(":uid", $this->uid);
    $st->bindParam(":from", $this->from);
    $st->bindParam(":to", $this->to);
    $st->bindParam(":msg", $this->msg);
    $st->bindParam(":isRead", $this->isRead);
    $st->bindParam(":status", $this->status);
    $st->bindParam(":dateSend", $this->dateSend);
    $st->execute();

    $st2 = sql::con1()->prepare("update user_online set dateSend_stat = :dt, isRead_stat = '1'  where uid = :uid");
    $st2->bindParam(":dt", $this->dateSend);
    $st2->bindParam(":uid", $this->user->idno);
    $st2->execute();
    return true;

  }
  public function recv() {
    if (is_numeric($this->offset))
      $this->offset = (int)$this->offset;
    else
      $this->offset = 0;
    if ($this->offset <= 0)
      $this->offset = 1;
    else
      $this->offset = $this->offset * 10;

    $st = sql::con1()->prepare("select top 10 start at :off * from chatBox where ((ffrom = 'admin' and tto = :myid) or (ffrom = :myid_c and tto = 'admin')) and status = :status order by id desc");
    $st->bindParam(':myid', $this->uid);
    $st->bindParam(':myid_c', $this->uid);
    $st->bindParam(':status', $this->status);
    $st->bindParam(':off', $this->offset);
    $st->execute();
    $this->dataRecv = $st->fetchAll();
    $len = count($this->dataRecv);
    for ($i = 0; $i < $len; $i++) {
      $this->dataRecv[$i]["ago"] = Date::ago($this->dataRecv[$i]["dateSend"]);
    }
    return $this->dataRecv;
  }
  public function seen() {
    $data = $this->dataRecv;
    $len = count($data);
    $st = sql::con1()->prepare("update chatBox set isRead = :isRead, dateSeen = :seen where id = :id and isRead = '0'");
    for ($i = 0; $i < $len; $i++) {
      if ($data[$i]['tto'] == $this->myId) {
        $st->bindParam(':isRead', $this->isRead);
        $st->bindParam(':id', $data[$i]['id']);
        $st->bindParam(':seen', $this->dateSeen);
        $st->execute();
      }
    }
  }
  public function listOnline() {
    $this->search = '%'.$this->search.'%';
    // $data = $this->feed->exec("select top 10 
    //   uo.uid,
    //   uo.name,
    //   uo.branch,
    //   (select count(id) from chatbox where ffrom = uo.uid and isRead = '0') as total_unread
    // from user_online as uo where name like :name and not uid = :id", [
    //   $this->search,
    //   $this->user->idno
    // ]);

    $data = $this->feed->exec("select top 100
    uo.uid,
    uo.name,
    uo.branch,
    uo.dt,
    (select count(id) from chatbox where ffrom = uo.uid and isRead = '0') as total_unread
    from user_online as uo left join chatbox as cb on uo.uid = cb.tto and uo.uid = cb.uid where uo.name like :name and not uo.uid = :id order by uo.isRead_stat desc, uo.dateSend_stat desc", [
      $this->search,
      $this->user->idno
    ]);

    return $data;
  }

  // public function getMsgCount() {

  // }

  public function trash() {
    
  }
}