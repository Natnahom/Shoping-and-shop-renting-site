<?php
    session_start();
?>
<?php
    include ('../../database/Database.php');

    // if (!isset($_SESSION['productId'])) {
    //     echo "<h2 style=\"color:Red; text-align:center;\">Session not found. Please log in.</h2>";
    //     exit();
    // }

    if (empty($_SESSION['usernameCust'])) { 
      header("Location: ../index.php"); // Redirect to index.php
      exit(); // Stop script execution after redirect
    }

    // $productId = $_SESSION['productId'];
    $usernameCust = $_SESSION['usernameCust'];

    // $sqlUn = "SELECT * FROM shop WHERE productId = '$productId'";
    // $resultUn = mysqli_query($conn, $sqlUn);

    
    // if (mysqli_num_rows($resultUn) > 0){
    //     $rowUn = mysqli_fetch_assoc($resultUn);
    // }
    // else {
    //     echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    // }

    // $usernameSh = $rowUn['username'];


    $sql1 = "SELECT shop.productId, shop.pictureName, shop.brand, shop.model, shop.category, shop.price 
              FROM shop 
              JOIN cart ON shop.productId = cart.productId
              JOIN shopkeepers ON shop.username = shopkeepers.username 
              WHERE shopkeepers.availability = 'On' AND cart.username = '$usernameCust'";

    // $sqlCount1 = "SELECT COUNT(*) AS username_count1 FROM cart WHERE username = '$usernameCust'";

    $sqlCount1 = "
    SELECT COUNT(c.username) AS username_count1 
    FROM cart c
    JOIN shop s ON c.productId = s.productId
    JOIN shopkeepers sk ON s.username = sk.username 
    WHERE sk.availability = 'On' and c.username = '$usernameCust';
    ";

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

    if (isset($_POST['deleteBtn'])) {
        //  $usernameCust = mysqli_real_escape_string($conn, $_POST['username']); // Sanitize input
          $productId = $_POST['productId'];
          
          // Prepare delete statements
          $sqlDelete1 = "DELETE FROM cart WHERE productId = '$productId' AND username = '$usernameCust'";
    
          // Execute both delete statements
          $deletedFromCart = mysqli_query($conn, $sqlDelete1);
    
          // Check if at least one delete was successful
          if ($deletedFromCart) {
              header("Location: " . $_SERVER['PHP_SELF']);
              exit();
          } else {
              echo "Couldn't delete item from cart. No records found."; // Show error if both deletions fail
          }
      }

      if (isset($_POST['goToAllMessages'])){      
      
        if ($usernameCust != null){
          header("Location: ../messages/allMessages.php");
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

      // $usernameSh = $row1['username'];

      // $sql2 = "SELECT * FROM shopkeepers WHERE username = '$usernameSh'";  
  
      // $result2 = mysqli_query($conn, $sql2);
      // $resultCount1 = mysqli_query($conn, $sqlCount1);
    
      // if (mysqli_num_rows($result2)){
      //     $row2 = mysqli_fetch_assoc($result2);
      // }
      // else{
      //     echo "Name not found";
      // }
  
      // if (mysqli_num_rows($resultCount1) > 0){
      //   $rowCount1 = mysqli_fetch_assoc($resultCount1);
      // }
      // else {
      //   $rowCount1 = 0;
      // }
  
        // Fetch reviews to display
    $query = "SELECT id, productId,username, rating, review FROM reviews WHERE username = '$usernameCust'";
    $result = $conn->query($query);
    $reviews = [];
    $totalRating = 0;
    $reviewCount = 0;
    
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
        $totalRating += $row['rating'];
        $reviewCount++;
    }
    
    // if (isset($_GET['otherItems'])){
    //   // $_SESSION['pName'] = $productId;
    //   header("Location: shopViews/OtherItems.php");
    //   exit();
    // }
  
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review_id'])) {
      $reviewId = $_POST['review_id'];
      // $userId = $_SESSION['user_id']; // Assuming you have a session with logged-in user ID
  
      // Prepare and execute the delete statement
      $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
      // AND user_id = ?
      $stmt->bind_param("i", $reviewId);
  
      if ($stmt->execute()) {
          echo "Review deleted successfully.";
      } else {
          http_response_code(500); // Internal Server Error
          echo "Error deleting review: " . $stmt->error;
      }
  
      $stmt->close();
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
    <title>Cart - Nathaven Shopysite</title>
    <style>
      .panel_shop #profile1{
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }
      .panel_shop .bx-user{
        width: 100px;
        height: 100px;
        padding: 3px;
        font-size: 55px;
        border-radius: 50%;
        border: 2px solid rgb(255, 115, 0);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
      }

.modal {
    display: flex; /* Use flexbox to center the modal */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    text-align: center;
}

.close {
    cursor: pointer;
    float: right;
    font-size: 20px;
}

.modal-content form{
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal-content button{
    width: 50px;
    cursor: pointer;
    margin-inline: 10px;
}

.showAllMessages {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    display: flex;
    align-items: center;
    background-color: rgb(255, 115, 0);
    border-radius: 5px;
    overflow: hidden;
    transition: width 0.3s ease;
    width: 55px; /* Initial width to show only the icon */
}

.showAllMessages:hover {
    width: 145px; /* Width on hover to show text */
}

.showAllMessages form {
    display: flex;
    align-items: center;
    height: 100%; /* Full height for button */
}

.showAllMessages button {
    background-color: transparent;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 10px;
    transition: background-color 0.3s ease;
}

.showAllMessages button:hover {
    background-color: #e64a19; /* Slight dark overlay on hover */
}

.showAllMessages h2 {
    margin: 0;
    opacity: 0; /* Hidden by default */
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: translateX(-10px); /* Slide effect */
}

.showAllMessages:hover h2 {
    opacity: 1; /* Show text on hover */
    transform: translateX(0); /* Slide in */
}

.showAllMessages i {
    font-size: 24px;
    color: white; /* Icon color */
    margin-right: 10px; /* Space between icon and text */
}

.item-count {
    font-size: 16px;
    color: #555;
    margin-bottom: 20px;
}

.filter {
    margin-bottom: 20px;
}

#reviewContainer::-webkit-scrollbar {
    width: 8px; /* Width of the scrollbar */
}
#reviewContainer::-webkit-scrollbar-thumb {
    background: rgb(255, 115, 0); /* Color of the scrollbar thumb */
    border-radius: 10px; /* Rounded corners of the scrollbar thumb */
}
#reviewContainer::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1); /* Color of the scrollbar track */
    border-radius: 10px; /* Rounded corners of the scrollbar track */
}

.reviews-section {
    margin: 20px 0;
}

.average-rating {
    margin-bottom: 20px;
    font-size: 18px; /* Adjust size as needed */
}

#reviewContainer {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
}

.review-item {
    margin-bottom: 15px;
    max-width: 500px; /* Set max width for review items */
    overflow: hidden; /* Prevent overflow */
    text-overflow: ellipsis; /* Show ellipsis for overflowed text */
    white-space: normal; /* Allow text to wrap */
    border-bottom: 1px solid black;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.star-rating {
    display: flex;
}

.star2 {
    font-size: 20px; /* Adjust size as needed */
}

.userNameDiv{
    text-align: left;
}

.username {
    color: gray; /* Optional: style for the username */
}

.review-text {
    margin-top: 5px; /* Adds space between rating and review text */
    overflow-wrap: break-word; /* Break long words if needed */
    word-wrap: break-word; /* For compatibility */
    word-break: break-word; /* For better handling of long text */
    line-height: 1.5; /* Improve readability */
    font-family: 'Lato', sans-serif;
}

#reviewsList {
    max-width: 100%; /* Ensure it doesn't exceed its parent width */
    overflow-x: hidden; /* Hide any horizontal overflow */
}

.star2.colored {
    color: gold; /* Color for filled stars */
}

.star2.grey {
    color: lightgray; /* Color for unfilled stars */
}

@media (max-width: 750px) {

  .review-item{
          max-width: 100%;
  }
}

    </style>
</head>
<body>

    <section class="main_shop">

      <section class="panel_shop">

        <div id="profile1">
          <a href="./customerProf.php"><i class="bx bx-user" title="Edit Your Profile"></i></a>
          <br>
          <h3><?php echo $usernameCust; ?></h3>
        </div><br>

        <h1 style="color: rgb(255, 115, 0);">CART</h1>
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

        <section class="reviews-section">
    <h3>Reviews</h3>
    <br>
    <div id="reviewToggleBtn" style="cursor: pointer; color: blue;">Show what you have Reviewed</div>
    <div id="reviewContainer" style="display: none; max-height: 300px; overflow-y: auto;">
        <h3>All Reviews:</h3>
        <?php echo "Total: " . count($reviews)?>
        <!-- Dropdown for filtering reviews -->
    <select id="ratingFilter" onchange="filterReviews()">
        <option value="All">All</option>
        <option value="5">5 Stars</option>
        <option value="4">4 Stars</option>
        <option value="3">3 Stars</option>
        <option value="2">2 Stars</option>
        <option value="1">1 Star</option>
    </select>

        <div id="reviewsList">
            <?php

            // Display only the first 7 reviews initially
            $displayedReviews = array_slice($reviews, 0, 7);
            if (empty($displayedReviews)) : ?>
                <p>No reviews available.</p>
            <?php else : ?>
                <?php foreach ($displayedReviews as $review) : ?>
                  <div class="review-item" data-id="<?php echo $review['id']; ?>" data-rating="<?php echo $review['rating']; ?>">
                        <div class="review-header">
                        <?php
                          $revProdId = $review['productId'];

                          $stmt = $conn->prepare("SELECT username, productName FROM shop WHERE productId = ?");
                          $stmt->bind_param("s", $revProdId);
                          $stmt->execute();
                          $resultRev = $stmt->get_result();
                          
                          if ($resultRev && mysqli_num_rows($resultRev) > 0) {
                              $rowRevUserName = mysqli_fetch_assoc($resultRev);
                              $usernameToDisplay = htmlspecialchars($rowRevUserName['username']);
                              $productNameToDisplay = htmlspecialchars($rowRevUserName['productName']);
                          } else {
                              $usernameToDisplay = "defaultUser";
                          }

                          $stmt2 = $conn->prepare("SELECT name FROM shopkeepers WHERE username = ?");
                          $stmt2->bind_param("s", $usernameToDisplay);
                          $stmt2->execute();
                          $resultRev2 = $stmt2->get_result();
                          
                          if ($resultRev2 && mysqli_num_rows($resultRev2) > 0) {
                              $rowRevName = mysqli_fetch_assoc($resultRev2);
                              $nameToDisplay = htmlspecialchars($rowRevName['name']);
                          } else {
                              $nameToDisplay = "defaultUser";
                          }
                        
                        ?>
                        <div class="userNameDiv">
                          <span class="username"><?php echo htmlspecialchars($nameToDisplay); ?></span><br>
                          <span class="username" style="font-size: 10px;"><?php echo htmlspecialchars($productNameToDisplay); ?></span>
                        </div>
                            <div class="star-rating">
                                <?php 
                                // Display colored stars based on rating
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $review['rating']) {
                                        echo '<span class="star2 colored">&#9733;</span>'; // Colored star
                                    } else {
                                        echo '<span class="star2 grey">&#9734;</span>'; // Grey star
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="review-text">
                            <?php echo htmlspecialchars($review['review']); ?>
                            <i class="bx bx-trash" style="float:right;" onclick="deleteReview(<?php echo htmlspecialchars($review['id']); ?>)"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php if (count($reviews) > 7) : ?>
            <div id="additionalReviews" style="display: none;">
                <?php foreach (array_slice($reviews, 7) as $review) : ?>
                  <div class="review-item" data-id="<?php echo $review['id']; ?>" data-rating="<?php echo $review['rating']; ?>">
                        <div class="review-header">
                        <?php
                          $revProdId = $review['productId'];

                          $stmt = $conn->prepare("SELECT username, productName FROM shop WHERE productId = ?");
                          $stmt->bind_param("s", $revProdId);
                          $stmt->execute();
                          $resultRev = $stmt->get_result();
                          
                          if ($resultRev && mysqli_num_rows($resultRev) > 0) {
                              $rowRevUserName = mysqli_fetch_assoc($resultRev);
                              $usernameToDisplay = htmlspecialchars($rowRevUserName['username']);
                              $productNameToDisplay = htmlspecialchars($rowRevUserName['productName']);
                          } else {
                              $usernameToDisplay = "defaultUser";
                          }

                          $stmt2 = $conn->prepare("SELECT name FROM shopkeepers WHERE username = ?");
                          $stmt2->bind_param("s", $usernameToDisplay);
                          $stmt2->execute();
                          $resultRev2 = $stmt2->get_result();
                          
                          if ($resultRev2 && mysqli_num_rows($resultRev2) > 0) {
                              $rowRevName = mysqli_fetch_assoc($resultRev2);
                              $nameToDisplay = htmlspecialchars($rowRevName['name']);
                          } else {
                              $nameToDisplay = "defaultUser";
                          }
                        
                        ?>
                        <div class="userNameDiv">
                          <span class="username"><?php echo htmlspecialchars($nameToDisplay); ?></span><br>
                          <span class="username" style="font-size: 10px;"><?php echo htmlspecialchars($productNameToDisplay); ?></span>
                        </div>
                            <div class="star-rating">
                                <?php 
                                // Display colored stars based on rating
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $review['rating']) {
                                        echo '<span class="star2 colored">&#9733;</span>'; // Colored star
                                    } else {
                                        echo '<span class="star2 grey">&#9734;</span>'; // Grey star
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="review-text">
                            <?php echo htmlspecialchars($review['review']); ?>
                            <i class="bx bx-trash" style="float:right;" onclick="deleteReview(<?php echo htmlspecialchars($review['id']); ?>)"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
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
                echo "<button id=\"deleteBtn\" onclick=\"toggleModal3('" . htmlspecialchars($row1['productId']) . "')\">Delete</button>";
                echo "</div>";
              }
              ?>
            </div>
          </div>
        </section>

    </div>
    </section>
    </section>

    <div class="showAllMessages">
          <form action="cart.php" method="post">
            <button type="submit" name="goToAllMessages">
              <i class="bx bx-envelope" style="font-size: 35px;"></i>  
              <h2>Messages</h2>
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

      <div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Are you sure you want to delete this item?</p>
      <form action="cart.php" method="post" id="deleteForm">
        <input type="hidden" name="productId" id="modalProductId">
        <button type="submit" id="confirmYes" name="deleteBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal()">No</button>
      </form>
    </div>
    </div>
  
<script>
//Function to delete reviews
function deleteReview(reviewId) {
    if (confirm("Are you sure you want to delete this review?")) {
        // Send DELETE request using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "ItemInfo2.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Successfully deleted, remove the review from the UI
                    var reviewItem = document.querySelector('.review-item[data-id="' + reviewId + '"]');
                    if (reviewItem) {
                        reviewItem.remove();
                    }
                } else {
                    alert("Error deleting review: " + xhr.responseText);
                }
            }
        };

        xhr.send("review_id=" + reviewId);
    }
}
</script>

<script>
// For the show reviews button
document.getElementById('reviewToggleBtn').addEventListener('click', function() {
    const reviewContainer = document.getElementById('reviewContainer');
    reviewContainer.style.display = reviewContainer.style.display === 'none' ? 'block' : 'none';
    if (reviewContainer.style.display === 'block') {
        const additionalReviews = document.getElementById('additionalReviews');
        additionalReviews.style.display = 'block'; // Show additional reviews when toggled
    }
});
</script>

      <script src="../../../Resources/js/script2.js"></script>
      <script src="../../../Resources/js/script3.js"></script>
      <script src="../../../Resources/js/script4.js"></script>

</body>
</html>