<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    echo json_encode(['related' => 'error', 'message' => 'Access denied. Please log in as admin.']);
    exit;
}

// Process POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/db_connect.php'; // Include your database connection file

    // Check if food_id is provided in POST data
    if (isset($_POST['food_id'])) {
        $food_id = $_POST['food_id']; // Get the food_id from POST
        
        // Check if the food_id exists in the bill_food_details table
        $query = "SELECT COUNT(*) as count FROM bill_food_details WHERE food_id = :food_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":food_id", $food_id, PDO::PARAM_INT); // Bind parameter as integer
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If food is linked to a bill, set its availability to 0
        if ($result['count'] > 0) {

            // Respond with an appropriate message
            echo json_encode([
                "related" => true,
                "message" => "This food item is linked to a bill. Availability has been set to 0.",
            ]);
        } else {
            // If not linked to a bill, the food can be edited
            echo json_encode([
                "related" => false,
                "message" => "This food item can be edited.",
            ]);
        }
    } else {
        echo json_encode(["error" => "food_id is missing in the POST data."]);
    }
} else {
    echo json_encode(["error" => "Invalid request method."]);
}
?>
