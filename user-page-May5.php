<?php

	include('DBConnect.php');
	session_start();

	$user_login = $_SESSION['user_log_in'];

	$sql1 = "SELECT user_id, first_name, last_name, user_type, support_plan, email FROM USERS where email = '$user_login' ";

	$res1 = mysqli_query($conn,$sql1);

	$row1 = mysqli_fetch_array($res1,MYSQLI_ASSOC);

	$user_email = $row1['email'];
	//echo " user_id ".$user_email;
	$user_type = $row1['user_type'];
	// echo " user_type ".$user_type;
	$first_name = $row1['first_name'];
	// echo " user_name ".$first_name;
	$last_name = $row1['last_name'];
	$user_id = $row1['user_id'];
	// echo " user_id ".$user_id;
	$user_support_plan = $row1['support_plan'];
	// echo " user_support_plan ".$user_support_plan;
	$running_price = 0;
	$stop_price = 0;

	/// BILLING

	$sqlBill1 = "SELECT * FROM Bill WHERE user_plan = '$user_support_plan' AND sensor_state = 'Active'";

	$resBill1 = mysqli_query($conn,$sqlBill1);

	$rowBill1 = mysqli_fetch_array($resBill1,MYSQLI_ASSOC);

	$running_price = $rowBill1['sensor_price'];

	//echo "running price: ".$running_price;

	$sqlBill2 = "SELECT * FROM Bill WHERE user_plan = '$user_support_plan' AND sensor_state = 'Stop'";

	$resBill2 = mysqli_query($conn,$sqlBill2);

	$rowBill2 = mysqli_fetch_array($resBill2,MYSQLI_ASSOC);

	$stop_price = 0.5*$rowBill2['sensor_price'];

	//echo "stop price: ".$stop_price;

	$sqlTotal = "SELECT sensorUse.usedTime, sensorUse.PausedTime FROM sensorUse INNER JOIN sensors ON sensors.sensor_id = sensorUse.sensor_id WHERE sensors.user_id='$user_id'";

	$resTotal = mysqli_query($conn,$sqlTotal);

	$temptotal = 0;

	while($row = mysqli_fetch_array($resTotal,MYSQLI_ASSOC))
	{
		$temptotal += ((($row['usedTime']/3600)*$running_price) + (($row['PausedTime']/3600)*$stop_price));
	}

	$temptotal = round($temptotal,2);

	//echo $temptotal;

	$sqlInsertBill = "UPDATE USERS SET billing = '$temptotal' WHERE user_id = '$user_id'";
	$resInsertBill = mysqli_query($conn,$sqlInsertBill);

	if($resInsertBill)
	{
		//echo "success";
	}else{
		echo "Error: " . mysqli_error($conn);
	}

	

	if(!isset($_SESSION['user_log_in']))
	{
		header("location : index.php");
	}

	if($user_type == "admin")
	{
		header("location : admin-page.php");
	}


	/// STATUS UPDATE

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_POST['action']) && isset($_POST['id']))
		{
			$id = 0;
			$id = $_POST['id'];
			//echo "id: ".$id;

			if($_POST['action'] == 'STOP')
			{
				date_default_timezone_set("America/Los_Angeles");
				$currentDate = date("Y-m-d H:i:s");
				$datetime1 = strtotime($currentDate);

				$sqlstop = "SELECT * FROM sensorUse WHERE sensor_id = '$id'";
				$resStop = mysqli_query($conn, $sqlstop);

				while($rowStop = mysqli_fetch_array($resStop,MYSQLI_ASSOC))
				{
					$StartStopTime = $rowStop['UpdateTime'];
					//echo 'update time: '.$StartStopTime;
					$usedTime = $rowStop['usedTime'];
					//echo 'used time: '.$usedTime;
				}
				$datetime2 = strtotime($StartStopTime);

				$time_diff = $datetime1 - $datetime2;
				//echo "time_diff STOP: ".$time_diff;

				$usedTime += $time_diff;

				$sqlstop1 = "UPDATE sensors SET sensor_status = 'STOP' WHERE sensor_id = '$id'";
				$resstop1 = mysqli_query($conn,$sqlstop1);

				$sqlstop2 = "UPDATE sensorUse SET UpdateTime = '$currentDate', usedTime = '$usedTime' WHERE sensor_id = '$id'";
				$resstop2 = mysqli_query($conn,$sqlstop2);

				if(!$resstop2)
				{
					die('couldnt update date: '.mysql_error());
				}

			
			}
			
		}
	}

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_POST['action']) && isset($_POST['id']))
		{
			$id = 0;
			$id = $_POST['id'];

			if($_POST['action'] == 'START')
			{
				date_default_timezone_set("America/Los_Angeles");
			$currentDate = date("Y-m-d H:i:s");
			$datetime1 = strtotime($currentDate);

			$sqlstart = "SELECT * FROM sensorUse WHERE sensor_id = $id";
			$resStart = mysqli_query($conn, $sqlstart);

				while($rowStart = mysqli_fetch_array($resStart,MYSQLI_ASSOC))
				{
					$StartStartTime = $rowStart['UpdateTime'];
					$PausedTime = $rowStart['PausedTime'];
				}
				$datetime2 = strtotime($StartStartTime);

				$time_diff = $datetime1 - $datetime2;
				//echo "time_diff START: ".$time_diff;

				$PausedTime += $time_diff;
				//$PausedTime = round($PausedTime,2);

				$sqlstart1 = "UPDATE sensors SET sensor_status = 'START' WHERE sensor_id = '$id'";
				$resstart1 = mysqli_query($conn,$sqlstart1);
			// 	//echo "updated ";

				$sqlstart2 = "UPDATE sensorUse SET UpdateTime = '$currentDate', PausedTime = '$PausedTime' WHERE sensor_id = '$id'";
				$resstop2 = mysqli_query($conn,$sqlstart2);

				if(!$resstart2)
				{
					die('couldnt update date: '.mysql_error());
				}
			}
		}	
	}

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(isset($_POST['action']) && isset($_POST['id']))
		{
			$id = 0;
			$id = $_POST['id'];

			if($_POST['action'] == 'Removed')
			{
				date_default_timezone_set("America/Los_Angeles");
				$currentDate = date("Y-m-d H:i:s");
				$datetime1 = strtotime($currentDate);

				$sqlremove = "SELECT * FROM sensorUse INNER JOIN sensors ON sensors.sensor_id = sensorUse.sensor_id WHERE sensor_id = $id";
				$resremove = mysqli_query($conn, $sqlremove);

				while($rowremove = mysqli_fetch_array($resremove, MYSQLI_ASSOC))
				{
					$sen_status = $rowremove['sensor_status'];
					$StartRemoveTime = $rowremove['UpdateTime'];
					$PausedTime = $rowremove['PausedTime'];
					$usedTime = $rowremove['usedTime'];
				}

				$datetime2 = strtotime($StartRemoveTime);
				$diffSeconds= $datetime1-$datetime2;


				/////
				if($sen_status =="STOP") {
                    $PausedTime += ($diffSeconds);
                    //$PausedTime = round($PausedTime, 2);
                    
                  }
                  else {
                    $usedTime += ($diffSeconds);
                    //$usedTime = round($usedTime, 2);
                  }

                  $sqlremove1 = "UPDATE sensors SET sensor_status = 'Removed' WHERE sensor_id = $id";
                  $resremove1 = mysqli_query($conn, $sqlremove1);
                    
                  
                  $sqlremove2 = "UPDATE sensorUse set UpdateTime = '$currentDate' WHERE sensor_id = $id";

                  $resremove2 = mysqli_query($conn,$sqlremove2);
                
                  if(! $resremove2)
                  {
                    die('Couldnt update data: ' . mysql_error());
                  }

		
			}
		}	
	}


?>


<!DOCTYPE html>
<html>
	<head>
		<title>Users</title>
	</head>
	<body>
		<!-- Side bar -->
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
		<p>Amount to pay : <?php echo $temptotal."$"; ?></p>
		</br>
		<a href="paybill.php"> Bill Payment </a>

		
		<a href="Logout.php"><h3>Logout</h3></a>

		<!-- center page -->
		<h3> Front page </h3>
		<div>
		<p>Total Sensors : </p>
		<?php 
			$resq = mysqli_query($conn,"SELECT COUNT(*) AS count FROM sensors WHERE user_id = '$user_id'");
			$rowq = mysqli_fetch_array($resq,MYSQLI_ASSOC);
			echo $rowq['count'];
		 ?>
		</div>
		<div>
			<p>Sensor Status</p>

			<div>
			<p> Active </p>
			<?php
			$resa = mysqli_query($conn,"SELECT COUNT(*) AS count FROM sensors WHERE user_id = '$user_id' AND sensor_status = 'ACTIVE'");
			$rowa = mysqli_fetch_array($resa,MYSQLI_ASSOC);
			$valActive = ($rowa['count']/$rowq['count'])*100;
			$valActive = round($valActive,2);
			echo $valActive;
			?>

			<?php
			echo $rowa['count'];
			?>
			</div>

			<div>
				<p>Stop</p>
				<?php
			$resb = mysqli_query($conn,"SELECT COUNT(*) AS count FROM sensors WHERE user_id = '$user_id' AND sensor_status = 'STOP'");
			$rowb = mysqli_fetch_array($resb,MYSQLI_ASSOC);
			$valStop = ($rowb['count']/$rowq['count'])*100;
			$valStop = round($valStop,2);
			echo $valStop;
			?>

			<?php
			echo $rowb['count'];
			?>
			</div>

			<div>
				<p>Remove</p>
				<?php
			$resc = mysqli_query($conn,"SELECT COUNT(*) AS count FROM sensors WHERE user_id = '$user_id' AND sensor_status = 'REMOVED'");
			$rowc = mysqli_fetch_array($resc,MYSQLI_ASSOC);
			$valRemove = ($rowc['count']/$rowq['count'])*100;
			$valRemove = round($valRemove,2);
			echo $valRemove;
			?>

			<?php
			echo $rowc['count'];
			?>
			</div>

		</div>

		<div>
			<p> Sensor Based on Type </p>
			<div>
				<p> Temperature </p>
				<?php
				$resd = mysqli_query($conn,"SELECT COUNT(*) AS count FROM sensors WHERE user_id = '$user_id' AND sensor_type = 'Temperature'");
			$rowd = mysqli_fetch_array($resd,MYSQLI_ASSOC);
			$valTemp = ($rowd['count']/$rowq['count'])*100;
			$valTemp = round($valTemp,2);
			echo $valTemp;
			?>

			<?php
			echo $rowd['count'];
			?>
			</div>

			<div>
				<p>Humidity</p>
				<?php
				$rese = mysqli_query($conn,"SELECT COUNT(*) AS count FROM sensors WHERE user_id = '$user_id' AND sensor_type = 'Humidity'");
			$rowe = mysqli_fetch_array($rese,MYSQLI_ASSOC);
			$valhumidity = ($rowe['count']/$rowq['count'])*100;
			$valhumidity = round($valhumidity,2);
			echo $valhumidity;
			?>

			<?php
			echo $rowe['count'];
			?>
			</div>

			<div>
				<p>Turbidity</p>
				<?php
				$resf = mysqli_query($conn,"SELECT COUNT(*) AS count FROM sensors WHERE user_id = '$user_id' AND sensor_type = 'Turbidity'");
			$rowf = mysqli_fetch_array($resf,MYSQLI_ASSOC);
			$valturbid = ($rowf['count']/$rowq['count'])*100;
			$valturbid = round($valturbid,2);
			echo $valturbid;
			?>

			<?php
			echo $rowf['count'];
			?>

			</div>
		</div>

		<div>
			<p> Table </p>
		<table>
			<thead>
				<tr>
					<th>Name</th>
                    <th>Type</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Status</th>
                    <th>Start date</th>
                    <th>Used Time</th>
                    <th>Paused Time</th>
                    <th>Change Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
				date_default_timezone_set('America/Los_Angeles');
                         
                 $resg = mysqli_query($conn,"SELECT sensors.sensor_id,sensors.sensor_name, sensors.sensor_type,sensors.sensor_latitude, sensors.sensor_longitude, sensors.sensor_location,sensors.sensor_status,sensors.sensor_date_join, sensorUse.usedTime, sensorUse.PausedTime, sensorUse.UpdateTime FROM sensors INNER JOIN sensorUse ON sensors.sensor_id = sensorUse.sensor_id WHERE sensors.user_id = '$user_id'");

                 While($rowg = mysqli_fetch_array($resg,MYSQLI_ASSOC))
                 {
                 	$usedHours = $rowg['usedTime']/3600;
                 	//echo "usedHours ".$usedHours;
                 	$usedMin = ($usedHours - floor($usedHours))*60;
                 	//echo "round ".(floor($usedHours));
                 	$pausedHours = $rowg['PausedTime']/3600;
                 	$pausedMin = ($pausedHours - floor($pausedHours))*60;
                 	echo '<tr>'.'<td>'.$rowg["sensor_name"].'</td>'.'<td>'.$rowg["sensor_type"].'</td>'.'<td>'.$rowg["sensor_latitude"].'</td>'.'<td>'.$rowg["sensor_longitude"].'</td>'.'<td>'.$rowg["sensor_status"].'</td>'.'<td>'.date_format(new DateTime($rowg["sensor_date_join"]),'d M Y').'</td>'.'<td>'.round($usedHours,0).'hrs '.round($usedMin,0).'min '.'</td>'.'<td>'.round($pausedHours,0).'hrs '.round($pausedMin,0).'min '.'</td>';

                 	echo '<td>';

                 	echo '<form method = "POST" action = "">';

                 	if($rowg['sensor_status'] == "START")
                 	{
                 		echo '<input type="submit" name="action" value="START" disabled/ >
                              <input type="submit" name="action"  value="STOP" / >
                              <input type="submit" name="action" value="Removed" / >
                              <input type="hidden" name="id" value="'.$rowg["sensor_id"].'" />';
                              echo "sensor_id: ".$rowg["sensor_id"];
                 	}else if($rowg['sensor_status'] == "STOP")
                 		{
                 			echo '<input type="submit" name="action" value="START" / >
                              <input type="submit" name="action"  value="STOP" disabled / >
                              <input type="submit" name="action" value="Removed" / >
                              <input type="hidden" name="id" value="'.$rowg["sensor_id"].'" />';
                              echo "sensor_id: ".$rowg["sensor_id"];
                 		}else if($rowg['sensor_status'] == "Removed")
                 		{
                 			echo '<input type="submit" name="action" value="START" disabled/ >
                 			<input type="submit" name="action"  value="STOP" disabled/ >
                 			<input type="submit" name="action" value="Removed" disabled/ >
                              <input type="hidden" name="id" value="'.$rowg["sensor_id"].'" />';
                              echo "sensor_id: ".$rowg["sensor_id"];

                 		}

                 	echo '</form>';
                 	echo '</td> </tr>';
                 }
				?>
			</tbody>
		</table>
		</div>
</body>
</html>


