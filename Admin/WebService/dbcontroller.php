<?php
class DBController {
	private $conn = "";
	private $host = "localhost";
	private $user = "root";
	private $password = "";
	private $database = "red_lantern";

	function __construct() {
		$conn = $this->connectDB();
		if(!empty($conn)) {
			$this->conn = $conn;			
		}
	}

	function connectDB() {
		$host = $this->host;
		$database = $this->database;
		$user = $this->user;
		$password = $this->password;
		try
		{
 			$conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
 			return $conn;
		}
		catch(PDOException $e)
 		{
    		echo  "<br>" . $e->getMessage();
 		}
	}

	function executeQuery($query, $data) {
		//Executes non-select queries (like INSERT, UPDATE, or DELETE) using a prepared statement.
        try{
        	
        	$stmt = $this->conn->prepare($query);
        	$result = $stmt->execute($data);
        	$affectedRows = $stmt->rowCount();
			return $affectedRows;
        }
        catch(PDOException $e)
 		{
    		echo  "<br>" . $e->getMessage();
 		}	        		
        
	}
    
    
	function executeSelectQuery($query) {
		$result = $this->conn->query($query);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}
	
}
?>
