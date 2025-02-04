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

          <a href="dashboard.php"
          <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="dashboard")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> dashboard </span>
            <h3>Dashboard</h3>
          </a>

          <a href="index.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="index")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> insights </span>
            <h3>Analytics</h3>
          </a>

          <a href="sale.php" <?php 
	  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	  if ($activemenu=="sale")	
		echo "class=\"active\"";
	  ?>
	>
            <span class="material-icons-sharp"> inventory </span>
            <h3>Sale List</h3>
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