<?php

function deliver_response($response){
	// Define HTTP responses
	$http_response_code = array(
		100 => 'Continue',  
		101 => 'Switching Protocols',  
		200 => 'OK',
		201 => 'Created',  
		202 => 'Accepted',  
		203 => 'Non-Authoritative Information',  
		204 => 'No Content',  
		205 => 'Reset Content',  
		206 => 'Partial Content',  
		300 => 'Multiple Choices',  
		301 => 'Moved Permanently',  
		302 => 'Found',  
		303 => 'See Other',  
		304 => 'Not Modified',  
		305 => 'Use Proxy',  
		306 => '(Unused)',  
		307 => 'Temporary Redirect',  
		400 => 'Bad Request',  
		401 => 'Unauthorized',  
		402 => 'Payment Required',  
		403 => 'Forbidden',  
		404 => 'Not Found',  
		405 => 'Method Not Allowed',  
		406 => 'Not Acceptable',  
		407 => 'Proxy Authentication Required',  
		408 => 'Request Timeout',  
		409 => 'Conflict',  
		410 => 'Gone',  
		411 => 'Length Required',  
		412 => 'Precondition Failed',  
		413 => 'Request Entity Too Large',  
		414 => 'Request-URI Too Long',  
		415 => 'Unsupported Media Type',  
		416 => 'Requested Range Not Satisfiable',  
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',  
		501 => 'Not Implemented',  
		502 => 'Bad Gateway',  
		503 => 'Service Unavailable',  
		504 => 'Gateway Timeout',  
		505 => 'HTTP Version Not Supported'
		);

	// Set HTTP Response
	header('HTTP/1.1 '.$response['status'].' '.$http_response_code[ $response['status'] ]);
	// Set HTTP Response Content Type
	header('Content-Type: application/json; charset=utf-8');
	// Format data into a JSON response
	$json_response = json_encode($response['data']);
	// Deliver formatted data
	echo $json_response;

	exit;
}


// Set default HTTP response of 'Not Found'
$response['status'] = 404;
$response['data'] = NULL;
echo "here 999 <br>", "\n";

$url_array = explode('/', $_SERVER['REQUEST_URI']);
echo $_SERVER['REQUEST_URI'], "\n"; 
echo json_encode($url_array), "\n";
array_shift($url_array); // remove first value as it's empty
// remove 2nd and 3rd array, because it's directory
array_shift($url_array); // 2nd = 'NativeREST'
array_shift($url_array); // 2nd = 'NativeREST'
array_shift($url_array); // 3rd = 'api'
echo json_encode($url_array), "\n";
echo $_SERVER['REQUEST_URI'], "\n"; 

// get the action (resource, collection)
$action = $url_array[0];
echo "action ",  $action, "$$", "\n" ;
// get the method
$method = $_SERVER['REQUEST_METHOD'];

require_once("Sensor_repo.php");
if( strcasecmp($action,'Sensor_repo') == 0){
	echo "here 11";
	$Sensor_repo = new Sensor_repo();
	if($method=='GET'){
		echo "here 1";
		if(!isset($url_array[1])){ // if parameter id not exist
			// METHOD : GET api/Sensor
			$data=$Sensor_repo->getAllSensor();
			$response['status'] = 200;
			$response['data'] = $data;
			echo "here 2";
		}else{ // if parameter id exist
			// METHOD : GET api/Sensor/:id
			$id=$url_array[1];
			$data=$Sensor_repo->getSensor($id);
			if(empty($data)) {
				$response['status'] = 404;
				$response['data'] = array('error' => 'Item not found');	
			}else{
				$response['status'] = 200;
				$response['data'] = $data;	
			}
			echo "here 3";
		}
	}
	elseif($method=='POST'){
		// METHOD : POST api/Sensor
		// get post from client
		$json = file_get_contents('php://input');
		$post = json_decode($json); // decode to object

		// check input completeness
		if($post->sensor_id==""  || $post->sensor_type==""  || $post->value1=="" || $post->timestamp1=="" || $post->key1==""){
			$response['status'] = 400;
			$response['data'] = array('error' => 'Data is incomplete');
		}else{
			$status = $Sensor_repo->insertSensor($post->sensor_id,$post->sensor_type, $post->value1, $post->timestamp1 , $post->key1);
			if($status==1){
				$response['status'] = 201;
				$response['data'] = array('success' => 'Data successfully saved');
			}else{
				$response['status'] = 400;
				$response['data'] = array('error' => 'There is an error');
			}
		}
	}
	elseif($method=='PUT'){
		// METHOD : PUT api/Sensor/:id
		if(isset($url_array[1])){
			$id = $url_array[1];
			// check if id exist in database
			$data=$Sensor_repo->getSensor($id);
			if(empty($data)) { 
				$response['status'] = 404;
				$response['data'] = array('error' => 'Data not found');	
			}else{
				// get post from client
				$json = file_get_contents('php://input');
				$post = json_decode($json); // decode to object

				// check input completeness
				if($post->sensor_id=="" ||$post->sensor_type=="" || $post->value1=="" || $post->timestamp1=="" || $post->key1==""){
					$response['status'] = 400;
					$response['data'] = array('error' => 'incomplete data');
				}else{
					$status = $Sensor_repo->updateSensor($id,$post->sensor_id, $post->sensor_type, $post->value1, $post->timestamp1, $post->key1);
					if($status==1){
						$response['status'] = 200;
						$response['data'] = array('success' => 'Data successfully edited');
					}else{
						$response['status'] = 400;
						$response['data'] = array('error' => 'There is an error');
					}
				}
			}
		}
	}
	elseif($method=='DELETE'){
		// METHOD : DELETE api/Sensor/:id
		if(isset($url_array[1])){
			$id = $url_array[1];
			// check if id exist in database
			$data=$Sensor_repo->getSensor($id);
			if(empty($data)) {
				$response['status'] = 404;
				$response['data'] = array('error' => 'Data not found');	
			}else{
				$status = $Sensor_repo->deleteSensor($id);
				if($status==1){
					$response['status'] = 200;
					$response['data'] = array('success' => 'Data successfully deleted');
				}else{
					$response['status'] = 400;
					$response['data'] = array('error' => 'There is an error');
				}
			}
		}
	}
}

// Return Response to browser
deliver_response($response);

?>