<?php 
require '../___autoload.php';
   
   
if (isset($_POST["save"]))
{
    
    //UPDATE
    if ($_POST["save"] == "Update") {

        $stmt = sql::con1()->prepare("UPDATE feed_quote SET content = :content, title = :title WHERE quote_id = :quote_id");
        $result = $stmt->execute(
            array(
                ':content' => $_POST["editor1"],
                ':title' => $_POST["title"],
                ':quote_id' => $_POST["quote_id"]
            )
        );
                // $archive_id = strtotime($date_inserted) + 1;
                // $statement = sql::con1()->prepare("INSERT INTO archive (archive_id, content, date_added, stat, content_type, quote_day) VALUES (:archive_id, :content, :date_added, :stat, :content_type, :quote_day)");
                // $result = $statement->execute(
                //     array(
                //         ':archive_id' => $archive_id,
                //         ':content' => $_POST["editor1"],
                //         ':date_added' => $date_inserted,
                //         ':stat' => 'displayed',
                //         ':content_type' => 'feed_quote',
                //         ':quote_day' => $day
                //     )
                // );

                // $statement = sql::con1()->prepare("UPDATE archive SET stat=:stat WHERE archive_id!='".$archive_id."' AND quote_day='".$day."'");
                // $result = $statement->execute(
                //     array(
                //         ':stat' => 'not_displayed'
                //     )
                // );

    }
}