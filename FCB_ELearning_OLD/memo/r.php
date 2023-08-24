<?php
use classes\memo;
use classes\Date as Date2;
use classes\url;
require_once "../___autoload.php";
class app {
  public function __construct() {
    $this->route = isset($_GET["r"]) ? $_GET["r"] : "";
  }
  public function run() {
    switch ($this->route) {
      case "get-memo":
        $memo = new memo();
        $data = $memo->get();
        $data_len = count($data);
        $resp = [];
        for ($m = 0; $m < $data_len; $m++) {
          $resp[$m]["id"] = $data[$m]["m_id"];
          $resp[$m]["name"] = $data[$m]["m_name"];
          $resp[$m]["date"] = $data[$m]["m_date"];
          $resp[$m]["code"] = $data[$m]["m_category"];
          $resp[$m]["m_date_ago"] = Date2::Ago($data[$m]["m_date"]);
        } 
        print json_encode($resp);
      break;
      case "get-memo-all":
        $memo = new memo();
        $data = $memo->getAllMemo();
        $data_len = count($data);
        $resp = [];
        for ($m = 0; $m < $data_len; $m++) {
          $resp[$m]["id"] = $data[$m]["m_id"];
          $resp[$m]["name"] = $data[$m]["m_name"];
          $resp[$m]["date"] = $data[$m]["m_date"];
          $resp[$m]["loc"] = $data[$m]["attach_file"];
          $resp[$m]["m_date_ago"] = Date2::Ago($data[$m]["m_date"]);
        }
        print json_encode($resp);
      break;
      case "getSearchMemos":
        $memo = new memo();
        $memo->search = url::post("search");
        $data = $memo->search();
        $data_len = count($data);
        for ($i = 0; $i < $data_len; $i++) {
            $data[$i]["dt"] = Date2::Ago($data[$i]["m_date"]);  
          }
        print json_encode($data);
    break;
    }
  }
}
$app = new app();
$app->run();