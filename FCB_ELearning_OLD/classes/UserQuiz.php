<?php 
namespace classes;
use classes\Date as Date2;
use classes\user;
use classes\QuizPrepare;
use classes\question_prepared_item;
use classes\UserQuizItem;
use classes\lib\sql;

class UserQuiz {
  public function __construct(string $uid = "") {
    $this->user = user::getInstance();
    $this->qp = new QuizPrepare();
    $this->feed = sql::getInstance();
    
    $this->dt = Date2::getDate();
    $this->uid = empty($uid) ? $this->user->idno : $uid;

    $this->question_prepared_id = '';
    $this->status = 0x01;
    $this->done = 0x00;

    $this->prepared_item = new question_prepared_item();

    $this->user_quiz_item = new UserQuizItem();
    $this->user_quiz_item->uid = $this->uid;  
    //$this->user_quiz_item->feed = $this->feed;
  }
  public function rank($prepared_id, $uid) {
    $data = $this->feed->exec("
      select * from (
        select 
        uid, RANK() over(order by uq.score desc) as rank_no 
        from user_quiz as uq where uq.question_prepared_id = :pr
      ) as tmp
      where tmp.uid = :uid
    ", [$prepared_id, $uid]);
    return isset($data[0]["rank_no"]) ? $data[0]["rank_no"] : "";
  }
  public function getRankByQuiz($prepared_id) {
    $data = $this->feed->exec("
      select * from (
        select 
        top 10
        uq.uid, 
        RANK() over(order by uq.score desc) as rank_no,
        u.name,
        u.branch,
        uq.score,
        uq.total_score
        from 
          user_quiz as uq 
          inner join user_online as u on u.uid = uq.uid
        where uq.question_prepared_id = :pr
      ) as tmp
    ", [$prepared_id]);
    return $data;
  }
  public function updateScore(float $score, float $total_score) {
    // cache
    $this->feed->exec("
      update user_quiz set score = :score, total_score = :tscore
      where uid = :uid and question_prepared_id = :pr       
    ", [
      $score,
      $total_score,
      $this->uid,
      $this->question_prepared_id
    ]);
  }
  public function getQuestion(): array {
    $arr = [
      "title" => "",
      "data" => []
    ];
    $data = $this->feed->exec("
      select qp.title from question_prepared as qp
      where qp.id = :id
    ", [
      $this->question_prepared_id
    ]);
    $arr["title"] = isset($data[0]["title"]) ? $data[0]["title"] : "";
    
    $qdata = $this->feed->exec("select qi.points, qi.Question as question, uqi.no, qi.QIId as id, qi.type from user_quiz_item as uqi
      inner join QuestionItems as qi on qi.QIId = uqi.question_item_id
      where uqi.uid  = :uid and uqi.question_prepared_id = :id order by uqi.no asc
    ", [
      $this->uid,
      $this->question_prepared_id
    ]);
    
    $arr["data"] = $qdata;
    $qdata_len = count($qdata);
    for ($i = 0; $i < $qdata_len; $i++) {
      $arr["data"][$i]["item"] = $this->feed->exec("select 
      qic.Des, qic.IsAnsKey
      from QuestionItemsChoices as qic
      where qic.QIId = :idd and qic.Status = '1'
      ", [
        $qdata[$i]["id"]
      ]);
      $arr["data"][$i]["ans"] = $this->feed->exec("
        select 
        qual.text_ans,
        score,
        is_late
        from quiz_user_ans_list as qual 

        where  qual.uid = :uid and qual.question_prepare_id = :pr and qual.question_item_id = :qid
      ", [
        $this->uid,
        $this->question_prepared_id,
        $qdata[$i]["id"]
      ]);
    }
    return $arr;
  }
  public function init() {
  }
  public function initQuestion($question_id): void {
   $data = $this->feed->exec('SELECT qpi.prepared_id from question_prepared_item as qpi
    where qpi.question_item_id = :i
    ', [$question_id]);
   if (count($data) > 0)
    $this->question_prepared_id = $data[0]['prepared_id'];
  }
  public function isDone() {
    $data = $this->feed->exec('SELECT done from user_quiz where question_prepared_id = :a and uid = :uid', [$this->question_prepared_id, $this->uid]);
    if (count($data) > 0) {
      return $data[0]['done'];
    }
    return 0;
  }
  public function done() {
    $this->feed->exec('update user_quiz set done = :done, datefinished = :dt where question_prepared_id = :pr and uid = :uid', [
      $this->done,
      $this->dt,
      $this->question_prepared_id,
      $this->uid
    ]);
    $score = $this->getScore();
    $this->updateScore((float) $score["correct"], (float) $score["total"]);
  }
  public function time_up() {
    $data = $this->feed->exec('SELECT uq.datestarted, qp.timelimit from user_quiz as uq
     inner join question_prepared as qp on qp.id = uq.question_prepared_id
     where uq.question_prepared_id = :pr and uq.uid = :uid', 
      [
        $this->question_prepared_id,
        $this->uid
      ]
    );    
    if (count($data) > 0) {
      $d = Date2::rem(time(), strtotime($data[0]["datestarted"]), $data[0]["timelimit"]);
      if (
        $d["hour"] == 0 &&
        $d["day"] == 0 &&
        $d["min"] == 0 &&
        $d["sec"] == 0
      ) {
        return true;
      }
    }
    return false;
  }
  public function add() {
    $this->feed->exec('INSERT into user_quiz(question_prepared_id, datestarted, uid, status, done) values(:uq, :dt, :uid, :status, :done)', [
      $this->question_prepared_id,
      $this->dt,
      $this->uid,
      $this->status,
      $this->done
    ]);
  }
  public function exist() {
    $data = $this->feed->exec('SELECT uid from user_quiz where question_prepared_id = :a and uid = :uid', [$this->question_prepared_id, $this->uid]);  
    return count($data) > 0 ? true : false;
  }
  public function getAnswered() {
    $data = $this->feed->exec('SELECT * from quiz_user_ans_list as qual
    where question_prepare_id = :id and uid = :uid
  ', [
      $this->question_prepared_id,
      $this->uid
    ]);
    return $data;
  }
  public function finish() {

  }
  // Choice Item
  public function choices($question_id) {
    $data = $this->feed->exec('SELECT QIC.Des, QIC.QICId as id, QIC.Type, QIC.Points as Points from QuestionItemsChoices as QIC
      where QIC.QIId = :id
    ', [
      $question_id
    ]);
    return $data;
  }
  // Question Items
  public function item($item = '*') {
    $gdata = [];
    $data = $this->feed->exec('SELECT '.$item.' from user_quiz_item as uqi
      inner join question_prepared_item as qpi on qpi.question_item_id = uqi.question_item_id
      inner join QuestionItems as QI on QI.QIId = qpi.question_item_id
      where uqi.uid = :uid and question_prepared_id = :qpi order by uqi.no asc
    ', [$this->uid, $this->question_prepared_id]);
    $data_len = count($data);
    for ($i = 0; $i < $data_len; $i++) {
      $gdata[$i]['QuestionItem'] = $data[$i];
      $gdata[$i]['ChoiceItem'] = $this->choices($data[$i]['id']);
    }
    return $gdata;
  }
  // 
  public function getScore(int $is_late = 0): array {
    // get the total score for the quiz
    // start of the quiz
    $total = 0;
    $score = 0;

    $mul = $this->feed->exec('SELECT * from question_prepared_item as qpi
      inner join QuestionItemsChoices as qic on qic.QIId = qpi.question_item_id and qic.IsAnsKey = \'1\' and qic.Type in(\'BL\', \'MUL\', \'ENUM\')
    where qpi.prepared_id = :pr', [$this->question_prepared_id]);
    
    $essay = $this->feed->exec('SELECT sum(qi.Points) as points from question_prepared_item as qpi
      inner join QuestionItems as qi on qi.QIId = qpi.question_item_id
    where qpi.prepared_id = :pr and qi.type = \'ESSAY\'', [$this->question_prepared_id]);

    if (count($mul) > 0)
      $total += count($mul);
    $total += $essay[0]['points'];
    // end total points of the quiz

    $initial_score = $this->feed->exec('SELECT qual.uid from quiz_user_ans_list as qual 
    inner join QuestionItemsChoices as qic on qic.QICId = qual.question_item_choice_id and qic.IsAnsKey = \'1\'
    where qual.uid = :uid and qual.question_prepare_id = :au and qic.Type in(\'BL\', \'MUL\')
    ', [$this->uid, $this->question_prepared_id]);
    if (count($initial_score) > 0)
      $score += count($initial_score);
     // for the enumeration score 
     $enum = $this->feed->exec("
      SELECT * FROM quiz_user_ans_list AS qual
      INNER JOIN QuestionItemsChoices AS qic ON qic.QIId = qual.question_item_id AND qic.status = '1' and qual.text_ans = qic.Des and qic.Type = 'ENUM'
      WHERE qual.question_prepare_id = :pr AND qual.uid = :uid
     ", [
      $this->question_prepared_id,
      $this->uid
     ]);

    $es = $this->feed->exec('SELECT sum(qual.score) as point from quiz_user_ans_list as qual
      inner join QuestionItems as qi on qi.QIId = qual.question_item_id and qi.type = \'ESSAY\'
      where qual.uid = :uid and qual.question_prepare_id = :quid
    ', [$this->uid, $this->question_prepared_id]);

    $score += count($enum);
    $score += $es[0]['point'];
    return [
      'correct' => $score,
      'total' => $total
    ];
  }
  public function get($dis = '*', $item = '*') {
    $gdata = ['header', 'data'];
    $data = $this->feed->exec('
      SELECT '.$dis.'  FROM user_quiz as uq
      inner join question_prepared as qp on qp.id = uq.question_prepared_id
      inner join QuestionGroup as qg on qg.QGId = qp.group_id and QCCode = \'SUBTOPIC\'
      inner join sub_topics as st on st.id = qg.Objid and QCCode = \'SUBTOPIC\'
      inner join topics as t on t.id = st.topic_id
      inner join topics_category as tc on tc.id = t.catid
      where uq.uid = :uid and uq.question_prepared_id = :pr
    ', [$this->uid, $this->question_prepared_id]);
    $gdata['header'] = $data;
    $gdata['data'] = $this->item('QI.Question, QI.QIId as id, QI.type');
    return $gdata;
  }
  public function create() {
    $this->prepared_item->prepared_id = $this->question_prepared_id;
    $this->qp->id = $this->question_prepared_id;
    $this->question_prepared_id;
    $data = $this->qp->getById();
    if (count($data) > 0) {
      if (!$this->exist()) {
         $this->add(); // add new quiz for the user
         // then add new randomize the items of the question 
         $item_data = $this->prepared_item->randomItem();
         $item_data_len = count($item_data);
         $this->user_quiz_item->question_prepared_id = $this->question_prepared_id;
         for ($i = 0; $i < $item_data_len; $i++) {
            $this->user_quiz_item->add($i + 1, $item_data[$i]['question_item_id']);
         }
      }
    }
  }
  public function getUsers() {
    $this->feed->exec('SELECT * from user_quiz as uq
      where uq.
    ');
  }
  public function completed(): int {
    $data = $this->feed->exec("select count(uid) as total from user_quiz where status = '1' and done = '1' and uid = :uid", [
      $this->uid
    ]);
    return isset($data[0]["total"]) ? $data[0]["total"] : 0;
  } 
}