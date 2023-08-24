<?php 
require '../___autoload.php';
   
if (isset($_POST["save"]))
{

    ///VIEW
    if ($_POST["save"] == "Load") {
        $stmt = sql::con1()->prepare("SELECT * FROM feed_about");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $output = '';
        if ($stmt->rowCount() > 0) {
            foreach ($result as $row) { 
                $output .= '
                    ' . $row["content"] . '
            ';
            }
        }
        echo $output;
    }

    //UPDATE
    if ($_POST["save"] == "Save") {
        $stmt = sql::con1()->prepare("UPDATE feed_about SET content = :content, title = :title WHERE about_id = :about_id");
        $result = $stmt->execute(
            array(
                ':content' => $_POST["editor1"],
                ':title' => $_POST["title"],
                ':about_id' => $_POST["about_id"]
            )
        );
        
        // $archive_id = strtotime($date_inserted) + 1;
        // $statement = sql::con1()->prepare("INSERT INTO archive (archive_id, content, date_added, stat, content_type) VALUES (:archive_id, :content, :date_added, :stat, :content_type)");
        // $result = $statement->execute(
        //     array(
        //         ':archive_id' => $archive_id,
        //         ':content' => $_POST["editor1"],
        //         ':date_added' => $date_inserted,
        //         ':stat' => 'not_displayed',
        //         ':content_type' => 'feed_about'
        //     )
        // );

        // $stmt = sql::con1()->prepare("SELECT TOP 1 * FROM archive ORDER BY date_added DESC");
        // $stmt->execute();
        // $result = $stmt->fetchAll();
        // if ($stmt->rowCount() > 0) {
        //     foreach ($result as $row) { 
        //         $date_added = $row["date_added"];
        //         $content = $row["content"];
        //         if ($content == ""){
        //             $stmt2 = sql::con1()->prepare("UPDATE archive SET stat = :stat WHERE date_added = '".$date_added."' AND content_type='feed_about'");
        //             $result2 = $stmt2->execute(
        //                 array(
        //                     ':stat' => 'not_displayed'
        //                 )
        //             );
        //         }else{
        //             $stmt2 = sql::con1()->prepare("UPDATE archive SET stat = :stat WHERE date_added != '".$date_added."' AND content_type='feed_about'");
        //             $result2 = $stmt2->execute(
        //                 array(
        //                     ':stat' => 'not_displayed'
        //                 )
        //             );
        //             $stmt2 = sql::con1()->prepare("UPDATE archive SET stat = :stat WHERE date_added = '".$date_added."' AND content_type='feed_about'");
		// 		        $result2 = $stmt2->execute(
		// 		            array(
		// 		                ':stat' => 'displayed'
		// 		            )
		// 		        );
        //         }

        //     }
        // }


    }
}