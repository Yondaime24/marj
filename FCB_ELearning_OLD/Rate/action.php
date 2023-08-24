<?php  
require '../___autoload.php';
use classes\Date as Date2;

if(isset($_POST["operation"]))
{
	//INSERT FEEDBACK
	if($_POST["operation"] == "Submit")
	{
		date_default_timezone_set("Asia/Manila");
		$date_rated = date('Y-m-d H:i:s');

		$rate_id = strtotime($date_rated) + 1;
		$uid = $_POST['user_id'];

		$statement = sql::con1()->prepare("INSERT INTO rate_us (user_id, rate_value, rate_cmt, date_rated, rate_id, seq) VALUES (:user_id, :rate_value, :rate_cmt, :date_rated, :rate_id, :seq)");
		$result = $statement->execute(
			array(
				':user_id'	      => $_POST['user_id'],
				':rate_value'	  => $_POST['rate_value'],
				':rate_cmt'	  	  => htmlspecialchars($_POST['rate_cmt']),
				':date_rated'	  => $date_rated,
				':rate_id'	      => $rate_id,
				':seq'			  => '1',
			)
		);
						// $stmt = sql::con1()->prepare("UPDATE rate_us SET seq = :seq WHERE user_id = '".$uid."' AND rate_id != '".$rate_id."'");
				    //     $result = $stmt->execute(
				    //         array(
				    //             ':seq' => '0'
				    //         )
				    //     );
		if(!empty($result))
		{
			echo 'Data Inserted';
			$user_id = $_POST['user_id'];

			$st = sql::con1()->prepare("SELECT count(*) as total FROM rate_us WHERE user_id = '".$_POST['user_id']."'");
			$st->execute();
		  	$count = $st->fetchAll();
			$count = $count[0]["total"];
		    if ($count > 3) {
		    	$dif =  $count - 3;
					$i = 0;
					while($i < $dif) {
						/////////
						$statement2 = sql::con1()->prepare("DELETE FROM rate_us WHERE rate_id = (SELECT TOP 1 rate_id FROM rate_us WHERE user_id = :user_id ORDER BY rate_id ASC)");
						$result2 = $statement2->execute(
							array(
								':user_id'	=>	$_POST["user_id"]
							)
						);
						/////////	
						$i++;
					}
					
					if(!empty($result2))
					{
						echo 'Data Deleted';
					}
		    }


		}
	}


}

	if(isset($_POST["operation2"]))
	{
		//VIEW PREVIOUS COMMENTS
		if ($_POST["operation2"] == "Fetch") {

			$user_id = $_POST["user_id"];

			$stmt = sql::con1()->prepare("SELECT * FROM rate_us WHERE user_id = '".$user_id."' AND seq = '0' ORDER BY rate_id DESC");
			$stmt->execute();
			$result = $stmt->fetchAll();
			$output = '';
			if (count($result) > 0) {
				foreach ($result as $row) { 

					$date_ago = Date2::Ago($row['date_rated']);

				$output .= '

					<div class="prev_cmt">
						<span class="fnum">'.$row['rate_value'].'<small class="snum">/5</small></span>
						<br>
						<div>
							<div class="cmt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row['rate_cmt'].'</div>
							<small class="date_rate">'.date('F j, Y / g:i a',strtotime($row['date_rated'])).'</small>
							<span class="ago" style="top:0px;"><i class="fas fa-clock"></i> '.$date_ago.' ago</span>
						</div>
					</div>

			   ';
			  
				}
			} else {
				$output .= '
				<div style="display: flex; align-items: center; justify-content: center;">
				<span style="font-family: sans-serif; font-size: 20px; font-weight: bold; color: red;">No Previous Reviews Yet!</span>
				</div>
			   ';
			}
			echo $output;
		}
	}