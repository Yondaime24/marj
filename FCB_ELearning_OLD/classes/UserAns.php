<?php 
// This object is use for retrieving the Users answer
// saving the answer of the individual user
namespace classes;
use classes\lib\sql;
use classes\Date as Date2;
use classes\user;
use classes\lib\sqlfunc;

class UserAns {
  public function __construct() {
    $this->user = user::getInstance();
    $this->dt = Date2::getDate();
    $this->uid = $this->user->idno;
    $this->feed = sql::getInstance();

    $this->choice_id = '';
    $this->question_prepared_id = '';
    $this->is_late = 0x00;
    $this->type = '';
    $this->text_ans = '';
    $this->question_item_id = '';
  }
  private function update() {
    $this->feed->exec('update quiz_user_ans_list set
      question_item_choice_id = :cid,
      dateans = :dateans,
      text_ans = :text_ans,
      is_late = :is_late
      where question_item_id = :q and question_prepare_id = :qw and uid = :uid', [
        $this->choice_id,
        Date2::getDate(),
        $this->text_ans,
        $this->is_late,
        $this->question_item_id,
        $this->question_prepared_id,
        $this->uid
    ]);
  }
  private function save() {
    $s = new sqlfunc();
    $s->table = 'quiz_user_ans_list';
    $s->col = 'id';
    $id = $s->lastId();
    // This query use for storing the new user answer in there questionaire
    $this->feed->exec('
      insert into quiz_user_ans_list(id, question_item_id, dateans, uid, text_ans, question_prepare_id, is_late, question_item_choice_id)
      values(:z,:a,:b,:c,:d,:e,:f,:g)
    ', [
      $id,
      $this->question_item_id,
      Date2::getDate(),
      $this->uid,
      $this->text_ans,
      $this->question_prepared_id,
      $this->is_late,
      $this->choice_id
    ]);
  }
  public function submit() {
    if (in_array($this->type, ['mul', 'tf', 'essay'])) {
      // Can also be use in the True or false Question
      // query for the multiple it check if the answer is already exist , 
      // so fthe answer is exist in the table then the 
      // the answer is updated
      $data = $this->feed->exec('select * from quiz_user_ans_list as qual  
      inner join question_prepared_item as qpi on qpi.question_item_id = qual.question_item_id
    where qpi.question_item_id = :qid and uid = :uid and qual.question_prepare_id = :pr', [
        $this->question_item_id,
        $this->uid,
        $this->question_prepared_id
      ]);
      if (count($data) > 0) {
        // update the answer
        // update the answer
        $this->update();
      } else {
        // insert new answer if not exist
        $this->save();
      }
    } else if ($this->type == 'enum') {
      // submit for enumeration
      $this->save();
    }
  }
  public function submitAll() {

  }
  public function remove() {

  }
  public function delete() {
    // permanent delete the enumeration 
    // $st = sql::con1()->prepare('delete from quiz_user_ans_list where id = :id and uid = :uid');
    // $st->bindParam(":uid", $this->uid);
    // $st->bindParam(":id", $this->id);
    // $st->execute();
  }
  public function enumAns() {
    $data = $this->feed->exec('select * from quiz_user_ans_list as qual  
      inner join question_prepared_item as qpi on qpi.question_item_id = qual.question_item_id
    where qpi.question_item_id = :qid and uid = :uid and qual.question_prepare_id = :pr', [
      $this->question_item_id,
      $this->uid,
      $this->question_prepared_id
    ]);
    return $data;
  }
  public function getAns() {
    // $st = sql::con1()->prepare('select * from quiz_user_ans_list as qual where quiz_id = :quiz_id and uid = :uid');
    // $st->bindParam(':quiz_id', $this->quiz_id);
    // $st->bindParam(":uid", $this->uid);
    // $st->execute();
    // $data = $st->fetchAll();
    // $len = count($data);
    // $obj = [];
    // for ($j = 0; $j < $len; $j++) {
    //   $obj[$j] = new UserAns();
    //   $obj[$j]->quiz_id = $data[$j]['quiz_id'];
    //   $obj[$j]->cid = $data[$j]['cid'];
    //   $obj[$j]->ctxt = $data[$j]['text_ans'];
    // }
    // return $obj;
  }
}