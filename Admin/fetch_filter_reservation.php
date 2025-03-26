<?php
require_once "includes/db_connect.php";
use Opis\JsonSchema\{
    Validator, ValidationResult,  Helper
};
use Opis\JsonSchema\Errors\{
    ErrorFormatter,
    ValidationError,
};

require '../vendor/autoload.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'recent'; // Default filter
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10; // Number of results per page
$offset = ($page - 1) * $perPage;

$reservations = [];
$totalPages = 1;

if ($filter == "recent") {
    // Count total reviews
    $sQuery = "SELECT COUNT(*) AS total FROM reservation";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch reviews ordered by latest review
    $reservationsQuery = "SELECT reservation_id, reservation_name, reservation_phone, reservation_people, 
                                 reservation_tables, reservation_date, reservation_time, reservation_note, `status`, user_id
                          FROM reservation
                          ORDER BY reservation_date DESC, reservation_time DESC
                          LIMIT :perPage OFFSET :offset";
} elseif ($filter == "ascending") {
    // Count total reviews
    $sQuery = "SELECT COUNT(*) AS total FROM reservation";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch good ratings first, then bad ratings
    $reservationsQuery = "SELECT reservation_id, reservation_name, reservation_phone, reservation_people, 
                                 reservation_tables, reservation_date, reservation_time, reservation_note, `status`, user_id
                          FROM reservation
                          ORDER BY reservation_name ASC
                          LIMIT :perPage OFFSET :offset";
} elseif ($filter == "descending") {
    // Count total reviews
    $sQuery = "SELECT COUNT(*) AS total FROM reservation";
    $stmt = $conn->prepare($sQuery);
    $stmt->execute();
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRows / $perPage);

    // Fetch bad ratings first, then good ratings
    $reservationsQuery = "SELECT reservation_id, reservation_name, reservation_phone, reservation_people, 
                                 reservation_tables, reservation_date, reservation_time, reservation_note, `status`, user_id
                          FROM reservation
                          ORDER BY reservation_name DESC
                          LIMIT :perPage OFFSET :offset";
} 

// Prepare and execute the final query
$stmt = $conn->prepare($reservationsQuery);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = json_encode((['reservations' => $reservations, 'totalPages' => $totalPages]), JSON_NUMERIC_CHECK);
		
$data1 = json_decode($data, false);

$loadschema = (file_get_contents(__DIR__ . '/schemaValidation/fetch_reservation_schema.json'));
$validator = new Validator();

/** @var ValidationResult $result */
$result = $validator->validate($data1, $loadschema);

if ($result->isValid()) {
    header('Content-Type: application/json');
    echo $data;
} else {
    $errorFormatter = new ErrorFormatter();
    $error = $errorFormatter->format($result->error());
    //$response->setStatusCode(400);
    echo json_encode([
        'result' => 'error',
        'message' => 'Invalid response format',
        'status' => 400,
        'errors' => $error
    ]);
}

// header('Content-Type: application/json');
// echo json_encode(['reservations' => $reservations, 'totalPages' => $totalPages]);

?>
