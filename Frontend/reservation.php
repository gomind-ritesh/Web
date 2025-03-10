<?php
  session_start();

if (!isset($_SESSION['username'])) { 
    header("Location: login.php?referer=menu");
} 
else 
{
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>Reservation</title>
        <link rel="stylesheet" href="css/reservation.css" />
        <link rel="stylesheet" href="css/mystyle.css">

        <style>
            .error{
                color: red;
            }
    </style>

    <?php 
        $activemenu = "reservation";
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

    // define variables and set to empty string values

    $successmsg = $reservationname = $partysize = $date = $time = $phone = $comment = $Msg ="";
    $reservationnameErr = $partysizeErr = $dateErr = $timeErr = $phoneErr = $commentErr ="";

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

         // reservation name
    if (empty($_POST["reservation_name"])) {//check if the field is empty
        $reservationnameErr = "Select a reservation name";
      } 
      else
      {
        $reservationname = test_input($_POST["reservation_name"]);//call the test_input function on $_POST["txt_name"]
        
        if (!preg_match("/^[a-zA-Z ]+$/",$reservationname)) 
        { //Use a regular expression to validate the name field
            $reservationnameErr = "Only letters and white space allowed";
        }
      }//end else

        //party-size
        if (empty($_POST["party-size"])) {
            $partysizeErr= "Select a party size";
        } else {
            $partysize = test_input($_POST["party-size"]);
            //Let us validate the party size
            if (!filter_var($partysize, FILTER_VALIDATE_INT)) { //Let us invoke the inbuilt function filter_var
            $partysizeErr = "party size should be numeric";
            }//end if
        
        }//end else
        
         // Date validation
        if (empty($_POST["date"])) {
            $dateErr = "Select a date";
        } else {
            $date = test_input($_POST["date"]);
            $test_arr = explode('-', $date);
            if (!checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
                $dateErr = "Invalid date";
            }
        }

        // Time validation
        if (empty($_POST["time"])) {
            $timeErr = "Select a time";
        } else {
            $time = test_input($_POST["time"]);
            if (!in_array($time, range(14, 21))) {
                $timeErr = "Choose a time between 14:00 and 21:00";
            }
        }

        //phone
        if (empty($_POST["phone"])) {//check if the field is empty
            $phoneErr = "phone number is required";
        } 
        else 
        {
            $phone = test_input($_POST["phone"]);
            
            if (!preg_match("/^\d{8}$/",$phone)) { //Use a regular expression to validate the phone field
                    $phoneErr = "phone number must contain 8 digits only";
            }
                
        }//end else
        
        //comment
        if (empty($_POST["txt_comment"])) {
            $commentErr = ""; //assume comment is optional
        } else {
            //Now let us remove illegal characters
            //$comment = test_input($_POST["txt_comment"]); ; not used because it will degrade meaning of comment
            $comment = 	$_POST["txt_comment"];
            $comment = filter_var($comment, FILTER_SANITIZE_SPECIAL_CHARS);	//Let us clean the data using filter_var
            
        }

    
        if($reservationnameErr == "" && $partysizeErr == "" && $dateErr == "" &&  $timeErr == "" && $phoneErr == "" && $commentErr =="")
        {
            
            
            require_once "includes/db_connect.php";
            
            $results = enter_reservation($conn, $reservationname, $partysize, $date, $time, $phone, $comment);

            if (!$results) {
                $Msg = "ERROR: Record could not be saved!";
                
            } else {
                $Msg = "Record saved successfully!";
            
            }
        }//end if
    
    }


    ?>

    <?php
    if(!( $_SERVER["REQUEST_METHOD"] == "POST" && $reservationnameErr == "" && $partysizeErr == "" && $dateErr == "" &&  $timeErr == "" && $phoneErr == "" && $commentErr ==""))
    {
    ?>

    <body>
        <div class="reservation-container">
        <div class="reservation-form">
            <h1>Make a Reservation</h1>
            <p>
            Enjoy an unforgettable dining experience with us. Whether you're
            celebrating a special occasion or just enjoying a night out, our
            restaurant offers the perfect ambiance and cuisine for every moment.
            Our chefs use only the freshest ingredients to craft exquisite dishes
            that will tantalize your taste buds. Reserve your table now to ensure
            your place at one of the finest dining establishments in town. We look
            forward to welcoming you and making your experience truly memorable.
            </p>

            <form  method="post" action="<?php echo $_SERVER["PHP_SELF"];?>" >

            <label for="reservation_name">  Reservation Name</label>

            <input type="text" id="reservation_name" class="reservation_name" name="reservation_name" placeholder="Reservation Name" title="Rservation name should consist of only characters"
            pattern="[A-Z][a-z]+( [A-Z][a-z]'+)*$" value = "<?php echo ($reservationnameErr==""?$reservationname:'')?>" required />
	        <span class="error"> <?php echo $reservationnameErr;?></span><br/><br/>

            <label for="party-size">Party size</label>

            <select id="party-size" class="select-box" name="party-size" required>
                    <option value="">Please select a value</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php if ($partysize == $i) echo "selected"; ?>>
                            <?php echo $i; ?> guest<?php if ($i > 1) echo 's'; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            <span class="error"> <?php echo $partysizeErr;?></span><br/>

            <label for="date">Date</label>
            <input type="date" id="date" name="date" value="<?php if($date!=""){echo "$date";}else{echo date("Y-m-d");}?>" required/>
            <span class="error"> <?php echo $dateErr;?></span><br/>

            <label for="time">Time</label>

            <select id="time" class="select-box" name="time" required>
                    <?php for ($i = 14; $i <= 21; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php if ($time == $i) echo "selected"; ?>>
                            <?php echo date("g:i A", strtotime("$i:00")); ?>
                        </option>
                    <?php endfor; ?>
                </select>

            <span class="error"> <?php echo $timeErr;?></span><br/>

            <label for="phone">Phone Number</label>
            <input type="tel" class="phone" name="phone" pattern="[0-9]{8}" value = "<?php echo ($phoneErr==""?$phone:'')?>" required /></br>
            <span class="error"> <?php echo $phoneErr;?></span><br/>

            
            <label for="txt_comment">Reservation Note</label>
            <textarea rows="10" cols="10" name="txt_comment" class="txt_comment" value = "<?php echo ($commentErr==""?$comment:'')?>" ></textarea>
            <span class="error"> <?php echo $commentErr;?></span><br/>

            <div style="padding-top: 20px"><button type="submit">Book now</button></div>
            </form>
        </div>

        <h2><?php echo $successmsg?></h2>

        <div class="reservation-image">
            <img src="images/reservation-background.jpg" alt="" />
        </div>
        </div>
    </body>

            
        <?php 
        $activemenu = "reservation";
        include('includes/footer.php');
        ?>

   

    <?php
    }
    ?>


    <?php

        //Add the codes to make sure that the message is only displayed when all the required fields are entered
        //post
        if( $_SERVER["REQUEST_METHOD"] == "POST" && $partysizeErr == "" && $dateErr == "" &&  $timeErr == "" && $phoneErr == "" && $commentErr =="")
        {
    ?>
                        
            <body >

            <h2>  <?php echo $Msg; ?></h2>
            <a href= "home.php">Click me to go to home page</a>
          
        </body>

    <?php
        }
    ?>
 
<?php
}
?>
</html>