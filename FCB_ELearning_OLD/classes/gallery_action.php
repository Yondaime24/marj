<?php
  use classes\key;
  use classes\access;
  use classes\user;
  use classes\auth;

require '../___autoload.php';
  $auth = new auth();
  $auth::isLogin();

  $key = new key();
  $admin=new access(['PR']);
  $user = user::getInstance();
  $user->load();

  $myrole = '';
  if ($admin->check($key->ulevel))
  $myrole = 'admin';

if(isset($_POST["operation"]))
{

	//INSERT
	if($_POST["operation"] == "Add")
	{
		date_default_timezone_set("Asia/Manila");
		$date_created = date('Y-m-d H:i:s');
		$status = 'No Image';

		$folder_id = uniqid('folder_id', true);
		$folder_id_md5 = md5($folder_id);

		$statement = sql::con1()->prepare("INSERT INTO img_folder (folder_id, folder_name, date_created, status) VALUES (:folder_id, :folder_name, :date_created, :status)");
		$result = $statement->execute(
			array(
				':folder_id'	  => $folder_id_md5,
				':folder_name'	  => $_POST['folder_name'],
				':date_created'	  => $date_created,
				':status'		  => $status
			)
		
		);
		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}


	///VIEW
    if ($_POST["operation"] == "Load") {
        $stmt = sql::con1()->prepare("SELECT * FROM img_folder ORDER BY date_created DESC");
        $stmt->execute();
        $result = $stmt->fetchAll();
        $output = '';

        if ($stmt->rowCount() > 0) {
            foreach ($result as $row) { 
            	if ($admin->check($key->ulevel)) {
            		if ($row['status'] == 'No Image') {
		            		
		            		$output .= '
			                    <li id="res-li">
			                    	<div id="a-tag" class="">
			                        <a class="a-folder" href="#" id="'.$row['folder_id'].'"> '.$row['folder_name'].'</a>
								    <div>
			                        <div class="button">
			                            <button class="add btn-primary" id="'.$row['folder_id'].'" href="#"  data-bs-toggle="modal" data-bs-target="#imgModal" title="Add Image"><i class="fas fa-plus i-plus"></i></button> 
			                            <button class="edit btn-info" id="'.$row['folder_id'].'" href="#" title="Edit Folder Name"><i class="fas fa-edit i-edit"></i></button> 
			                            <button class="delete btn-danger" id="'.$row['folder_id'].'"  href="#" title="Delete Folder"><i class="fas fa-trash-alt i-trash"></i></button>
			                    	</div>
			                    </li>
		            		';

            		}else{

            			$output .= '
				           	<li id="res-li">
				           		<div id="a-tag">
		                        <a class="a-folder" href="#" id="'.$row['folder_id'].'"> '.$row['folder_name'].'</a>
		                        <div>
		                        <div class="button">
			                            <button class="add btn-primary" id="'.$row['folder_id'].'" href="#"  data-bs-toggle="modal" data-bs-target="#imgModal" title="Add Image"><i class="fas fa-plus i-plus"></i></button> 
			                            <button class="edit btn-info" id="'.$row['folder_id'].'" href="#" title="Edit Folder Name"><i class="fas fa-edit i-edit"></i></button> 
			                    </div>
		                    </li>
		            		';

            		}
        		}else{
        			$output .= '
			                 <li id="res-li">
			                 	<div id="a-tag">
		                        <a class="a-folder" href="#" id="'.$row['folder_id'].'"> '.$row['folder_name'].'</a>
		                        <div>
		                    </li>
		            ';
        		}
            }
        } else {
            $output .= '
             <div style="position:absolute;top:0;bottom:0;left:0;right:0;display:flex;text-align:center;align-items:center;justify-content:center;margin-top:100px; height:400px;">
             <span style="font-size: 3vh;color:red;">No<span>
						 <br><br>
             <span style="font-size: 3vh;color:red;">Albums<span>
						 <br><br>
             <span style="font-size: 3vh;color:red;">Added<span>
						 <br><br>
             <span style="font-size: 3vh;color:red;">Yet!<span>
             <hr>
             <div>
           ';
	       
        }

        echo $output;
    }


    //FETCH SINGLE DATA
    if ($_POST["operation"] == "Select") {
        $output = array();
        $stmt = sql::con1()->prepare("SELECT * FROM img_folder WHERE folder_id = '".$_POST['folder_id']."'");
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $output["folder_name"] = $row["folder_name"];
        }
        echo json_encode($output);
    }

    //UPDATE
    if ($_POST["operation"] == "Update") {
        $stmt = sql::con1()->prepare("UPDATE img_folder SET folder_name = :folder_name WHERE folder_id = :folder_id");
        $result = $stmt->execute(
            array(
                ':folder_name' => $_POST["folder_name"],
                ':folder_id'   => $_POST["folder_id"]
            )
        );
        if (!empty($result)) {
            echo 'Data Updated';
        }
    }

    //DELETE FOLDER
	if ($_POST["operation"] == "Delete") {
		$statement = sql::con1()->prepare("DELETE FROM img_folder WHERE folder_id = :folder_id");
		$result = $statement->execute(
			array(
				':folder_id'	=>	$_POST["folder_id"]
			)
		);
		
		$statement = sql::con1()->prepare("DELETE FROM img_folder_contents WHERE folder_id = :folder_id");
		$result = $statement->execute(
			array(
				':folder_id'	=>	$_POST["folder_id"]
			)
		);
		
		if(!empty($result))
		{
			echo 'Data Deleted';
		}
	}
}

if(isset($_POST["operation2"]))
{

		//UPLOAD IMAGE
	if($_POST["operation2"] == "Upload")
	{
		date_default_timezone_set("Asia/Manila");
		// $image = '';
		$date_uploaded = date('Y-m-d H:i:s');

		$image_id = uniqid('image_id', true);
		$image_id_md5 = md5($image_id);

		$image_tmp = $_FILES['img_name']['tmp_name'];
		$name = $_FILES['img_name']['name'];
		$image = base64_encode(file_get_contents(addslashes($image_tmp)));

		$statement = sql::con1()->prepare("INSERT INTO img_folder_contents (img_id, folder_id, img, img_title, date_uploaded, img_desc, img_name) VALUES (:img_id, :folder_id, :img, :img_title, :date_uploaded, :img_desc, :img_name)");
		$result = $statement->execute(
			array(
				':img_id'	     => $image_id_md5,
				':folder_id'	 => $_POST['folder_to_img_id'],
				':img'	     => $image,
				':img_title'     => $_POST['img_title'],
				':img_desc'      => $_POST['img_desc'],
				':date_uploaded' => $date_uploaded,
				':img_name' => $name
			)
		);

		$status = 'Has Image';
		$stmt = sql::con1()->prepare("UPDATE img_folder SET status = :status WHERE folder_id = :folder_id");
        $result = $stmt->execute(
            array(
                ':status' => $status,
                ':folder_id'   => $_POST['folder_to_img_id']
            )
        );

		if(!empty($result))
		{
			echo 'Data Inserted';
		}
	}


		//VIEW
	if ($_POST["operation2"] == "View") {

		$folder_id = $_POST["folder_to_img_id"];

		$stmt = sql::con1()->prepare("SELECT * FROM img_folder_contents ORDER BY date_uploaded DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $output = '';
        if ($stmt->rowCount() > 0) {
        	foreach ($result as $row) {
        		if ($folder_id === $row['folder_id']) {

        			if ($admin->check($key->ulevel)) {

        			if ($row['img_title'] != "" || $row['img_desc'] != "") {

        				$output .= '
	        			 <div class="uploaded">
	        			 	<a class="img" id="'.$row['img_id'].'" href="data:image;base64,'.$row['img'].'" title="'.$row['img_title'].' | '.$row['img_desc'].'" data-gallery="">
	        			 		<img src="data:image;base64,'.$row['img'].'" width="200">
	        			 	</a>

	        			 	<div class="buttons">
	        			 		<a class="btn btn-primary btn-sm img_edit" href="#edit" id="'.$row['img_id'].'" title="Edit"> <i class="fa fa-edit"></i></a>
	        			 		<a class="btn btn-danger btn-sm img_delete" href="#delete" id="'.$row['img_id'].'" title="Delete"> <i class="fa fa-trash-alt"></i></a>
	        			 	</div>

	                     </div>
			           ';
        				
        			}else{

        				$output .= '
	        			 <div class="uploaded">
	        			 	<a class="img" id="'.$row['img_id'].'" href="data:image;base64,'.$row['img'].'" title="" data-gallery="">
	        			 		<img src="data:image;base64,'.$row['img'].'" width="200">
	        			 	</a>

	        			 	<div class="buttons">
	        			 		<a class="btn btn-primary btn-sm img_edit" href="#edit" id="'.$row['img_id'].'" title="Edit"> <i class="fa fa-edit"></i></a>
	        			 		<a class="btn btn-danger btn-sm img_delete" href="#delete" id="'.$row['img_id'].'" title="Delete"> <i class="fa fa-trash-alt"></i></a>
	        			 	</div>

	                     </div>
			           ';

        			}

        		}else{

        			if ($row['img_title'] != "" || $row['img_desc'] != "") {

        				$output .= '
	        			 <div class="uploaded">
	        			 	<a class="img" id="'.$row['img_id'].'" href="data:image;base64,'.$row['img'].'" title="'.$row['img_title'].' | '.$row['img_desc'].'" data-gallery="">
	        			 		<img src="data:image;base64,'.$row['img'].'" width="200">
	        			 	</a>
	                     </div>
			           ';
        				
        			}else{

        				$output .= '
	        			 <div class="uploaded">
	        			 	<a class="img" id="'.$row['img_id'].'" href="data:image;base64,'.$row['img'].'" title="" data-gallery="">
	        			 		<img src="data:image;base64,'.$row['img'].'" width="200">
	        			 	</a>
	                     </div>
			           ';

        			}

        		}
        			 
        		}
        	}
        }
        echo $output;
	}


		if ($_POST["operation2"] == "Fetch") {

		$stmt = sql::con1()->prepare("SELECT * FROM img_folder_contents ORDER BY date_uploaded DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $output = '';
        if ($stmt->rowCount() > 0) {
        	foreach ($result as $row) {
        		$folder_id = $_POST['folder_to_img_id'];
        		if ($row['folder_id'] == $folder_id) {

        			if ($row['img_title'] != "" || $row['img_desc'] != "") {

        				$output .= '
	        			 <div class="uploaded">
	        			 	<a class="img" id="'.$row['img_id'].'" href="data:image;base64,'.$row['img'].'" title="'.$row['img_title'].' | '.$row['img_desc'].'" data-gallery="">
	        			 		<img src="data:image;base64,'.$row['img'].'" width="200">
	        			 	</a>

	        			 	<div class="buttons">
	        			 		<a class="btn btn-primary btn-sm img_edit" href="#edit" id="'.$row['img_id'].'" title="Edit"> <i class="fa fa-edit"></i></a>
	        			 		<a class="btn btn-danger btn-sm img_delete" href="#delete" id="'.$row['img_id'].'" title="Delete"> <i class="fa fa-trash-alt"></i></a>
	        			 	</div>

	                     </div>
			           ';
        				
        			}else{

        				$output .= '
	        			 <div class="uploaded">
	        			 	<a class="img" id="'.$row['img_id'].'" href="data:image;base64,'.$row['img'].'" title="" data-gallery="">
	        			 		<img src="data:image;base64,'.$row['img'].'" width="200">
	        			 	</a>

	        			 	<div class="buttons">
	        			 		<a class="btn btn-primary btn-sm img_edit" href="#edit" id="'.$row['img_id'].'" title="Edit"> <i class="fa fa-edit"></i></a>
	        			 		<a class="btn btn-danger btn-sm img_delete" href="#delete" id="'.$row['img_id'].'" title="Delete"> <i class="fa fa-trash-alt"></i></a>
	        			 	</div>

	                     </div>
			           ';

        			}
        		
		        }
        	}
        }

        if (!$result) {
        	$output .= '
        			 	<div style="color: red; position: absolute; right: 0; left: 0; bottom:0; top:50px;font-weight:bold;">
                        <strong style="display: flex;justify-content:center;align-items:center;margin-top:200px; font-size: 28px;"><u>No Image Available Yet!</u></strong>
                        <small style="display: flex;justify-content:center;align-items:center;margin-top:20px;">Note: A folder can be deleted once all contents has been removed!</small>

                      </div>

		           ';
        }
        echo $output;
	}


	//FETCH IMAGE DETAILS
    if ($_POST["operation2"] == "Select") {
        $output = array();
        $stmt = sql::con1()->prepare("SELECT * FROM img_folder_contents WHERE img_id = '". $_POST["folder_to_img_id"] ."'" );
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $output["img_title"] = $row["img_title"];
            $output["img_desc"] = $row["img_desc"];
            $output["folder_id"] = $row["folder_id"];
         if($row["img_name"] != '')
		{
			$output['img_name'] = '<small style="color: red;position:absolute; top:0;">*Last uploaded image preview</small>
								   <img src="data:image;base64,'.$row['img'].'" class="img-thumbnail" width="200" height="100" style="margin-top:20px;"/>
								   <input type="hidden" name="hidden_user_image" value="'.$row["img_name"].'" />';
		}
		else
		{
			$output['img_name'] = '<input type="hidden" name="hidden_user_image" value="" />';
		}
        }
        echo json_encode($output);
    }

    //UPDATE IMAGE DETAILS
   if($_POST["operation2"] == "Update")
	{
		$image = '';
		if($_FILES["img_name"]["name"] != '')
		{
			$image = upload_image();
		}
		else
		{
			$image = $_POST["hidden_user_image"];
		}
		$statement = sql::con1()->prepare("UPDATE img_folder_contents SET img_title = :img_title, img_desc = :img_desc, img_name = :img_name  WHERE img_id = :img_id");
		$result = $statement->execute(
			array(
				':img_title'	=>	$_POST["img_title"],
				':img_desc'	=>	$_POST["img_desc"],
				':img_name'		=>	$image,
				':img_id'			=>	$_POST["img_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Updated';
		}
	}
}

if(isset($_POST["popup_operation"]))
{

	//IMAGE VIEW ON MODAL
	if ($_POST["popup_operation"] == "Load") {

		$stmt = sql::con1()->prepare("SELECT * FROM img_folder_contents");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $output = '';
        if ($stmt->rowCount() > 0) {
        	foreach ($result as $row) {
        		if ($_POST["img_id_popup"] == $row['img_id']) {
        			if ($admin->check($key->ulevel)) {
        				 $output .= '
			        	<div class="modal-options">
			                <div class="modal-options-content">
			                    <a href="#" class="btn delete_btn" id="'.$row['img_id'].'" title="Remove"><i class="fas fa-trash"></i></a>
			                    <a href="#" class="btn edit_btn" id="'.$row['img_id'].'" data-bs-toggle="modal" data-bs-target="#editModal" title="Edit"><i class="fas fa-edit"></i></a>
			                    <small id="img_small" style="color: white;margin-left:10px;">Uploaded On: '.date('F j, Y / g:i a',strtotime($row['date_uploaded'])).'</small>
			                    <a href="#" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="remove_img_on_modal"></a>
			                </div>                
			            </div>
			            <div class="modal-body">
			                <div class="modal-display">
			                	<input type="hidden" name="img_folder_id" id="img_folder_id" value="'.$row['folder_id'].'"/>
			                	<img src="data:image;base64,'.$row['img'].'" width="200" id="image_on_popup" alt="...">
			        			<span id="img_span">'.$row['img_title'].'</span>
			        			<p align="justify" id="img_p">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['img_desc'].'</p>
			                </div>
			            </div>
		           ';
        			}else{
	        			$output .= '
				        	<div class="modal-options">
				                <div class="modal-options-content">
				                    <small id="img_small" style="color: white;margin-left:10px;">Uploaded On: '.date('F j, Y / g:i a',strtotime($row['date_uploaded'])).'</small>
				                    <a href="#" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="remove_img_on_modal"></a>
				                </div>                
				            </div>
				            <div class="modal-body">
				                <div class="modal-display">
				                	<img src="data:image;base64,'.$row['img'].'" width="200" id="image_on_popup" alt="...">
				        			<span id="img_span">'.$row['img_title'].'</span>
				        			<p align="justify" id="img_p">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['img_desc'].'</p>
				                </div>
				            </div>
			           ';

        			}
        		}
        	}
        }
        echo $output;
	}

	//DELETE IMAGE AND UPDATE FOLDER TO NO IMAGE
	if ($_POST["popup_operation"] == "Delete") {

		// $image = get_image_name($_POST["img_id_popup"]);
		// if($image != '')
		// {
		// 	unlink("../assets/gallery_img/" . $image);
		// }
		$statement = sql::con1()->prepare("DELETE FROM img_folder_contents WHERE img_id = :img_id");
		$result = $statement->execute(
			array(
				':img_id'	=>	$_POST["img_id_popup"]
			)
		);
		if(!empty($result))
		{
			$img_id = $_POST['img_id_popup'];
			$folder_id = $_POST['img_folder_id'];
			$status = 'Has Image';

			$statement2 = sql::con1()->prepare("SELECT * FROM img_folder WHERE status = '".$status."'");
			$statement2->execute();
			$result2 = $statement2->fetchAll();
			foreach ($result2 as $row2) {
				$statement3 = sql::con1()->prepare("SELECT * FROM img_folder_contents WHERE folder_id = '".$row2['folder_id']."'");
				$statement3->execute();
				$result3 = $statement3->fetchAll();
				if (!$result3) {
						$status = 'No Image';
						$stmt = sql::con1()->prepare("UPDATE img_folder SET status = :status WHERE folder_id = :folder_id");
				        $result = $stmt->execute(
				            array(
				                ':status' => $status,
				                ':folder_id'   => $_POST['img_folder_id']
				            )
				        );
				}

			}
			echo 'Data Deleted';
		}

	}

}

if(isset($_POST["del_operation"]))
{
	if ($_POST["del_operation"] == "Delete") {

		$statement = sql::con1()->prepare("DELETE FROM img_folder_contents WHERE folder_id = :folder_id");
		$result = $statement->execute(
			array(
				':folder_id'	=>	$_POST["folder_id"]
			)
		);
		if(!empty($result))
		{
			echo 'Data Deleted';
		}

		$statement2 = sql::con1()->prepare("DELETE FROM img_folder WHERE folder_id = :folder_id");
		$result2 = $statement2->execute(
			array(
				':folder_id'	=>	$_POST["folder_id"]
			)
		);
		if(!empty($result2))
		{
			echo 'Data Deleted';
		}

	}
}