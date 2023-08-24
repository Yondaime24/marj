<?php 
namespace classes;
use classes\user;
use classes\Date as Date2;
use classes\lib\sql as sql2;
class QuizFull {
  public function __construct(string $uid = "") {
    $this->feed = sql2::getInstance();

    $this->user = user::getInstance();
    $this->status = '1';

    $this->default_q = '';
    $this->q = '';
    $this->uid = !empty($uid) ? $uid : $this->user->idno;
  }
  public function getQuiz(): array {
    $data = $this->feed->exec("
      select  
        qp.id as id,
        qp.title,
        qp.timelimit,
        st.title as st_title,
        t.title as t_title,
        tc.title as tc_title,
        st.id as sub_id,
        uq.done,
        uq.datestarted
      from question_prepared as qp
      inner join QuestionGroup as qg on qg.QGId = qp.group_id and qg.QCCode = 'SUBTOPIC'
        --inner join question_prepared_item as qpi on qp.id = qpi.prepared_id
        inner join sub_topics as st on st.id = qg.ObjId and qg.QCCode = 'SUBTOPIC'
        inner join topics as t on t.id = st.topic_id
        inner join topics_category as tc on tc.id = t.catid
        left join user_quiz as uq on uq.question_prepared_id = qp.id and uq.uid = :uid
    ", [$this->uid]);

    return $data;
  }
  public function isCompleteAll($cat_id) {
    $data = $this->feed->exec("select tc.id as catid, uq.done from question_prepared as qp
      inner join QuestionGroup as qg on qg.QGId = qp.group_id
      inner join sub_topics as st on st.id = qg.ObjId and qg.QCCode = 'SUBTOPIC'
      inner join topics as t on t.id = st.topic_id
      inner join topics_category as tc on tc.id = t.catid
      left join user_quiz as uq on uq.question_prepared_id = qp.id and uq.uid = :uid 
      where tc.id = :catid
    ", [$this->user->idno, $cat_id]);
    $data_len = count($data);
    for ($i = 0; $i < $data_len; $i++) {
      if ($data[$i]['done'] != 1) {
        return false;
      }
    }
    return true;
  }
}