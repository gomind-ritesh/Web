<?php
require_once "includes/db_connect.php";

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch bill details using the function defined in db_connect.php
    $nowuserbillResult = user_bill_details($conn, $user_id);

    if ($nowuserbillResult && count($nowuserbillResult) > 0) {
        // Extract user_name from the first row
        $firstRow = $nowuserbillResult[0];
        $user_name_display = htmlspecialchars($firstRow['user_name']);

        echo "<h3 style='font-weight: bold;'>User Name: $user_name_display</h3></br>";
        echo "<tr>";
        echo "<th>Bill ID</th>";
        echo "<th>Bill Date</th>";
        echo "<th>Status</th>";
        echo "<th>Total Price</th>";
        echo "</tr>";

        foreach ($nowuserbillResult as $user_bill_details) {
            echo "<tr>";
            echo "<td>" . $user_bill_details['bill_id'] . "</td>";
            echo "<td>" . $user_bill_details['bill_date'] . "</td>";
            echo "<td>" . $user_bill_details['status'] . "</td>";
            echo "<td>" . $user_bill_details['total_price'] . "</td>";
            echo "</tr>";
        }//end foreach
    } // end if 
    else {
        echo "<tr><td colspan='4'>No details found for this user bill.</td></tr>";
        // else{echo "<td>No details found for this user bill.</td>";}
    }
} else {
    echo "Invalid request.";
}
?>

