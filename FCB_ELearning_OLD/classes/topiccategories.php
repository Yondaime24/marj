<?php 
namespace classes;
use sql;
use classes\Date as Date2;
use classes\user;
use classes\Html;
class topiccategories {
  
  public $id = '';
  public $title = '';
  public $tes = '';
  public $createdon = '';
  public $createdby = '';
  public function __construct() {
    $this->user = user::getInstance();
    $this->cover = "";
    // transactor
    $this->uid = $this->user->idno;;
    $this->dt = Date2::getDate();
    $this->status = '1';
    $this->search = "";
  }
  public function file() {
    header("Content-Type:image/png");
    $st = sql::con1()->prepare("select cover from topics_category where id = :id and status = :status");
    $st->bindParam(":id", $this->id);
    $st->bindParam(":status", $this->status);
    $st->execute();
    $data = $st->fetchAll();
    if (count($data) > 0) {
      if ($data[0]["cover"] != "")
        print base64_decode($data[0]["cover"]);
      else
        print file_get_contents("../assets/images/feed-logo.png");
    } else {
      print file_get_contents("../assets/images/feed-logo.png");
    }
  }
  public function get() {
    $st = sql::con1()->prepare('SELECT * FROM topics_category where id = :id AND status = :status');
    $st->bindParam(':id', $this->id);
    $st->bindParam(':status', $this->status);
    $st->execute();
    return $st->fetchAll();
  }
  public function search() {
    $search = "%".$this->search."%";
    $st =  sql::con1()->prepare('select tc.id, tc.title, tc.des, tc.createdon, tc.createdby, b.objid from topics_category as tc
    left join bookmark as b on b.objid = tc.id and b.type = \'cat\' and b.uid = :uid
    where (
      tc.status = :status
    ) and 
    (
      tc.title like :title or
      tc.des like :des or 
      tc.id like :id
    )');
    $st->bindParam(':status', $this->status);
    $st->bindParam(':title', $search);
    $st->bindParam(':id', $search);
    $st->bindParam(":des", $search);
    $st->bindParam(":uid", $this->user->idno);
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $data_len = count($data);
    for ($i = 0; $i < $data_len; $i++ ) {
      Html::escape($data[$i]["title"]);
      Html::escape($data[$i]["des"]);
    }
    $st = null;
    return $data;
  }
  public function getall() {
    $st =  sql::con1()->prepare('select id, title, des, createdon, createdby from topics_category where status = :status');
    $st->bindParam(':status', $this->status);
    $st->execute();
    $data = $st->fetchall(sql::assoc());
    $st = null;
    return $data;
  }
  public function saveCover() {

  }
  public function existOnSave() {
    $st = sql::con1()->prepare('select id from topics_category where title = :title and status = 1');
    $st->bindParam(':title', $this->title);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return count($data) > 0 ? true : false;
  }
  public function existOnUpdate() {
    $st = sql::con1()->prepare('select id from topics_category where title = :title and not id = :id and status = 1'); // EDITED
    $st->bindParam(':title', $this->title);
    $st->bindParam(':id', $this->id);
    $st->execute();
    $data = $st->fetchAll(sql::assoc());
    return count($data) > 0 ? true : false;
  }
  public function save() {
    $cover_col = !empty($this->cover) ? ",cover" : "";
    $cover_bind = !empty($this->cover) ? ",:cover" : "";
    $st = sql::con1()->prepare('insert into topics_category(title, des, createdby, createdon, status'.$cover_col.') values(:title,:des, :createdby,:createdon, :status'.$cover_bind.')');
    $st->bindParam(':title', $this->title);
    $st->bindParam(':des', $this->des);
    $st->bindParam(':createdby', $this->uid);
    $st->bindParam(':createdon', $this->dt);
    $st->bindParam(':status', $this->status);
    if (!empty($this->cover))
      $st->bindParam(":cover", $this->cover);
    $st->execute();
    return ['ok' => 1, 'msg' => 'Submitted'];
  }
  public function status() {
    $st = sql::con1()->prepare('update topics_category set status = :status where id = :id');
    $st->bindParam(':id', $this->id);
    $st->bindParam(':status', $this->status);
    $st->execute();
    return ['ok' => 1, 'msg' => 'Removed'];
  }
  public function update() {
    $cover_col = !empty($this->cover) ? ",cover" : "";
    $cover_bind = !empty($this->cover) ? "=:cover" : "";
    $st = sql::con1()->prepare('update topics_category set title=:title, des = :des'.$cover_col.$cover_bind.' where status = 1 and id = :id');
    $st->bindParam(':title', $this->title);
    $st->bindParam(':des', $this->des);
    $st->bindParam(':id', $this->id);
    if (!empty($this->cover))
      $st->bindParam(":cover", $this->cover);
    $st->execute();
    return ['ok' => 1, 'msg' => 'Updated successfully'];
  }
  public function trash() {
    $st = sql::con1()->prepare("update topics_category set status = '0' where id = :id");
    $st->bindParam(":id", $this->id);
    $st->execute();
    return ["ok" => "1", "msg" => "Category removed success!"];
  }
  public function submit() {
    if (empty($this->id)) {
      if (!$this->existOnSave()) {
        return $this->save();
      }
      return ['ok' => 0, 'msg' => 'Category is already exist'];
    } else {
      if (!$this->existOnUpdate()) {
        return $this->update();
      }
      return ['ok' => 0, 'msg' => 'Category is already exist'];
    }
    //$st = sql::con1()->prepare('');
  }
}