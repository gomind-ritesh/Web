<?php
require_once "includes/db_connect.php";

if (isset($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];

    // Fetch bill details using the function defined in db_connect.php
    $nowbillResult = bill_details($conn, $bill_id);

    if ($nowbillResult) {
        foreach ($nowbillResult as $bill_details) {
            echo "<tr>";
            echo "<td>" . $bill_details['bill_id'] . "</td>";
            echo "<td>" . $bill_details['food_name'] . "</td>";
            echo "<td>" . $bill_details['item_qty'] . "</td>";
            echo "<td>" . $bill_details['total_qty_price'] . "</td>";
            echo "</tr>";
        }//end foreach
    } // end if 
    else {
        echo "<tr><td colspan='4'>No details found for this bill.</td></tr>";
        // else{echo "<td>No details found for this bill.</td>";}
    }
} else {
    echo "Invalid request.";
}
?>

