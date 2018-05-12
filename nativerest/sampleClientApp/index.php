
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Sample Client App</title>
	<link href="bootstrap-3.3.51/css/bootstrap.css" rel="stylesheet"/>
</head>

<body>

	<div class="container">
		<br/>

		<?php

		// URL API
		$url = 'http://localhost:8888/NativeREST/api/Sensor_repo';
		//echo $url;
		$client = curl_init();
		$options = array(
	    CURLOPT_URL				=> $url, // Set URL of API
	    CURLOPT_CUSTOMREQUEST 	=> "GET", // Set request method
	    CURLOPT_RETURNTRANSFER	=> true, // true, to return the transfer as a string
	    );
		curl_setopt_array( $client, $options );

		// Execute and Get the response
		$response = curl_exec($client);
		// Get HTTP Code response
		$httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
		// Close cURL session
		curl_close($client);

		$daftarSensor=null;
		if($httpCode=="200"){ // if success
			$daftarSensor=json_decode($response);
		}else{ // if failed
			$response=json_decode($response);
			
		}

		?>

		<h1>Sensor Data Information</h1>
		<br/>
		
		<br/><br/>
		<table class="table" cellspacing="0" width="100%">
			<tr>
				<th>No.</th>
				<th>Sensor_id</th>
				<th>Sensor Type</th>
				<th>Timestamp</th>
				<th>Value</th>
				<th>Status</th>
			</tr>
			<?php
			if($daftarSensor!=null){
				$i=1;
				foreach($daftarSensor as $Sensor_repo){
					echo "<tr>";
					echo "<td>".$i++.".</td>";
					echo "<td>".$Sensor_repo->sensor_id."</td>";
					echo "<td>".$Sensor_repo->sensor_type."</td>";
					echo "<td>".$Sensor_repo->timestamp."</td>";
					echo "<td>".$Sensor_repo->value."</td>";
					echo "<td>Active</td>";
					echo "<td>";
					#echo "<a class='btn btn-warning btn-sm' href='edit.php?id=".$Sensor->id."'>Pause</a> ";
					#echo "<a class='btn btn-danger btn-sm' href='hapus.php?id=".$Sensor->id."'>Stop</a> ";
					echo "</td>";
					echo "</tr>";
				}
			}
			?>
		</table>

	</div>
</body>
</html>