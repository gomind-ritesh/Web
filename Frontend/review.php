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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <script>
  $(document).ready(function(){
	$("button#showOrder").click(function(){
		$("div.order_details").fadeToggle();
    $("#selectedOrderDiv").fadeOut();
	});
	$("button#hideOrder").click(function(){
		$("div.order_details").fadeToggle();
    $("#selectedOrderDiv").fadeIn();
	});

	$(document).on("click", "#tableOrder tbody tr", function(){
    $("#tableOrder tr").css("background-color", "#FFFFFF");
    $(this).css("background-color", "#D4E1D4");
    //     var valueclicked = $(this).children('td.fullvalue').text();
    //     //alert(valueclicked);
    //     //Let us set the hidden field to the full value
    //     $("#txt_visit").val(valueclicked) ;
    //     //alert("You clicked " + $(this).children('td.fullvalue').text());
    // });

    var bill_id = $(this).children("td:eq(0)").text();
    var bill_date = $(this).children("td:eq(1)").text();
    var sum_item_qty = $(this).children("td:eq(2)").text();
    var sum_item_total_price = $(this).children("td:eq(3)").text();

    // // (Optional) Still set the hidden field if needed for your server-side processing.
    // var fullValue = $(this).children("td.fullvalue").text();
    // var parts = fullValue.split("|");
    // // Assign parts to variables
    // var house_id  = parts[0];
    // var a_address   = parts[1];  // for display purposes
    // var a_owner     = parts[2];  // for display purposes
    // var a_price     = parts[3];  // for display purposes
    // var a_dateFrom  = parts[4];
    // var a_dateTo    = parts[5];
    // var renter_id = parts[6];

    // Update the new div with the selected visit details.
    $("#sel_bill_id").text(bill_id);
    $("#sel_bill_date").text(bill_date);
    $("#sel_sum_item_qty").text(sum_item_qty);
    $("#sel_sum_item_total_price").text(sum_item_total_price);

    $("#txt_bill_id").val(bill_id);
    $("#txt_bill_date").val(bill_date);
    $("#txt_sum_item_qty").val(sum_item_qty);
    $("#txt_sum_item_total_price").val(sum_item_total_price);

    // (Optional) You can still set the old hidden field if needed:
    // $("#txt_visit").val(fullValue);

    // Show the selected visit div
    // $("#selectedVisitDiv").fadeIn();

});

    /* alternative syntax usually used for items that are not available when the document has loaded (when using ajax)
    $(document).on("click", "#tableVisit tbody tr", function() {
    	$("#tableVisit tr").css("background-color", "#EEEEEE");
    	$(this).css("background-color", "yellow");
        var valueclicked = $(this).children('td.fullvalue').text();
        //alert(valueclicked);
        //Let us set the hidden field to the full value
        $("#txt_visit").val(valueclicked) ;
        //alert("You clicked " + $(this).children('td.fullvalue').text());
    }); */

});
</script>
  
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
$ratingErr = $commentErr = $billErr= "";
$rating = $comment = $bill_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") { //check if the page is being invoked after form data has been submitted
   
  if (empty($_POST["txt_bill_id"])) {
    $billErr = "Please choose a Bill Order"; 
  } else {
    $bill_id = test_input($_POST["txt_bill_id"]);
    if (!filter_var($bill_id, FILTER_VALIDATE_INT)) { 
      $billErr = "Bill ID should be numeric";
    }//end if

  }//end else

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

  if($ratingErr == "" && $commentErr ==  "" && $billErr == "")
    {
    //   $bill = $_POST["txt_bill"];
    // #echo $visit;
    // #Let us split the string according to |
    // list($bill_id, $bill_date, $user_id) = explode( "|", $bill);

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
 if(!( $_SERVER["REQUEST_METHOD"] == "POST" && $ratingErr == "" && $commentErr ==  "" && $billErr == ""))
{
?>

<?php
require_once "includes/db_connect.php";

  $showResult = bill_id_show($conn, $_SESSION['username']);

    if (!($showResult->rowCount()>0))
   {
   	echo "<div><h3 style='color:red'>You have not ordered any food from us yet or have already reviewed all bills !!!!!</h3>";
    echo '<p><a href="home.php">Home</a></p></div>';
   }//end if
   else
   {
	

    ?>

<body>


  <div class="contact-container">
    <!-- Right Side: Review Text -->
    <div class="review-section">
      <h1>We would love to hear from you</h1>
      <p>
      It all begins with your experience. Whether you enjoyed a special night out, celebrated an important occasion, or simply had a great meal, we’d love to hear your thoughts. Your feedback helps us ensure that every guest has an exceptional dining experience. Share your comments about the food, ambiance, or service, and let us know how we can make your next visit even better.
      </p>
      <h4>Contact Info: </h4>
      <p>redlantern@gmail.com<br> 57382381</p>
    </div>

    <!-- Right Side: Form -->
    <div class="form-section">
    <form  method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >
    <button id="showOrder" type="button">Click here to choose the bill you want to rate </button>
      <div class="order_details" style="display:none;background-color:#FFF5E1">
      <h3 style="text-align: center">Click a row to select a bill to rate</h3>
        <table id="tableOrder" style="width: 395px">
      <thead>
      <tr>
      	<th>Bill ID</th>
      	<th>Bill Date</th>
      	<th>Total Number of Items</th>
      	<th>Total Price</th>
      	<th></th>
      </tr>
    </thead>
      <?php
      	while ($row = $showResult->fetch()) {

      	// $str = "";
      	// $str = $str. $row['bill_id'] . "|";
      	// $str = $str. $row['bill_date'] . "|";
      	// $str = $str . $row['sum_item_qty'] . "|";
      	// $str = $str . $row['sum_item_total_price'] ;
      	echo "<tr>";
      	echo "<td style=\"text-align: center\">" . $row['bill_id'] . "</td>";
      	echo "<td style=\"text-align: center\">" . $row['bill_date'] . "</td>";
      	echo "<td style=\"text-align: center\">" . $row['sum_item_qty'] . "</td>";
      	echo "<td style=\"text-align: center\">" . $row['sum_item_total_price'] . "</td>";
      	// echo "<td style='display:none' class='fullvalue'>". $str . "</td>";
      	echo "</tr>";
      	}//end while
      ?>
      </table></br>
      <button type="button" id="hideOrder" style="float:right;">Done choosing</button>
      </div>

      <input type="hidden" name="txt_bill_id" id="txt_bill_id">
      <input type="hidden" name="txt_bill_date" id="txt_bill_date">
      <input type="hidden" name="txt_sum_item_qty" id="txt_sum_item_qty">
      <input type="hidden" name="txt_sum_item_total_price" id="txt_sum_item_total_price">

      <!-- <input type="hidden" name="txt_visit" id="txt_visit"> -->

      <div id="selectedOrderDiv" style="display:none; background-color:#FFF5E1; padding:10px; margin-top:10px;">

  <h3 style="text-align: center">Selected Bill Details</h3>
  <table border="1" style="width: 370px;">
    <tr>
      <th>Bill ID</th>
      <td id="sel_bill_id" style="text-align: center"></td>
    </tr>
    <tr>
      <th>Bill Date</th>
      <td id="sel_bill_date" style="text-align: center"></td>
    </tr>
    <tr>
      <th>Total Number of Items</th>
      <td id="sel_sum_item_qty" style="text-align: center"></td>
    </tr>
    <tr>
      <th>Total Price</th>
      <td id="sel_sum_item_total_price" style="text-align: center"></td>
    </tr>
  </table>
      </div> <span class="error">* <?php echo $billErr;?></span><br/>


        <!-- <select name="txt_bill">
         <?php 
          // while ($row = $showResult->fetch()) {
          // $str = "";
          // $str = $str . $row['bill_id'] . "|";
          // $str = $str . $row['bill_date'] . "|";
          // $str = $str . $row['user_id'] ;
          // echo "<option value = '$str'>$str</option>";
          // }//end while
          
          ?> 
        </select></br> -->

        
        <label for="txt_rating" >Rating</label>
        <select id="txt_rating" name="txt_rating" required>
                    <option value="">Please select a value</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php if ($rating == $i) echo "selected"; ?>>
                            <?php echo $i; ?> star<?php if ($i > 1) echo 's'; ?>
                        </option>
                    <?php endfor; ?>
        </select>
        <span class="error">* <?php echo $ratingErr;?></span></br>


        <label for="txt_comment">Comment </label>
        <textarea id="txt_comment" name="txt_comment" ><?php echo $comment;?></textarea>
                    </br>
        <span class="error"> <?php echo $commentErr;?></span>


        <div style="width: 50px"><button type="submit">Submit</button></div>
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
if( $_SERVER["REQUEST_METHOD"] == "POST" && $ratingErr == "" && $commentErr ==  "" && $billErr == "")
{

?>

<body >

  <h2> <?php echo "Record Saved successfully"; ?></h2>
            <a href= "home.php">Click me to go to home page</a>

</body>
<?php
}
?>
</html>

<?php
  }//end else
  
  ?>