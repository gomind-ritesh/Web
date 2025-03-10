<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
            // Function to fetch food details based on the selected filter
            function fetchFood(filter, page = 1) {
                $.ajax({
                    url: "fetch_filter_food.php",
                    type: "GET",
                    data: { filter: filter, page: page },
                    dataType: "json",
                    success: function(response) {
                      var tableBody = $("#recent-food-table-body"); // Update the table body based on filter
                        tableBody.empty(); // Clear existing rows

                        const { foodItems, totalPages } = response;

                        if (foodItems.length === 0) {
                    tableBody.append("<tr><td colspan='8'>No food found.</td></tr>");
                        } else {
                            $.each(foodItems, function(index, food) {
                              let available_string = food.available === 1 ? "Yes" : "No"; // Simplified conditional logic
                                var row = `<tr>
                                    <td>${food.food_id}</td>
                                    <td>${food.food_name}</td>
                                    <td>${food.food_price}</td>
                                    <td>${food.food_discount}</td>
                                    <td>${food.food_description}</td>
                                    <td>${food.food_category}</td>
                                    <td>${food.food_type}</td>
                                    <td><a href="#" class="one details-link" data-food-id="${food.food_id}">${food.food_source}</a></td> 
                                    <td style="color: ${getStatusColor(available_string)}">${available_string}</td>        
                                    <td><a href="#" class="two delete-link" data-delete-id="${food.food_id}">Delete</a></td>  
                                    <td><a href="#" class="three edit-link" data-edit-id="${food.food_id}">Edit</a></td>     
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
            fetchFood($("#filterOptions").val(), page);
        });
    }


            // Default filter: Recent food
            fetchFood("recent");
            // Retrieve the saved filter or default to "recent"

//             Retrieve the Filter on Page Load
// When the page loads, check if a filter is stored in localStorage and use it to fetch data:
            const selectedFilter = localStorage.getItem("selectedFilter") || "recent";
            $("#filterOptions").val(selectedFilter); // Set the dropdown to the saved filter
            fetchFood(selectedFilter); // Fetch orders with the selected filter

            // Change event listener for filter selection
//             Save the Selected Filter in localStorage
// Update the filter dropdown change event to save the selected filter to localStorage:
            $("#filterOptions").change(function() {
                var selectedFilter = $(this).val(); // Get selected filter option
                localStorage.setItem("selectedFilter", selectedFilter); // Save to localStorage
                fetchFood(selectedFilter);
            });

            // Function to return color based on food available
            function getStatusColor(available) {
                if (available === "No") return "#FF0060";
                if (available === "Yes") return "#1B9C85";
                return "black";
            }
        });


            $(document).on('click', '.details-link', function (event) {
                event.preventDefault();
                let foodId = $(this).data('food-id');

                $.ajax({
                    url: 'fetch_food_image.php',
                    type: 'GET',
                    data: { food_id: foodId },
                    success: function (response) {
                        $('#food-details').html(response);
                    },
                    error: function () {
                        alert('Error fetching food image.');
                    }
                });
            });

            $(document).on("click", ".delete-link", function (event) {
    event.preventDefault();

    // Get the food ID from the delete button's data attribute
    let foodId = $(this).data("delete-id");

    // Ask for confirmation before deleting
    if (confirm("Are you sure you want to delete this food item?")) {

        $.ajax({
            url: "delete_food.php",
            type: "POST",
            data: { food_id: foodId },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert(response.message);

                    //   // Dynamically remove the row from the table
                    //   $("#recent-food-table-body").find(`tr td:contains(${foodId})`).closest("tr").remove();
                    // // Refresh the food list after successful deletion
                    // let selectedFilter = $("#filterOptions").val();  // Retrieve the selected value
                    // console.log("Selected Filter:", selectedFilter);
                    // fetchFood(selectedFilter);  // Fetch data based on the selected filter
                } else {
                    alert(response.message); // Show the error message
                }
                window.location.reload();
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("An error occurred while attempting to delete the food item.");
            }
        });
    }
});

$(document).on("click", ".edit-link", function (event) {
    event.preventDefault();

    // Get the food ID from the edit button's data attribute
    let foodId = $(this).data("edit-id");

    $.ajax({
        url: "check_edit_food.php", // PHP script to handle the check
        type: "POST",
        data: { food_id: foodId },
        dataType: "json",
        success: function (response) {
          if (response.related === true) {
        window.location.href = `add_edit_food_form.php?food_id=${foodId}`;
    } else if (response.related === false) {
        window.location.href = `edit_food_form.php?food_id=${foodId}`;
    } else {
        alert("Unexpected response format. Please check the server response.");
    }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
});



    
    </script>

    <style>
  .error{
    color: red;
  }
  input[type=submit], input[type=reset], input[type=button] {
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
    input[type=submit]:hover, input[type=reset]:hover, input[type=button]:hover {
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

        /* for delete "details" */
    a.two:link {color:#FF0060;
    text-decoration: none;}
    a.two:visited {color:#FF0060;
        text-decoration: none;}
    a.two:hover {color:#96033b;
        text-decoration: none;}

        /* for edit "details" */
    a.three:link {color:#F7D060;
    text-decoration: none;}
    a.three:visited {color:#F7D060;
        text-decoration: none;}
    a.three:hover {color:#ab8b2e;
        text-decoration: none;}

    .right-section .food-image{
    background-color: var(--color-white);
    width: 100%;
    padding: var(--card-padding);
    text-align: center;
    box-shadow: var(--box-shadow);
    border-radius: var(--card-border-radius);
    transition: all 0.3s ease;
    margin-top: 4rem;
    }

    .right-section .food-image:hover{
        box-shadow: none;
    }

    .right-section .food-image h2{
        margin-bottom: 0.8rem;
    }

    .right-section .food-image{
        overflow-y: scroll; 
        max-height: 45%;   
    }

    /* .right-section .food-image table tbody td{
        height: 2.8rem;
        border-bottom: 1px solid var(--color-light);
        color: var(--color-dark-variant);
    }

     .right-section .food-image table tbody tr:last-child td{
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

        <!-- Recent Food Table -->
        <div class="recent-orders">
        <h2>Filter Food</h2>

        <!-- Filter Dropdown -->
        <table><thead><tr><th><label for="filterOptions" style="font-size: 15px;">Choose Filter: </label>
        <select id="filterOptions" style="font-size: 15px; border: none; background: none; outline: none; color: var(--color-dark-variant);">
            <option value="recent" selected>Recent Food Creation</option>
            <option value="appetizer-nonveg">Appetizer, Non-Veg</option>
            <option value="appetizer-veg">Appetizer, Veg</option>
            <option value="maincourse-veg">Main Course, Veg</option>
            <option value="maincourse-nonveg">main Course, Non-veg</option>
            <option value="dessert">Dessert, Veg</option>
        </select></table></thead></tr></th></br></br>

          <h2>Food</h2>
          <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"  >

               <!-- Orders Table -->
    <table>
        <thead>
            <tr>
                <th>Food ID</th>
                <th>Name</th>
                <th style='padding: 12px; width: 5%;'>Price </th>
                <th>Discount</th>
                <th>Description</th>
                <th>Category</th>
                <th>Type</th>
                <th>Source</th>
                <th>Available</th>
                <th style='padding: 12px; width: 5%;'>Delete</th>
                <th style='padding: 12px; width: 5%;'>Edit</th>
            </tr>
        </thead>
        <tbody id="recent-food-table-body">
            <!-- This part will be populated dynamically via JavaScript -->
        </tbody>
    </table>
 
          
       

            <!-- <input type="reset" value="Reset">
            <input type="submit" value="Submit"> -->
        </form>
        </div>
        <div id="pagination"></div>
        <a href="add_food_form.php">
    <input type="button" value="Add">
   </a>
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
      <div class="food-image">
      <h2>Food Image</h2>
      <div id="food-details">

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