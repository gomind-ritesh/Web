<?php
// Start the session
//The session_start() function must be the very first thing in your document. Before any HTML tags.
session_start();

 
?>

<html>
 <head>
   <title>Register</title>
   
<style>
  .error{
    color: red;
  }
  </style>
  <link rel="stylesheet" href="css/mystyle.css">
  <link rel="stylesheet" href="css/uregistration.css">
 </head>

 <?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


// define error variables and set to empty values
$usernameErr =  $firstnameErr = $lastnameErr = $phoneErr = $passwordErr = $emailErr = $Msg = "";
$username = $firstname = $lastname = $phone = $password = $email =  "";


if ($_SERVER["REQUEST_METHOD"] == "POST") { //check if the page is being invoked after form data has been submitted

  //username
    if (empty($_POST["txt_username"])) {//check if the field is empty
      $usernameErr = "Name is required";
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
                $usernameErr = "User Name already exists";
              }
        }
    }//end else

  //email
    if (empty($_POST["txt_email"]))
    { //check if the field is empty
      $emailErr = "Email is required";
    } 
    else
    {
      $email = test_input($_POST["txt_email"]);
      
      if (!filter_var($email, FILTER_VALIDATE_EMAIL))  //Invoke the inbuilt function filter_var for email
      {
        $emailErr = "Email not valid";
      }else
        {
          require_once "includes/db_connect.php";
          if(exist_email( $conn,$email))
              {
                $emailErr = "Email already exists";
              }
        }
    }//end else


 //firstname
    if (empty($_POST["txt_firstname"])) {//check if the field is empty
      $firstnameErr = "First name is required";
    } 
    else 
    {
      $firstname = test_input($_POST["txt_firstname"]);
      
      if (!preg_match("/^[a-zA-Z']+$/",$firstname)) 
      { //Use a regular expression to validate the firstname field
        $firstnameErr = "Only letters and white space allowed";
      }
      
    }//end else

    //Lastname
    if (empty($_POST["txt_lastname"])) {//check if the field is empty
      $lastnameErr = "Last name is required";
    } 
    else 
    {
      $lastname = test_input($_POST["txt_lastname"]);
      
      if (!preg_match("/^[a-zA-Z' ]+$/",$lastname)) 
      { //Use a regular expression to validate the lastname field
        $lastnameErr = "Only letters and white space allowed";
      }
      
    }//end else

    //password
    if (empty($_POST["txt_password"])) {//check if the field is empty
      $passwordErr = "password is required";
    } 
    else 
    {
      $password = test_input($_POST["txt_password"]);
      
      if (!preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,16}$/",$password))
      { //Use a regular expression to validate the password field
        $passwordErr = "Password must contain one digit from 1 to 9, one lowercase letter, one uppercase letter, one special character, no space, and it must be 8-16 characters lnog.";
      }
      
    }//end else

    //phone
    if (empty($_POST["txt_phone"])) {//check if the field is empty
      $phoneErr = "phone number is required";
    } 
    else 
    {
      $phone = test_input($_POST["txt_phone"]);
      
      if (!preg_match("/^[0-9]{8}$/",$phone)) 
      { //Use a regular expression to validate the phone field
        $phoneErr = "Password must contain 8 digits only";
      }
      
    }//end else

  
  
    if($usernameErr == "" &&  $firstnameErr == "" &&  $lastnameErr == "" && $phoneErr == "" && $passwordErr == "" && $emailErr ==  "")
    {

      require_once "includes/db_connect.php";
     $addResult = insert_data_register($conn, $username, $firstname, $lastname, $phone, $password, $email);

    if (!$addResult) {
        $Msg = "ERROR: Record could not be saved!";
        // echo "<h3 class='error'>$Msg</h3>";
    } else {
        $Msg = "Record saved successfully!";
        // echo "<h3>$Msg</h3>";
        // Optionally, redirect the user or clear the form here
    }

    }//end if($nameErr == "" && $clean_ratingErr == "" && $genderErr == "" && $commentErr == ""  )
}//end if ($_SERVER["REQUEST_METHOD"] == "POST")
  

?>

<?php
if(!( $_SERVER["REQUEST_METHOD"] == "POST" &&  $usernameErr == "" &&  $firstnameErr == "" &&  $lastnameErr == "" && $phoneErr =="" && $passwordErr == "" && $emailErr ==  ""))
{
?>


 <body>
  <!-- Reference https://www.w3schools.com/css/css_navbar.asp-->
  
  <?php 
   $activemenu = "register";
   include('includes/navbar.php');
  ?>

  <div class = "back">
  <?php
  if(isset($_SESSION['username']))
  { 
    header("Location: home.php?referer=register");
    
  }//end if
  else
  {	  
  ?>
  
    <form class="loginbox" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>"  >
      <h3 class="title">Register</h3>
        
        <input class= "input" type="text" name="txt_firstname" placeholder="Firstname" title="First name should consist of only characters"
        pattern="[A-Z][a-z]+( [A-Z][a-z]'+)*$" value = "<?php echo ($firstnameErr==""?$firstname:'')?>" required />
	      <span class="error"> <?php echo $firstnameErr;?></span><br/>

        <input class= "input" type="text" name="txt_lastname" placeholder="Lastname" title="Last name should consist of only characters" 
        pattern="[A-Z][a-z]+( [A-Z][a-z]'+)*$" value = "<?php echo ($lastnameErr==""?$lastname:'')?>" required />
	      <span class="error"> <?php echo $lastnameErr;?></span><br/>

        <input class= "input" type="text" name="txt_email" placeholder="Email" title="Email contain @"
        pattern = "^.*@.*$" value = "<?php echo ($emailErr==""?$email:'')?>" required />
	      <span class="error"> <?php echo $emailErr;?></span><br/>

        <input class= "input" type="text" name="txt_phone" placeholder="Phone Number" title="Phone number should contain 8 digits"
        pattern = "^[0-9]{8}$" value = "<?php echo ($phoneErr==""?$phone:'')?>" required />
	      <span class="error"> <?php echo $phoneErr;?></span><br/>

        <input class= "input" type="text" name="txt_username" placeholder="Username" title="Name should consist of one or more words, starting with an uppercase letter 
        followed by lowercase characters, and separated by spaces" pattern="[A-Z][a-z]+( [A-Z][a-z]+)*$" value = "<?php echo ($usernameErr==""?$username:'')?>" required />
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
   $activemenu = "register";
   include('includes/footer.php');
  ?>

<?php
}// end if(!( $_SERVER["REQUEST_METHOD"] == "POST" && $nameErr == "" && $clean_ratingErr == "" && $genderErr == "" && $commentErr == "" &&  $emailErr == ""))
?>


<?php

//Add the codes to make sure that the message is only displayed when all the required fields are entered
//post
if( $_SERVER["REQUEST_METHOD"] == "POST" &&  $usernameErr == "" &&  $firstnameErr == "" &&  $lastnameErr == "" && $phoneErr == "" && $passwordErr == "" && $emailErr =="")
{

?>

<body >

  <h2>  <?php echo $Msg; ?></h2>
  <a href= "login.php">Click me to go to login page</a>
<body>
<?php
}
?>
</html>