<?php
    session_start();
?>

<?php
    include ('../../database/Database.php');

      $sqlCheckUser = "SELECT COUNT(*) as count FROM customers WHERE username = '" . mysqli_real_escape_string($conn, $_SESSION['usernameCust'] ?? '') . "'";
      $resultCheckUser = mysqli_query($conn, $sqlCheckUser);
      $row = mysqli_fetch_assoc($resultCheckUser);

      // Check if the session variable is set and if the count is greater than 0
      if (isset($_SESSION['usernameCust']) && $row['count'] > 0) {
        $usernameCust = $_SESSION['usernameCust'];
      } else {
        $usernameCust = null;
      }

    $sql1 = "SELECT shop.productId, shop.pictureName, shop.productName,shop.brand, shop.model, shop.category, shop.price 
              FROM shop 
              JOIN shopkeepers ON shop.username = shopkeepers.username 
              WHERE shopkeepers.availability = 'On'";

    $sqlBlog = "SELECT * FROM blog";
    // $sqlCount1 = "SELECT COUNT(*) AS username_count1 FROM shop";
    $sqlCount1 = "
    SELECT COUNT(s.username) AS username_count1 
    FROM shop s
    JOIN shopkeepers sk ON s.username = sk.username 
    WHERE sk.availability = 'On';
    ";

    $result1 = mysqli_query($conn, $sql1);
    $resultBlog = mysqli_query($conn, $sqlBlog);
    $resultCount1 = mysqli_query($conn, $sqlCount1);

    if (mysqli_num_rows($resultBlog) > 0){
      $rowBlog = mysqli_fetch_assoc($resultBlog);
    }
    else {
      $rowBlog = "Blog";
    }

    if (mysqli_num_rows($resultCount1) > 0){
      $rowCount1 = mysqli_fetch_assoc($resultCount1);
    }
    else {
      $rowCount1 = 0;
    }
    

    if (isset($_GET['goToCart'])){      
      
      if ($usernameCust != null){
        header("Location: cart.php");
        exit();
      }
      else if ($usernameCust == null){
        echo '
        <div id="overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 3000; display: flex; justify-content: center; align-items: center;">
            <div style="background-color: white; border: 1px solid #ccc; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); position: relative; text-align: center;">
                <button id="closeBtn" style="width:fit-content; position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 30px; cursor: pointer;">&times;</button><br><br>
                <h3 style="color:red;">You are not logged in!</h3><br><br>
                <a href="../../auth/login.php" class="modal-link">Log In</a>
                <a href="../../auth/register.php" class="modal-link">Register</a>
            </div>
        </div>
      <style>
          /* Styles for the links */
        .modal-link {
            text-decoration: none; 
            margin: 10px; 
            padding: 10px 20px; 
            background-color: rgb(255, 115, 0); 
            color: white; 
            border-radius: 5px;
            transition: color 0.5s; 
        }
        
        /* Hover effect for links */
        .modal-link:hover {
            color: black; 
            transition: color 0.5s;
        }
          </style>
        <script>
            document.getElementById("closeBtn").onclick = function() {
            // Refresh the page to reset it to normal state
            window.history.back();
        };
      </script>';
      }
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
    <style>

      #overlay a:hover {
        background-color:#e64a19;
      }
    </style>
</head>
<body>

<section class="home">
        <nav class="home-nav">
            <a href="../index.php"><img src="../../../Resources/images/logos/logo1.png" alt="logo"></a>
            
            <ul class="dropdown-content">
                <li><a href="../index.php">Home</a></li>
                <li><a href="../about.html">About us</a></li>
                <li><a href="../contact.html">Contact us</a></li>
                <li><a href="../../auth/login.php"><button>Log in</button></a></li>
                <li><a href="../../auth/register.php"><button>Register</button></a></li>
            </ul>

            <div class="toggle_btn">
                <i class="bx bx-menu"></i>
                <!-- <i class="fa-solid fa-x"></i> -->
            </div>
                
        </nav>

        <ul class="dropdown-menu">
                <a href="../index.php"><li class="links">Home</li></a>
                <a href="../about.html"><li class="links">About us</li></a>
                <a href="../contact.html"><li class="links">Contact us</li></a>
                <li><a href="../../auth/login.php"><button>Log in</button></a></li>
                <li><a href="../../auth/register.php"><button>Register</button></a></li>
        </ul>

      <div class="blog" style="text-align: center;">
        <?php
          echo $rowBlog['ShopBlog'];
        ?>
      </div>
        
    </section>

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
        <div id="searchCont">
            <input type="text" id="search" name="search" placeholder="Search..." onkeyup="searchItems()">
        </div>
        <div class="contentSecDiv" id="itemContainer">
            <?php
            while ($row1 = mysqli_fetch_assoc($result1)){
                echo "<div class=\"conts item\" data-price=\"" . htmlspecialchars($row1['price']) . "\" data-category=\"" . htmlspecialchars($row1['category']) . "\" style=\"text-align:center; display:none;\">"; // Initially hide items
                echo "<p style=\"display:none;\" class=\"productId\">" . htmlspecialchars($row1['productId']) . "</p>";
                echo "<img src=\"../" . htmlspecialchars($row1['pictureName']) . "\" width=\"100%\" height=\"200px\">";
                echo "<h4 style=\"display:none;\" class=\"name\">" . htmlspecialchars($row1['productName']) . "</h4>";
                echo "<h4 style=\"font-size:16px;\" class=\"brand\">" . htmlspecialchars($row1['brand']) . "</h4>";
                echo "<h4 style=\"font-size:16px;\" class=\"model\">" . htmlspecialchars($row1['model']) . "</h4>";
                echo "<h4 style=\"font-size:16px;\" class=\"price\">" . htmlspecialchars($row1['price']) . " ETB</h4>";
                echo "<form action=\"ItemInfoShop.php\" method=\"GET\" style=\"display:inline;\">";
                echo "<input type=\"hidden\" name=\"productId\" value=\"" . htmlspecialchars($row1['productId']) . "\">";
                echo "<button type=\"submit\" id=\"showBtn\">Show</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>

      <div id="paginationControls" class="pagination-container">
          <button id="prevBtn" onclick="showPage(currentPage - 1)" disabled>Previous</button>
          <!-- Page number buttons will be inserted here -->
          <button id="nextBtn" onclick="showPage(currentPage + 1)">Next</button>
      </div>
        
    </div>
    
</section>

    </section>
    
    </section>

    
          
    

    <div class="showCart">
          <form action="shop.php" method="get">
            <button type="submit" name="goToCart">
              <i class="bx bx-cart" style="font-size: 35px;"></i>  
              <h2>Show <br> Cart</h2>
            </button>
          </form>
    </div>

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

      <script>
    const itemsPerPage = 15;
    let currentPage = 1;
    const items = document.querySelectorAll('#itemContainer .item');
    const totalPages = Math.ceil(items.length / itemsPerPage);
    const visibleButtons = 3; // Number of buttons to display at once

    function showPage(page) {
        if (page < 1 || page > totalPages) return; // Prevent out-of-bounds page numbers
        currentPage = page;

        items.forEach((item, index) => {
            item.style.display = (index >= (currentPage - 1) * itemsPerPage && index < currentPage * itemsPerPage) ? 'block' : 'none';
        });

        updatePaginationControls();
    }

    function updatePaginationControls() {
        const paginationControls = document.getElementById('paginationControls');
        paginationControls.innerHTML = ''; // Clear existing buttons

        const prevBtn = document.createElement('button');
        prevBtn.innerText = 'Previous';
        prevBtn.onclick = () => showPage(currentPage - 1);
        prevBtn.disabled = (currentPage === 1);
        paginationControls.appendChild(prevBtn);

        // Calculate the start and end page numbers to display
        let startPage = Math.max(1, currentPage - Math.floor(visibleButtons / 2));
        let endPage = Math.min(totalPages, startPage + visibleButtons - 1);

        // Adjust startPage if it goes below 1
        if (startPage < 1) {
            endPage = Math.min(totalPages, endPage + (1 - startPage));
            startPage = 1;
        }

        // Adjust endPage if it exceeds totalPages
        if (endPage > totalPages) {
            startPage = Math.max(1, startPage - (endPage - totalPages));
            endPage = totalPages;
        }

        for (let page = startPage; page <= endPage; page++) {
            const button = document.createElement('button');
            button.innerText = page;
            button.disabled = (page === currentPage);
            button.onclick = () => showPage(page);
            paginationControls.appendChild(button);
        }

        const nextBtn = document.createElement('button');
        nextBtn.innerText = 'Next';
        nextBtn.onclick = () => showPage(currentPage + 1);
        nextBtn.disabled = (currentPage === totalPages);
        paginationControls.appendChild(nextBtn);
    }

    // Initialize the first page
    showPage(currentPage);
</script>

      <script src="../../../Resources/js/script.js"></script>
      <script src="../../../Resources/js/script3.js"></script>

</body>
</html>