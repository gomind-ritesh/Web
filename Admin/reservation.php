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
    <title>Reservation | Red Lantern</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to fetch reservations based on the selected filter
            function fetchReservations(filter, page = 1) {
                $.ajax({
                    url: "fetch_filter_reservation.php",
                    type: "GET",
                    data: { filter: filter, page: page },
                    dataType: "json",
                    success: function(response) {
                      var tableBody = $("#recent-reservations-table-body"); // Update the table body based on filter
                        tableBody.empty(); // Clear existing rows

                        const { reservations, totalPages } = response;

                        if (reservations.length === 0) {
                    tableBody.append("<tr><td colspan='8'>No reservations found.</td></tr>");
                        } else {
                            $.each(reservations, function(index, reservation) {
                                var row = `<tr>
                                    <td>${reservation.reservation_id}</td>
                                    <td>${reservation.reservation_name}</td>
                                    <td>${reservation.reservation_phone}</td>
                                    <td>${reservation.reservation_people}</td>
                                    <td>${reservation.reservation_tables}</td>
                                    <td>${reservation.reservation_date}</td>
                                    <td>${reservation.reservation_time}</td>
                                    <td>${reservation.reservation_note}</td>
                                    <td>${reservation.user_id}</td> 
                                    <td style="color: ${getStatusColor(reservation.status)}">${reservation.status}</td>
                                    <td><input type="radio" name="rdo_reservation_id_${reservation.reservation_id}" value="active"></td>
                                    <td><input type="radio" name="rdo_reservation_id_${reservation.reservation_id}" value="completed"></td>
                                    <td><input type="radio" name="rdo_reservation_id_${reservation.reservation_id}" value="cancel"></td>
                                    <td><a href="#" class="one details-link" data-user-id="${reservation.user_id}">Details</a></td>                   
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
            fetchReservations($("#filterOptions").val(), page);
        });
    }


            fetchReservations("recent");

            const selectedFilter = localStorage.getItem("selectedFilter") || "recent";
            $("#filterOptions").val(selectedFilter); // Set the dropdown to the saved filter
            fetchReservations(selectedFilter); // Fetch orders with the selected filter

            $("#filterOptions").change(function() {
                var selectedFilter = $(this).val(); // Get selected filter option
                localStorage.setItem("selectedFilter", selectedFilter); // Save to localStorage
                fetchReservations(selectedFilter);
            });

            // Function to return color based on reservation status
            function getStatusColor(status) {
                if (status === "cancel") return "#FF0060";
                if (status === "completed") return "#1B9C85";
                if (status === "active") return "#F7D060";
                return "black";
            }
        });


            $(document).on('click', '.details-link', function (event) {
                event.preventDefault();
                let userId = $(this).data('user-id');

                $.ajax({
                    url: 'fetch_reservation_user_details.php',
                    type: 'GET',
                    data: { user_id: userId },
                    success: function (response) {
                        $('#userr-details').html(response);
                    },
                    error: function () {
                        alert('Error fetching user details.');
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

    /* .right-section .user-details table tbody td{
        height: 2.8rem;
        border-bottom: 1px solid var(--color-light);
        color: var(--color-dark-variant);
    }

     .right-section .user-details table tbody tr:last-child td{
        border: none;
    } */

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
  $reservation_id = "";
  $status="";
  foreach($_POST as $key => $value) {
    if (strpos($key, 'rdo_reservation_id_')>=0) {
       $reservation_id = substr($key, 19);
       #echo $reservation_id;
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
       $updateResult = update_status_reservation($conn, $status, $reservation_id);

    // if (!$updateResult) {
    //     $Msg = "ERROR: Record could not be saved!";
    //     echo "<h3 class='error'>$Msg</h3>";
    // } else {
    //     $Msg = "Record saved successfully!";
    //     echo "<h3>$Msg</h3>";
    //     // Optionally, redirect the user or clear the form here
    // }
       
    }//end if(strpos($key, 'chk_reservation_id_')>=0)
  }//end foreach
   	
  $conn==null;    

}//end if ($_SERVER["REQUEST_METHOD"] == "POST")


  
?>
  <div class="container">
  <?php 
   $activemenu = "reservation"; 
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

        <!-- Recent Reservation Table -->
        <div class="recent-orders">
        <h2>Filter Reservation</h2>

        <!-- Filter Dropdown -->
        <table><thead><tr><th><label for="filterOptions" style="font-size: 15px;">Choose Filter: </label>
        <select id="filterOptions" style="font-size: 15px; border: none; background: none; outline: none; color: var(--color-dark-variant);">
            <option value="recent" selected>Recent Reservations</option>
            <option value="descending">Reservation Name: Descending</option>
            <option value="ascending">Reservation Name: Ascending</option>
        </select></table></thead></tr></th></br></br>

          <h2>Reservations</h2>
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"  >

               <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th>R_ID</th>
                <th>Name</th>
                <th>Tel No.</th>
                <th>People</th>
                <th>Tables</th>
                <th>Date</th>
                <th>Time</th>
                <th>Note</th>
                <th>User ID</th>
                <th>Status</th>
                <th style="color: #F7D060">Active</th>
                <th style="color: #1B9C85">Completed</th>
                <th style="color: #FF0060">Cancel</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody id="recent-reservations-table-body">
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
      <div id="userr-details">

          </div>
                    
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