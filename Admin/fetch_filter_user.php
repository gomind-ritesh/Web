<?php
require_once "includes/db_connect.php";

use Opis\JsonSchema\{
    Validator, ValidationResult,Helper
};
use Opis\JsonSchema\Errors\{
    ErrorFormatter,
    ValidationError,
};

require '../vendor/autoload.php';

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

$data = json_encode((['users' => $users, 'totalPages' => $totalPages]), JSON_NUMERIC_CHECK);
		
$data1 = json_decode($data, false);

$loadschema = (file_get_contents(__DIR__ . '/schemaValidation/fetch_users_schema.json'));
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
// echo json_encode(['users' => $users, 'totalPages' => $totalPages]);

?>
