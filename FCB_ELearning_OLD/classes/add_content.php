<?php 
require '../___autoload.php';
if (isset($_POST["save"]))
{

    //UPDATE
    if ($_POST["save"] == "Save") {
        date_default_timezone_set("Asia/Manila");
        $day = $_POST["quote_day"];
		$date_uploaded = date('Y-m-d H:i:s');
		$status = "Has Content";
        $stmt = sql::con1()->prepare("UPDATE feed_quote SET content = :content, date_uploaded = :date_uploaded, status = :status WHERE quote_id = :quote_id");
        $result = $stmt->execute(
            array(
                ':content' => $_POST["editor1"],
                ':date_uploaded' => $date_uploaded,
                ':status' => $status,
                ':quote_id' => $_POST["quote_id"]
            )
        );

        // $archive_id = strtotime($date_uploaded) + 1;
        // $statement = sql::con1()->prepare("INSERT INTO archive (archive_id, content, date_added, stat, content_type, quote_day) VALUES (:archive_id, :content, :date_added, :stat, :content_type, :quote_day)");
        // $result = $statement->execute(
        //     array(
        //         ':archive_id' => $archive_id,
        //         ':content' => $_POST["editor1"],
        //         ':date_added' => $date_uploaded,
        //         ':stat' => 'displayed',
        //         ':content_type' => 'feed_quote',
        //         ':quote_day' => $day
        //     )
        // );

    }
}