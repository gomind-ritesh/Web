<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['admin'])) {
    echo "<h3 style=\"color:red\">Please log in first to access this page</h3>";
    echo '<p><a href="login.php">Back to the login page</a></p>';
    exit();
}

// Database connection
require_once "includes/db_connect.php";

// Fetch food details by food_id
$food_id = isset($_GET['food_id']) ? intval($_GET['food_id']) : 0;

if ($food_id > 0) {
    // Prepare the query to fetch food details by food_id
    $query = "SELECT * FROM food WHERE food_id = :food_id";
    $stmt = $conn->prepare($query);
    
    // Bind the food_id parameter as an integer
    $stmt->bindParam(":food_id", $food_id, PDO::PARAM_INT);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch the result
    $food = $stmt->fetch(PDO::FETCH_ASSOC);

    // You can process $food further as needed (e.g., edit details)
} else {
    echo "<h3 style=\"color:red\">Invalid food ID</h3>";
    exit();
}



// Initialize variables with fetched data
$default_name = $food['food_name'];
$default_price = $food['food_price'];
$default_discount = $food['food_discount'];
$default_description = $food['food_description'];
$default_category = $food['food_category'];
$default_type = $food['food_type'];
$default_source = $food['food_source'];

// define variables and set to empty string values
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
    
    // define variables and set to empty string values
    
    $successmsg = $name = $price = $discount = $description = $category = $type = $source = $Msg ="";
    $nameErr = $priceErr = $discountErr = $descriptionErr = $categoryErr = $typeErr = $sourceErr ="";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
         // food name
    if (empty($_POST["name"])) {//check if the field is empty
        $nameErr = "Select a food name";
      } 
      else
      {
        $name = test_input($_POST["name"]);//call the test_input function on $_POST["txt_name"]
        
        if (!preg_match("/^[a-zA-Z ]+$/",$name)) 
        { //Use a regular expression to validate the name field
            $nameErr = "Only letters and white space allowed";
        }
      }//end else
    
        //food price
        if (empty($_POST["price"])) {
            $priceErr= "Select a food price";
        } else {
            $price = test_input($_POST["price"]);
            //Let us validate the price
            if (!filter_var($price, FILTER_VALIDATE_FLOAT)) { //Let us invoke the inbuilt function filter_var
            $priceErr = "Food price should be numeric";
            }//end if
        
        }//end else
        
         //food discount
        if (empty($_POST["discount"])) {
            $discountErr= "Select a food discount";
        } else {
            $discount = test_input($_POST["discount"]);
            //Let us validate the price
            if (!filter_var($discount, FILTER_VALIDATE_FLOAT)) { //Let us invoke the inbuilt function filter_var
            $discountErr = "Food discount should be numeric";
            }//end if
        
        }//end else
    
        //description
        if (empty($_POST["description"])) {
            $descriptionErr = "Please write a food description"; 
        } else {
            //Now let us remove illegal characters
            //$description = test_input($_POST["description"]); ; not used because it will degrade meaning of comment
            $description = 	$_POST["description"];
            $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);	//Let us clean the data using filter_var
            
        }
    
             // food category
    if (empty($_POST["category"])) {//check if the field is empty
        $categoryErr = "Select a food category";
      } 
      else
      {
        $category = test_input($_POST["category"]);//call the test_input function on $_POST["txt_name"]
        
        if (!preg_match("/^[a-zA-Z ]+$/",$category)) 
        { //Use a regular expression to validate the name field
            $categoryErr = "Only letters and white space allowed";
        }
      }//end else
    
           // food type
    if (empty($_POST["type"])) {//check if the field is empty
        $typeErr = "Select a food type";
      } 
      else
      {
        $type = test_input($_POST["type"]);//call the test_input function on $_POST["txt_name"]
        
        if (!preg_match("/^[a-zA-Z -]+$/",$type)) 
        { //Use a regular expression to validate the name field
            $typeErr = "Only letters and white space allowed(also including '-')";
        }
      }//end else
    
      if(!$_FILES["file-select"]["error"]===0){
        $sourceErr = "Error in file.";
      }
    
      // Validate food source (file upload)
      if (empty($_FILES["file-select"]["name"])) {
        $sourceErr = "Please upload a food source image.";
    } else {
        $source = test_input($_FILES["file-select"]["name"]);
        // Validate file type and extension
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($source, PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $sourceErr = "Invalid food source image. Only jpg, jpeg, png, and gif images are allowed.";
        }
        // Check file size (5MB limit)
        if ($_FILES["file-select"]["size"] > 5000000) {
            $sourceErr = "File is too large. Maximum file size is 5MB.";
        }
    }
    
    // If no errors, proceed with file upload and database insertion
    if ($nameErr == "" && $priceErr == "" && $discountErr == "" && $descriptionErr == "" && $categoryErr == "" && $typeErr == "" && $sourceErr == "") {
        $target_dir = "../Frontend/images/";
        // $sanitized_file_name = preg_replace("/[^a-zA-Z0-9.-_]/", "_", basename($_FILES["file-select"]["name"]));
        $target_destination = $target_dir . ($_FILES["file-select"]["name"]);
        $file_tmpname = $_FILES["file-select"]["tmp_name"];
    
        // Attempt to move uploaded file
        if (move_uploaded_file($file_tmpname, $target_destination)) {
            
            require_once "includes/db_connect.php";
            $target_final = "images/" . ($_FILES["file-select"]["name"]);  // Store relative path to the image
            $results = edit_food($conn, $food_id, $name, $price, $discount, $description, $category, $type, $target_final);
    
            if (!$results) {
                $Msg = "ERROR: Record could not be saved!";
            } else {
                $Msg = "Record saved successfully!";
            }
        } else {
            $Msg = "Sorry, there was an error uploading your file." . $sourceErr;
    
    print_r($FILES);
    
        }
    }
    
    else {
        $Msg =  $sourceErr;
    }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "includes/metatags.php"; ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />
    <link rel="stylesheet" href="includes/style.css" />
    <link rel="stylesheet" href="includes/food_form.css" />
    <title>Edit Food Menu | Red Lantern</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        .error {
            color: red;
        }

        .back-link:hover {
            color: red;
        }
    </style>
</head>
<body>
<?php
if(!( $_SERVER["REQUEST_METHOD"] == "POST" && $nameErr == "" && $priceErr == "" && $discountErr == "" &&  $descriptionErr == "" && $categoryErr == "" && $typeErr =="" && $sourceErr ==""))
{
?>
    <div class="food-container">
        <div class="food-form">
            <form method="post" action="<?php echo $_SERVER["PHP_SELF"] . "?food_id=" . $food_id; ?>" enctype="multipart/form-data">
            <label for="name">Food Name</label>

            <input type="text" id="name" class="name" name="name" 
            pattern="[A-Za-z][a-zA-Z]*( [A-Za-z][a-zA-Z]*)*$" value="<?php echo htmlspecialchars($default_name); ?>" required />
            <span class="error"> <?php echo $nameErr;?></span><br/><br/><br/>

            <label for="price">Food Price</label>
            <input type="number" class="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($default_price); ?>" required /><br/>
            <span class="error"><?php echo $priceErr; ?></span><br/>

            <label for="discount">Food Discount</label>
            <input type="number" class="discount" name="discount" step="0.01" min="0" value="<?php echo htmlspecialchars($default_discount); ?>" required /><br/>
            <span class="error"><?php echo $discountErr; ?></span><br/>

            <label for="description">Food Description</label>
            <textarea rows="10" cols="10" name="description" class="description"><?php echo htmlspecialchars($default_description); ?></textarea>
            <span class="error"> <?php echo $descriptionErr;?></span><br/><br/><br/>

            <!-- Food Category -->
            <label for="category">Choose Food Category: </label>
            <select id="category" name="category" class="styled-select">
            <option value="" disabled>Select an option</option>
            <option value="Appetizer" <?php echo $default_category == "Appetizer" ? "selected" : ""; ?>>Appetizer</option>
            <option value="Main Course" <?php echo $default_category == "Main Course" ? "selected" : ""; ?>>Main Course</option>
            <option value="Dessert" <?php echo $default_category == "Dessert" ? "selected" : ""; ?>>Dessert</option>
            </select><br/><br/>

            <!-- Food Type -->
            <label for="type">Choose Food Type: </label><br/>
            <div style="display: inline-block; margin-right: 20px;">
                <input type="radio" id="veg" name="type" value="Veg" <?php echo $default_type == "Veg" ? "checked" : ""; ?>>
                <label for="veg">Veg</label><br/>
            </div>
            <div style="display: inline-block;">
                <input type="radio" id="nonveg" name="type" value="Non-Veg" <?php echo $default_type == "Non-Veg" ? "checked" : ""; ?>>
                <label for="nonveg">Non Veg</label>
            </div><br/><br/><br/>

                <label for="file" class="form-label">Choose file</label>
                <input
                    type="file"
                    class="form-control"
                    name="file-select"
                    id="file-select"
                    accept=".jpg,.jpeg,.png,.gif"
                    placeholder="Choose a File..." />
                <div id="fileHelpId" class="form-text">Upload type: jpg, jpeg, png, gif</div><br><br>

                <div style="padding-top: 20px"><button type="submit">ADD FOOD</button></div>
            </form>
        </div>
    </div>
</body>
<?php
    }
    ?>

<?php

//Add the codes to make sure that the message is only displayed when all the required fields are entered
//post
if( $_SERVER["REQUEST_METHOD"] == "POST" && $nameErr == "" && $priceErr == "" && $discountErr == "" &&  $descriptionErr == "" && $categoryErr == "" && $typeErr =="" && $sourceErr =="")
{
?>
                
    <body >

    <h2>  <?php echo $Msg; ?></h2>
    <a href="foodmenu.php">
    <span id="back" class="material-icons-sharp" style="color:#2C3A47"> arrow_back </span>
    <p class="back-link">Back Food Menu OR</p>
</a>

<a href="add_food_form.php">
    <span id="initial" class="material-icons-sharp" style="color:#2C3A47"> add </span>
    <p class="back-link">Add another Food</p>
</a>

</body>

<?php
}
?>

</html>
