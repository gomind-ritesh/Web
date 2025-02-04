<?php
session_start();
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="css/menu.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<?php 
   $activemenu = "takeout";
   include('includes/navbar.php');
?>

<body>

<?php
if (!isset($_SESSION['username'])) { 
    header("Location: login.php?referer=menu");
} else {
?>

<div class="margin">
    <header class="top">
        <nav class="top--menu">
            <div class="burger--icon">
                <i class="fa-solid fa-bars"></i>
            </div>
            <div class="search--box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Search" />
            </div>
            <div class="menu--icons">
                <i class="fa-solid fa-bowl-food"></i>
                <div class="cart--icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>0</span>
                </div>
            </div>
        </nav>
    </header>

    <section class="cover">
        <div class="cover_background">
            <h1>Quick Bite
                <span class="cover_text">Delicacies of Red Lantern</span>
            </h1>
        </div>
    </section>

    <main>

        <h2 class="heading">Appetizers -- Non-Veg</h2>
        <div class="menu_list">

        <?php
            require_once "includes/db_connect.php";

            $foodResults = retrieve_food($conn, 'Appetizer','Non-Veg');

            while ($food = $foodResults->fetch(PDO::FETCH_ASSOC)) {

                
        ?>


                <div class='menu_items'>
                    <img src=" <?php echo $food['food_source']; ?> " alt="">
                    <h4 class='item_title'><?php echo $food['food_name']; ?></h4>
                    <div class='item_price'>
                        <div class='price'>RS <?php echo $food['food_price']; ?></div>
                        <i class='fa-solid fa-plus add-to-cart'></i>
                    </div>
          
                  </div>

                <?php
                    }
                ?>
        </div>

        <h2 class="heading">Appetizers -- Veg</h2>
        <div class="menu_list">

    
        <?php
            require_once "includes/db_connect.php";

            $foodResults = retrieve_food($conn, 'Appetizer', 'Veg');

            while ($food = $foodResults->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <div class='menu_items'>
                    <img src=" <?php echo $food['food_source']; ?> " alt="">
                    <h4 class='item_title'><?php echo $food['food_name']; ?></h4>
                    <div class='item_price'>
                        <div class='price'>RS <?php echo $food['food_price']; ?></div>
                        <i class='fa-solid fa-plus add-to-cart'></i>
                    </div>
          
                  </div>

                <?php
                    }
                ?>
        </div>

        <h2 class="heading">Main Course -- Non-Veg</h2>
        <div class="menu_list">

        <?php
            require_once "includes/db_connect.php";

            $foodResults = retrieve_food($conn, 'Main course','Non-Veg');

            while ($food = $foodResults->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <div class='menu_items'>
                    <img src=" <?php echo $food['food_source']; ?> " alt="">
                    <h4 class='item_title'><?php echo $food['food_name']; ?></h4>
                    <div class='item_price'>
                        <div class='price'>RS <?php echo $food['food_price']; ?></div>
                        <i class='fa-solid fa-plus add-to-cart'></i>
                    </div>
          
                  </div>

                <?php
                    }
                ?>
        </div>

        <h2 class="heading">Main Course -- Veg</h2>
        <div class="menu_list">

        <?php
            require_once "includes/db_connect.php";

            $foodResults = retrieve_food($conn, 'Main course', 'Veg');

            while ($food = $foodResults->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <div class='menu_items'>
                    <img src=" <?php echo $food['food_source']; ?> " alt="">
                    <h4 class='item_title'><?php echo $food['food_name']; ?></h4>
                    <div class='item_price'>
                        <div class='price'>RS <?php echo $food['food_price']; ?></div>
                        <i class='fa-solid fa-plus add-to-cart'></i>
                    </div>
          
                  </div>

                <?php
                    }
                ?>
        </div>

        <h2 class="heading">Dessert -- Veg</h2>
        <div class="menu_list">

        <?php
            require_once "includes/db_connect.php";

            $foodResults = retrieve_food($conn, 'Dessert', 'Veg');

            while ($food = $foodResults->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <div class='menu_items'>
                    <img src=" <?php echo $food['food_source']; ?> " alt="">
                    <h4 class='item_title'><?php echo $food['food_name']; ?></h4>
                    <div class='item_price'>
                        <div class='price'>RS <?php echo $food['food_price']; ?></div>
                        <i class='fa-solid fa-plus add-to-cart'></i>
                    </div>
          
                  </div>

                <?php
                    }
                ?>
        </div>
                

        <div class="sidebar" id="sidebar">
            <div class="sidebar-close">
                <i class="fa-solid fa-close"></i>
            </div>
            <div class="cart-menu">
                <h3>My cart</h3>
                <div class="cart-items"></div>
            </div>
            <div class="sidebar_footer">
                <div class="total_amount">
                    <h5>Total</h5>
                    <div class="cart_total">
                        Rs 0
                    </div>
                    
                    <button class="checkout_button">Checkout</button>
                    
                </div>
            </div>
        </div>

    </main>

    <script src="takeout.js"></script> <!-- Link to the JS file -->

</div>

<?php
} // end else
?>

</body>

<footer>
<?php 
   include('includes/footer.php');
?>
</footer>
</html>
