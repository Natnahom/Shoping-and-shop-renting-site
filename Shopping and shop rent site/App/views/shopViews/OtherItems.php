<?php
    session_start();
?>
<?php
    include ('../../database/Database.php');

    if (!isset($_SESSION['productId'])) {
        echo "<h2 style=\"color:Red; text-align:center;\">Session not found. Please log in.</h2>";
        exit();
    }
    // if (isset($_SESSION['uname'])){
      $productId = $_SESSION['productId'];
      // echo "Product ID is: " . $productId;
        // }
    // else {
    //     echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    // }

    $sqlUn = "SELECT * FROM shop WHERE productId = '$productId'";
    $resultUn = mysqli_query($conn, $sqlUn);

    
    if (mysqli_num_rows($resultUn) > 0){
        $rowUn = mysqli_fetch_assoc($resultUn);
    }
    else {
        echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    }

    $usernameSh = $rowUn['username'];


    $sql1 = "SELECT shop.productId, shopkeepers.name, shop.pictureName, shop.brand, shop.model, shop.category, shop.price 
              FROM shop 
              JOIN shopkeepers ON shop.username = shopkeepers.username 
              WHERE shopkeepers.availability = 'On' AND shop.username = '$usernameSh'";

    $sqlCount1 = "SELECT COUNT(*) AS username_count1 FROM shop WHERE username = '$usernameSh'";

    $result1 = mysqli_query($conn, $sql1);
    // $resultBlog = mysqli_query($conn, $sqlBlog);
    $resultCount1 = mysqli_query($conn, $sqlCount1);

    // if (mysqli_num_rows($resultBlog) > 0){
    //   $rowBlog = mysqli_fetch_assoc($resultBlog);
    // }
    // else {
    //   $rowBlog = "Blog";
    // }

    if (mysqli_num_rows($resultCount1) > 0){
      $rowCount1 = mysqli_fetch_assoc($resultCount1);
    }
    else {
      $rowCount1 = 0;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../../Resources/css/forShop.css">
    <link rel="stylesheet" href="../../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Shop - Nathaven Shopysite</title>
</head>
<body>

    <section class="main_shop">

      <section class="panel_shop">
        <h1 style="color: rgb(255, 115, 0);">PANEL</h1>
        <div id="itemCount" class="item-count">Total Items: <span id="count"><?php echo $rowCount1['username_count1'] ?></span></div>
        <div class="filter">
            <h3>Filter by Price Range</h3>
            <label><input type="checkbox" value="0-5000" onchange="filterItemsShop()"> 0 ETB - 5,000 ETB</label><br>
            <label><input type="checkbox" value="5000-10000" onchange="filterItemsShop()"> 5,000 ETB - 10,000 ETB</label><br>
            <label><input type="checkbox" value="10000-50000" onchange="filterItemsShop()"> 10,000 ETB - 50,000 ETB</label><br>
            <label><input type="checkbox" value="50000-200000" onchange="filterItemsShop()"> 500,00 ETB - 200,000 ETB</label><br>
            <label><input type="checkbox" value="200000+" onchange="filterItemsShop()"> 200,000 ETB+</label><br>
        </div>
        <div class="filter">
            <h3>Category</h3>
            <select id="categorySelect" onchange="filterItemsShop()">
                <option value="">Select Category</option>
                <option value="Electronics">Electronics</option>
                <option value="Clothing">Clothing</option>
                <option value="Home and living">Home and living</option>
                <option value="Sports and Outdoors">Sports and Outdoors</option>
                <option value="Beauty and Health">Beauty and Health</option>
                <option value="Books and Media">Books and Media</option>
                <option value="Toys and Games">Toys and Games</option>
                <option value="Automotive and Vehicles">Automotive and Vehicles</option>
            </select>
        </div>
      </section>

        <section id="div4" class="content">
          <div class="contentDiv">
            <!-- <h1>ITEMS</h1> -->
            <div id="searchCont">
              <!-- <form action="dashboard.php" method="post"> -->
                <input type="text" id="search" name="search" placeholder="Search..." onkeyup="searchItems()">
                <!-- <button type="submit" name="submit2" id="searchBtn" onclick="searchItems()">Search</button> -->
              <!-- </form> -->
            </div>
            <div class="contentSecDiv" id="itemContainer">
              <?php
              while ($row1 = mysqli_fetch_assoc($result1)){
                echo "<div class=\"conts item\" data-price=\"" . htmlspecialchars($row1['price']) . "\" data-category=\"" . htmlspecialchars($row1['category']) . "\"style=\"text-align:center;\">";
                echo "<p style=\"display:none;\" class=\"productId\">" . htmlspecialchars($row1['productId']) . "</p>";
                echo "<img src=\"../" . htmlspecialchars($row1['pictureName']) . "\" width=\"100%\" height=\"200px\">";
                echo "<h4 style=\"font-size:16px;\" class=\"brand\">" . htmlspecialchars($row1['brand']) . "</h4>";
                echo "<h4 style=\"font-size:16px;\" class=\"model\">" . htmlspecialchars($row1['model']) . "</h4>";
                // echo "<p style=\"font-size:12px;\" class=\"category\">Category: " . htmlspecialchars($row1['category']) . "</p>";
                // echo "<p style=\"font-size:12px;\" class=\"quantity\">Quantity: " . htmlspecialchars($row1['quantity']) . "</p>";
                echo "<h4 style=\"font-size:16px;\" class=\"price\">" . htmlspecialchars($row1['price']) . "ETB</h4>";
                echo "<form action=\"ItemInfoShop.php\" method=\"GET\" style=\"display:inline;\">";
                echo "<input type=\"hidden\" name=\"productId\" value=\"" . htmlspecialchars($row1['productId']) . "\">";
                echo "<button type=\"submit\" id=\"showBtn\">Show</button>";
                // echo "<a href=\"ItemInfo.php?productId=" . htmlspecialchars($row3['productId']) . "\"><button id=\"showBtn\">Show</button></a>";
                echo "</form>";
                echo "</div>";
              }
              ?>
            </div>
          </div>
        </section>

    </div>
    </section>
    </section>

    <footer>
        <div class="footer-content">
          <div class="footer-section about">
            <h3>About Us</h3>
            <p>Welcome to our online shop! We offer a wide range of high-quality products for all your needs. Explore our collection and find the perfect items for you.</p>
            <div class="contact">
            <span><i class="bx bx-phone"></i> +251983311590</span>
            <span><i class="bx bx-envelope"></i> nathavenshop@gmail.com</span>
            </div>
            <div class="social">
                    <a href="#"><i class=' bx bxl-facebook'></i></a>
                    <a href="#"><i class=' bx bxl-twitter'></i></a>
                    <a href="https://www.instagram.com/nat12nahom/" target="_blank"><i class=' bx bxl-instagram'></i></a>
                    <a href="https://t.me/natnahom12" target="_blank"><i class=" bx bxl-telegram"></i></a>
                    <a href="https://www.linkedin.com/in/natnahom-asfaw/" target="_blank"><i class=" bx bxl-linkedin"></i></a>
                </div>
          </div>
          <div class="footer-section links">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="../index.php">Home</a></li>
              <li><a href="../shopViews/shop.php">Shop</a></li>
              <li><a href="../about.html">About</a></li>
              <li><a href="../contact.html">Contact</a></li>
            </ul>
          </div>
          <div class="footer-section contact-form">
            <h3>Contact Us</h3>
            <form>
              <input type="text" name="name" placeholder="Your Name" />
              <input type="email" name="email" placeholder="Your Email" />
              <textarea name="message" placeholder="Your Message"></textarea>
              <button type="submit">Send</button>
            </form>
          </div>
        </div>
        <div class="footer-bottom">
          &copy; 2023 Nathaven. All rights reserved.
        </div>
      </footer>

      <script src="../../../Resources/js/script.js"></script>
      <script src="../../../Resources/js/script3.js"></script>

</body>
</html>