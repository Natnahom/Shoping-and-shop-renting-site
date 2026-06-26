<?php
  session_start();
?>
<?php
    include ('../../database/Database.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      if (isset($_GET['productId'])) {
          $_SESSION['productId'] = $_GET['productId']; // Store productId in session
          $productId = $_SESSION['productId'];
  
          // Now you can use $productId for further logic
      }
      else{
        echo 'ProductID is not set';
      }
    }

    if (isset($_SESSION['usernameCust'])) {
      $usernameCust = $_SESSION['usernameCust'];

      // Now you can use $productId for further logic
    }
    else{
      $usernameCust = null;
    }

    if (!isset($_SESSION['productId'])) {
      echo "<h2 style=\"color:Red; text-align:center;\">Product ID not found. Please choose item.</h2>";
      exit();
    }

    $sql1 = "SELECT * FROM shop WHERE productId = '$productId'";
    $result1 = mysqli_query($conn, $sql1);

    
    if (mysqli_num_rows($result1) > 0){
        $row1 = mysqli_fetch_assoc($result1);
    }
    else {
        echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    }

    $usernameSh = $row1['username'];

    $sql2 = "SELECT * FROM shopkeepers WHERE username = '$usernameSh'";
    $sqlCount1 = "SELECT COUNT(*) AS username_count1 FROM shop WHERE username = '$usernameSh'";


    $result2 = mysqli_query($conn, $sql2);
    $resultCount1 = mysqli_query($conn, $sqlCount1);
  
      if (mysqli_num_rows($result2)){
          $row2 = mysqli_fetch_assoc($result2);
      }
      else{
          echo "Name not found";
      }

      if (mysqli_num_rows($resultCount1) > 0){
        $rowCount1 = mysqli_fetch_assoc($resultCount1);
      }
      else {
        $rowCount1 = 0;
      }

      if (isset($_GET['otherItems'])){
        // $_SESSION['pName'] = $productId;
        // echo "Session variable 'uname' set to: " . $_SESSION['pName'];
        header("Location: OtherItems.php");
        exit();
      }

    if (isset($_GET['addToCart'])){      
      
      if ($usernameCust != null) {
        // Check if the item already exists in the cart
        $checkCartSql = "SELECT * FROM cart WHERE username = '$usernameCust' AND productId = '$productId'";
        $checkResult = mysqli_query($conn, $checkCartSql);
        
        if (mysqli_num_rows($checkResult) > 0) {
            // Item already exists in the cart
            echo "<h3 style=\"color:red; text-align:center; position:relative;\">This item is already in your cart!</h3>";
        } else {
            // Item does not exist, proceed to add it to the cart
            $sqlCart = "INSERT INTO cart (username, productId) VALUES ('$usernameCust', '$productId')";
            
            try {
                // Execute the query
                if (mysqli_query($conn, $sqlCart)) {
                    echo "<h3 style=\"color:green; text-align:center; position:relative;\">Item added to cart successfully!</h3>";
                } else {
                    echo "<h3 style=\"color:red; text-align:center; position:relative;\">Could not add to cart!</h3>";
                }
            } catch (Exception) {
                echo "<h3 style=\"color:red; text-align:center; position:relative;\">Error entering to cart!</h3>";
            }
        }
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

    if (isset($_GET['makeOffer'])){      
      $usernameShopk = $_GET['username'];

      if ($usernameShopk != null) {
        header("Location: ../messages/messages.php?usernameShopk=".$usernameShopk);
        exit();
      }
      else {
        echo "<h3 style=\"color:red; text-align:center; position:relative;\">Couldn't find shopkeepers username, <br>Try some other method to communicate with them!</h3>";
      }
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
  

    // Check if the form is submitted
    if (isset($_POST['revSubmit'])) {
      $usernameCust = $_SESSION['usernameCust'] ?? 'defaultUser'; // Example to get the username
      $rating = $_POST['rating'] ?? null; // Get rating from submitted form
      $review = $_POST['review'] ?? null; // Get review text from submitted form
      $productId = $_POST['productId'] ?? null; // Get productId from submitted form
  
      if ($rating != 0 && !empty(trim($review))) {
          // Insert into the reviews database
          $query = "INSERT INTO reviews (productId, username, rating, review) VALUES (?, ?, ?, ?)";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("ssis", $productId, $usernameCust, $rating, $review);
          $stmt->execute();
  
          // Retain productId in session or redirect to the same page
          $_SESSION['productId'] = $productId; // Ensure productId is stored in session
          header("Location: ItemInfoShop.php?productId=" . urlencode($productId)); // Redirect back with productId
          exit(); // Exit to prevent further code execution
      } else {
          // Store error message in session
          $_SESSION['message'] = 'Rating or review cannot be empty!';
          
          // Retain productId in session or redirect to the same page
          $_SESSION['productId'] = $productId; // Ensure productId is stored in session
          header("Location: ItemInfoShop.php?productId=" . urlencode($productId)); // Redirect back with productId
          echo "<h3 style=\"color:red; text-align:center; position:relative;\">" . $_SESSION['message'] . "</h3>";
          exit(); // Exit to prevent further code execution
      }
  }

  if (isset($_SESSION['message'])) {
      echo "<h3 style=\"color:red; text-align:center; position:relative;\">" . $_SESSION['message'] . "</h3>";
      unset($_SESSION['message']); // Clear the message after displaying it
  }

  // Fetch reviews to display
  $query = "SELECT username, rating, review FROM reviews WHERE productId = '$productId'";
  $result = $conn->query($query);
  $reviews = [];
  $totalRating = 0;
  $reviewCount = 0;
  
  while ($row = $result->fetch_assoc()) {
      $reviews[] = $row;
      $totalRating += $row['rating'];
      $reviewCount++;
  }
  
  // Calculate average rating
  $averageRating = $reviewCount > 0 ? round($totalRating / $reviewCount, 1) : 0; // Round to 1 decimal place

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../../Resources/css/forDashboard.css">
    <link rel="stylesheet" href="../../../Resources/css/forItemInfoShop.css">
    <!-- <link rel="stylesheet" href="../../Resources/css/forIndex.css"> -->
    <link rel="stylesheet" href="../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Item info - Nathaven Shopysite</title>
</head>
<body>

    <section class="main_item_shop">
      <section class="panel_shop">
        <!-- <div id="itemCount" class="item-count">Views: </div> -->
        <h1 style="color: rgb(255, 115, 0);">ABOUT SELLER</h1>
        <div id="itemCount" class="item-count">Total Items: <?php echo $rowCount1['username_count1']?></div>
        <div class="infoSeller">
            <div>
                <h4>Name</h4>
                <p><?php echo $row2['name']?></p>
            </div>
            <div>
                <h4>Email</h4>
                <p><?php echo $row2['email']?></p>
            </div>
            <div>    
                <h4>Phone</h4>
                <p><?php echo $row2['phone']?></p>
            </div>
            <div>
                <h4>Address</h4>
                <p><?php echo $row2['address']?></p>
            </div>
        </div>

        <section class="reviews-section">
          <!-- Average Rating Display -->
    <div class="average-rating">
        <strong>Average Rating: </strong>
        <?php
        // Display stars for average rating
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $averageRating) {
                echo '<span class="star2 colored">&#9733;</span>'; // Filled star
            } else {
                echo '<span class="star2 grey">&#9734;</span>'; // Grey star
            }
        }
        ?>
        <span>(<?php echo $averageRating; ?>)</span>
    </div>
    <h3>Reviews</h3>
    <form id="reviewForm" action="ItemInfoShop.php" method="POST">
        <div class="stars">
            <span class="star" data-value="1">&#9734;</span>
            <span class="star" data-value="2">&#9734;</span>
            <span class="star" data-value="3">&#9734;</span>
            <span class="star" data-value="4">&#9734;</span>
            <span class="star" data-value="5">&#9734;</span>
        </div>
        <input type="hidden" name="rating" id="ratingInput" value="0">
        <textarea name="review" id="reviewText" placeholder="Write your review here..."></textarea>
        <button type="submit" name="revSubmit">Submit Review</button>
        <?php 
          echo "<input type=\"hidden\" name=\"productId\" class=\"inputField\" value=\"" . htmlspecialchars($row1['productId']) . "\">";

        ?>

    </form>
    <br>
    <div id="reviewToggleBtn" style="cursor: pointer; color: blue;">Show Reviews</div>
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
                    <div class="review-item" data-rating="<?php echo $review['rating']; ?>">
                        <div class="review-header">
                        <?php
                          $revUsername = $review['username'];

                          $stmt = $conn->prepare("SELECT name FROM customers WHERE username = ?");
                          $stmt->bind_param("s", $revUsername);
                          $stmt->execute();
                          $resultRev = $stmt->get_result();
                          
                          if ($resultRev && mysqli_num_rows($resultRev) > 0) {
                              $rowRevName = mysqli_fetch_assoc($resultRev);
                              $nameToDisplay = htmlspecialchars($rowRevName['name']);
                          } else {
                              $usernameToDisplay = "defaultUser";
                          }
                        
                        ?>
                        <div class="userNameDiv">
                          <span class="username"><?php echo htmlspecialchars($nameToDisplay); ?></span><br>
                          <span class="username" style="font-size: 10px;"><?php echo htmlspecialchars($revUsername); ?></span>
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
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php if (count($reviews) > 7) : ?>
            <div id="additionalReviews" style="display: none;">
                <?php foreach (array_slice($reviews, 7) as $review) : ?>
                    <div class="review-item"data-rating="<?php echo $review['rating']; ?>">
                        <div class="review-header">
                        <?php
                          $revUsername = $review['username'];

                          $stmt = $conn->prepare("SELECT name FROM customers WHERE username = ?");
                          $stmt->bind_param("s", $revUsername);
                          $stmt->execute();
                          $resultRev = $stmt->get_result();
                          
                          if ($resultRev && mysqli_num_rows($resultRev) > 0) {
                              $rowRevName = mysqli_fetch_assoc($resultRev);
                              $nameToDisplay = htmlspecialchars($rowRevName['name']);
                          } else {
                              $usernameToDisplay = "defaultUser";
                          }
                        
                        ?>
                        <div class="userNameDiv">
                          <span class="username"><?php echo htmlspecialchars($nameToDisplay); ?></span><br>
                          <span class="username" style="font-size: 10px;"><?php echo htmlspecialchars($revUsername); ?></span>
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
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

        <div class="tips">
            <h3>TIPS</h3>
            <p>Here are some important tips for your safety:</p>
        <ul>
            <li>Always meet in a public place, such as a coffee shop or shopping center.</li>
            <li>Consider bringing a friend or family member with you when meeting the seller.</li>
            <li>Trust your instincts—if something feels off, do not proceed with the meeting.</li>
            <li>Do not provide personal information, such as your home address, unless necessary.</li>
            <li>Inspect the item thoroughly before completing the transaction.</li>
            <li>Use cash or secure payment methods; avoid wiring money or sending checks.</li>
            <li>Keep your phone handy; let someone know where you are going and who you are meeting.</li>
        </ul>
        </div>
        <form action="ItemInfoShop.php" method="get">
          <div class="showDiv2"><button type="submit" name="otherItems" id="saveBtn">Other items</button></div>
        </form>
      </section>

    <section class="showInfoSection">
        <div class="showInfoSecDiv">
          
        <!-- <div id="divBars">
          <i class="fa-solid fa-bars" onclick="toggleDropdown()"></i>
        </div>
        <div class="dropdownEdit" style="display: none;">
            <li><a href="dashboard.php"><button id="dashboardBtn">Dashboard</button></a></li>
            <li><button id="editBtn" onclick="showInputField()">Edit item</button></li>
            <li><button id="deleteBtn" onclick="toggleModal()">Delete item</button></li>
        </div> -->
      <form action="ItemInfoShop.php" method="get" enctype="multipart/form-data">    
          <?php
            // while ($row1 = mysqli_fetch_assoc($result1)){
            // if (mysqli_fetch_assoc($result1) > 0){
          try{

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"Date\">Uploaded Date: " . htmlspecialchars($row1['Date']) . "</h4>";
            echo "</div>";

            echo "<div></div>";
            echo "<div></div>";


            echo "<div class=\"showDiv2\">";
              echo "<img src=\"../" . htmlspecialchars($row1['pictureName']) . "\" width=\"255px\" height=\"200px\"><br>";
            //   echo "<input type=\"file\" id=\"image\" name=\"image\" accept=\"image/*\">";              
            echo "</div>";
            echo "<div></div>";


            echo "<div></div>";
            echo "<div></div>";
            // echo "<div class=\"showDiv2\">";
            //   echo "<h4 style=\"font-size:16px;\" class=\"productId\">Product ID: " . htmlspecialchars($row1['productId']) . "</h4>";
            //   echo "<input type=\"hidden\" name=\"productId\" class=\"inputField\" value=\"" . htmlspecialchars($row1['productId']) . "\">";
            // echo "</div>";

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"productName\">Product Name: " . htmlspecialchars($row1['productName']) . "</h4>";
            //   echo "<input type=\"text\" name=\"productName\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['productName']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";            
              echo "<h4 style=\"font-size:16px;\" class=\"brand\">Brand: " . htmlspecialchars($row1['brand']) . "</h4>";
            //   echo "<input type=\"text\" name=\"brand\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['brand']) . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"model\">Model: " . htmlspecialchars($row1['model']) . "</h4>";
            //   echo "<input type=\"text\" name=\"model\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['model']) . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"category\">Category: " . htmlspecialchars($row1['category']) . "</h4>";
        //       echo "<select id=\"category\" name=\"category\" class=\"inputField\" style=\"display:none;\">";
        //     $categories = [
        //       "Electronics", "Clothing", "Home and living", "Sports and Outdoors",
        //       "Beauty and Health", "Books and Media", "Toys and Games",
        //       "Automotive and Vehicles", "Other"
        //     ];
        //   foreach ($categories as $category) {
        //       $selected = ($category === htmlspecialchars($row1['category'])) ? 'selected' : '';
        //       echo "<option value=\"$category\" $selected>$category</option>";
        //   }
        //     echo "</select>";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"quantity\">Quantity: " . htmlspecialchars($row1['quantity']) . "</h4>";
            //   echo "<input type=\"number\" name=\"quantity\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['quantity']) . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
                echo "<h4 style=\"font-size:16px;\">Condition:" . $row1['condition'] . "</h4>";
            //     echo "<select id=\"condition\" name=\"condition\" class=\"inputField\" style=\"display:none;\">";
            //     $conditions = ["New", "Used"];
            // foreach ($conditions as $condition) {
            //     $selected = ($condition === htmlspecialchars($row1['condition'])) ? 'selected' : '';
            //     echo "<option value=\"$condition\" $selected>$condition</option>";
            // }
            // echo "</select>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"color\">Color: " . htmlspecialchars($row1['color']) . "</h4>";
            //   echo "<input type=\"text\" name=\"color\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['color']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"description\">Description: " . htmlspecialchars($row1['description']) . "</h4>";
            //   echo "<textarea id=\"description\" name=\"description\" rows=\"4\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['description']) . "\"></textarea>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"manufactureDate\">Manufactured Date: " . htmlspecialchars($row1['manufactureDate']) . "</h4>";
            //   echo "<input type=\"date\" name=\"manufactureDate\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['manufactureDate']) . "\">";
            echo "</div>";

            echo "<div></div>";

            echo "<div></div>";
            echo "<div></div>";

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"price\">Price: " . htmlspecialchars($row1['price']) . "ETB</h4>";
            //   echo "<input type=\"number\" name=\"price\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row1['price']) . "\">";
            echo "</div>";

            echo "<div></div>";
            echo "<div></div>";
            echo "<div class=\"showDiv2\">
                    <input type=\"hidden\" name=\"productId\" value=\"" . htmlspecialchars($productId) . "\">
                      <button type=\"submit\" name=\"addToCart\" id=\"cartBtn\"><i class=\"bx bx-cart-add\" style=\"float:left; color:white; position:relative; font-size:20px;\"></i>&nbsp;Add to cart</button>
                        <input type=\"hidden\" name=\"username\" value=\"" . htmlspecialchars($row2['username']) . "\">
                        <button type=\"submit\" name=\"makeOffer\" id=\"saveBtn\"><i class=\"bx bx-envelope\" style=\"float:left; color:white; position:relative; font-size:20px;\"></i>&nbsp;Make an offer</button>
                  </div>";
            // echo "<div class=\"showDiv2\"></div>";

          }catch(mysqli_sql_exception){
            echo "Product ID not found!";
          }

            
          ?>
        </form>

          </div>
        </section>
    </section>

    <script>
    let selectedRating = 1;

// Handle star rating selection
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
        selectedRating = this.getAttribute('data-value');
        document.getElementById('ratingInput').value = selectedRating; // Set the hidden input value

        document.querySelectorAll('.star').forEach(star => {
            star.classList.remove('selected');
        });
        for (let i = 0; i < selectedRating; i++) {
            document.querySelectorAll('.star')[i].classList.add('selected');
        }
    });
});


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

        <!-- Confirmation Modal -->
<!-- <div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Are you sure you want to delete this item?</p>
      <form action="ItemInfo.php" method="post">
        <input type="hidden" name="productId" value="">
        <button type="submit" id="confirmYes" name="deleteBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal()">No</button>
      </form>
    </div>
</div> -->
      <script src="../../../Resources/js/script4.js"></script>
    
</body>
</html>