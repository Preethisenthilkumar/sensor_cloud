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



?>




<!DOCTYPE html>
<html>
	<head>
		<title>Administrator</title>
	</head>
	<body>
		<span> <h2>Hydro Caliber Sensor Cloud !</h2></span>
		<span> <h4>Hello Admin, <?php echo ucfirst($last_name)." " . ucfirst($first_name) ?></h4></span>
		</br>

		<h3>Sensors</h3>
		<ul>
			<li><a href="AdminViewSensorDetails.php"> View Sensor Details </a></li>
			<li><a href="AdminAdditionOfSensors.php"> Add Sensor </a></li>
			<!-- <li><a href="AdminDeletionOfSensors.php"> Delete Sensor </a></li> -->
			<li><a href="AdminUpdationOfSensors.php"> Update active/stop/remove for Sensor </a></li>
			<li><a href="AdminGeographicView.php">Sensors Location Details </a></li>
		</ul>

		<h3>Clusters</h3>
		<ul>
			<li><a href="AdminViewClusterDetails.php"> View Cluster Details </a></li>
			<li><a href="AdminGeographicViewOfClusters.php"> View Cluster Location Details </a></li>
			<li><a href=""> Add/Delete/Update Clusters</a></li>

		</ul>

		<h3>Billing Detail</h3>
		<a href="BillingDetail.php"> Click Here </a>

		<h3>Logout</h3>
		<a href="Logout.php"> Click Here </a>

	</body>
</html>
