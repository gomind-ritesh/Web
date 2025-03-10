<?php
require_once "includes/db_connect.php";

if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];

    // Fetch food image using the function defined in db_connect.php
    $nowfoodResult = food_image($conn, $food_id);

    if ($nowfoodResult) {

        echo "<h3 style='font-weight: bold;'>Food ID: " . htmlspecialchars($nowfoodResult['food_id']) . "</h3></br>";
        echo "<h3 style='font-weight: bold;'>Food Name: " . htmlspecialchars($nowfoodResult['food_name']) . "</h3></br>";
        echo "<img src='../Frontend/" . htmlspecialchars($nowfoodResult['food_source']) . "' class='w3-circle' alt='Food' style='width:100%; display: block; margin-left: auto; margin-right: auto;'>";
        
    } // end if 
    else {
        echo "<tr><td colspan='4'>No image found for this food.</td></tr>";
        // else{echo "<td>No image found for this food.</td>";}
    }
} else {
    echo "Invalid request.";
}
?>

