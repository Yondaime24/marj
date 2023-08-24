<?php 
use classes\url;
use classes\auth;
use classes\Question\Group;
use classes\Date as Date2;
use classes\user;
use classes\Question\Items;
use classes\Question\ItemsChoices;
use classes\Quiz;
use classes\Q;
use classes\access;
use classes\QuizPrepare;
use classes\notification;

require '../___autoload.php';
auth::isLogin();

$route = isset($_GET["route"]) ? $_GET["route"] : "";

$user = new user();
$ac = new access(['PR']);
$admin = $ac->check($user->ulevel);
if (!$admin) {
  print 'Unauthorized access!';
  exit;
}

function App($route) {
  switch ($route) {

    case 'AddGroup':
      
      $user = new user();
      $group = new Group();
      $group->QCCode = url::post('Code');
      $group->Title = url::post('Title'). "_".date('m.d.y_h.i.s');
      $group->Description = url::post('Description');
      $group->DateCreated = Date2::GetDate();
      $group->CreatedBy = $user->idno; 
      $group->Status = '1';
      $group->QGId = url::post('Id');
      $group->ObjId = url::post('ObjId');
      print $group->Add();
    
    break;
      
    case 'GetGroup':
      
      $group = new Group();
      $group->Status = '1';
      $group->QCCode = url::post('GroupType');
      $group->ObjId = url::post('ObjId');
      print $group->GetAll();
    
    break;

    case 'AddQuestionItem':

      $user = new user();
      $items = new Items();
      $itemChoice = new ItemsChoices();

      $points = url::post('points');
      $items->QIId = url::post('QIId');
      $items->Question = url::post('Question');
      $items->AnsKey = url::post('AnsKey');
      $items->QCCode = url::post('QCCode');
      $items->QGId = url::post('QGId');
      $items->Type = url::post('Type');
      $items->Status = '1';
      $items->DateCreated = Date2::GetDate();
      $items->idno = $user->idno;
      $items->points = '1';
      switch ($items->Type) {
        //for multiple choices only
        case 'MUL':
          print $items->Add();
        break;
        // for true and false only
        // the true or false must auto create a choice items
        case 'BL':
          // the response to the client is change a little bit
          // getting all the list of the ids created in the items
          // only add a true or false for the new add question items
          
          $true = '{}';
          $false = '{}';
          $q_add = $items->Add();
          $j = json_decode($q_add);
          if (empty($items->QIId)) {
            $itemChoice->QIId = $j->LastId;
            $itemChoice->Type = $items->Type;
            $itemChoice->Status = '1';
            $itemChoice->Des = 'True';
            $itemChoice->CreatedBy = $user->idno;
            $itemChoice->DateCreated = Date2::getDate();
            $true = $itemChoice->Add();

            $itemChoice->QIId = $j->LastId;
            $itemChoice->Type = $items->Type;
            $itemChoice->Status = '1';
            $itemChoice->Des = 'False';
            $itemChoice->CreatedBy = $user->idno;
            $itemChoice->DateCreated = Date2::getDate();
            $false = $itemChoice->Add();
          }
          $resp = [
            'q' => $q_add,
            't' => $true,
            'f' => $false
          ];
          print json_encode($resp);
        break;
        case 'ENUM':
          print $items->Add();          
        break;
        case 'ESSAY':
          $items->points = $points;
          print $items->Add();
        break;
        default:
          print 'Invalid';
        break;
      }
      break;
    case 'trashQ':
      $item = new Items();
      $user = new user();
      $item->QIId = url::post('id');
      //set status to zero to remove the  questions 
      $item->Status = '0';
      $item->dt = Date2::getDate();
      $item->uid = $user->idno;
      $item->trash();
      break;
    // set the choices items as key answer
    case 'key-answer':
      $q_id = url::post('q_id');
      $c_id = url::post('c_id');

      $choice = new ItemsChoices();
      $choice->id = $c_id;
      $choice->q_id = $q_id;
      $choice->Status = '1';
      $choice->setKey();
    break;
    // adding items to the answer
    case 'AddItemtoQuestion':
      
      $user = new user();
      $item = new ItemsChoices();
      $item->QICId = url::post('Id');
      $item->QIId = url::post('QIId');
      $item->Des = url::post('Question');
      $item->Type = url::post('Type');
      $item->CreatedBy = $user->idno;
      $item->DateCreated = Date2::GetDate();
      $item->Status = '1';

      if ($item->Type == 'MUL') {
        print $item->Add();
      }
      
      else if ($item->Type == 'TF') {
        //directly add the True false question
        $item->Des = 'True';
        $item->Add();
        $item->Des = 'False';
        print $item->Add();
      } else if ($item->Type == 'ENUM') {
        $item->IsAnsKey = '1';
        print $item->Add();
      } else if ($item->Type == "SY") {
        $item->IsAnsKey = '0';
        $item->Points = $item->Des;
        $item->Add();
      }
      break;

    case 'GetQuiz':
      $user = new user();
      $quiz = new Quiz();
      $quiz->GroupId = url::post('GroupId');
      print base64_encode(json_encode($quiz->Get()));
      break;
    case 'addNewGroup':
      $group = new Group();
      $user = new user();
      $q = new Q();

      $group->Status = '1';
      $group->dt = Date2::getDate();
      $group->uid = $user->idno;
     

     $type = url::post('type');
     $objId = url::post('obj');

     $group->QCCode = $type;
     
     
     $group->uid = $user->idno;
     $group->dt = Date2::getDate();
     $group->ObjId = $objId;  
     $q->type = $type;    
     $q->objId = $objId;
    
    if ($type == 'CAT') {
       $data = $q->getCategory();
       if (count($data) == 0) exit;
       $group->Title = $data[0]['title'];
       $group->Add();
    } else if ($type == 'MAINTOPIC') {
       $data = $q->getMainTopic();
       if (count($data) == 0) exit;
       $group->Title = $data[0]['title'];
       $group->Add();
    } else if ($type == 'SUBTOPIC') {
       $data = $q->getSubTopic();
       if (count($data) == 0) exit;
       $group->Title = $data[0]['title'];
       $group->Add();
    }
    /*
    Group properties
    public $QGId = '';
    public $Title = '';
    public $Description = '';
    public $Status = '';
    public $TimeLimit = '';
    public $QCCode = '';
    public $CreatedBy = '';
    public $DateCreated  = '';
    public $ObjId = '';
     */

    //$group->Title = url::post('Title');
    
    //$group->Add();

    break;
    case 'ChoiceItemTrash':
      $user = new user();
      $item = new ItemsChoices();
      $item->QICId = url::post('id');
      $item->dt = Date2::GetDate();
      $item->uid = $user->idno;
      $item->Status = '0';
      $item->Trash();
      break;
    case 'grem':
      $group = new Group();
      $user = new user();
      $group->Status = '0'; 
      $group->QGId = url::post('id');
      $group->uid = $user->idno;
      $group->dt = Date2::getDate();
      print $group->Trash();
    break;
    case 'prepare':
      /* prepare quizz */
      $user = new user(); // object of the user
      $qp = new QuizPrepare(); // object for QuizPrepare
      $qp->group_id = url::post('group_id');
      $qp->title = url::post('title');
      $qp->itemno = url::post('itemno');
      $qp->timelimit = url::post('timelimit');
      $qp->status = '1';
      $qp->dt = Date2::getDate();
      $qp->uid = $user->idno;
      print $qp->submit();
      $notif = new notification();
      $notif->write([
        "msg" => "New quiz is available!",
        "code" => "QUIZ",
        "id" => ""
      ]);
    break;
    default:
      print 'Invalid Url';
    break;
  }
}
App($route);