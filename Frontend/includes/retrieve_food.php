<?php
require_once "db_connect.php";

function retrieve_all_food($conn) {
    $query = "SELECT * FROM food WHERE available = 1"; // Get all available food items
    $stmt = $conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');

try {
    $data = retrieve_all_food($conn);
    echo json_encode($data);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch data: ' . $e->getMessage()]);
}
?>