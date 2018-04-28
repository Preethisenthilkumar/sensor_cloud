
<?php

	$host = "localhost";
	$dbuser = "root";
	$password = "root";
	$dbname = "SensorCloudEngine";
	$conn = mysqli_connect($host,$dbuser,$password,$dbname);
	if(!$conn)
	{
		die("connection Failed! " . mysqli_connect_error());
	}
	else
	{
	echo "Connected to database {$dbname}";
	}

?> 

<!DOCTYPE HTML>
<html>
	<head>
		<title>Database Connection!</title>
	</head>
<body>
	<?php
		// $sql = "CREATE TABLE USERS ";
		// $sql.= "(user_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,";
		// $sql.="first_name VARCHAR(30) NOT NULL,";
		// $sql.="last_name VARCHAR(30) NOT NULL,";
		// $sql.="email VARCHAR(60) NOT NULL,";
		// $sql.="password VARCHAR(100) NOT NULL,";
		// $sql.="street VARCHAR(50) NOT NULL,";
		// $sql.="city VARCHAR(40) NOT NULL,";
		// $sql.="state CHAR(2) NOT NULL,";
		// $sql.="zip MEDIUMINT UNSIGNED NOT NULL,";
		// $sql.="phone_no VARCHAR(20) NOT NULL,";
		// $sql.="credit_card_no VARCHAR(20) NOT NULL,";
		// $sql.="expiry_date DATE NOT NULL,";
		// $sql.="card_holder_name VARCHAR(255) NOT NULL,";
		// $sql.="support_plan VARCHAR(20) NOT NULL,";
		// $sql.="user_type VARCHAR(20) NOT NULL)";

		// $res = mysqli_query($conn,$sql);

		// if(!$res)
		// {
		// 	die("Query Failed!");
		// }else
		// {
		// 	echo "TAble created successfully!";
		// }

	?> 
</body>
</html>

 <?php
	// mysqli_close($conn);
?> 