<?php
 require_once "connect.php";

 // for register.php
 //functions to insert data in databasefunction insert_data_register_admin($conn, $username, $password, $email) {
   function insert_data_register_admin($conn, $username, $password, $email) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      try {
          $conn->beginTransaction(); // Start transaction
          
          $type = "admin";
          $default_value= NULL;
          $sInsert = "INSERT INTO user (user_name, user_email, user_pwd, usertype, firstname, lastname, phone) 
                      VALUES (:username, :useremail, :hashed_password, :usertype, :fname, :lname, :phone)";
          
          $stmt = $conn->prepare($sInsert);
          $stmt->bindParam(":username", $username);
          $stmt->bindParam(":useremail", $email);
          $stmt->bindParam(":hashed_password", $hashed_password);
          $stmt->bindParam(":usertype", $type);
          $stmt->bindParam(":fname", $default_value);
          $stmt->bindParam(":lname", $default_value);
          $stmt->bindParam(":phone", $default_value);
          
          $stmt->execute();
          
          // Check if the row was inserted
          if ($stmt->rowCount() > 0) {
              $conn->commit(); // Commit transaction if successful
              return true;
          } else {
              $conn->rollBack(); // Rollback if insertion failed
              return false;
          }
      } catch (Exception $e) {
          $conn->rollBack(); // Rollback in case of an error
          return false;
      }
  }
  

//functions to check if username already exists in database
function exist_username( $conn,$name)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $sQuery = "SELECT user_name FROM user WHERE user_name = :username;";

   $stmt = $conn->prepare($sQuery);
 
   $stmt->bindParam(":username", $name );

   $stmt->execute();
   $Result = $stmt->fetch(PDO::FETCH_ASSOC);
   
   //returns false if username doesn't exist
   return($Result);
}

//functions to check if email already exists in database
function exist_email($conn, $email)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $sQuery = "SELECT user_email FROM user WHERE user_email = :email;";

   $stmt = $conn->prepare($sQuery);
 
   $stmt->bindParam(":email", $email );

   $stmt->execute();
   $Result = $stmt->fetch(PDO::FETCH_ASSOC);
   
   //returns false if username doesn't exist
   return($Result);
}
   
//for index.php
//functions to update data in databasefunction update_status_bill($conn, $status, $bill_id) {
function update_status_bill($conn, $status, $bill_id) {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $conn->beginTransaction(); // Start transaction
        
        $sUpdate = "UPDATE bill SET `status`= :statuss WHERE bill_id=:bill_id";
        
        $stmt = $conn->prepare($sUpdate);
        $stmt->bindParam(":statuss", $status);
        $stmt->bindParam(":bill_id", $bill_id);
        
        $stmt->execute();
        
        // Check if the row was inserted
        if ($stmt->rowCount() > 0) {
            $conn->commit(); // Commit transaction if successful
            return true;
        } else {
            $conn->rollBack(); // Rollback if insertion failed
            return false;
        }
    } catch (Exception $e) {
        $conn->rollBack(); // Rollback in case of an error
        return false;
    }
}

//function to retrieve login information
function retrieve_login_admin($conn,$username)
{

    
  	$sQuery = "SELECT * FROM user WHERE user_name = '$username'  AND usertype = 'admin' ";
  	
  	#echo $sQuery;
  	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $Result = $conn->query($sQuery) ;
    $userResults = $Result->fetch(PDO::FETCH_ASSOC);

    return($userResults);

}

//functions to retrieve the recent orders
function latest_date($conn)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // Query to select all the rows with the latest bill_date
   $sQuery = "SELECT u.user_name, b.bill_id, SUM(d.item_total_price) AS total_price, b.status, b.bill_date 
   FROM bill b 
   JOIN user u ON b.user_id = u.user_id
   JOIN bill_food_details d ON b.bill_id = d.bill_id
   WHERE b.bill_date = (SELECT MAX(bill_date) FROM bill) -- Subquery returning a scalar value (latest date)
   GROUP BY u.user_name, b.bill_id, b.status, b.bill_date
   ORDER BY b.bill_id DESC"; 

   $stmt = $conn->prepare($sQuery);

    // Execute the query
   $stmt->execute();
   
   // Fetch the result (the latest bill)
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetches the row as an associative array

   // Return the result
   return $result;
}   

//functions to get the bill details
function bill_details($conn, $bill_id)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // Query to select all the rows pertaining to the bill details
   $sQuery = "SELECT b.bill_id, f.food_name, d.item_qty, (d.item_qty * f.food_price) AS total_qty_price
   FROM bill b 
   JOIN bill_food_details d ON b.bill_id = d.bill_id
   JOIN food f ON d.food_id = f.food_id
   WHERE b.bill_id=:bill_id
   ORDER BY food_name ASC"; 

   $stmt = $conn->prepare($sQuery);

   $stmt->bindParam(":bill_id", $bill_id );

    // Execute the query
   $stmt->execute();
   
   // Fetch the result 
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetches the row as an associative array

   // Return the result
   return $result;
}   

?>

