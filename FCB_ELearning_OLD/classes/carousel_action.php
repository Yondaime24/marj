<?php
require '../___autoload.php'; 

if(isset($_POST["operation"]))
{

    //INSERT
	if($_POST["operation"] == "Add")
	{
		date_default_timezone_set("Asia/Manila");
		$created_on = date('Y-m-d H:i:s');
		$status = 'not_displayed';

		$image_tmp = $_FILES['carousel_image']['tmp_name'];
		$name = $_FILES['carousel_image']['name'];
		$image = base64_encode(file_get_contents(addslashes($image_tmp)));

		$datetime = date('Y-m-d H:i:s');
    $id = strtotime($datetime) + 1;

		$statement = sql::con1()->prepare("INSERT INTO carousel_image (id, image, title, created_on, status, img_name) VALUES (:id, :image, :title, :created_on, :status, :img_name)");
		$result = $statement->execute(
			array(
				':id'	        => $id,
				':image'	    => $image,
				':title'	    => $_POST['image_title'],
				':created_on' => $created_on,
				':status'     => $status,
				':img_name'     => $name
			)
		);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}

	//VIEW
	if ($_POST["operation"] == "Load") {
		$stmt = sql::con1()->prepare("SELECT * FROM carousel_image ORDER BY sequence DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt2 = sql::con1()->prepare("SELECT * FROM carousel_image ORDER BY id DESC");
        $stmt2->execute();
        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        $output = '';
        if ($stmt2->rowCount() > 0) {
        	foreach ($result2 as $row2) {
        		if ($row2['status'] == 'not_displayed') {
		            $output .= '
				        <div class="pic uploaded">
				        <img src="data:image;base64,'.$row2['image'].'">
				        	<p class="title">'.$row2['title'].'</p>
					        <div class="overlay"></div>
					        <div class="button" style="z-index:10000;">
					          <a class="delete_anchor" href="#" title="Delete" id="'.$row2['id'].'"><i class="fas fa-trash"></i> </a>
					          <a class="display_anchor" href="#" title="Display" id="'.$row2['id'].'"><i class="fas fa-eye"></i> </a>
					        </div>
				      	</div>
		           ';
            	}
        	}
        }
        if ($stmt->rowCount() > 0) {
        	foreach ($result as $row) {
        		if ($row['status'] == 'displayed'){
            		  $output .= '
				        <div class="pic displayed">
					        <img src="data:image;base64,'.$row['image'].'">
					        <p class="title">'.$row['title'].'</p>
					                <div class="overlay"></div>
					                <div class="button2" style="z-index:10000;">
					                 <a class="remove" href="#" id="'.$row['id'].'" title="Remove"><i class="fas fa-ban"></i> </a>
					           </div>
					      </div>
		           ';
            	}
        	}
        }
        echo $output;
	}

	//DELETE
	if ($_POST["operation"] == "Delete") {
		
		$statement = sql::con1()->prepare("DELETE FROM carousel_image WHERE id = :id");
		$result = $statement->execute(
			array(
				':id'	=>	$_POST["carousel_id"]
			)
		);
		
		if(!empty($result))
		{
			echo 'Data Deleted';
		}
	}

    //UPDATE TO DISPLAY
    if ($_POST["operation"] == "Update") {
		$time = date('Y-m-d H:i:s');
        $stmt = sql::con1()->prepare("UPDATE carousel_image SET status = :status, sequence = :sequence WHERE id = :carousel_id");

        $result = $stmt->execute(
            array(
                ':carousel_id' => $_POST["carousel_id"],
                ':status' => 'displayed',
                ':sequence' => $time
            )
        );
        if (!empty($result)) {
            echo 'Data Updated';
        }
    }

    //UPDATE TO NOT DISPLAY
    if ($_POST["operation"] == "Update2") {
    	$time = date('Y-m-d H:i:s');
        $stmt = sql::con1()->prepare("UPDATE carousel_image SET status = :status, sequence = :sequence WHERE id = :carousel_id");
        $result = $stmt->execute(
            array(
                ':carousel_id' => $_POST["carousel_id"],
                ':status' => 'not_displayed',
                ':sequence' => null
            )
        );
        if (!empty($result)) {
            echo 'Data Updated';
        }
    }
	
}

?>