<?php 
require '../___autoload.php';
   
if (isset($_POST["save_about"]))
{
    //UPDATE
    if ($_POST["save_about"] == "Save") {
    	date_default_timezone_set("Asia/Manila");
		$date_created = date('Y-m-d H:i:s');
        if ( $_POST["feedabout_info"] == "") {
            $stmt = sql::con1()->prepare("UPDATE info SET content = :content, date_created = :date_created, status = :status WHERE info_id = :info_id");
            $result = $stmt->execute(
                array(
                    ':content' => $_POST["feedabout_info"],
                    ':date_created' => $date_created,
                    ':status' => "No Content",
                    ':info_id' => $_POST["info_id"]
                )
            );
        }else{
            $stmt = sql::con1()->prepare("UPDATE info SET content = :content, date_created = :date_created, status = :status WHERE info_id = :info_id");
            $result = $stmt->execute(
                array(
                    ':content' => $_POST["feedabout_info"],
                    ':date_created' => $date_created,
                    ':status' => "Has Content",
                    ':info_id' => $_POST["info_id"]
                )
            );
        }
      

    }
}

if (isset($_POST["save_about2"]))
{
    //UPDATE
    if ($_POST["save_about2"] == "Save") {
    	date_default_timezone_set("Asia/Manila");
		$date_created = date('Y-m-d H:i:s');
        if ( $_POST["fcbabout_info"] == "") {
            $stmt = sql::con1()->prepare("UPDATE info SET content = :content, date_created = :date_created, status = :status WHERE info_id = :info_id2");
            $result = $stmt->execute(
                array(
                    ':content' => $_POST["fcbabout_info"],
                    ':date_created' => $date_created,
                    ':status' => "No Content",
                    ':info_id2' => $_POST["info_id2"]
                )
            );
        }else{
            $stmt = sql::con1()->prepare("UPDATE info SET content = :content, date_created = :date_created, status = :status WHERE info_id = :info_id2");
            $result = $stmt->execute(
                array(
                    ':content' => $_POST["fcbabout_info"],
                    ':date_created' => $date_created,
                    ':status' => "Has Content",
                    ':info_id2' => $_POST["info_id2"]
                )
            );
        }
      

    }
}