<?php 
use classes\access;
use classes\lib\sql;
use classes\url;
use classes\Html;
use classes\Cert\FeedCertificate;
use classes\Date as Date2;
use classes\user;
use classes\UserQuiz;
require_once '../___autoload.php';
$access = new access();
if (!$access->check()) {
  if (REQ == 'get') {
    print '<div style="color:red;">Unauthorized Access!</div>';
  } else if (REQ == 'post') {
    print '{"ok":false,"msg":"Unauthorized Access!"}';
  }
  exit;
}
class route {
  public function __construct() {
    $this->feed = new sql();
    $this->get = isset($_GET['r']) ? $_GET['r'] : '';
    $this->user = user::getInstance();
  }
  public function run() {
    switch ($this->get) {
      case 'test':
        print 'test';
      break;
      case 'userlist':
        $prepared_id = url::post('question_prepared_id');
        $user = $this->feed->exec('select distinct uq.uid, uo.name, uo.branch from user_quiz as uq
          inner join user_online as uo on uo.uid = uq.uid 
          where uq.question_prepared_id = :pr
        ', [
          $prepared_id
        ]);
        print json_encode($user);
      break;
      case 'essay-list':
        $prepared_id = url::post('prepared_id');
        $uid = url::post('uid');
        $data = $this->feed->exec('
          select 
            qi.type,
            qi.Question,
            qi.QIId as question_id,
            qi.points   
          from user_quiz as uq
          inner join user_quiz_item as uqi on uqi.uid = uq.uid and uq.uid = :u and uq.question_prepared_id = :pr and uqi.question_prepared_id = :prr
          inner join QuestionItems as qi on qi.QIId = uqi.question_item_id and qi.type = \'ESSAY\'
        ' ,[
          $uid,
          $prepared_id,
          $prepared_id
        ]);
        print json_encode($data);
      break;
      case 'essay_data':
        $uid = url::post('uid');
        $qid = url::post('question_id');
        $prepared_id = url::post('prepared_id');
        $data = $this->feed->exec('
          select 
            qual.text_ans,
            qual.score 
          from quiz_user_ans_list as qual
          where qual.question_item_id = :qid and qual.uid = :uid and qual.question_prepare_id = :pr
        ', [$qid, $uid, $prepared_id]);
        if (count($data) > 0) {
          Html::escape($data[0]['text_ans']);
          $data[0]['text_ans'] = base64_encode($data[0]['text_ans']);
          $data[0]['score'] = empty($data[0]['score']) || $data[0]['score'] == null ? 0: $data[0]['score'];
        }
        print json_encode($data);
      break;
      case 'essay_points':
        $score = url::post('score');
        $uid = url::post('uid');
        $prepared_id = url::post('prepared_id');
        $question_id = url::post('question_id');
        $this->feed->exec('update quiz_user_ans_list set score = :score where question_item_id = :qid and question_prepare_id = :pr and uid = :uid', [
          $score,
          $question_id,
          $prepared_id,
          $uid
        ]);
        $u = new UserQuiz($uid);
        $u->question_prepared_id = $prepared_id;
        $score = $u->getScore();
        $u->updateScore($score["correct"], $score["total"]);
        print json_encode(['ok' => 1, 'msg' => 'score updated']);
      break;
      case 'topic_cert':
        $feedCert = new FeedCertificate();
        $feedCert->edit = true;
        $feedCert->setTitle('Edit Certificate')->init()->draw()->display();
      break;
      case 'getSig':
        $no = url::post('no');
        $data = $this->feed->exec('select display_name, display_position from cert_signatories where sig_id = 1 and no = :no', [$no]);
        print json_encode($data);
      break;
      case "saveContent":
        $content = url::post("content");
        $data = $this->feed->exec("select cert_id from cert where cert_id = '1'");
        if (count($data) > 0) {
          // update
          $this->feed->exec("update cert set cert_content = :c, uid = :uid, dt = :dt where cert_id = '1'", [$content, $this->user->idno, Date2::getDate()]);
        } else {
          // insert new 
          $this->feed->exec("INSERT into cert(cert_id, cert_content, uid, dt) values(:a, :b,:c, :d)", [
            1, $content, $this->user->idno, Date2::getDate()
          ]);
        }
      break;
      case 'saveSign':
        $no = url::post('no');
        $name = url::post('name');
        $pos = url::post('pos');
        if (in_array($no, [1, 2])) {
          $data = $this->feed->exec('select top 1 no from cert_signatories where sig_id = 1 and no = :no', [$no]);
          if (count($data) > 0) {
            // update
            $this->feed->exec('
              UPDATE cert_signatories set
                display_name = :name, 
                display_position = :dispos,
                addedby = :d,
                dateadded = :uid

                where sig_id = 1 and no = :no
            ', [
              $name, $pos, $this->user->idno, Date2::getDate(), $no
            ]);
            print json_encode(['ok' => 'u']);
          } else {
            // insert
            $this->feed->exec('INSERT into cert_signatories
              (display_name,display_position,no,sig_id,addedby,dateadded)
              values(:dis,:dp,:no,:si, :a, :b)
              ', [
              $name,
              $pos,
              $no,
              '1',
              $this->user->idno,
              Date2::getDate()
            ]);
            print json_encode(['ok' => 's']);
          }
        }
      break;
      case "searchUser":
        $search = url::post("search");
        $data = $this->user->searchOnline($search);
        echo json_encode($data);
      break;
    }
  }
}
$route = new route();
$route->run();