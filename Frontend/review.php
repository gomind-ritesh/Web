<?php
session_start();

if(!isset($_SESSION['username']))
  { 
    echo "<h3 style=\"color:red\">Please log in first to access this page</h3>";
    echo '<p><a href="home.php">Back to the home page</a></p>';
    // header("Location: login.php?referer=index");
    // die();
    
  }//end if
  else
  {	  
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
  <style>
    .error {color: #FF0000;}
    </style>
  <link rel="stylesheet" href="css/review.css"> 
  <link rel="stylesheet" href="css/mystyle.css">


  
<?php
    // currently on review page
    $activemenu = "review"; 
    include('includes/navbar.php');
   ?>

</head>

<?php

// define variables and set to empty string values
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  // define error variables and set to empty values
$ratingErr = $commentErr = "";
$rating = $comment = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") { //check if the page is being invoked after form data has been submitted

  //Rating
  if (empty($_POST["txt_rating"])) {
    $ratingErr= "A proper rating is required";
  } else {
    $rating = test_input($_POST["txt_rating"]);
    //Let us validate the clean_rating
    if (!filter_var($rating, FILTER_VALIDATE_INT)) { //Let us invoke the inbuilt function filter_var
      $ratingErr = "Rating should be numeric";
    }//end if

  }//end else

  //Comment 
  if (empty($_POST["txt_comment"])) {
    $commentErr = ""; //assume comment is optional
  } else {
    //Now let us remove illegal characters
    //$comment = test_input($_POST["txt_comment"]); ; not used because it will degrade meaning of comment
    $comment = 	$_POST["txt_comment"];
    $comment = filter_var($comment, FILTER_SANITIZE_SPECIAL_CHARS);	//Let us clean the data using filter_var
    
  }

  if($ratingErr == "" && $commentErr ==  "")
    {
      $bill = $_POST["txt_bill"];
    #echo $visit;
    #Let us split the string according to |
    list($bill_id, $bill_date, $user_id) = explode( "|", $bill);

      $Msg = "";
      require_once "includes/db_connect.php";

      $username=$_SESSION['username'];

      
    $user_id = fetch_userid_review($conn, $username);
 
    if (!$user_id) {
      $Msg = "ERROR: User ID could not be fetched!";
      echo "<h3 class='error'>$Msg</h3>";
  } else {
      $Msg = "User ID fetched successfully!";
      echo "<h3>$Msg</h3>";
      // Optionally, redirect the user or clear the form here
  }
      

    $addResult = insert_data_review($conn, $rating, $comment, $user_id, $bill_id);

    if (!$addResult ) {
        $Msg = "ERROR: Record could not be saved!";
        echo "<h3 class='error'>$Msg </h3>";
    } else {
        $Msg = "Record saved successfully!";
        echo "<h3>$Msg</h3>";
        // Optionally, redirect the user or clear the form here
    }

    $updateResult = update_data_review($conn, $bill_id);

    if (!$updateResult) {
        $Msg = "ERROR: Record could not be updated!";
        echo "<h3 class='error'>$Msg</h3>";
    } else {
        $Msg = "Record updated successfully!";
        echo "<h3>$Msg</h3>";
        // Optionally, redirect the user or clear the form here
    }



    }//end if($ratingErr == "" && $commentErr ==  "")
}//end if ($_SERVER["REQUEST_METHOD"] == "POST")
  

?>

<?php
 if(!( $_SERVER["REQUEST_METHOD"] == "POST" && $ratingErr == "" && $commentErr ==  ""))
{
?>

<?php
require_once "includes/db_connect.php";

  $showResult = bill_id_show($conn, $_SESSION['username']);

    if (!($showResult->fetch()))
   {
   	echo "<div><h3 style='color:red'>You have not ordered any food from us yet or have already reviewed all bills !!!!!</h3>";
    echo '<p><a href="home.php">Home</a></p></div>';
   }//end if
   else
   {
	

    ?>

<body>


  <div class="contact-container">
    <!-- Left Side: Review Text -->
    <div class="review-section">
      <h1>We would love to hear from you.</h1>
      <p>
      It all begins with your experience. Whether you enjoyed a special night out, celebrated an important occasion, or simply had a great meal, we’d love to hear your thoughts. Your feedback helps us ensure that every guest has an exceptional dining experience. Share your comments about the food, ambiance, or service, and let us know how we can make your next visit even better.
      </p>
      <p>redlantern@gmail.com</p>
      <p>57382381</p>
    </div>

    <!-- Right Side: Form -->
    <div class="form-section">
    <form  method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >

        Choose the bill you want to rate: <br/>
        <select name="txt_bill">
        <?php
          while ($row = $showResult->fetch()) {
          $str = "";
          $str = $str . $row['bill_id'] . "|";
          $str = $str . $row['bill_date'] . "|";
          $str = $str . $row['user_id'] ;
          echo "<option value = '$str'>$str</option>";
          }//end while
          
         ?>
        </select></br>

        
        <label for="txt_rating" >Rating</label>
        <select id="txt_rating" name="txt_rating" required>
                    <option value="">Please select a value</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php if ($rating == $i) echo "selected"; ?>>
                            <?php echo $i; ?> star<?php if ($i > 1) echo 's'; ?>
                        </option>
                    <?php endfor; ?>
        </select>
        <span class="error">* <?php echo $ratingErr;?></span><br/><br/>


        <label for="txt_comment">Comment </label>
        <textarea id="txt_comment" name="txt_comment" ><?php echo $comment;?></textarea>
        <span class="error">* <?php echo $commentErr;?></span><br/><br/>


        <button type="submit">Submit</button>
      </form>
      <?php
   
  }//end else if ($numrows ==0) ?>

    </div>
  </div>
</body>
<?php 
   $activemenu = "review";
   include('includes/footer.php');
  ?>
<?php
}// end if(!($ratingErr == "" && $commentErr ==  ""))
?>

<?php

//Add the codes to make sure that the message is only displayed when all the required fields are entered
//post
if( $_SERVER["REQUEST_METHOD"] == "POST" && $ratingErr == "" && $commentErr ==  "")
{

?>

<body >

  <h2> <?php echo "Record Saved successfully"; ?></h2>

<body>
<?php
}
?>
</html>

<?php
  }//end else
  
  ?>