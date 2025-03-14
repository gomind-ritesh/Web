<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_GET['username'])) {
    $username = trim($_GET['username']);
    //The trim() function removes any whitespace (or other predefined characters) from the beginning and end of the string. In this case, it ensures that if a user accidentally enters extra spaces before or after the username, those spaces are removed before the username is used in the query. This helps prevent mismatches or errors when checking the database for the username's existence.
    require_once "db_connect.php";
    
    // Prepare and execute a query to check if the username exists.
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE user_name = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    if($count > 0) {
        echo "<span style='color: red;'>Username already taken</span>";
    } else {
        echo "<span style='color: green;'>Username available</span>";
    }
} else {
    echo "";
    //The else { echo ""; } at the end ensures that if no username parameter was provided in the GET request, the script returns an empty response. This avoids any unwanted output or errors when the page is accessed without the username parameter. Essentially, it makes sure that the file outputs nothing if there's no data to check.
}
?>
