<?php 
/*
 * Class form preparing the quiz
 */
namespace classes;
use classes\Question\Items;
use classes\Question\ItemsChoices;
use classes\lib\sql;
use classes\lib\sqlfunc;
use classes\question_prepared_item;
use classes\Factory\Statistics;

class QuizPrepare implements Statistics {
  public function __construct() {
    $this->id = '';
    $this->title = '';
    $this->group_id = '';
    $this->itemno = 0;
    $this->timelimit = 0;
    $this->ud = ''; // user id who transact the query
    $this->dt = ''; // date transaction
    $this->status = 1;
    $this->dt = '';
    $this->feed = sql::getInstance();
    $this->sqlf = new sqlFunc();
  }
  // @overide from statistics
  public function count() {
    $data = $this->feed->exec("select count(id) as total from question_prepared where status = :status ", [
      1
    ]);
    return isset($data[0]["total"]) ? $data[0]["total"] : 0;
  }

  private function msg($t, $m) {
    // $t must 1 or zero
    return '{"ok":"'.$t.'","msg":"'.$m.'"}';
  }
  // retrive all the quiz by category
  // retrieve the data by group
  public function get() {
    return $this->feed->exec('select * from question_prepared as qp where group_id = :group_id and status = :status', [
      $this->group_id, $this->status
    ]);
  }
  public function getById() {
    return $this->feed->exec('select * from question_prepared as qp where qp.id = :id and qp.status = :status', [$this->id, $this->status]);
  }
  public function getData() {
    return $this->feed->exec('select * from question_prepared as qp where id = :id and status = :status', [$this->id, $this->status]);
  }
  private function checkTotalItem() {
    $item = new Items();
    $item->QGId = $this->group_id;
    $item->Status = 1;
    return $item->getTotal();
  }
  private function checkOnSave() {
    $data = $this->feed->exec('select * from question_prepared as qp where group_id = :group_id and status = :status', [$this->group_id, $this->status]);
    return count($data) > 0 ? true: false;    
  }
  private function checkOnUpdate() {
    $data = $this->feed->exec('select * from question_prepared as qp where (group_id = :group_id and status = :status) and not id = :id', [$this->group_id, $this->status, $this->id]);
    return count($data) > 0 ? true : false;
  }
  public function submit() {
    $this->timelimit = (int)$this->timelimit;
    if (empty($this->title))
      return $this->msg(0, "Title is required");
    if (empty($this->group_id))
      return $this->msg(0, 'Something went wrong!, Please reload your browser!');
    if (empty($this->itemno))
      return $this->msg(0, 'Item number is required!');
    if ($this->timelimit < 0)
      return $this->msg(0, 'Time limit is required! Set zero if no time limit!');
    $totalItem = $this->checkTotalItem();
    if ($totalItem == 0)
      return $this->msg(0, 'You can\'t prapare quiz, you have no question added in your topics or quiz group');
    if ($this->itemno > $totalItem) 
      return $this->msg(0, 'Number of Items must not greater than the number of question added in your Quiz group, Max Total Item: ' . $totalItem);
    if (empty($this->id)) {
      /* insert new preparation */  
      if ($this->checkOnSave())
        return $this->msg(0, "Title already exist!");
      return $this->save();
    } else {
      /* update the prepared quiz */
      if ($this->checkOnUpdate())
        return $this->msg(0, "Title already exist!");
      $this->update();
    }
  }
  public function getLastId() {
    $this->sqlf->col = 'id';
    $this->sqlf->table = 'question_prepared';
    return $this->sqlf->lastId();
  }
  public function save() {
    $prepared_item = new question_prepared_item();
    $prepared_item->feed = $this->feed;
    $prepared_item->init();
    
    $item = new Items();
    $choice = new ItemsChoices();
    $item->QGId = $this->group_id;
    $item->Status = 1;
    
    $data = $item->random($this->itemno);
    $gid = $this->getLastId();
    $data_len = count($data);
    $prepared_item->prepared_id = $gid;
    $this->feed->exec('insert into question_prepared
      (id, title, group_id, timelimit, createdby, datecreated, status) 
      values(:id, :title,:group_id,:timelimit,:createdby,:datecreated, :status)', [
      $gid,
      $this->title,
      $this->group_id,
      $this->timelimit,
      $this->uid,
      $this->dt,
      $this->status
    ]);
    for ($i = 0; $i < $data_len; $i++) 
      $prepared_item->add($i + 1, $data[$i]['QIId']);
    return $this->msg(1, "New Quiz Added!");
  }
  // this for the quiz prepared Item
  public function update() {

  }
  public function trash() {
 print "test";
  }
}