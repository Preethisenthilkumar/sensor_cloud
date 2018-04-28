<?php
	
	include('DBConnect.php');
	session_start();

	$user_login = $_SESSION['user_log_in'];

	if(!isset($_SESSION['user_log_in']))
	{
		header("location:index.php");
	}

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$sensor_name = mysqli_real_escape_string($conn,$_POST['sensor_name']);
		
		$sensor_status = mysqli_real_escape_string($conn,$_POST['sensor_status']);
		$user_email = mysqli_real_escape_string($conn,$_POST['email_add']);


		date_default_timezone_set("America/Los_Angeles");
		$PresentDate = date("Y-m-d H:i:s");


	//$userStatus = $_SESSION['userStatus']
	
	$sql1 = "SELECT user_id, first_name, last_name, user_type, email FROM USERS WHERE email = '$user_email'";

	$res1 = mysqli_query($conn,$sql1);

	$row1 = mysqli_fetch_array($res1,MYSQLI_ASSOC);

	$email = $row1['email'];
	$user_name = $row1['first_name'];
	$user_id = $row1['user_id'];
	$user_type =$row1['user_type'];


		$sql2 = "SELECT * FROM sensors WHERE user_id = '$user_id' AND sensor_name = '$sensor_name'";
		$res2 = mysqli_query($conn,$sql2);

		$row2 = mysqli_fetch_array($res2,MYSQLI_ASSOC);

		$sensor_id = $row2['sensor_id'];
		$sensor_date_join = $row2['sensor_date_join'];

		if($sensor_status == "ACTIVE")
		{
				$TimeNow = strtotime($PresentDate);

				$sqlSTART = "SELECT * FROM sensorUse WHERE sensor_id = '$sensor_id' ";

				$resSTART = mysqli_query($conn,$sqlSTART);

				while($row = mysqli_fetch_array($resSTART,MYSQLI_ASSOC)){

					$S_Status = $row['sensor_status'];
					$S_update_time = $row['updateTime'];
					$S_paused_time = $row['PausedTime'];

				}

				$TimePast = strtotime($S_update_time);

				$TimeDiff = $TimeNow - $TimePast;

				$S_paused_time = $S_paused_time + ($TimeDiff/3600);
				$S_paused_time = round($S_paused_time,2);


				$sql3 = "UPDATE sensors SET sensor_status='ACTIVE' WHERE user_id = '$user_id' AND sensor_name = '$sensor_name'";
				$res3 = mysqli_query($conn,$sql3);
				
				$sqlACTIVEtime = "UPDATE sensorUse set updateTime = '$PresentDate', PausedTime = '$S_paused_time' WHERE sensor_id = '$sensor_id'";

				$resACTIVEtime = mysqli_query($conn,$sqlACTIVEtime);

				if($resACTIVEtime)
				{
					echo "Sensor ".$sensor_name." status is ACTIVE";
				}

				$sqlBillActive = "UPDATE Bill set sensor_state = 'ACTIVE' WHERE sensor_id = '$sensor_id'";

				$resBillActive = mysqli_query($conn,$sqlBillActive);

		}
		else if($sensor_status == "STOP")
		{
				
				$TimeNow = strtotime($PresentDate);

				$sqlSTOP = "SELECT * FROM sensorUse WHERE sensor_id = '$sensor_id'";

				$resSTOP = mysqli_query($conn,$sqlSTOP);

				while($row = mysqli_fetch_array($resSTOP,MYSQLI_ASSOC)){

					$S_Status = $row['sensor_status'];
					$S_update_time = $row['updateTime'];
					$S_used_time = $row['usedTime'];

				}

				$TimePast = strtotime($S_update_time);

				$TimeDiff = $TimeNow - $TimePast;
				$S_used_time = $S_used_time + ($TimeDiff/3600);
				$S_used_time = round($S_used_time,2);


				$sql4 = "UPDATE sensors SET  sensor_status='STOP' WHERE user_id = '$user_id' AND sensor_name = '$sensor_name'";
				$res4 = mysqli_query($conn,$sql4);

				$sqlSTOPtime = " UPDATE sensorUse set updateTime = '$PresentDate', usedTime = '$S_used_time'
								WHERE sensor_id = '$sensor_id'";

				$resSTOPtime = mysqli_query($conn,$sqlSTOPtime);
				
				if($resSTOPtime)
				{
				echo "Sensor ".$sensor_name." status is STOPPED";
				}

				$sqlBillStop = "UPDATE Bill set sensor_state = 'STOP' WHERE sensor_id = '$sensor_id'";

				$resBillStop = mysqli_query($conn,$sqlBillStop);


		}
		else if($sensor_status == "REMOVE")
		{
				$TimeNow = strtotime($PresentDate);

				$sqlREMOVE = " SELECT * FROM sensorUse,sensors WHERE sensorUse.sensor_id = sensors.sensor_id AND sensors.sensor_id = '$sensor_id'";

				$resREMOVE = mysqli_query($conn,$sqlREMOVE);

				while($row = mysqli_fetch_array($resREMOVE,MYSQLI_ASSOC)){

					$S_Status = $row['sensor_status'];
					$S_update_time = $row['updateTime'];
					$S_paused_time = $row['pausedTime'];
					$S_used_time = $row['usedTime'];

				}

				$TimePast = strtotime($S_update_time);

				$TimeDiff = $TimeNow - $TimePast;

				if($S_Status == "Paused")
				{
					$S_paused_time = $S_paused_time + ($TimeDiff/3600);
					$S_paused_time = round($S_paused_time,2);	
				}
				else
				{
					$S_used_time = $S_used_time + ($TimeDiff/3600);
					$S_used_time = round($S_used_time,2);

				}
				


			$sql5 = "UPDATE sensors SET sensor_status = 'REMOVED' WHERE sensor_id = '$sensor_id' ";
			
			$res5= mysqli_query($conn,$sql5);
			

			$sql6 = "UPDATE sensorUSe SET updateTime = '$PresentDate' WHERE sensor_id = '$sensor_id'";
			$res6 = mysqli_query($conn,$sql6);

			if($res6)
			{
			echo "Sensor ".$sensor_name." status is REMOVED";
			}

			$sqlBillRemove = "UPDATE Bill set sensor_state = 'REMOVE' WHERE sensor_id = '$sensor_id'";

				$resBillRemove = mysqli_query($conn,$sqlBillRemove);
		}
	}
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Updation Of Sensors</title>
	</head>
	<body>
		<h2> Update Sensors </h2>
		<h4> Please enter the sensor details: </h4>
		<form action = "" method = "post">
			<label> User Email :* </label>
			<input type="text" name = "email_add" required="">
			</br>
			<label> Sensor Name :* </label>
			<input type="text" name = "sensor_name" required="">
			</br>
			
			<label> Sensor Status :* </label>
			</br>
			<label>
			<input type="radio" checked="" value="ACTIVE" name="sensor_status" /> ACTIVE 
			</label>
			</br>
			<label>
			<input type="radio" checked="" value="STOP" name="sensor_status" /> STOP
			</label>
			</br>
			<label>
			<input type="radio" checked="" value="REMOVE" name="sensor_status" /> REMOVE
			</label>
			</div>
			</br>
			</select>
			</br>
			<input type="submit" value="Submit" name="submit1">
		</form>

		<h3>Logout</h3>
		<a href="Logout.php"> Click Here </a>

	</body>
</html>