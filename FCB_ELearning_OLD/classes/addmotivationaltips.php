<?php 
namespace classes;
use sql;
use classes\lib\sql as SQL1;
use classes\Date as Date2;

class addmotivationaltips {

    public $quote_id = '';
    public $content = '';
    public $date_uploaded = '';
    public $status = '';
    public $title = '';

    public function __construct() {
        $this->dt = Date2::getDate();
        $this->status = 'Not Shown';
        $this->con = SQL1::getInstance();
        $this->search = "";
    }

    public function savetips() {
        $datetime = date('Y-m-d H:i:s');
        $quote_id = strtotime($datetime) + 1;
        $this->con->exec('insert into feed_quote(quote_id, content, date_uploaded, status, title) values(:quote_id, :content, :date_uploaded, :status, :title)',
        [
          $quote_id, 
          $this->content, 
          $this->dt, 
          $this->status, 
          $this->title
        ]);
        return ['ok' => 1, 'msg' => 'Submitted'];
      }

      public function submit() {
        if (empty($this->quote_id)) {
          if (!$this->existOnSave()) {
            return $this->savetips();
          }
          return ['ok' => 0, 'msg' => 'Title already exist'];
        }
      }

      public function existOnSave() {
        $st = sql::con1()->prepare('select quote_id from feed_quote where title = :title');
        $st->bindParam(':title', $this->title);
        $st->execute();
        $data = $st->fetchAll(sql::assoc());
        return count($data) > 0 ? true : false;
      }

      public function getAll(){
        $st = sql::con1()->prepare("SELECT * FROM feed_quote ORDER BY date_uploaded DESC");
        $st->execute();
        $data = $st->fetchAll();
        return $data;
    }

    public function search() {
        $search = "%".$this->search."%";
        $st =  sql::con1()->prepare('select title, status, date_uploaded, quote_id from feed_quote where (title like :title or date_uploaded like :date_uploaded or status like :status or quote_id like :quote_id) order by date_uploaded desc');
        $st->bindParam(':title', $search);
        $st->bindParam(':date_uploaded', $search);
        $st->bindParam(":status", $search);
        $st->bindParam(":quote_id", $search);
        $st->execute();
        $data = $st->fetchall(sql::assoc());
        $data_len = count($data);
        $st = null;
        return $data;
    }

    public function trash() {
        $st = sql::con1()->prepare("DELETE FROM feed_quote WHERE quote_id = :id");
        $st->bindParam(":id", $this->id);
        $st->execute();
    }

    public function display() {
        $st = sql::con1()->prepare("update feed_quote set status = 'Displayed' where quote_id = :id");
        $st->bindParam(":id", $this->id);
        $st->execute();
        $st2 = sql::con1()->prepare("update feed_quote set status = 'Not Shown' where quote_id != :id");
        $st2->bindParam(":id", $this->id);
        $st2->execute();
    }

    public function undisplay() {
        $st = sql::con1()->prepare("update feed_quote set status = 'Not Shown' where quote_id = :id");
        $st->bindParam(":id", $this->id);
        $st->execute();
    }



}