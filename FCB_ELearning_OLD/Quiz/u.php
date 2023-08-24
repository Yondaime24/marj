<?php 
use classes\url;
use classes\QuizPrepare;
use classes\user;
use classes\auth;
use classes\UserQuiz;
use classes\Date as Date2;
use classes\UserAns;
use classes\lib\sql;
require_once '../___autoload.php';
auth::isLogin();
class App {
  public function __construct() {
    $this->route = isset($_GET['route']) ? $_GET['route'] : '';
  }
  public function Run() {
    switch ($this->route) {
      case 'start':
        $id = url::post('id');
        $uq = new UserQuiz();
        $uq->id = $id;
        $uq->RData();
           /*
            * $this->qid = '';
            * $this->uid = '';
            * $this->data = '';
            * $this->dt = '';
            * $this->score = 0;
            * $this->timelimit = 0;
            * $this->totalitem = 0;
            *
            */
          //////////////////////end shuffle
        print '{"ok":"1"}';
      break;
      case 'load':
        $res = [];
        if (isset($_SESSION['quiz'])) {
            $index = url::post('index');
            $data = $_SESSION['quiz'];
            $q = json_decode($data[0]['data']);
            $res = [];
            if (isset($q[$index])) {
              // ok
              $res['ok'] = 1;
              $res['data'] = json_encode($q[$index]);
              print json_encode($res);
            } else {
              // not okay
              print '{"ok":"0","msg":"Error"}';
            }
        } else {
          print '{"ok":"0","msg":"Error"}';
        }
      break;
      case 'submitI':
        $cid = url::post('cid');
        $question_id = url::post('question_id');
        $user = new User();
        $ua = new UserAns();
        $userQuiz = new UserQuiz();
        $userQuiz->feed = new sql();
        $userQuiz->init();
        $userQuiz->initQuestion($question_id);
        $ua->choice_id = $cid;
        $ua->question_item_id = $question_id;
        $ua->type = url::post('type');
        $ua->uid = $user->idno;
        $ua->question_prepared_id = $userQuiz->question_prepared_id;
        $ua->text_ans = url::post('ctxt');
        if (!$userQuiz->isDone()) {
          // the user can only answer if they are done
          // or the hit the button done
          if ($userQuiz->time_up())
            $ua->is_late = '1';
          $ua->submit();
        }

      break;
      case 'myAns':
        $question_id = url::post("question_id");
        $userQuiz = new UserQuiz();
        $userQuiz->feed = new sql();
        $userQuiz->init();
        $userQuiz->initQuestion($question_id);

        $userAns = new UserAns();
        $userAns->question_item_id = $question_id;
        $userAns->question_prepared_id = $userQuiz->question_prepared_id;
        $data = $userAns->enumAns();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
          $data[$i]['text_ans'] = base64_encode($data[$i]['text_ans']);
        }
        print json_encode($data);
      break;
      case 'rem':
        $id  = url::post("id");
        $userAns = new UserAns();
        $userAns->id = $id;
        $userAns->delete();
      break;
      case "done":
        $quiz_id = url::post("quiz_id");
        $uq = new UserQuiz();
        $uq->feed = new sql();
        $uq->init();
        $uq->question_prepared_id = $quiz_id;
        $user = new user();
        $uq->id = $quiz_id;
        $uq->uid = $user->idno;
        $uq->dt = Date2::getDate();
        $uq->done = 0x01;
        $uq->done();
        /**
         * if done clear the session
         */
        unset($_SESSION['quiz']);
      break;
      default:
      break;
    }
  }
}
$app = new App();
$app->Run();