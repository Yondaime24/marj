<?php 
use classes\pptx;
use classes\url;
require '___autoload.php';
/*
** ?route=route&ip=myip
** 
** Use in special in many platform like, mobile phone, & Desktop Application 
** 
*/
class FCB {
  public function __construct() {

  }
  protected function ip() {
    return str_replace('.', '_', $this->myIp).'.txt';
  }
  /*
  ** closing the file after opening it to the window
  */
  protected function closeFile() {
    $info = [
      'ip' => $this->myIp,
      'file' => '',
      'id' => '001', 
      /*
      ** 001 is in the user type top open
      */
      'isOpened' => '0',
      'type' => 'ppsx'
    ];
    $info['isOpened'] = '0';
    $fp = fopen('api/ip/'.$this->ip(), 'w');
    fwrite($fp, json_encode($info));
    fclose($fp);
  } 
}
/*
** Extending the class of api
*/
class api extends FCB {
  public function __construct() {
    $this->route = isset($_GET['route']) ? $_GET['route'] : '';
    $this->ip = isset($_GET['ip']) ? $_GET['ip'] : '';
    $this->myIp = $_SERVER['REMOTE_ADDR'];
  }
  public function run() {
    switch ($this->route) {
      case 'feed/open/ppt':
      break;
      case 'feed/task':
        $info = [
          'ip' => $this->myIp,
          'file' => '',
          'id' => '001', 
          /*
          ** 001 is in the user type top open
          */
          'isOpened' => '0',
          'type' => 'ppsx'
        ];    
        $info['isOpened'] = '0';
        $ft = file_get_contents('api/ip/'.$this->ip());
        $ft = json_decode($ft);
        $r = [
          'file' => $ft->file,
          'data' => base64_encode(file_get_contents('_res/ppts/'.$ft->file))
        ];
        $info['isOpened'] = '0';
        $fp = fopen('api/ip/'.$this->ip(), 'w');
        fwrite($fp, json_encode($info));
        fclose($fp); 
        print json_encode($r);
      break;
      case 'check':
        /*
        ** Ip address of the client computer
        ** it is used in Application based, to render there ip Address
        ** 
        */
        $info = [
          'ip' => $this->myIp,
          'file' => '',
          'id' => '001', 
          /*
          ** 001 is in the user type top open
          */
          'isOpened' => '0',
          'type' => 'ppsx'
        ];

        if (file_exists('api/ip/'.$this->ip())) {
          //print 'nofile';
          $data = file_get_contents('api/ip/'.$this->ip());
          $data = json_decode($data);
          $info['file'] = $data->file;
          $info['isOpened'] = $data->isOpened;
        }
        print json_encode($info);
      break;
      case 'ppsx/open':
        $ppt = new pptx();
        $ppt->subTopicId = url::post('id');
        $ppt->status = '1';
        $data = $ppt->get();
        if (count($data) == 0) {
          print '{"ok": 0, "msg" => "No Powerpoint Slideshow uploaded(PPSX)"}';
          return;
        }
        $info = [
          'ip' => $this->myIp,
          'file' => '',
          'id' => '001', 
          /*
          ** 001 is in the user type top open
          */
          'isOpened' => '0',
          'type' => 'ppsx'
        ];
        $info['isOpened'] = '1';
        $info['file'] = $data[0]['name'].'.ppsx';
        if (!file_exists('_res/ppts/'.$info['file'] = $data[0]['name'].'.ppsx')) {
          print '{"ok": 0, "msg" => "No Powerpoint Slideshow uploaded(PPSX)"}';
          return;
        }
        $fp = fopen('api/ip/'.$this->ip(), 'w');
        fwrite($fp, json_encode($info));
        fclose($fp);
        print '{"ok": 1, "msg" => "Powerpoint opened!"}';
      break;
      default: 
      break;
    }
  }
}
$api = new api();
$api->run();
/*
** End Api
** 
*/