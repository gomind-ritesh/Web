<?php
  session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/mystyle.css">
    
    <title>Home Page</title>
    <style>

/* This applies to the <body> element (the main content of the page) */
body {
    background-color: #F8EFBA;
    color: black; /* Optional: set text color to white for better contrast */
    overflow: auto; /* Allows the page to scroll if the content overflows the viewport */
    min-height: 100vh; /* Sets the minimum height of the body to 100% of the viewport height (the full height of the browser window) */
    overflow-x: hidden; /* Prevent horizontal scrolling */
}

h2{
    text-align: center;
    font-size: 50px;
    font-family: "Lucida Handwriting", "Brush Script MT", serif;
    margin-top: 10px; /* Add margin-top if needed, or set to 0 */
    padding-bottom:50px;
    color:#4A3C2A;
    text-transform: uppercase;
}

h4.homepage{
    font-family: "Lucida Handwriting", "Brush Script MT", serif;
    color:#F97F51;
    text-transform: uppercase;
    font-size: 25px;
}

h1.homepage {
    text-align: center;
    margin: auto; 
    color: #F8EFBA;
    font-size: 80px;
    padding-top:150px;
    font-family: "Cursive", Times, serif;
    text-shadow: 2px 2px 15px black;
}

p.para1 {
    font-size: 30px;
    padding-left:400px;
}

p.para2{
    text-align:center;
    font-family: "Courier New", "Lucida Handwriting", serif;
}

p.para2bold{
    text-align:center;
    font-family: "Courier New", "Lucida Handwriting", serif;
    text-transform: uppercase;
    font-weight: bold
}
p.para3{
    color:white;
    text-align: center;
    font-size: 25px;
}
p.para4bold{
    text-align:center;
    font-family: "Courier New", "Lucida Handwriting", serif;
    font-size: 35px; 
    padding-bottom: 20px;
    text-transform: uppercase;
    font-weight: bold;
    color:#556B2F;

}

hr.line{
    border-width:0;
    width:75%;
    height:4px;
    color:#f0b3ad;
    background-color: #f0b3ad; 
    margin-left:175px;
    margin-top: 10.0em;
    margin-bottom: 3.5em;
}
a.one:link {color:#F8EFBA;
    text-decoration: none;}
a.one:visited {color:#F8EFBA;
    text-decoration: none;}
a.one:hover {color:#9AECDB;
    text-decoration: none;}

div.background1 {
    background-image: url('images/restaurant.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 650px; /* Set the height of the container */
}

div.background2 {
    background-image: url('images/foods.jpeg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: 700px; /* Set the height of the container */
}

.containerevent {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    justify-content: center;
    max-width: 1200px;
    margin: 0 auto;
}

.item {
    padding: 20px;
    border-radius: 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}



/* This applies to all elements on the page */
* {
    margin: 0; /* Resets all margins to 0 for a consistent layout */
    padding: 0; /* Resets all padding to 0 */
    box-sizing: border-box; /* Ensures padding and border are included in the element's total width and height */
}


/* Applies to all elements with the class 'image' that are direct children of the element with ID 'image-track' */
#image-track > .image {
    width: 40vmin; /* Sets the width of each image to 40% of the viewport's minimum dimension (either width or height, whichever is smaller) */
    height: 56vmin; /* Sets the height of each image to 56% of the viewport's minimum dimension */
    object-fit: cover; /* Ensures that the image covers the entire area without stretching (it crops the image if needed) */
    object-position: 100% 50%; /* Centers the image within its container both horizontally and vertically */
}


/* This applies to the element with the ID 'image-track' */
#image-track {
    display: flex; /* Sets up a flex container, aligning child elements in a row by default */
    gap: 4vmin; /* Sets a gap between the images of 4% of the viewport's minimum dimension */
    position: relative; /* Positions the element relative to its normal document flow (allows top/left positioning) */
    
    top: 200px; /* Moves the element 200px down from its original position */
    left: 50px; /* Moves the element 50px to the right from its original position */

    transform: translate(0%, -50%);  /* transform: translate(-100%, -50%); Moves the element 100% to the left, making it fully off-screen horizontally, 
    and 50% up, centering it vertically relative to its container's height */
}

    </style>

</head>

<?php 
   $activemenu = "home"; 
   include('includes/navbar.php');
  ?>

<body>
<div class="background1">
    <!--<img src="images/Experience Red Lantern today.png" alt="Centered Image">-->
    <h1 class="homepage">Red Lantern</h1>
    </div>

<h2 style="padding-top:80px">The Red Lantern Room</h2>
<p class="para1">Having relocated from its original spot in Port Louis, <br/>
Red Lantern Restaurant has been serving authentic<br/>
Japanese cuisine from its new location just outside <br/>
Grand Baie since February 2016. By the end of <br/>
July that year, we opened our doors to both<br/>
locals and visitors, offering the kind of hospitality <br/>
we are known for—warm, genuine, and welcoming.</p>

<hr class="line">

<h2>Opening Hours</h2>

<p class="para2bold">We are open 5 days a week!<br/></br>
Monday to Friday (closed Saturday & Sunday):</p>

<p class="para2">From 8am until 10pm* for drinks, lunch & dinner</p><br/>

<p class="para2">Reservations indoors for the Red Lantern Room can be made for dinner from <br/>
     5.30pm onwards for any number of guests. To make a reservation email<br/>
      redlantern@gmail.com or call 57382381<br/><br/>
      * our kitchen might close earlier on a weekday - please call 57382381<br/>
       for up-to-date information.</p><br/>

<p class="para2bold">Dogs</p>
<p class="para2">We do not allow dogs anywhere on our premises.<br/>
Thank you for your cooperation and understanding.</p><br/><br/><br/>

<div class="background2">
<h2 style="padding-top:150px; color:#9AECDB"><a class="one" href="menu.php">Menu</a></h2>

<p class="para3">Our menu celebrates the artisans and producers we collaborate with.<br/>
Ingredients are carefully sourced, seasonal, and availability often depends on<br/>
the catch or harvest. Our fresh seafood, rice, miso, vegetables, and herbs<br/>
are largely sourced from local farmers and the waters<br/>
around Mauritius.<br/><br/>
Our dishes are designed to be shared among friends and family, <br/>
with each plate served as it is prepared.<br/>
</p>
</div>

<div class="para2">
<h2 style="margin-top: 75px">Reservations</h2>
<p class="para4bold">Daytime</p>
<p class="para2">For groups under 20pax we are a walk-in only venue between 8am -<br/>
noon. Just come on in at any time and we will accommodate you on your<br/>
arrival.<br/><br/>
Group bookings of 20 or more on a set menu can be offered a choice of<br/>
arrival times, please email us for more information.<br/><br/></p>
<p class="para4bold">Dinner</p>
<p class="para2">Reservations indoors for the Smoko Room can be made for dinner from<br/>
5.30pm onwards for any number of guests. To make a reservation email<br/>
redlantern@gmail.com or call 57382381.<br/><br/>
We don't take reservations for our outdoor area so please come early to<br/>
secure a table if you'd like to dine outside.<br/><br/></p>
<p class="para4bold">WEDDINGS</p>
<p class="para2">Getting married in the Grand Baie area? If you’re after a pre-wedding meal<br/>
with your guests, we can accommodate 20 – 60 people depending on the<br/>
day of the week, and season. If you’re looking to hold your wedding<br/>
reception at the Sawmill, we’re also open to talk about this! Again, this is<br/>
dependent on day of the week, and season, and includes a hireage fee and<br/>
set menu.<br/><br/>
Please note that we aren’t wedding planners, so can’t take care of all the<br/>
details on your special day – we’re here to make sure you have an amazing<br/>
dining experience and great drinks. Any decorations or flowers are up to<br/>
you, and if set-up is more than a couple basic bits, you’ll also need to<br/>
organize staff for this, as we don’t have enough of our own.<br/>
We also request that decorations do not contain<br/>
any small plastic bits (confetti, balloons etc), as these can get into the<br/>
waterways near our site and have negative environmental impact. Please<br/>
reach out via email and we’ll be in touch!<br/>
redlantern@gmail.com<br/></p>
</div>

<hr class="line" style="margin-top: 4.0em">

<h2 style="margin-top: 50px">Popular Food</h2>

<section id="image-track" data-mouse-down-at="0" data-prev-percentage="0"> <!--data-mouse-down-at="0" - A new custom value on our track element that will update every time the mouse is pressed down, data-prev-percentage="0" - This is stored so we can take the current percentage slid and add it to the last percentage to get the new percentage-->
        <img src="images/sushi platter.jpeg" class="image" draggable="false"> <!-- if(!draggable="false") - When I click an image, it just drags it off the track-->
        <img src="images/yakitori.jpeg" class="image" draggable="false">
        <img src="images/gyoza.jpeg" class="image" draggable="false">
        <img src="images/tempura.jpeg" class="image" draggable="false">
        <img src="images/mochi.jpeg" class="image" draggable="false">
        <img src="images/ramen.jpeg" class="image" draggable="false">
        <img src="images/sashimi.jpeg" class="image" draggable="false">
        <img src="images/Chocolate_cake.jpeg" class="image" draggable="false">
        <img src="images/katsu_curry.jpeg" class="image" draggable="false">
        <img src="images/oyster.jpeg" class="image" draggable="false">
        <img src="images/sushi.jpeg" class="image" draggable="false">
        

    </section> <!--define a section of content within a webpage-->

    <script src="home.js"></script>

    <hr class="line"  style="margin-top: 4.0em">

<h2>Events</h2>

<div class="containerevent">
        <div class="item">
            <h4 class = "homepage"a style="text-align:left">Board games -<br/>
            Thursday<br/><br/></h4>
            <p class="para2" style="text-align:left">Join us for board games in the Red<br/>
            Lantern Room every Thursday from 5pm.<br/><br/>
            Everyone is welcome!<br/><br/><br/><br/><br/></p>
        </div>
        <div class="item">
            <h4 class = "homepage" style="text-align:left">The Happiest of Hours<br/><br/></h4>
            <p class="para2" style="text-align:left">HAPPY HOUR - Every Monday,<br/>
            Thursday & Friday from 7pm - 10pm.<br/><br/>
            Sake glass <b>Rs 200</b> / Carafe + tempura<br/>
            chicken <b>Rs 900</b> | Japanese beer <br/> <b>Rs 200</b> glass
            / <b>Rs 900</b> bottle<br/><br/><br/><br/><br/></p>
        </div>
        <div class="item">
            <h4 class = "homepage" style="text-align:left">Special sushi Monday<br/><br/></h4>
            <p class="para2" style="text-align:left">Every Monday from 5.30pm you can<br/>
            enjoy sushi from our special chef from Japan<br/><br/><br/><br/><br/><br/><br/><br/></p>
        </div>
    </div>

   
</body>
</html>
</html>
<?php
   $activemenu = "home";
   include("includes/footer.php");
  ?>
</html>