<?php 
    require_once "includes/metatags.php";
  ?>
<!-- Sidebar Section -->
<div class="container">
<aside>
        <div class="toggle">
          <div class="logo">
          <img src="includes/images/logo.jpeg" />
            <h2>Red <span class="danger">Lantern</span></h2>
          </div>
          <div class="close" id="close-btn">
            <span class="material-icons-sharp"> close </span>
          </div>
        </div>

        <div class="sidebar">

          <a href="reviews.php"
          <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="reviews")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> reviews </span>
            <h3>Reviews</h3>
          </a>

          <a href="index.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="index")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> home </span>
            <h3>Home</h3>
          </a>

          <a href="foodmenu.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="foodmenu")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> menu_book </span>
            <h3>Food Menu</h3>
          </a>

          <a href="reservation.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="reservation")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> event_seat </span>
            <h3>Reservation</h3>
          </a>

          <a href="deluser.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="deluser")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> group </span>
            <h3>Users</h3>
          </a>

          <a href="register.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="newlogin")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> add </span>
            <h3>New Login</h3>
          </a>

          <a href="logout.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="logout")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> logout </span>
            <h3>Logout</h3>
          </a>

        </div>
      </aside>
</div>
      <!-- End of Sidebar Section -->