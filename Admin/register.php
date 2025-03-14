<?php
// Start the session
//The session_start() function must be the very first thing in your document. Before any HTML tags.
session_start();



  ?>

<!DOCTYPE html>
<html lang="en">
 <head>
 <?php 
    require_once "includes/metatags.php";
  ?>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
   
<style>
  .error{
    color: red;
  }
  </style>
  <link rel="stylesheet" href="includes/uregistration.css">
  <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp"
      rel="stylesheet"
    />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
  $(document).ready(function(){
     // When the user types in the username field, send an AJAX request to check if it's available.
     //This ensures that the script runs only after the document (webpage) has fully loaded.
     //It prevents errors caused by trying to interact with elements before they exist.
    //->> replaced  $("#txt_username").keyup(function(){
      $("#txt_username").keyup(function(){
      //This event fires every time the user releases a key inside the #txt_username input field.
      //Meaning, whenever the user types, it triggers the function.
       var username = $(this).val();
       //$(this) refers to the input field (#txt_username).
       //.val() gets the current value that the user has typed.
       if(username.length > 0) {
        //Ensures that an AJAX request is only sent if the user has typed something.
        //This prevents unnecessary requests when the field is empty.
         $.ajax({
           url: "includes/check_username.php", // The PHP file that will process the request
           type: "GET", // Using GET method to send data
           data: { username: username }, // Sending the username input
           success: function(response) { // When the request is successful
             // Update the span with the returned hint message.
             $("#usernameHint").html(response); // Update the span with the response
           }
         });
       } else {
         $("#usernameHint").html("");
         //If the input field is empty, it clears the #usernameHint message.
         //This avoids showing old messages when the user deletes everything.
       }
     });
   });
  </script>

 </head>

 <?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


// define error variables and set to empty values
$usernameErr = $passwordErr = $emailErr =  "";
$username = $password = $email =  "";


if ($_SERVER["REQUEST_METHOD"] == "POST") { //check if the page is being invoked after form data has been submitted

  //username
    if (empty($_POST["txt_username"])) {//check if the field is empty
      $usernameErr = "Admin Name is required";
    } 
    else
    {
      $username = test_input($_POST["txt_username"]);//call the test_input function on $_POST["txt_name"]
      
      if (!preg_match("/^[a-zA-Z ]+$/",$username)) 
      { //Use a regular expression to validate the name field
        $usernameErr = "Only letters and white space allowed";
      }else
        {
          require_once "includes/db_connect.php";
          if(exist_username( $conn,$username))
              {
                $usernameErr = "Name already exists";
              }
        }
    }//end else

  //email
    if (empty($_POST["txt_email"]))
    { //check if the field is empty
      $emailErr = "Admin Email is required";
    } 
    else
    {
      $email = test_input($_POST["txt_email"]);
      
      if (!filter_var($email, FILTER_VALIDATE_EMAIL))  //Invoke the inbuilt function filter_var for email
      {
        $emailErr = "Admin Email not valid";
      }else
        {
          require_once "includes/db_connect.php";
          if(exist_email( $conn,$email))
              {
                $emailErr = "Email already exists";
              }
        }
    }//end else

    //password
    if (empty($_POST["txt_password"])) {//check if the field is empty
      $passwordErr = "Admin Password is required";
    } 
    else 
    {
      $password = test_input($_POST["txt_password"]);
      
      if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,16}$/",$password)) 
      { //Use a regular expression to validate the password field
        $passwordErr = "Admin Password must contain one digit from 1 to 9, one lowercase letter, one uppercase letter, one special character, no space, and it must be 8-16 characters lnog.";
      }
      
    }//end else
  
    if($usernameErr == ""  && $passwordErr == "" && $emailErr ==  "")
    {

      $Msg = "";
      require_once "includes/db_connect.php";
    $addResult = insert_data_register_admin($conn, $username, $password, $email);

    if (!$addResult) {
        $Msg = "ERROR: Record could not be saved!";
        echo "<h3 class='error'>$Msg</h3>";
    } else {
        $Msg = "Record saved successfully!";
        echo "<h3>$Msg</h3>";
        // Optionally, redirect the user or clear the form here
    }

    }//end ifif($usernameErr == ""  && $passwordErr == "" && $emailErr ==  "")
}//end if ($_SERVER["REQUEST_METHOD"] == "POST")
  

?>

<?php
if(!( $_SERVER["REQUEST_METHOD"] == "POST" &&  $usernameErr == "" && $passwordErr == "" && $emailErr ==  ""))
{
?>


 <body>
  <!-- Reference https://www.w3schools.com/css/css_navbar.asp-->

  <div class = "back">
  <?php
  if(!isset($_SESSION['username']) || (!isset($_SESSION['admin'])))
  { 
    echo "<h3 style=\"color:red\">You have to log in first to be able to register an admin user</h3><br/>";
    echo '<p><a href="login.php">Back to login page</a></p>';
    // header("Location: index.php?referer=login");
    // die();
    
  }//end if
  else
  {	  
  ?>

    <a href="index.php"><span class="material-icons-sharp" style="color:white"> arrow_back </span>
    <p>Back Home Page</p>
    </a>

    <form class="loginbox" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"  >
      <h3 class="title">New Account for Admin</h3>


        <input class= "input" type="text" name="txt_email" placeholder="Email" title="Email contain @"
        pattern = "^.*@.*$" value = "<?php echo ($emailErr==""?$email:'')?>" required />
	      <span class="error"> <?php echo $emailErr;?></span><br/>

        <input class= "input" type="text" name="txt_username" id="txt_username" placeholder="Username" title="Name should consist of one or more words, starting with an uppercase letter 
        followed by lowercase characters, and separated by spaces" pattern="[A-Z][a-z]+( [A-Z][a-z]+)*$" value = "<?php echo ($usernameErr==""?$username:'')?>" required />
        <!-- This span will contain the AJAX hint -->
        <span id="usernameHint"></span>
	      <span class="error"> <?php echo $usernameErr;?></span><br/>
	
	      <input class= "input" type="password" name="txt_password" placeholder="Password" title="Password must contain one digit from 1 to 9, one lowercase letter, one 
        uppercase letter, one special character, no space, and it must be 8-16 characters lnog." pattern="^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,16}$"  required/>
	      <span class="error"> <?php echo $passwordErr;?></span><br/>
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
}// end if(!( $_SERVER["REQUEST_METHOD"] == "POST" &&  $usernameErr == "" && $passwordErr == "" && $emailErr ==  ""))
?>


<?php

//Add the codes to make sure that the message is only displayed when all the required fields are entered
//post
if( $_SERVER["REQUEST_METHOD"] == "POST" &&  $usernameErr == "" && $passwordErr == "" && $emailErr =="")
{

  ?>
  
  <body >
  
    <h2> <?php "echo Welcome :"  . $username; ?></h2>
  
    <a href= "index.php">Click me to go to Analytics page</a>
  <body>
  <?php
  }
  ?>
  </html>