<?php
require_once "includes/db_connect.php";

function retrieve_food($conn, $food_category, $food_type) {
    $sQuery = "SELECT * FROM food WHERE food_category = :food_category AND food_type = :food_type AND available = 1";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare($sQuery);
    $stmt->bindParam(':food_category', $food_category);
    $stmt->bindParam(':food_type', $food_type);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Only process GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['category']) && isset($_GET['type'])) {
        $food_category = $_GET['category'];
        $food_type = $_GET['type'];

        // Fetch data and output JSON
        $foods = retrieve_food($conn, $food_category, $food_type);
        echo json_encode($foods);
    } else {
        echo json_encode(["error" => "Invalid request parameters"]);
    }
}
?>