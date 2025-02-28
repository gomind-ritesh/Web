<?php
session_start();

if(!isset($_SESSION['username']) || (!isset($_SESSION['admin'])))
  { 
    echo "<h3 style=\"color:red\">Please log in first to access this page</h3>";
    echo '<p><a href="login.php">Back to the login page</a></p>';
    // header("Location: login.php?referer=index");
    // die();
    
  }//end if
  else
  {	  
    ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php 
    require_once "includes/metatags.php";
  ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="includes/style.css" />
    <title>Food Menu | Red Lantern</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to fetch orders based on the selected filter
            function fetchOrders(filter, page = 1) {
                $.ajax({
                    url: "fetch_orders.php",
                    type: "GET",
                    data: { filter: filter, page: page },
                    dataType: "json",
                    success: function(response) {
                      var tableBody = $("#recent-orders-table-body"); // Update the table body based on filter
                        tableBody.empty(); // Clear existing rows

                        const { orders, totalPages } = response;

                        if (orders.length === 0) {
                    tableBody.append("<tr><td colspan='8'>No orders found.</td></tr>");
                        } else {
                            $.each(orders, function(index, bill) {
                                var row = `<tr>
                                    <td>${bill.user_name}</td>
                                    <td>${bill.bill_id}</td>
                                    <td>${bill.bill_date}</td>
                                    <td>${bill.total_price}</td>
                                    <td style="color: ${getStatusColor(bill.status)}">${bill.status}</td>
                                    <td><input type="radio" name="rdo_bill_id_${bill.bill_id}" value="active"></td>
                                    <td><input type="radio" name="rdo_bill_id_${bill.bill_id}" value="completed"></td>
                                    <td><input type="radio" name="rdo_bill_id_${bill.bill_id}" value="cancel"></td>
                                    <td><a href="#" class="one details-link" data-bill-id="${bill.bill_id}">Details</a></td>                   
                                </tr>`;  
                                tableBody.append(row);
                            });
                        }
                        updatePagination(totalPages, page);

                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                    }
                });
            }

            // Function to update pagination links
        function updatePagination(totalPages, currentPage) {  
        var pagination = $("#pagination");
        pagination.empty();

        const maxVisiblePages = 5; // Maximum number of page links to show at once
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    // Ensure we show exactly `maxVisiblePages` if possible
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    // Add "Previous" button
    if (currentPage > 1) {
        pagination.append(`<a href="#" class="page-link prev" data-page="${currentPage - 1}">&laquo; Previous</a>`);
    }

    // Add first page and ellipsis if necessary
    if (startPage > 1) {
        pagination.append(`<a href="#" class="page-link" data-page="1">1</a>`);
        if (startPage > 2) {
            pagination.append(`<span class="ellipsis">...</span>`);
        }
    }


         // Add page links
    for (let i = startPage; i <= endPage; i++) {
        let pageLink = `<a href="#" class="page-link ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</a>`;
        pagination.append(pageLink);
    }

        if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            pagination.append(`<span class="ellipsis">...</span>`);
        }
        pagination.append(`<a href="#" class="page-link" data-page="${totalPages}">${totalPages}</a>`);
    }

    // Add "Next" button
    if (currentPage < totalPages) {
        pagination.append(`<a href="#" class="page-link next" data-page="${currentPage + 1}">Next &raquo;</a>`);
    }

        $(".page-link").click(function (event) {
            event.preventDefault();
            var page = $(this).data("page");
            fetchOrders($("#filterOptions").val(), page);
        });
    }


            // Default filter: Recent Orders
            fetchOrders("recent");
            // Retrieve the saved filter or default to "recent"

//             Retrieve the Filter on Page Load
// When the page loads, check if a filter is stored in localStorage and use it to fetch data:
            const selectedFilter = localStorage.getItem("selectedFilter") || "recent";
            $("#filterOptions").val(selectedFilter); // Set the dropdown to the saved filter
            fetchOrders(selectedFilter); // Fetch orders with the selected filter

            // Change event listener for filter selection
//             Save the Selected Filter in localStorage
// Update the filter dropdown change event to save the selected filter to localStorage:
            $("#filterOptions").change(function() {
                var selectedFilter = $(this).val(); // Get selected filter option
                localStorage.setItem("selectedFilter", selectedFilter); // Save to localStorage
                fetchOrders(selectedFilter);
            });

            // Function to return color based on order status
            function getStatusColor(status) {
                if (status === "cancel") return "#FF0060";
                if (status === "completed") return "#1B9C85";
                if (status === "active") return "#F7D060";
                return "black";
            }
        });


            $(document).on('click', '.details-link', function (event) {
                event.preventDefault();
                let billId = $(this).data('bill-id');

                $.ajax({
                    url: 'fetch_bill_details.php',
                    type: 'GET',
                    data: { bill_id: billId },
                    success: function (response) {
                        $('#bill-details').html(response);
                    },
                    error: function () {
                        alert('Error fetching bill details.');
                    }
                });
            });
    
    </script>

    <style>
  .error{
    color: red;
  }
  input[type=submit], input[type=reset] {
    background: #0066A2;
    color: white;
    border-style: outset;
    border-color: #0066A2;
    height: 50px;
    width: 100px;
    border-radius: 40px;
    font: arial,sans-serif;
    text-shadow: none;
    }
    input[type=submit]:hover, input[type=reset]:hover {
      background: #016ABC;
      color: #fff;
      border: 1px solid #eee;
      border-radius: 40px;
      box-shadow: 5px 5px 5px #eee;
      text-shadow: none;
    }
    /* for link "details" */
    a.one:link {color:#6C9BCF;
    text-decoration: none;}
    a.one:visited {color:#6C9BCF;
        text-decoration: none;}
    a.one:hover {color:blue;
        text-decoration: none;}

    .right-section .user-details{
    background-color: var(--color-white);
    width: 100%;
    padding: var(--card-padding);
    text-align: center;
    box-shadow: var(--box-shadow);
    border-radius: var(--card-border-radius);
    transition: all 0.3s ease;
    margin-top: 4rem;
    }

    .right-section .user-details:hover{
        box-shadow: none;
    }

    .right-section .user-details h2{
        margin-bottom: 0.8rem;
    }

    .right-section .user-details{
        overflow-y: scroll; 
        max-height: 45%;   
    }

    .right-section .user-details table tbody td{
        height: 2.8rem;
        border-bottom: 1px solid var(--color-light);
        color: var(--color-dark-variant);
    }

     .right-section .user-details table tbody tr:last-child td{
        border: none;
    }

    #pagination {
    display: flex; /* Use flexbox for horizontal alignment */
    justify-content: center; /* Center the pagination links */
    gap: 8px; /* Add spacing between the links */
    margin-top: 20px; /* Optional: Add some spacing from the top */
}

#pagination a {
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd; /* Optional: Add a border for better visibility */
    border-radius: 4px; /* Optional: Rounded corners */
    color: var(--color-dark-variant);
}

#pagination a.active {
    background-color: #1B9C85; /* Highlight active link */
    color: white;
    border: 1px solid #1B9C85;
}

#pagination a:hover {
    background-color: #ddd; /* Change background on hover */
    color: black;
}

/* Style for "Previous" and "Next" buttons */
#pagination a.prev,
#pagination a.next {
    font-weight: bold;
}

/* Style for ellipsis */
#pagination .ellipsis {
    padding: 8px 16px;
    color: #666;
}
  </style>
  </head>

  <body>

  <?php  
// define variables and set to empty string values
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
  require_once "includes/db_connect.php";
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $bill_id = "";
  $status="";
  foreach($_POST as $key => $value) {
    if (strpos($key, 'rdo_bill_id_')>=0) {
       $bill_id = substr($key, 12);
       #echo $bill_id;
       #echo $value;
       
       if($value == "cancel")
       {
       	$status = "cancel";
       }
       if($value == "completed")
       {
       	$status = "completed";
       }
       if($value == "active")
       {
       	$status = "active";
       }
       $Msg = "";
       $updateResult = update_status_bill($conn, $status, $bill_id);

    if (!$updateResult) {
        $Msg = "ERROR: Record could not be saved!";
        echo "<h3 class='error'>$Msg</h3>";
    } else {
        $Msg = "Record saved successfully!";
        echo "<h3>$Msg</h3>";
        // Optionally, redirect the user or clear the form here
    }
       
    }//end if(strpos($key, 'chk_bill_id_')>=0)
  }//end foreach
   	
  $conn==null;    

}//end if ($_SERVER["REQUEST_METHOD"] == "POST")


  
?>
  <div class="container">
  <?php 
   $activemenu = "foodmenu"; 
   include_once('includes/menu.php');
  ?>

     <!-- Main Content -->
     <main>
        <h1>Analytics</h1>
        <!-- Analyses -->
        <div class="analyse">
          <div class="sales">
            <div class="status">
              <div class="info">
                <h3>Total Sales</h3>
                <h1>$65,024</h1>
              </div>
              <div class="progresss">
                <svg>
                  <circle cx="38" cy="38" r="36"></circle>
                </svg>
                <div class="percentage">
                  <p>+81%</p>
                </div>
              </div>
            </div>
          </div>
          <div class="visits">
            <div class="status">
              <div class="info">
                <h3>Site Visit</h3>
                <h1>24,981</h1>
              </div>
              <div class="progresss">
                <svg>
                  <circle cx="38" cy="38" r="36"></circle>
                </svg>
                <div class="percentage">
                  <p>-48%</p>
                </div>
              </div>
            </div>
          </div>
          <div class="searches">
            <div class="status">
              <div class="info">
                <h3>Searches</h3>
                <h1>14,147</h1>
              </div>
              <div class="progresss">
                <svg>
                  <circle cx="38" cy="38" r="36"></circle>
                </svg>
                <div class="percentage">
                  <p>+21%</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End of Analyses -->

        <!-- Recent Orders Table -->
        <div class="recent-orders">
        <h2>Filter Orders</h2>

        <!-- Filter Dropdown -->
        <table><thead><tr><th><label for="filterOptions" style="font-size: 15px;">Choose Filter: </label>
        <select id="filterOptions" style="font-size: 15px; border: none; background: none; outline: none; color: var(--color-dark-variant);">
            <option value="recent" selected>Recent Orders</option>
            <option value="highest">Highest Price</option>
            <option value="lowest">Lowest Price</option>
        </select></table></thead></tr></th></br></br>

          <h2>Reviews</h2>
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"  >

               <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th>Review ID</th>
                <th>Comment</th>
                <th>Rating</th>
                <th>Total Price</th>
                <th>Status</th>
                <th style="color: #F7D060">Active</th>
                <th style="color: #1B9C85">Completed</th>
                <th style="color: #FF0060">Cancel</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody id="recent-orders-table-body">
            <!-- This part will be populated dynamically via JavaScript -->
        </tbody>
    </table>
 
          
       

            <input type="reset" value="Reset">
            <input type="submit" value="Submit">
        </form>
        </div>
        <div id="pagination"></div>
        <!-- End of Recent Orders -->
      </main>
      <!-- End of Main Content -->

      <!-- Right Section -->
     <div class="right-section"> 
        <div class="nav">
          <button id="menu-btn">
            <span class="material-icons-sharp"> menu </span>
          </button>
          <div class="dark-mode">
            <span class="material-icons-sharp active"> light_mode </span>
            <span class="material-icons-sharp"> dark_mode </span>
          </div>

          <div class="profile">
            <div class="info">
              <p>Hey, <b><?=$_SESSION['username']?></b></p>
              <small class="text-muted">Admin</small>
            </div>
            <div class="profile-photo">
              <span class="material-icons-sharp"> person </span>
            </div>
          </div>
        </div>
        <!-- End of Nav -->

        <div class="user-profile">
          <div class="logo">
            <img src="includes/images/logo.jpeg" />
            <h2>Red Lantern</h2>
            <p>Restaurant Website</p>
          </div>
        </div>
      
      <!-- Details pertaining to recent orders -->
      <div class="user-details">
      <h2>User Details</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Bill ID</th>
                            <th>Food Name</th>
                            <th>Food Quantity</th>
                            <th>Food Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="bill-details"></tbody>
                </table>        
            </div>  
      </div>
  </div>
  <!-- End of Right Section -->

    <script src="index.js"></script>

  </body>
</html>

<?php
  }//end else
  
  ?>