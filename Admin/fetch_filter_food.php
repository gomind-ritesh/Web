<?php
require_once "includes/db_connect.php";

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'recent'; // Default filter
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of results per page
$offset = ($page - 1) * $perPage;

$foodItems = [];
$totalPages = 1;

if ($filter === "recent") {
    // Count total food items for recent
    $sQuery = "SELECT COUNT(*) AS total FROM food";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch recent food items
    $foodQuery = "SELECT food_id, food_name, food_price, food_discount, food_description, 
                         food_category, food_type, food_source, available 
                  FROM food
                  ORDER BY food_id DESC
                  LIMIT :perPage OFFSET :offset";
} elseif ($filter === "appetizer-nonveg") {
    // Count total appetizer and non-veg items
    $sQuery = "SELECT COUNT(*) AS total FROM food WHERE food_category = 'Appetizer' AND food_type = 'Non-Veg'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch appetizer and non-veg food items
    $foodQuery = "SELECT food_id, food_name, food_price, food_discount, food_description, 
                         food_category, food_type, food_source, available 
                  FROM food
                  WHERE food_category = 'Appetizer' AND food_type = 'Non-Veg'
                  ORDER BY food_name ASC
                  LIMIT :perPage OFFSET :offset";
} elseif ($filter === "appetizer-veg") {
    // Count total appetizer and veg items
    $sQuery = "SELECT COUNT(*) AS total FROM food WHERE food_category = 'Appetizer' AND food_type = 'Veg'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch appetizer and veg food items
    $foodQuery = "SELECT food_id, food_name, food_price, food_discount, food_description, 
                         food_category, food_type, food_source, available 
                  FROM food
                  WHERE food_category = 'Appetizer' AND food_type = 'Veg'
                  ORDER BY food_name ASC
                  LIMIT :perPage OFFSET :offset";
} elseif ($filter === "maincourse-veg") {
    // Count total main course and veg items
    $sQuery = "SELECT COUNT(*) AS total FROM food WHERE food_category = 'Main Course' AND food_type = 'Veg'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch main course and veg food items
    $foodQuery = "SELECT food_id, food_name, food_price, food_discount, food_description, 
                         food_category, food_type, food_source, available 
                  FROM food
                  WHERE food_category = 'Main Course' AND food_type = 'Veg'
                  ORDER BY food_name ASC
                  LIMIT :perPage OFFSET :offset";
} elseif ($filter === "maincourse-nonveg") {
    // Count total main course and non-veg items
    $sQuery = "SELECT COUNT(*) AS total FROM food WHERE food_category = 'Main Course' AND food_type = 'Non-Veg'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch main course and non-veg food items
    $foodQuery = "SELECT food_id, food_name, food_price, food_discount, food_description, 
                         food_category, food_type, food_source, available 
                  FROM food
                  WHERE food_category = 'Main Course' AND food_type = 'Non-Veg'
                  ORDER BY food_name ASC
                  LIMIT :perPage OFFSET :offset";
} elseif ($filter === "dessert") {
    // Count total dessert items
    $sQuery = "SELECT COUNT(*) AS total FROM food WHERE food_category = 'Dessert'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch dessert food items
    $foodQuery = "SELECT food_id, food_name, food_price, food_discount, food_description, 
                         food_category, food_type, food_source, available 
                  FROM food
                  WHERE food_category = 'Dessert'
                  ORDER BY food_name ASC
                  LIMIT :perPage OFFSET :offset";
}

// Prepare and execute the final query
$stmt = $conn->prepare($foodQuery);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$foodItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['foodItems' => $foodItems, 'totalPages' => $totalPages]);
?>
