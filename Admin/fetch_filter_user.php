<?php
require_once "includes/db_connect.php";

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'recent';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of orders per page
$offset = ($page - 1) * $perPage;

$orders = [];
$totalPages = 1;

if ($filter == "descending") {
    $sQuery = "SELECT COUNT(*) AS total FROM user WHERE usertype='customer'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    $usersQuery = "SELECT u.user_id, u.user_name, u.user_email, u.firstname, u.lastname, u.phone, u.ban
                    FROM user u
                    WHERE u.usertype='customer'
                    ORDER BY u.user_name DESC 
                    LIMIT :perPage OFFSET :offset";
} elseif ($filter == "ascending") {
    $sQuery = "SELECT COUNT(*) AS total FROM user WHERE usertype='customer'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    $usersQuery = "SELECT u.user_id, u.user_name, u.user_email, u.firstname, u.lastname, u.phone, u.ban
                    FROM user u
                    WHERE u.usertype='customer'
                    ORDER BY u.user_name ASC 
                    LIMIT :perPage OFFSET :offset";
} else {
    $sQuery = "SELECT COUNT(*) AS total FROM user WHERE usertype='customer'";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    $usersQuery = "SELECT u.user_id, u.user_name, u.user_email, u.firstname, u.lastname, u.phone, u.ban
                    FROM user u
                    WHERE u.usertype='customer'
                    ORDER BY u.user_id DESC 
                    LIMIT :perPage OFFSET :offset";
}

$stmt = $conn->prepare($usersQuery);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['users' => $users, 'totalPages' => $totalPages]);

?>
