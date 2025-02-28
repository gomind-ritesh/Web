<?php
require_once "includes/db_connect.php";

if (isset($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];

    // Fetch bill details using the function defined in db_connect.php
    $nowreviewbillResult = review_bill_details($conn, $bill_id);

    if ($nowreviewbillResult && count($nowreviewbillResult) > 0) {
        // Extract bill_id from the first row
        $firstRow = $nowreviewbillResult[0];
        $bill_id_display = htmlspecialchars($firstRow['bill_id']);

        echo "<h3>Bill ID: $bill_id_display</h3>";
        echo "<tr>";
        echo "<th style='padding: 12px; width: 40%; word-wrap: break-word;'>Food Name</th>";
        echo "<th style='padding: 12px; width: 20%;'>Food Quantity</th>";
        echo "<th>Food Price</th>";
        echo "</tr>";

        foreach ($nowreviewbillResult as $review_bill_details) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($review_bill_details['food_name']) . "</td>";
            echo "<td>" . htmlspecialchars($review_bill_details['item_qty']) . "</td>";
            echo "<td>" . htmlspecialchars($review_bill_details['total_qty_price']) . "</td>";
            echo "</tr>";
        } // end foreach
    } else {
        echo "<tr><td colspan='4'>No details found for this review bill.</td></tr>";
    }
} else {
    echo "<tr><td colspan='4'>Invalid request.</td></tr>";
}
?>
