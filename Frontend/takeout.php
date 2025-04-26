<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php?referer=menu");
    exit();
}

function fetch_all_food_data() {
    $url = "http://localhost/Web/Frontend/includes/retrieve_food.php"; // Path to the JSON file
    $response = @file_get_contents($url);
    if ($response === false) {
        error_log("Failed to fetch data from URL: " . $url);
        return null;
    }

    $data = json_decode($response, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decoding failed: " . json_last_error_msg());
        return null;
    }

    return $data;
}

// Fetch all food data
$foods = fetch_all_food_data();
if ($foods === null) {
    die("<p>Error: Could not retrieve menu data. Please try again later.</p>");
}

$categories = [
    ['category' => 'Appetizer', 'type' => 'Non-Veg'],
    ['category' => 'Appetizer', 'type' => 'Veg'],
    ['category' => 'Main Course', 'type' => 'Non-Veg'],
    ['category' => 'Main Course', 'type' => 'Veg'],
    ['category' => 'Dessert', 'type' => 'Veg']
];
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takeout Menu</title>
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <?php 
        $activemenu = "takeout";
        include('includes/navbar.php');
    ?>
</head>
<body>

    <div class="margin">
        <header class="top">
            <nav class="top--menu">
                <div class="burger--icon">
                    <i class="fa-solid fa-bars"></i>
                </div>
                <div class="search--box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search">
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
                <h1>Quick Bite <span class="cover_text">Delicacies of Red Lantern</span></h1>
            </div>
        </section>

        <main>
            <?php foreach ($categories as $category): ?>
                <h2 class="heading"><?= htmlspecialchars($category['category']) ?> -- <?= htmlspecialchars($category['type']) ?></h2>
                <div class="menu_list">
                    <?php
                    // Filter food items based on category and type
                    $filteredFoods = array_filter($foods, function ($food) use ($category) {
                        return $food['food_category'] === $category['category'] && $food['food_type'] === $category['type'];
                    });

                    if (empty($filteredFoods)) {
                        echo "<p>No items found for this category and type.</p>";
                    } else {
                        foreach ($filteredFoods as $food): ?>
                            <div class="menu_items">
                                <img src="<?= htmlspecialchars($food['food_source']) ?>" alt="<?= htmlspecialchars($food['food_name']) ?>">
                                <h4 class="item_title"><?= htmlspecialchars($food['food_name']) ?></h4>
                                <div class="item_price">
                                    <div class="price">RS <?= htmlspecialchars($food['food_price']) ?></div>
                                    <i class="fa-solid fa-plus add-to-cart"></i>
                                </div>
                            </div>
                        <?php endforeach;
                    } ?>
                </div>
            <?php endforeach; ?>
        </main>

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
                    <div class="cart_total">Rs 0</div>
                    <button class="checkout_button">Checkout</button>
                </div>
            </div>
        </div>
    </div>
    <script src="takeout.js"></script>
    <?php include('includes/footer.php'); ?>
</body>
</html>