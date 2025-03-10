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

//functions to filter by highest price
function greatestprice($conn)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sQuery = "SELECT u.user_name, b.bill_id, SUM(d.item_total_price) AS total_price, b.status, b.bill_date 
    FROM bill b 
    JOIN user u ON b.user_id = u.user_id
    JOIN bill_food_details d ON b.bill_id = d.bill_id
    GROUP BY u.user_name, b.bill_id, b.status, b.bill_date
    ORDER BY total_price DESC
    LIMIT 25"; // Get only the first 25 rows with the highest total_price

   $stmt = $conn->prepare($sQuery);

    // Execute the query
   $stmt->execute();
   
   // Fetch the result (the highest price first)
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetches the row as an associative array

   // Return the result
   return $result;
}  

//functions to filter by lowest price first
function leastprice($conn)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sQuery = "SELECT u.user_name, b.bill_id, SUM(d.item_total_price) AS total_price, b.status, b.bill_date 
    FROM bill b 
    JOIN user u ON b.user_id = u.user_id
    JOIN bill_food_details d ON b.bill_id = d.bill_id
    GROUP BY u.user_name, b.bill_id, b.status, b.bill_date
    ORDER BY total_price ASC
    LIMIT 25"; // Get only the first 25 rows with the lowest total_price

   $stmt = $conn->prepare($sQuery);

    // Execute the query
   $stmt->execute();
   
   // Fetch the result (the lowest price first)
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetches the row as an associative array

   // Return the result
   return $result;
}  

//functions to get the first 5 latest bill id
function user_bill_details($conn, $user_id)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sQuery = "SELECT u.user_name, b.bill_id, b.bill_date, b.status, SUM(d.item_total_price) AS total_price
    FROM bill b 
    JOIN user u ON b.user_id = u.user_id
    JOIN bill_food_details d ON b.bill_id = d.bill_id
    WHERE b.user_id=:user_id
    GROUP BY u.user_name, b.bill_id, b.bill_date, b.status
    ORDER BY b.bill_date DESC
    LIMIT 5"; // Get only the first 5 rows with the latest bill_id

   $stmt = $conn->prepare($sQuery);

   $stmt->bindParam(":user_id", $user_id );

    // Execute the query
   $stmt->execute();
   
   // Fetch the result (the latest first 5 bill id)
   $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetches the row as an associative array

   // Return the result
   return $result;
}  

//functions to update data in databasefunction ban($conn, $ban, $user_id)
function ban_user($conn, $ban, $user_id) {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $conn->beginTransaction(); // Start transaction

        $deleteQuery = "UPDATE user SET `ban`= :ban WHERE user_id=:user_id";

        $stmt = $conn->prepare($deleteQuery);

        // Convert boolean to integer (0 or 1)
        $ban = (int) $ban;
        $stmt->bindParam(":ban", $ban);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        // Check if the row was deleted
        if ($stmt->rowCount() > 0) {
            $conn->commit(); // Commit transaction if successful
            return true;
        } else {
            $conn->rollBack(); // Rollback if deletion failed
            return false;
        }
    } catch (Exception $e) {
        $conn->rollBack(); // Rollback in case of an error
        return false;
    }
}

//function to fetch bill details in review page
function review_bill_details($conn, $bill_id)
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

//for index.php
//functions to update data in databasefunction update_status_reservation($conn, $status, $reservation_id) {
    function update_status_reservation($conn, $status, $reservation_id) {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        try {
            $conn->beginTransaction(); // Start transaction
            
            $sUpdate = "UPDATE reservation SET `status` = :statuss WHERE reservation_id = :reservation_id";
            
            $stmt = $conn->prepare($sUpdate);
            $stmt->bindParam(":statuss", $status);
            $stmt->bindParam(":reservation_id", $reservation_id);
            
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

    //functions to retrieve the user details of the reservation
function reservation_user_details($conn, $user_id)
{
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sQuery = "SELECT 
                    u.user_id, 
                    u.user_name, 
                    u.user_email, 
                    u.firstname, 
                    u.lastname, 
                    u.phone
               FROM user u
               WHERE u.user_id = :user_id"; 

   $stmt = $conn->prepare($sQuery);

   $stmt->bindParam(":user_id", $user_id );

    // Execute the query
   $stmt->execute();
   
   $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetches the row as an associative array

   // Return the result
   return $result;
}  

    //functions to retrieve the food details 
    function food_image($conn, $food_id)
    {
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        $sQuery = "SELECT 
                        f.food_id, 
                        f.food_name, 
                        f.food_source
                   FROM food f
                   WHERE f.food_id = :food_id"; 
    
       $stmt = $conn->prepare($sQuery);
    
       $stmt->bindParam(":food_id", $food_id );
    
        // Execute the query
       $stmt->execute();
       
       $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetches the row as an associative array
    
       // Return the result
       return $result;
    }  

    //functions to enter new food details
    function enter_food($conn, $name, $price, $discount, $description, $category, $type, $source)
    {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        try {
            $conn->beginTransaction(); // Start transaction
    
            $sInsert = "INSERT INTO food (food_name, food_price, food_discount, food_description, food_category, food_type, food_source) 
                        VALUES (:namee, :price, :discount, :descriptionn, :category, :typee, :source)";
            
            $stmt = $conn->prepare($sInsert);
            $stmt->bindParam(":namee", $name);
            $stmt->bindParam(":price", $price);
            $stmt->bindParam(":discount", $discount);
            $stmt->bindParam(":descriptionn", $description);
            $stmt->bindParam(":category", $category);
            $stmt->bindParam(":typee", $type);
            $stmt->bindParam(":source", $source);
            
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
            return false;
        }
    }
//functions to update food availability
    function update_food_availability($conn, $food_id) {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        try {
            $conn->beginTransaction();
            
            $updateQuery = "UPDATE food SET available = 0 WHERE food_id = :food_id";
            
            $stmt = $conn->prepare($updateQuery);
            
            $stmt->bindParam(":food_id", $food_id, PDO::PARAM_INT);
            
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                
                $conn->commit();
                return true; 
            } else {
                $conn->rollBack();
                return false; 
            }
        } catch (Exception $e) {
            $conn->rollBack();
            return false; 
        }
    }
    


?>

