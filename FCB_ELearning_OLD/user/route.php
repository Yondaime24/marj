<?php 
use classes\adddailyreads as DailyReads;
use classes\addmotivationaltips as MotivationalTips;
use classes\auth;
use classes\url;
use classes\lib\sql;
use classes\Image;
use classes\user;
require_once "../___autoload.php";
auth::isLogin();

class route {

    public function __construct() {
        $this->route = isset($_GET["r"]) ? $_GET["r"] : "";
        $this->dailyreads = new DailyReads();
        $this->motivationaltips = new MotivationalTips();
        $this->feed = sql::getInstance();
        $this->user = user::getInstance();
    }
    public function run() {
        switch($this->route) {
            case "Save":
                $this->dailyreads->about_id = url::post("about_id");
                $this->dailyreads->title = url::post("title");
                $this->dailyreads->content = url::post("editor1");
                print json_encode($this->dailyreads->submit());
            break;
            case "getAllDailyReads":
                $dailyReads = new DailyReads();
                $data = $dailyReads->getAll();
                $data_len = count($data);
                for ($i = 0; $i < $data_len; $i++) {
                    $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_inserted"]));  
                  }
                print json_encode($data);
            break;
            case "getSearchReads":
                $dailyReads = new DailyReads();
                $dailyReads->search = url::post("search");
                $data = $dailyReads->search();
                $data_len = count($data);
                for ($i = 0; $i < $data_len; $i++) {
                    $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_inserted"]));  
                  }
                print json_encode($data);
            break;
            case "deleteDailyReads":
                $dailyReads = new DailyReads();
                $dailyReads->id = url::post("id");
                print json_encode($dailyReads->trash());
            break; 
            case "displayDailyReads":
                $dailyReads = new DailyReads();
                $dailyReads->id = url::post("id");
                print json_encode($dailyReads->display());
            break;  
            case "undisplayDailyReads":
                $dailyReads = new DailyReads();
                $dailyReads->id = url::post("id");
                print json_encode($dailyReads->undisplay());
            break; 
            case "saveTips":
                $this->motivationaltips->quote_id = url::post("quote_id");
                $this->motivationaltips->title = url::post("title");
                $this->motivationaltips->content = url::post("editor1");
                print json_encode($this->motivationaltips->submit());
            break;
            case "getAllMotivationalTips":
                $motivationaltips = new MotivationalTips();
                $data = $motivationaltips->getAll();
                $data_len = count($data);
                for ($i = 0; $i < $data_len; $i++) {
                    $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_uploaded"]));  
                  }
                print json_encode($data);
            break;
            case "getSearchTips":
                $motivationaltips = new MotivationalTips();
                $motivationaltips->search = url::post("search");
                $data = $motivationaltips->search();
                $data_len = count($data);
                for ($i = 0; $i < $data_len; $i++) {
                    $data[$i]["dt"] = date('F j, Y / g:i a',strtotime($data[$i]["date_uploaded"]));  
                  }
                print json_encode($data);
            break;
            case "deleteMotivationalTips":
                $motivationaltips = new MotivationalTips();
                $motivationaltips->id = url::post("id");
                print json_encode($motivationaltips->trash());
            break; 
            case "displayMotivationalTips":
                $motivationaltips = new MotivationalTips();
                $motivationaltips->id = url::post("id");
                print json_encode($motivationaltips->display());
            break; 
            case "undisplayMotivationalTips":
                $motivationaltips = new MotivationalTips();
                $motivationaltips->id = url::post("id");
                print json_encode($motivationaltips->undisplay());
            break; 
            case "updatePic":
                $img = new Image();
                if (count($_FILES) > 0) {
                    if (in_array($_FILES["files"]["type"], [
                        "image/png",
                        "image/jpg",
                        "image/jpeg"
                    ])) {
                        $tmp_path = "../tmp/".time().mt_rand(10000, 99999).".png";
                        $img->Scale(file_get_contents($_FILES["files"]["tmp_name"]), 128, $tmp_path);
                        $byte = bin2hex(file_get_contents($tmp_path));
                        $et = $this->feed->exec("select uid from profile_pic where uid = :uid", [$this->user->idno]);
                        if (count($et) > 0) {
                            $this->feed->exec("update profile_pic set data = :data where uid = :uid", [$byte, $this->user->idno]);
                        } else {
                            $this->feed->exec("insert into profile_pic(uid, data) values(:uid, :data)", [$this->user->idno, $byte]);
                        }
                        unlink($tmp_path);
                    }
                }
            break;
            case "profile":
                header("Content-Type:image/png");
                $id = url::get("id");
                $data = $this->feed->exec("select data from profile_pic where uid = :uid", [$id]);
                if (count($data) > 0) {
                    // if image exist
                    echo hex2bin($data[0]["data"]);
                } else {
                    echo file_get_contents("../assets/images/user.png");
                } 
            break;
        }
    }


}

$route = new route();
$route->run();