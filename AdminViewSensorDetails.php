
<!-- /* Need to Complete */ -->

<?php

	include('DBConnect.php');
	session_start();

	$user_login = $_SESSION['user_log_in'];

	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		
		$email_add = mysqli_real_escape_string($conn,$_POST['email_add']);

		$sql1 = "SELECT * FROM USERS where email = '$email_add' ";


		$res1 = mysqli_query($conn,$sql1);

		$row1 = mysqli_fetch_array($res1,MYSQLI_ASSOC);

		$user_email = $row1['email'];
		$user_type = $row1['user_type'];
		$first_name = $row1['first_name'];
		$last_name = $row1['last_name'];
		$user_id = $row1['user_id'];
		$user_support_plan = $row1['support_plan'];
		$user_bill = $row1['billing'];

	}

	

	

?>


<!DOCTYPE html>
<html>
	<head>
		<title>Admin</title>
	</head>
	<body>
		<span> <h2>Hydro Caliber Sensor Cloud !</h2></span>
		<span> <h4>Hello Admin <?php //echo ucfirst($last_name)." " . ucfirst($first_name); ?></h4></span>

		<form action="" method = "post">
			Enter User Email Address:
			<input type="text" name="email_add" required="">
		</form>

		<h3> Sensor Details </h3>
		<p> Total number of sensors: 

		<?php 
			$sqla = "SELECT COUNT(*) as countValue from sensors where user_id ='$user_id'";
			$resa = mysqli_query($conn,$sqla);
			$rowa = mysqli_fetch_array($resa,MYSQLI_ASSOC);

			$tempa = $rowa['countValue'];

			echo $tempa;

		?></p>

		<h4> Sensors based on its status </h4>
		<p> Active: 
			<span>Percentage :
			<?php 

			$sqlb = "SELECT COUNT(*) as countValue from sensors where user_id='$user_id' AND sensor_status = 'ACTIVE'";
			$resb = mysqli_query($conn,$sqlb);
			$rowb = mysqli_fetch_array($resb,MYSQLI_ASSOC);

			$tempb_1 = $rowb['countValue'];
			$tempb_2 = ($tempb_1/$tempa)*100;

			echo round($tempb_2,2); 
			?>%
			</span>
			<span>Count :
			<?php echo $tempb_1 ?>
			</span>
		</p>

		<p> Stop: 
			<span>Percentage :
			<?php 

			$sqlc = "SELECT COUNT(*) as countValue from sensors where user_id='$user_id' AND sensor_status = 'STOP'";
			$resc = mysqli_query($conn,$sqlc);
			$rowc = mysqli_fetch_array($resc,MYSQLI_ASSOC);

			$tempc_1 = $rowc['countValue'];
			$tempc_2 = ($tempc_1/$tempa)*100;

			echo round($tempc_2,2); 
			?>%
			</span>
			<span>Count :
			<?php 
			echo $tempc_1;
			 ?>
			</span>
		</p>

		<p> Removed: 
			<span>Percentage :
			<?php 

			$sqld = "SELECT COUNT(*) as countValue from sensors where user_id='$user_id' AND sensor_status = 'Removed'";
			$resd = mysqli_query($conn,$sqld);
			$rowd = mysqli_fetch_array($resd,MYSQLI_ASSOC);

			$tempd_1 = $rowd['countValue'];
			$tempd_2 = ($tempd_1/$tempa)*100;

			echo round($tempd_2,2); 
			?>%
			</span>
			<span>Count :
			<?php 
			echo $tempd_1; 
			?>
		</p>

		<h4> Sensors based on its types </h4>
		<p> Temperature: 
			<span>Percentage :
			<?php
			$sqle = "SELECT COUNT(*) as countValue from sensors where user_id='$user_id' AND sensor_type = 'Temperature'";
			$rese = mysqli_query($conn,$sqle);
			$rowe = mysqli_fetch_array($rese,MYSQLI_ASSOC);

			$tempe_1 = $rowe['countValue'];
			$tempe_2 = ($tempe_1/$tempa)*100;

			echo round($tempe_2,2); 
			?>%
			</span>
			<span>Count :
			<?php 
			echo $tempe_1; 
			?>
		</p>

		<p> Turbidity: 
			<span>Percentage :
			<?php
			$sqlf = "SELECT COUNT(*) as countValue from sensors where user_id='$user_id' AND sensor_type = 'Turbidity'";
			$resf = mysqli_query($conn,$sqlf);
			$rowf = mysqli_fetch_array($resf,MYSQLI_ASSOC);

			$tempf_1 = $rowf['countValue'];
			$tempf_2 = ($tempf_1/$tempa)*100;

			echo round($tempf_2,2); 
			?>%
			</span>
			<span>Count :
			<?php echo $tempf_1; ?>
		</p>

		<p> Humidity: 
			<span>Percentage :
			<?php
			$sqlg = "SELECT COUNT(*) as countValue from sensors where user_id='$user_id' AND sensor_type = 'Humidity'";
			$resg = mysqli_query($conn,$sqlg);
			$rowg = mysqli_fetch_array($resg,MYSQLI_ASSOC);

			$tempg_1 = $rowg['countValue'];
			$tempg_2 = ($tempg_1/$tempa)*100;

			echo round($tempg_2,2); 
			?>%
			</span>
			<span>Count :
			<?php echo $tempg_1; ?>
		</p>

		<h4> View Billing Details of users </h4>
		<?php
		echo "Payment Amount: ".$user_bill."$</br>"; 
		?>

		<h4> Add/remove Users </h4>

		<a href="Logout.php"><h3>Logout</h3></a>

	</body>
</html>
