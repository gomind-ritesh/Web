<?php
require_once "includes/db_connect.php";

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'recent'; // Default filter
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of results per page
$offset = ($page - 1) * $perPage;

$reviews = [];
$totalPages = 1;

if ($filter == "recent") {
    // Count total reviews
    $sQuery = "SELECT COUNT(DISTINCT r.review_id) AS total
               FROM reviews r 
               JOIN user u ON r.user_id = u.user_id";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch reviews ordered by latest review
    $reviewsQuery = "SELECT r.review_id, r.review_comment, r.review_rating, u.user_name, b.bill_date, b.bill_id
                   FROM reviews r
                   JOIN user u ON r.user_id = u.user_id
                   JOIN bill b ON r.bill_id = b.bill_id
                   ORDER BY r.review_id DESC
                   LIMIT :perPage OFFSET :offset";
} elseif ($filter == "good_rating") {
    // Count total reviews
    $sQuery = "SELECT COUNT(DISTINCT r.review_id) AS total
               FROM reviews r 
               JOIN user u ON r.user_id = u.user_id";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch good ratings first, then bad ratings
    $reviewsQuery = "SELECT r.review_id, r.review_comment, r.review_rating, u.user_name, b.bill_date, b.bill_id
                   FROM reviews r
                   JOIN user u ON r.user_id = u.user_id
                   JOIN bill b ON r.bill_id = b.bill_id
                   ORDER BY r.review_rating DESC
                   LIMIT :perPage OFFSET :offset";
} elseif ($filter == "bad_rating") {
    // Count total reviews
    $sQuery = "SELECT COUNT(DISTINCT r.review_id) AS total
               FROM reviews r 
               JOIN user u ON r.user_id = u.user_id";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch bad ratings first, then good ratings
    $reviewsQuery = "SELECT r.review_id, r.review_comment, r.review_rating, u.user_name, b.bill_date, b.bill_id
                   FROM reviews r
                   JOIN user u ON r.user_id = u.user_id
                   JOIN bill b ON r.bill_id = b.bill_id
                   ORDER BY r.review_rating ASC
                   LIMIT :perPage OFFSET :offset";
} 

// Prepare and execute the final query
$stmt = $conn->prepare($reviewsQuery);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['reviews' => $reviews, 'totalPages' => $totalPages]);

?>
