<?php 
namespace classes;
use sql;
use classes\lib\sql as SQL1;
use classes\Date as Date2;

class adddailyreads {

    public $about_id = '';
    public $content = '';
    public $date_inserted = '';
    public $status = '';
    public $title = '';

    public function __construct() {
      $this->dt = Date2::getDate();
      $this->status = 'Not Shown';
      $this->con = SQL1::getInstance();
      $this->search = "";
    }

    public function save() {
        // $st = sql::con1()->prepare('insert into feed_about(content, date_inserted, status, title) values(:content, :date_inserted, :status, :title)');
        // $st->bindParam(':content', $this->content);
        // $st->bindParam(':date_inserted', $this->dt);
        // $st->bindParam(':status', $this->status);
        // $st->bindParam(':title', $this->title);
        // $st->execute();
        $datetime = date('Y-m-d H:i:s');
        $about_id = strtotime($datetime) + 1;
        $this->con->exec('insert into feed_about(about_id, content, date_inserted, status, title) values(:about_id, :content, :date_inserted, :status, :title)',
        [
          $about_id, 
          $this->content, 
          $this->dt, 
          $this->status, 
          $this->title
        ]);
        return ['ok' => 1, 'msg' => 'Submitted'];
      }

    public function getAll(){
        $st = sql::con1()->prepare("SELECT * FROM feed_about ORDER BY date_inserted DESC");
        $st->execute();
        $data = $st->fetchAll();
        return $data;
    }

    public function submit() {
        if (empty($this->id)) {
          if (!$this->existOnSave()) {
            return $this->save();
          }
          return ['ok' => 0, 'msg' => 'Title already exist'];
        }
      }

    public function existOnSave() {
        $st = sql::con1()->prepare('select about_id from feed_about where title = :title');
        $st->bindParam(':title', $this->title);
        $st->execute();
        $data = $st->fetchAll(sql::assoc());
        return count($data) > 0 ? true : false;
    }

    public function search() {
      $search = "%".$this->search."%";
      $st =  sql::con1()->prepare('select title, status, date_inserted, about_id from feed_about where (title like :title or date_inserted like :date_inserted or status like :status or about_id like :about_id) order by date_inserted desc');
      $st->bindParam(':title', $search);
      $st->bindParam(':date_inserted', $search);
      $st->bindParam(":status", $search);
      $st->bindParam(":about_id", $search);
      $st->execute();
      $data = $st->fetchall(sql::assoc());
      $data_len = count($data);
      $st = null;
      return $data;
    }

    public function trash() {
      $st = sql::con1()->prepare("DELETE FROM feed_about WHERE about_id = :id");
      $st->bindParam(":id", $this->id);
      $st->execute();
    }

    public function display() {
      $st = sql::con1()->prepare("update feed_about set status = 'Displayed' where about_id = :id");
      $st->bindParam(":id", $this->id);
      $st->execute();
      $st2 = sql::con1()->prepare("update feed_about set status = 'Not Shown' where about_id != :id");
      $st2->bindParam(":id", $this->id);
      $st2->execute();
    }

    public function undisplay() {
      $st = sql::con1()->prepare("update feed_about set status = 'Not Shown' where about_id = :id");
      $st->bindParam(":id", $this->id);
      $st->execute();
    }



}