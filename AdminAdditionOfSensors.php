<?php
	
	include('DBConnect.php');
	session_start();

	$user_login = $_SESSION['user_log_in'];
	$userStatus = $_SESSION['userStatus'];

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		
		$email_add = mysqli_real_escape_string($conn,$_POST['email_add']);
		$sensor_name = mysqli_real_escape_string($conn,$_POST['sensor_name']);
		$sensor_latitude = mysqli_real_escape_string($conn,$_POST['sensor_latitude']);
		$sensor_longitude = mysqli_real_escape_string($conn,$_POST['sensor_longitude']);
		$sensor_location = mysqli_real_escape_string($conn,$_POST['sensor_location']);
		$sensor_type = mysqli_real_escape_string($conn,$_POST['sensor_type']);

		$sql1 = "SELECT user_id, first_name, last_name, user_type, support_plan, email FROM USERS WHERE email = '$email_add'";

		$res1 = mysqli_query($conn,$sql1);

		$row1 = mysqli_fetch_array($res1,MYSQLI_ASSOC);

		$email = $row1['email'];
		$user_name = $row1['first_name'];
		$user_id = $row1['user_id'];

		$user_plan = $row1['support_plan'];


		if(!isset($_SESSION['user_log_in']))
		{	
		header("location:index.php");
		}


		date_default_timezone_set("America/Los_Angeles");
		$PresentDate = date("Y-m-d H:i:s");

		$sql2 = "INSERT INTO sensors (user_id, sensor_name, sensor_type, sensor_latitude, sensor_longitude, sensor_location, sensor_status, sensor_date_join) VALUES ('$user_id','$sensor_name','$sensor_type', '$sensor_latitude', '$sensor_longitude', '$sensor_location', 'ACTIVE', '$PresentDate')";
		$res2 = mysqli_query($conn,$sql2);

		if($res2)
		{
			$sql3 = "SELECT sensor_id, sensor_date_join FROM sensors WHERE user_id = '$user_id' AND sensor_name = '$sensor_name' AND sensor_latitude = '$sensor_latitude' AND sensor_longitude = '$sensor_longitude' ";
			
			$res3 = mysqli_query($conn,$sql3);
			$row3 = mysqli_fetch_array($res3,MYSQLI_ASSOC);

			$sensor_id = $row3['sensor_id'];
			$sensor_date_join = $row3['sensor_date_join'];
			$sensor_status = $row3['sensor_status'];

			$sql4 = "INSERT INTO sensorUse(user_id,sensor_id,updateTime) VALUES('$user_id','$sensor_id','$sensor_date_join')";
			$res4 = mysqli_query($conn,$sql4);
			
			if($user_plan === 'Basic Plan')
			{
				$price = 0.5;
			}else if($user_plan === 'Premium Plan')
			{
				$price = 0.3;
			}else if($user_plan === 'PRO')
			{
				$price = 0.1;
			}

			$sql5 = "INSERT INTO Bill(user_plan,sensor_state,sensor_price,user_id,sensor_id) VALUES('$user_plan','$sensor_status','$price','$user_id','$sensor_id')";

			$res5= mysqli_query($conn,$sql5);
		}
		else
		{
    		echo "Error: " . $sql2 . "<br>" . mysqli_error($conn);
		}
	}
?>


<!DOCTYPE html>
<html>
	<head>
		<title>Addition Of Sensors</title>
	</head>
	<body>
		<h2> Addition of Sensors </h2>
		<h4> Please enter the sensor details: </h4>
		<form action = "" method = "post">
			<label> User Email Address :* </label>
			<input type="text" name = "email_add" required="">
			</br>
			<label> Sensor Name :* </label>
			<input type="text" name = "sensor_name" required="">
			</br>
			<label> Sensor Latitude :* </label>
			<input type="text" name = "sensor_latitude" required="">
			</br>
			<label> Sensor Longitude :* </label>
			<input type="text" name = "sensor_longitude" required="">
			</br>
			<label> Sensor Location :* </label>
			<input type="text" name = "sensor_location" required="">
			</br>
			<label> Sensor Type :* </label>
			<select name = "sensor_type">
				<option>Temperature</option>
				<option>Turbidity</option>
				<option>Humidity</option>
			</select>
			</br>
			<input type="submit" value="Submit" name="submit1">
		</form>

		<h3>Logout</h3>
		<a href="Logout.php"> Click Here </a>

	</body>
</html>