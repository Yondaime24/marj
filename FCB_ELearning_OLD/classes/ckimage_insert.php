<?php
require '../___autoload.php';

function upload_image()
{
    if(isset($_FILES["user_image"]))
    {
        $extension = explode('.', $_FILES['user_image']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = '../assets/imageFolder/images/' . $new_name;
        move_uploaded_file($_FILES['user_image']['tmp_name'], $destination);
        return $new_name;
    }
}

function get_image_name($img_id)
{
	$statement = sql::con1()->prepare("SELECT img_name FROM ckimage WHERE id = '$img_id'");
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row["img_name"];
	}
}

if(isset($_POST["operation"]))
{

	
	//INSERT
	if($_POST["operation"] == "Add")
	{
		date_default_timezone_set("Asia/Manila");
		$image = '';
		$created_at = date('Y-m-d H:i:s');

		$ckimage_id = uniqid('ckimage_id', true);
		$ckimage_id_md5 = md5($ckimage_id);

		if($_FILES["user_image"]["name"] != '')
		{
			$image = upload_image();
		}
		$statement = sql::con1()->prepare("INSERT INTO ckimage (id, img_name, created_at) VALUES (:id, :img_name, :created_at)");
		$result = $statement->execute(
			array(
				':id'		=>	$ckimage_id_md5,
				':img_name'		=>	$image,
				':created_at'	=>  $created_at
			)
		);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}


	//VIEW
	if ($_POST["operation"] == "Load") {
		$stmt = sql::con1()->prepare("SELECT * FROM ckimage ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $output = '';
        if ($stmt->rowCount() > 0) {
            foreach ($result as $row) { 
            $output .= '
	            <div class="pic displayed" >
	                <a href="javascript:selectImage(\''.$row['img_name'].'\')" class="thumbnail">
	                <img class="img" src="images/' . $row['img_name'] . '">
	                <div class="overlay"></div>
	                <div class="button">
	                    <a href="#" class="delete_btn" id="'.$row['id'].'" title="Delete"><i class="fas fa-trash"></i> </a>
	                </div>
	            </div>
           ';
          
            }
        } else {
            $output .= '
            <div style="display: flex; align-items: center; justify-content: center; width: 176vh; margin-top: 50px;">
            <span style="font-family: sans-serif; font-size: 20px; font-weight: bold; color: red;">No Uploaded Images Yet!</span>
            </div>
           ';
        }
        $output .= '</table>';
        echo $output;
	}

	//DELETE
	if ($_POST["operation"] == "Delete") {

		$image = get_image_name($_POST["user_id"]);
		if($image != '')
		{
			unlink("../assets/imageFolder/images/" . $image);
		}
		$statement = sql::con1()->prepare("DELETE FROM ckimage WHERE id = :id");
		$result = $statement->execute(
			array(
				':id'	=>	$_POST["user_id"]
			)
		);
		
		if(!empty($result))
		{
			echo 'Data Deleted';
		}
	}

	
}

?>