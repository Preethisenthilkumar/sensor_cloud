<?php
// URL API
$url = 'http://localhost:8888/nativerest/api/Sensor_repo';

$client = curl_init();

$data = array("sensor_id"=>"11111","sensor_type"=>"water", "timestamp"=>"2004-03-04 08:00:00", "value"=>"11.09");
$data = json_encode($data);

$options = array(
    CURLOPT_URL				=> $url, // Set URL of API
    CURLOPT_CUSTOMREQUEST 	=> "POST", // Set request method
    CURLOPT_RETURNTRANSFER	=> true, // true, to return the transfer as a string
    CURLOPT_POSTFIELDS 		=> $data, // Send the data in HTTP POST
);

curl_setopt_array( $client, $options );

// Execute and Get the response
$response = curl_exec($client);
// Get HTTP Code response
$httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
// Close cURL session
curl_close($client);

// Show response
echo '<h3>HTTP Code</h3>';
echo $httpCode;
echo '<h3>Response</h3>';
echo $response;

?>