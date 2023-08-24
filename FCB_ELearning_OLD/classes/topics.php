<?php 
namespace classes;
use sql;
use classes\user;
use classes\Date as Date2;
use classes\Html;
use classes\lib\sql as sql2;
class topics {
  public $id = '';
  public $title = '';
  public $des = '';
  public $createdby = '';
  public $createdon = '';
  public function __construct(string $uid = "") {
    $this->feed = sql2::getInstance();

    $this->user = user::getInstance();
    $this->status = '1';
    $this->catid = '';
    $this->dt = Date2::getDate();
    $this->uid = empty($uid) ? $this->user->idno : $uid;
  }
  public function getInfo() {
    $st = sql::con1()->prepare("select * from topics where id = :id and status = :status");
    $st->bindParam(":id", $this->id);
    $st->bindParam(":status", $this->status);
    $st->execute();
    $data = $st->fetchAll();
    $data_len = count($data);
    return $data; 
  }
  public function get() {
    $data = $this->feed->exec('select * from topics where catid = :catid and status = :status', [$this->catid, $this->status]);
    $data_len = count($data);
    for ($i = 0; $i < $data_len; $i++) {
      Html::escape($data[$i]["title"]);
    }
    return $data;
  }
  public function getByCategory() {
    //retrived all the topics in the specific category
    $st = sql::con1()->prepare('select * from topics where catid = ? and status = :status');
    $st->bindParam(':catid', $this->catid);
    $st->bindParam(':status', $this->status);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    $st = null; //clear up the memory
    return $data;
  }
  public function existOnSave() {
    $st = sql::con1()->prepare('select id from topics where catid = :catid and title = :title and status = 1'); //EDITED
    $st->bindParam(':catid', $this->catid);
    $st->bindParam(':title', $this->title);
    $st->execute();
    $data = $st->fetchAll();
    return count($data) > 0 ? true : false;
  }
  public function existOnUpdate() {
    $st = sql::con1()->prepare('select id from topics where catid = :catid and title = :title and not id = :id');
    $st->bindParam(':catid', $this->catid);
    $st->bindParam(':title', $this->title);
    $st->bindParam(':id', $this->id);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return count($data) > 0 ? true : false;
  }
  public function save() {
    $st = sql::con1()->prepare('insert into topics(catid,title,des,createdby,createdon,status) values(:catid,:title,:des,:createdby,:createdon,:status)');
    $st->bindParam(':catid', $this->catid);
    $st->bindParam(':title', $this->title);
    $st->bindParam(':des', $this->des);
    $st->bindParam(':createdby', $this->uid);
    $st->bindParam(':createdon', $this->dt);
    $st->bindParam(':status', $this->status);
    $st->execute();
  }
  public function update() {
    $st = sql::con1()->prepare("update topics set catid = :catid, title = :title, des = :des where id = :id and status = :status");
    $st->bindParam(":catid", $this->catid);
    $st->bindParam(":id", $this->id);
    $st->bindParam(":title", $this->title);
    $st->bindParam(":status", $this->status);
    $st->bindParam(":des", $this->des);
    $st->execute();
    return ['ok' => true, 'msg' => 'Topics Updated!'];
  }
  public function getSubData($sub_id) {
     $st = sql::con1()->prepare("select 
      st.id as sub_id,
      st.title as sub_title,
      st.createdon as sub_dt,
      t.id as main_id,
      t.title as main_title,
      t.createdon as main_dt,
      tc.id as cat_id,
      tc.title as cat_title,
      tc.createdon as cat_dt
      from sub_topics as st 
      inner join topics as t on st.topic_id = t.id
      inner join topics_category as tc on tc.id = t.catid
      where (t.status = '1' and st.status = '1') and 
      (
      st.id = :id
      ) order by st.createdon desc
    ");
     $st->bindParam(":id", $sub_id);
     $st->execute();
     $data = $st->fetchAll();
     return $data;
  }
  public function submit() {
    if (empty($this->id)) {
      //insert operation
      if (!$this->existOnSave()) {
        //ok
        $this->save();
        return ['ok' => true, 'msg' => 'New Topics created'];
      }
      return ['ok' => false, 'msg' => 'Topics is already is exist in the category'];
    } else {
      //update operation
      if (!$this->existOnUpdate()) {
        //processed to update operation
        return $this->update();
      }
      //failed to update the program
      return ['ok' => false, 'msg' => 'Something went wrong/ the Topics is already created in the category'];
    }
  }
  public function trash() {
    $st = sql::con1()->prepare('update topics set status = :status where id = :id');
    $st->bindParam(':status', $this->status);
    $st->bindParam(':id', $this->id);
    $st->execute();
    return ["type" => "delete", 'ok' => 1, 'msg' => 'Topics Removed Successfully'];
  }
  public function completed() {
    $data = $this->feed->exec('select id from topics as t
      inner join usertask as ut on ut.objid = t.id and ut.objtype = :type
      where ut.uid = :uid and ut.status = :status
    ', ['main', $this->uid, '1']);
    return $data;
  }
}
