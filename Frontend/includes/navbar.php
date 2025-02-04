<html>
 <nav>
   <div class="container" id="top" role="banner">
   
    <a href="home.php"
         
      <?php
	    //In the codes below, we escape the inner double quotes, since they are to be included in the string"
	       if ($activemenu=="home")
		     echo "class=\"active\"";
	     ?>
	    >Home</a>
	   
	<?php
		if(!isset($_SESSION['username']))
		{
	?>
			
	
			<a href="login.php"
         
			<?php
			  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
				 if ($activemenu=="login")
				   echo "class=\"active\"";
			   ?>
			  >Login</a>
	  
		  <a href="register.php"
			   
			<?php
			  //In the codes below, we escape the inner double quotes, since they are to be included in the string"
				 if ($activemenu=="register")
				   echo "class=\"active\"";
			   ?>
		
		    >Register</a>

			


    <?php
		}
	?>

		<?php
				if(isset($_SESSION['username']))
				{
			?>
				
			<a href="takeout.php"
				
				<?php
				//In the codes below, we escape the inner double quotes, since they are to be included in the string"
					if ($activemenu=="takeout")
						echo "class=\"active\"";
					?>
				>Takeout</a>

			<a href="reservation.php"
			
			<?php
			//In the codes below, we escape the inner double quotes, since they are to be included in the string"
				if ($activemenu=="reservation")
					echo "class=\"active\"";
				?>
			>Reservation</a>

			<a href="review.php"
			
			<?php
			//In the codes below, we escape the inner double quotes, since they are to be included in the string"
				if ($activemenu=="review")
					echo "class=\"active\"";
				?>
			>Review</a>


			<a href="logout.php"
				
				<?php
				//In the codes below, we escape the inner double quotes, since they are to be included in the string"
					if ($activemenu=="logout")
						echo "class=\"active\"";
					?>
				>Logout</a>

    <?php
		}
    ?>
		
	   


</div>
	</nav>
</html>