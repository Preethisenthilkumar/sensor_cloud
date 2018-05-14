<?php

Class Sensor_repo {
	
	public function __construct(){
		$this->db = $this->getDB();
	}

	// Connect Database
	private function getDB() {
		$dbhost="sensorcloudengine.cpvb8ltfseqh.us-east-2.rds.amazonaws.com";
		$dbuser="preethi";
		$dbpass="cmpe281preethi";
		$dbname="SensorCloudEngine";
		#$conn = mysqli_connect($host,$dbuser,$password,$dbname);
	

		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
		if(!$dbConnection)
		{
		die("connection Failed! " . mysqli_connect_error());
		}
		else
		{
		echo "Connected to database {$dbname}";
		}
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	}

	public function getAllSensor(){
        $sql = "SELECT * FROM Sensor_repo ORDER BY sensor_type ASC";
        $stmt = $this->db->query($sql); 
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $data;
	}

	public function getSensor($id){
        $sql = "SELECT * FROM Sensor_repo WHERE id=?";
        $stmt = $this->db->prepare($sql); 
        $stmt->execute(array($id));
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        return $data;
	}

	public function insertSensor($sensor_id,$sensor_type, $value1, $timestamp1 , $key1){
        $sql = "INSERT INTO Sensor_repo (sensor_id,sensor_type, value1, timestamp1 , key1) VALUES (?,?,?,?,?)";
        $stmt = $this->db->prepare($sql); 
        $status = $stmt->execute(array($sensor_id,$sensor_type, $value1, $timestamp1, $key1));
        return $status;
	}

	public function updateSensor($id,$sensor_id, $sensor_type, $value1, $timestamp1, $key1){
        $sql = "UPDATE Sensor_repo SET sensor_id=?,sensor_type=?, value1=?, timestamp1=?, key1=? WHERE id=?";
        $stmt = $this->db->prepare($sql); 
        $status = $stmt->execute(array($sensor_type, $SensorType, $value1, $timestamp1,$key1, $id));
        return $status;
	}

	public function deleteSensor($id){
        $sql = "DELETE FROM Sensor_repo WHERE id=?";
        $stmt = $this->db->prepare($sql); 
        $status = $stmt->execute(array($id));
        return $status;
	}
}
?>