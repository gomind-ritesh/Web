<?php
 require_once "connect.php";

 //functions to insert data in databasefunction insert_data_register($conn, $username, $firstname, $lastname, $phone, $password, $email) {
   function insert_data_register($conn, $username, $firstname, $lastname, $phone, $password, $email) {
    
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      try {
          $conn->beginTransaction(); // Start transaction
          
          $type = "customer";
          $sInsert = "INSERT INTO user (user_name, user_email, user_pwd, usertype, firstname, lastname, phone) 
                      VALUES (:username, :useremail, :hashed_password, :usertype, :fname, :lname, :phone)";
          
          $stmt = $conn->prepare($sInsert);
          $stmt->bindParam(":username", $username);
          $stmt->bindParam(":useremail", $email);
          $stmt->bindParam(":hashed_password", $hashed_password);
          $stmt->bindParam(":usertype", $type);
          $stmt->bindParam(":fname", $firstname);
          $stmt->bindParam(":lname", $lastname);
          $stmt->bindParam(":phone", $phone);
          
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
   $sQuery = "SELECT user_name,user_pwd FROM user WHERE user_name = :username;";

   $stmt = $conn->prepare($sQuery);
 
   $stmt->bindParam(":username", $name );

   $stmt->execute();
   $result = $stmt->fetch(PDO::FETCH_ASSOC);

   //returns false if username doesn't exist
   return($result);
}

//functions to check if email already exists in database
function exist_email($conn, $email)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $sQuery = "SELECT user_email FROM user WHERE user_email = :email;";

   $stmt = $conn->prepare($sQuery);
 
   $stmt->bindParam(":email", $email );

   $stmt->execute();
   $result = $stmt->fetch(PDO::FETCH_ASSOC);
   
   //returns false if username doesn't exist
   return($result);
}
   
//function to retrieve login information

function retrieve_login($conn, $username)
{
    $sQuery = "SELECT * FROM user WHERE user_name = '$username'  ";
  	
  	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $result = $conn->query($sQuery) ;
    $userResults = $result->fetch(PDO::FETCH_ASSOC);

    return($userResults);

}

   
//function to retrieve food deatils

function retrieve_food($conn, $food_category ,$food_type)
{
    $sQuery = "SELECT * FROM food WHERE food_category = '$food_category' AND food_type = '$food_type'  ";
  	
  	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $result = $conn->query($sQuery) ;

    //send object $Result
    return($result);

}
function retrieve_userid($conn)
{
    $name = $_SESSION['username'];
    
    // Use prepared statement to prevent SQL injection
    $sQuery = "SELECT * FROM user WHERE user_name = :username";
    $stmt = $conn->prepare($sQuery);
    $stmt->bindParam(':username', $name);
   
        $stmt->execute();
        $userResults = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userResults["user_id"];

}

function enter_reservation($conn, $reservationname, $partysize, $date, $time, $phone, $comment)
{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $conn->beginTransaction(); // Start transaction
        
        $user_id = retrieve_userid($conn);
        if (!$user_id) {
            throw new Exception("User ID could not be retrieved.");
        }

        $numtables = ceil($partysize / 3); // Use ceil to calculate number of tables
        
        $rtime = date("H:i:s", strtotime(sprintf("%02d:00:00", $time)));

        $sInsert = "INSERT INTO reservation (reservation_name, reservation_phone, reservation_people, reservation_tables, reservation_date, reservation_time, reservation_note, user_id) 
                    VALUES (:reservation_name, :reservation_phone, :reservation_people, :reservation_table, :reservation_date, :reservation_time, :reservation_note, :user_id)";
        
        $stmt = $conn->prepare($sInsert);
        $stmt->bindParam(":reservation_name", $reservationname);
        $stmt->bindParam(":reservation_phone", $phone);
        $stmt->bindParam(":reservation_people", $partysize);
        $stmt->bindParam(":reservation_table", $numtables);
        $stmt->bindParam(":reservation_date", $date);
        $stmt->bindParam(":reservation_time", $rtime);
        $stmt->bindParam(":reservation_note", $comment);
        $stmt->bindParam(":user_id", $user_id);
        
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
        $conn->commit(); // Commit transaction if successful
        return true;
        }
        else {
            $conn->rollBack(); // Rollback if insertion failed
            return false;
        }
    } catch (Exception $e) {
        $conn->rollBack(); // Rollback in case of an error
        error_log("Error entering reservation: " . $e->getMessage()); // Log the error
        return false;
    }
}


//For review.php
//functions to insert data in databasefunction insert_data_review($conn, $rating, $comment, $user_id, $bill_id) {
    function insert_data_review($conn, $rating, $comment, $user_id, $bill_id) {
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        try {
            $conn->beginTransaction(); // Start transaction
            
            $sInsert = "INSERT INTO reviews (review_comment, review_rating, user_id, bill_id)
                        VALUES (:comment, :rating, :userid, :billid)";
            
            $stmt = $conn->prepare($sInsert);
            $stmt->bindParam(":comment", $comment);
            $stmt->bindParam(":rating", $rating);
            $stmt->bindParam(":userid", $user_id);
            $stmt->bindParam(":billid", $bill_id);
            
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
            $conn->rollBack();
            return false; // Return false on failure
        }
    }


//functions to update review in databasefunction update_data_review($conn, $bill_id)
    function update_data_review($conn, $bill_id) {
    
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        try {
            $conn->beginTransaction(); // Start transaction
            
            $sUpdate = "UPDATE bill SET reviewed = 1 WHERE bill_id = :billid;";
            
            $stmt = $conn->prepare($sUpdate);
            $stmt->bindParam(":billid", $bill_id);
            
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


    //functions to fetch user id in databasefunction fetch_userid_review($conn, $username)
    function fetch_userid_review($conn, $username)
    {
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // Query to fetch ID of user
   $sQuery = "SELECT user_id
   FROM user
   WHERE user_name = :username;";

   $stmt = $conn->prepare($sQuery);

   $stmt->bindParam(":username", $username );

    // Execute the query
   $stmt->execute();
   
   // Fetch the result 
   $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetches the row as an associative array

   // Return the result
   return $result["user_id"];
    }


//functions to fetch bill ID details in databasefunction bill_id_show($conn, $username)
function bill_id_show($conn, $username)
{
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query to fetch bill details of user
$sQuery = "SELECT b.bill_id, b.bill_date, SUM(bf.item_qty) AS sum_item_qty, SUM(bf.item_total_price) AS sum_item_total_price
FROM bill b
JOIN bill_food_details bf ON bf.bill_id = b.bill_id
JOIN user u ON b.user_id = u.user_id
WHERE b.reviewed = 0
AND status = \"completed\"
AND u.user_name = :username
GROUP BY b.bill_id, b.bill_date;";

$stmt = $conn->prepare($sQuery);

$stmt->bindParam(":username", $username );

 // Execute the query
$stmt->execute();

// Return the result
return $stmt;
}




?>