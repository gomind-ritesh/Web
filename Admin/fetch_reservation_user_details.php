<?php
require_once "includes/db_connect.php";

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch bill details using the function defined in db_connect.php
    $nowreservationuserResult = reservation_user_details($conn, $user_id);

    if ($nowreservationuserResult) {

        echo "<h3 style='font-weight: bold;'>User ID: " . htmlspecialchars($nowreservationuserResult['user_id']) . "</h3></br>";
        echo "<h3 style='font-weight: bold;'>User Name: " . htmlspecialchars($nowreservationuserResult['user_name']) . "</h3></br>";
        echo "<h3 style='font-weight: bold;'>Email: " . htmlspecialchars($nowreservationuserResult['user_email']) . "</h3></br>";
        echo "<h3 style='font-weight: bold;'>Fistname: " . htmlspecialchars($nowreservationuserResult['firstname']) . "</h3></br>";
        echo "<h3 style='font-weight: bold;'>Lastname: " . htmlspecialchars($nowreservationuserResult['lastname']) . "</h3></br>";
        echo "<h3 style='font-weight: bold;'>Tel No.: " . htmlspecialchars($nowreservationuserResult['phone']) . "</h3></br>";
        
    } // end if 
    else {
        echo "<tr><td colspan='4'>No details found for this reservation user.</td></tr>";
        // else{echo "<td>No details found for this reservation user.</td>";}
    }
} else {
    echo "Invalid request.";
}
?>

