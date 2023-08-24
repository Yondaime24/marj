<?php 
use classes\user;
use classes\chatBox;
use classes\url;
use classes\auth;
use classes\key;
use classes\access;
use classes\Date as Date2;
use classes\lib\sql;
require '../___autoload.php';
auth::isLogin();

$route = isset($_GET["route"]) ? $_GET["route"] : "";

function app($route) {
  if ($route == "send") { 
    $user = user::getInstance();
    $chat = new chatBox();
    $key = new key();
    
    $admin = new access(['PR']); //list of admin roles
    $chat->status = 1;
    $chat->dateSend = Date2::getDate();
    $chat->isRead = '0';
    if ($admin->check($user->ulevel)) {
      /* if the personel is logged in he only replies to the users   */
      $chat->uid = $user->idno;
      $chat->from = "admin";
      $chat->to = url::post("uid");
    } else {
      /* User can only chat to the admin or the personel or the system access allowed */
      $chat->from = $key->idno;
      $chat->to = "admin";
      $chat->uid = $user->uid;
    }
    $chat->msg = url::post("msg");
    
    if (!empty($chat->to))
      print json_encode($chat->send());

  } else if($route == "recv") {
    $user = user::getInstance();
    $chat = new chatBox();
    $admin = new access(['PR']); //list of admin roles
    $chat->offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
    $chat->isRead = '1';
    $chat->dateSeen = Date2::getDate();
    if ($admin->check($user->ulevel)) {
      /* user admin */
      //$user->from = 'admin';
      $chat->myId = 'admin';
      $chat->uid = url::post("uid");
    } else {
      /* normal user */
      $chat->myId = $user->idno;
      $chat->uid = $user->idno;
    }
    $chat->status = 1;
    $data = $chat->recv();
    $chat->seen();
    $len = count($data);
    for ($i = 0; $i < $len; $i ++) {
      $data[$i]['msg'] = htmlspecialchars($data[$i]['msg']);
    }
    print json_encode($data);
  } else if ($route == 'list') {
    $feed = new sql();
    $chat = new chatBox();
    $chat->search = url::post('search');
    $data = $chat->listOnline();

    $data_len = count($data);
    $time = time() - 60;
    for ($i = 0; $i < $data_len; $i++) {
      $data[$i]["status"] = $data[$i]["dt"] > $time ? 1 : 0;
      $uid = $data[$i]["uid"];
      $stat = 0;
      if(!$data[$i]["status"]){
        $feed->exec('update user_online set isRead_stat = :stat where uid = :uid and isRead_stat = 1', [
          $stat,
          $uid
        ]);
      }
    }
    $len = count($data);
    for ($i = 0; $i < $len; $i++) {
      $data[$i]["name"] = strtoupper($data[$i]["name"]);    
    }
    print json_encode($data);
  } else if ($route == "me") {
    $user = user::getInstance();
    $admin = new access();
    print json_encode([
      "idno" => $user->idno,
      "role" => $admin->check() ? "admin" : ""
    ]);
  } else if($route == 'getLatestChat') {
    $chat = new chatBox();
    $data = [];
    if (access::getInstance()->check()) {
      $data = $chat->getLatestChatId();
    }
    print json_encode($data);
  }

}

app($route);