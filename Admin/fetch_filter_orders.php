<?php
// require_once "includes/db_connect.php";

// $filter = isset($_GET['filter']) ? $_GET['filter'] : 'recent';

// // Initialize the orders variable
// $orders = [];

// if ($filter == "highest") {
//     $orders = greatestprice($conn);
// } elseif ($filter == "lowest") {
//     $orders = leastprice($conn);
// } elseif ($filter == "recent") {
//     $orders = latest_date($conn);
// }

// header('Content-Type: application/json');
// echo json_encode($orders);
require_once "includes/db_connect.php";

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'recent';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of orders per page
$offset = ($page - 1) * $perPage;

$orders = [];
$totalPages = 1;

if ($filter == "highest") {
    $sQuery = "SELECT COUNT(*) AS total FROM bill b JOIN user u ON b.user_id = u.user_id
               WHERE u.usertype = 'customer'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    $ordersQuery = "SELECT u.user_name, b.bill_id, SUM(d.item_total_price) AS total_price, b.status, b.bill_date 
                    FROM bill b 
                    JOIN user u ON b.user_id = u.user_id
                    JOIN bill_food_details d ON b.bill_id = d.bill_id
                    WHERE u.usertype = 'customer'
                    GROUP BY u.user_name, b.bill_id, b.status, b.bill_date
                    ORDER BY total_price DESC 
                    LIMIT :perPage OFFSET :offset";
} elseif ($filter == "lowest") {
    $sQuery = "SELECT COUNT(*) AS total FROM bill b 
               JOIN user u ON b.user_id = u.user_id
               WHERE u.usertype = 'customer'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    $ordersQuery = "SELECT u.user_name, b.bill_id, SUM(d.item_total_price) AS total_price, b.status, b.bill_date 
                    FROM bill b 
                    JOIN user u ON b.user_id = u.user_id
                    JOIN bill_food_details d ON b.bill_id = d.bill_id
                    WHERE u.usertype = 'customer'
                    GROUP BY u.user_name, b.bill_id, b.status, b.bill_date
                    ORDER BY total_price ASC 
                    LIMIT :perPage OFFSET :offset";
} else {
    $sQuery = "SELECT COUNT(*) AS total FROM bill b JOIN user u ON b.user_id = u.user_id WHERE u.usertype = 'customer' AND b.bill_date = (SELECT MAX(bill_date) FROM bill)";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    $ordersQuery = "SELECT u.user_name, b.bill_id, SUM(d.item_total_price) AS total_price, b.status, b.bill_date 
                    FROM bill b 
                    JOIN user u ON b.user_id = u.user_id
                    JOIN bill_food_details d ON b.bill_id = d.bill_id
                    WHERE u.usertype = 'customer' 
                    AND b.bill_date = (SELECT MAX(bill_date) FROM bill)
                    GROUP BY u.user_name, b.bill_id, b.status, b.bill_date
                    ORDER BY b.bill_id DESC
                    LIMIT :perPage OFFSET :offset";
}

$stmt = $conn->prepare($ordersQuery);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['orders' => $orders, 'totalPages' => $totalPages]);

?>
