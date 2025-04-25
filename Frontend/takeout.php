<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php?referer=menu");
    exit();
}

function fetch_menu_data($category, $type) {
    $url = "http://localhost/Web/Frontend/includes/retrieve_food.php?category=$category&type=$type";
    $response = file_get_contents($url); // Fetch JSON data from the separate file
    return json_decode($response, true); // Decode JSON into an array
}

$categories = [
    ['category' => 'Appetizer', 'type' => 'Non-Veg'],
    ['category' => 'Appetizer', 'type' => 'Veg'],
    ['category' => 'Main course', 'type' => 'Non-Veg'],
    ['category' => 'Main course', 'type' => 'Veg'],
    ['category' => 'Dessert', 'type' => 'Veg']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takeout Menu</title>
    <link rel="stylesheet" href="css/mystyle.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>
    <?php include('includes/navbar.php'); ?>

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
                <h2 class="heading"><?= $category['category'] ?> -- <?= $category['type'] ?></h2>
                <div class="menu_list">
                    <?php
                    $foods = fetch_menu_data($category['category'], $category['type']);
                    if (isset($foods['error'])) {
                        echo "<p>Error: " . htmlspecialchars($foods['error']) . "</p>";
                    } else {
                        foreach ($foods as $food): ?>
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

    <?php include('includes/footer.php'); ?>
</body>
</html>