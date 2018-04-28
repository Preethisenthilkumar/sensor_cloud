
<?php

	include('DBConnect.php');
	session_start();

	$user_login = $_SESSION['user_log_in'];

	$sql1 = "SELECT user_id, first_name, last_name, user_type, support_plan, email FROM USERS where email = '$user_login' ";

	$res1 = mysqli_query($conn,$sql1);

	$row1 = mysqli_fetch_array($res1,MYSQLI_ASSOC);

	$user_email = $row1['email'];
	$user_type = $row1['user_type'];
	$first_name = $row1['first_name'];
	$last_name = $row1['last_name'];
	$user_id = $row1['user_id'];
	$user_support_plan = $row1['support_plan'];
	$running_price = 0;
	$stop_price = 0;

	// Active Bill

	$sqlBill1 = "SELECT * FROM Bill WHERE user_plan = '$user_support_plan' AND sensor_state = 'Active'";

	$resBill1 = mysqli_query($conn,$sqlBill1);

	$rowBill1 = mysqli_fetch_array($resBill1,MYSQLI_ASSOC);

	$running_price = $rowBill1['sensor_price'];

	//echo "running price: ".$running_price;

	// Pause Bill

	$sqlBill2 = "SELECT * FROM Bill WHERE user_plan = '$user_support_plan' AND sensor_state = 'Stop'";

	$resBill2 = mysqli_query($conn,$sqlBill2);

	$rowBill2 = mysqli_fetch_array($resBill2,MYSQLI_ASSOC);

	$stop_price = $rowBill2['sensor_price'];

	//echo "stop price: ".$stop_price;
	///

	$sqlTotal = "SELECT sensorUse.usedTime, sensorUse.PausedTime FROM sensorUse INNER JOIN sensors ON sensors.sensor_id = sensorUse.sensor_id WHERE sensors.user_id='$user_id'";

	$resTotal = mysqli_query($conn,$sqlTotal);

	$temptotal = 0;

	while($row = mysqli_fetch_array($resTotal,MYSQLI_ASSOC))
	{
		$temptotal += ((($row['usedTime']/3600)*$running_price) + (($row['PausedTime']/3600)*$stop_price));
	}

	$temptotal = round($temptotal*0.10,2);
	//$temptotal = round($temptotal,2);

	$sqlInsertBill = "UPDATE USERS SET billing = '$temptotal' WHERE user_id = '$user_id'";
	$resInsertBill = mysqli_query($conn,$sqlInsertBill);

	if($resInsertBill)
	{
		echo "success";
	}else{
		echo "Error: " . mysqli_error($conn);
	}


?>




<!DOCTYPE html>
<html>
	<head>
		<title>Users</title>
	</head>
	<body>
		<span> <h2>Hydro Caliber Sensor Cloud !</h2></span>
		<span> <h4>Hello, <?php echo ucfirst($last_name)." " . ucfirst($first_name) ?></h4></span>

		<h3>Sensors</h3>
		<ul>
			<li><a href="UserViewSensorDetails.php"> View Sensor Details </a></li>
			<li><a href="UserAdditionOfSensors.php"> Add Sensor </a></li>
			<!-- <li><a href="DeletionOfSensors.php"> Delete Sensor </a></li> -->
			<li><a href="UserUpdationOfSensors.php"> Update active/stop/remove Sensor </a></li>
			<li><a href="UserGeographicView.php">Sensors Location Details </a></li>
		</ul>

		<h3>Clusters</h3>
		<ul>
			<li><a href="ViewClusterDetails.php"> View Cluster Details </a></li>
			<li><a href="GeographicViewOfClusters.php"> View Cluster Location Details </a></li>
		</ul>

		<h3>Billing Detail</h3>
		<p>Amount to pay : <?php echo $temptotal."$" ?></p>
		</br>
		<a href="paybill.php"> Bill Payment </a>

		
		<a href="Logout.php"><h3>Logout</h3></a>

	</body>
</html>

























