<?php
require_once("dbcontroller.php");
use Opis\JsonSchema\{
    Validator, ValidationResult, ValidationError, Schema
};

require 'vendor/autoload.php';
/* 
A domain Class to demonstrate RESTful web services
*/
Class Reservation {
	private $reservations = array();

	private function validateJson($data) {
		$data2 = json_encode($data, JSON_NUMERIC_CHECK);
		//echo $data;
		//die;
		$data3 = json_decode($data2, false);

        $schema = Schema::fromJsonString(file_get_contents(__DIR__ . '/schemas/FoodSchema.json'));
        $validator = new Validator();

        /** @var ValidationResult $result */
        $result = $validator->schemaValidation($data3, $schema);

        if ($result->isValid()) {
            return true;
        } else {
            /** @var ValidationError $error */
            $error = $result->getFirstError();
            echo json_encode([
                'success' => 0,
                'message' => 'Invalid JSON data',
                'error' => [
                    'keyword' => $error->keyword(),
                    'args' => $error->keywordArgs()
                ]
            ]);
            return false;
        }
    }

	public function getAllReservation(){
		if(isset($_GET['name'])){
			$name = $_GET['name'];
			$query = 'SELECT * FROM reservation WHERE reservation_name LIKE "%' .$name. '%"';
		} else {
			$query = 'SELECT * FROM reservation';
		}
		$dbcontroller = new DBController();
		$this->reservations = $dbcontroller->executeSelectQuery($query);
		// Validate the output JSON
        if (!$this->validateJson($this->reservations)) {
            return null; // Stop execution if validation fails
        }

        return $this->reservations;
	}

	public function addReservation(){

		if(isset($_POST['name'])){

			$name = $_POST['name'];
			$phone = "";
			$people = "";
			$date = "";
			$time = "";
			$note = "";
			$status = "";
			$user_id = "";

			if(isset($_POST['phone'])){
				$phone = $_POST['phone'];
			}
			if(isset($_POST['people'])){
				$people = $_POST['people'];
			}
			$tables = ceil($people / 3);
			if(isset($_POST['date'])){
				$date = $_POST['date'];
			}	
			if(isset($_POST['time'])){
				$time = $_POST['time'];
			}
			if(isset($_POST['note'])){
				$note = $_POST['note'];
			}
			if(isset($_POST['status'])){
				$status = $_POST['status'];
			}
			if(isset($_POST['user_id'])){
				$user_id = $_POST['user_id'];
			}
			
			$query = "INSERT INTO reservation (reservation_name, reservation_phone, reservation_people, reservation_tables, reservation_date, reservation_time, reservation_note, status, user_id) values (?,?,?,?,?,?,?,?,?)";
			$data = [$name, $phone, $people, $tables, $date, $time, $note, $status, $user_id];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data );
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function deleteReservation(){
	// $inputData = file_get_contents('php://input');
    // $decodedData = json_decode($inputData);

    // if (!$this->validateJson($decodedData)) {
    //     return; // Stop execution if JSON is invalid
    // }
		if(isset($_GET['rid'])){
			$reservation_id = $_GET['rid'];
			$query = 'DELETE FROM reservation WHERE reservation_id = ?';
			$data = [$reservation_id];
			$dbcontroller = new DBController();
			$result = $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
	}
	
	public function editReservation(){
		$_SERVER['REQUEST_METHOD'] === "PUT"
            ? parse_str(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']), $_PUT)
            : $_PUT = array();

		// 	$decodedData = json_decode(json_encode($_PUT), true);

        // // Validate input JSON
        // if (!$this->validateJson($decodedData)) {
        //     return null; // Stop execution if validation fails
        // }

		if(isset($_PUT['name']) && isset($_GET['rid'])){
			$name = $_PUT['name'];
			$phone = $_PUT['phone'];
			$people = $_PUT['people'];
			$tables = ceil($people / 3);
			$date = $_PUT['date'];
			$time = $_PUT['time'];
			$note = $_PUT['note'];
			$status = $_PUT['status'];
			$user_id = $_PUT['user_id'];
			$reservation_id = $_GET['rid'];
			$query = "UPDATE reservation SET reservation_name = ?, reservation_phone = ?, reservation_people = ?, reservation_tables = ?, reservation_date = ?, reservation_time = ?, reservation_note = ?, status = ?, user_id = ? WHERE reservation_id = ? ";
			$data = [$name, $phone , $people, $tables, $date, $time, $note, $status, $user_id, $reservation_id];
			$dbcontroller = new DBController();
			$result= $dbcontroller->executeQuery($query, $data);
			if($result != 0){
				$result = array('success'=>1);
				return $result;
			}
		}
		
	}
	
}
?>