<?php
//flush();
// Start the session
//The session_start() function must be the very first thing in your document. Before any HTML tags.
session_start();

// define variables and set to empty string values
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


// define variables and set to empty string values

$usernameErr = $passwordErr = $Msg = "";
$username = $userpassword =  "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["txt_username"])) {
    $usernameErr = "User Name is required";
  } else {
    $username = test_input($_POST["txt_username"]);
  }//end else
  if (empty($_POST["txt_password"])) {
    $passwordErr = "Password is required";
  } else {
    $userpassword= test_input($_POST["txt_password"]);
   
  }//end else
  
  if($usernameErr == "" && $passwordErr == "" )
  {
    //We hashed passwords using
    //$hashed_password = password_hash($password,PASSWORD_DEFAULT);
  	//References http://php.net/manual/en/function.password-verify.php

  	require_once "includes/db_connect.php";
  	
    $userResults = retrieve_login($conn,$username);

    if($userResults )//the user exists
    {
    	$hashed_password = $userResults['user_pwd'];
      if($userResults["ban"] == 0){
    	if(password_verify($userpassword,$hashed_password))
    	{
        echo 'password is valid';
    		$_SESSION['username'] = $username;
    		#echo $_SESSION['username'];
    		header("Location: home.php?referer=login");
    	}
    	else
    	{
    		$Msg = "Password ERROR: Your credentials seem to be wrong. Try again or make sure you are a registered user!";
    	}
    	
    }
    else
    {
      $Msg = "User ERROR: This account has been banned";
    }
  }
    else
    {
       $Msg = "User name ERROR: Your credentials seem to be wrong. Try again or make sure you are a registered user!";
    	
    }
  }//end if
  
 }//end else 
  

?>


<html>
 <head>
   <title>Login</title>
   
<style>
  
  </style>
  <link rel="stylesheet" href="css/mystyle.css">
  <link rel="stylesheet" href="css/uregistration.css">
  
 </head>

 <?php 
   $activemenu = "login";
   include('includes/navbar.php');
  ?>
 <body>
  <!--margin-left:15%;padding:1px 16px; Reference https://www.w3schools.com/css/css_navbar.asp-->
  
  <?php
  if(isset($_SESSION['username']))
  { 
    // echo "<h3 style=\"color:red\">You are already logged in</h3>";
    // echo '<p><a href="index.php">Index</a></p>';
    header("Location: home.php?referer=login");
  }//end if
  else
  {
  ?>
  
  <div class = "back" >
 
  <form class="loginbox" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"  >
      <h3 class="title">Log In</h3>
        
        <span  style ="color:red"> <?php echo $Msg;?></span><br/>
        <input class= "input" type="text" name="txt_username" placeholder="Username"/>
	      <span  style ="color:red"> <?php echo $usernameErr;?></span><br/>
	
	      <input class= "input" type="password" name="txt_password" placeholder="Password"/>
	      <span style ="color:red"> <?php echo $passwordErr;?></span><br/>
	      <input class="button" type="submit"></input>
        <!--
        <a href="register.php">
          <button class="button">Register</button>
        </a>
  -->
  </form>
  <?php
  }//end else
  
  ?>
  
  </div>
 </body>
 <?php 
   $activemenu = "login";
   include('includes/footer.php');
  ?>
</html>
<html>