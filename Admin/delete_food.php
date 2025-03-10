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
                // Food item exists in bill_food_details, cannot delete
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Cannot delete this food item. It is associated with existing bills which are used for analytics.'
                ]);
            } else {
                // Delete the food from the food table
                $deleteQuery = "DELETE FROM food WHERE food_id = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->execute([$food_id]);

                if ($stmt->rowCount() > 0) {
                    // Successfully deleted
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Food item successfully deleted.'
                    ]);
                } else {
                    // Food item not found in the food table
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
