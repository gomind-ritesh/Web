<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    echo json_encode(['status' => 'error', 'message' => 'Access denied. Please log in as admin.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/db_connect.php'; // Include your database connection file

    if (isset($_POST['food_id'])) {
        $food_id = intval($_POST['food_id']); // Sanitize input

        try {
            $conn->beginTransaction();

            // Check if the food_id exists in bill_food_details
            $checkQuery = "SELECT COUNT(*) as count FROM bill_food_details WHERE food_id = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->execute([$food_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                // Food item exists in bill_food_details, set availability to 0
                $updateQuery = "UPDATE food SET available = 0 WHERE food_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->execute([$food_id]);

                if ($stmt->rowCount() > 0) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Food item availability set to 0 successfully due to association with bills.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Failed to update availability. Please try again.'
                    ]);
                }
            } else {
                // Food item is not associated with bill_food_details, proceed with deletion
                $deleteQuery = "DELETE FROM food WHERE food_id = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->execute([$food_id]);

                if ($stmt->rowCount() > 0) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Food item successfully deleted.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Food item not found or already deleted.'
                    ]);
                }
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'An error occurred while processing the request: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Food ID is missing.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
