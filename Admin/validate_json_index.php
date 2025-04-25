<?php
// validate_json.php
require_once '../vendor/autoload.php';
header('Content-Type: application/json');

use Opis\JsonSchema\Validator;
use Opis\JsonSchema\ValidationResult;
use Opis\JsonSchema\Errors\ErrorFormatter;

// Function to validate the incoming JSON data against the schema
function validateJsonData($jsonData) {
    // Load the schema
    $schema = file_get_contents(__DIR__ . '/schemaValidation/validate_index.json');

    // Create validator
    $validator = new Validator();

    // Decode the JSON data before validation
    $data = json_decode($jsonData);

    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return ['valid' => false, 'errors' => ['message' => 'Invalid JSON string']];
    }

    // Validate
    $result = $validator->validate($data, $schema);

    if ($result->isValid()) {
        return ['valid' => true, 'errors' => null];
    } else {
        $errorFormatter = new ErrorFormatter();
        $errors = $errorFormatter->format($result->error());

        return ['valid' => false, 'errors' => $errors];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['json_data'])) {
    $jsonData = $_POST['json_data'];

    // Validate the complete JSON structure
    $validationResult = validateJsonData($jsonData);

    if ($validationResult['valid']) {
        echo json_encode([
            'result' => 'success',
            'message' => 'JSON validation passed'
        ]);
    } else {
        echo json_encode([
            'result' => 'error',
            'message' => 'Invalid JSON format',
            'status' => 400,
            'errors' => $validationResult['errors']
        ]);
    }
} else {
    echo json_encode([
        'result' => 'error',
        'message' => 'Invalid request method or missing json_data parameter',
        'status' => 400
    ]);
}
?>