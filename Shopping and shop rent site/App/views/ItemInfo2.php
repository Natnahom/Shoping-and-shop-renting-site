<?php
  session_start();
?>
<?php
    include ('../database/Database.php');

    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['productId'])) {
          $_SESSION['productId'] = $_POST['productId']; // Store productId in session  
          // Now you can use $productId for further logic
      }
      else if (!isset($_POST['productId']) && !isset($_SESSION['productId'])){
        header("Location: ./index.php"); // Redirect to index.php
        exit(); // Stop script execution after redirect
      }
    // }

    $productId = $_SESSION['productId'];

    $sql1 = "SELECT * FROM shop WHERE productId = '$productId'";
    $result1 = mysqli_query($conn, $sql1);

    
    if (mysqli_num_rows($result1) > 0){
        $row1 = mysqli_fetch_assoc($result1);
      }
      else {
        echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>" . mysqli_error($conn);
      }

    if (isset($_POST['deleteBtn'])){
      if (isset($_POST['productId'])) {
        // Fetch the current image path
        $sqlFetch = "SELECT pictureName FROM shop WHERE productId = '$productId'";
        $resultImage = mysqli_query($conn, $sqlFetch);

        if ($resultImage && mysqli_num_rows($resultImage) > 0) {
          $row = mysqli_fetch_assoc($resultImage);
          $imagePath = $row['pictureName']; // Get the image path

          $sql2 = "DELETE FROM shop WHERE productId = '$productId'";
          $sqlDelCart = "DELETE FROM cart WHERE productId = '$productId'";
          $sqlReviews = "DELETE FROM reviews WHERE productId = '$productId'";
      
          if (mysqli_query($conn, $sql2) && mysqli_query($conn, $sqlDelCart) && mysqli_query($conn, $sqlReviews)){
            // Delete the image file from the server
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image
            }
            header("Location: ./confirmation2.php");
            
            exit();
          }
          else {
            echo "Couldn't delete item."; // Show error if deletion fails
          }
        }
      }
        else {
          echo "Product ID is not set.";
        }
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

      // Fetch reviews to display
  $query = "SELECT id, username, rating, review FROM reviews WHERE productId = '$productId'";
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

  if (isset($_GET['otherItems'])){
    // $_SESSION['pName'] = $productId;
    // echo "Session variable 'uname' set to: " . $_SESSION['pName'];
    header("Location: shopViews/OtherItems.php");
    exit();
  }

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

  // mysqli_close($conn);
      
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/css/forDashboard.css">
    <link rel="stylesheet" href="../../Resources/css/forItemInfo.css">
    <!-- <link rel="stylesheet" href="../../Resources/css/forIndex.css"> -->
    <link rel="stylesheet" href="../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../Resources/boxicons-2.1.4/css/boxicons.min.css">    
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Item info - Nathaven Shopysite</title>
</head>
<body>

    <section class="main_item_shop">
      <section class="panel_shop">
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
                  <div class="review-item" data-id="<?php echo $review['id']; ?>" data-rating="<?php echo $review['rating']; ?>">
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
                            <i class="bx bx-trash" style="float:right;" onclick="deleteReview(<?php echo htmlspecialchars($review['id']); ?>)"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

        <form action="ItemInfo2.php" method="get">
          <div class="showDiv2"><button type="submit" name="otherItems" id="saveBtn">Other items</button></div>
        </form>
      </section>

    <section class="showInfoSection">
        <div class="showInfoSecDiv">
          
        <div id="divBars">
          <i class="bx bx-menu" onclick="toggleDropdown()"></i>
        </div>
        <div class="dropdownEdit" style="display: none;">
            <li><a href="dashboardAdmin.php"><button id="dashboardBtn" style="color: white;">Dashboard</button></a></li>
            <li><button id="deleteBtn" onclick="toggleModal()">Delete item</button></li>
        </div>
      <form action="ItemInfo.php" method="POST">    
          <?php
            // while ($row1 = mysqli_fetch_assoc($result1)){
            // if (mysqli_fetch_assoc($result1) > 0){
          try{

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"Date\">Recorded Date: " . htmlspecialchars($row1['Date']) . "</h4>";
            echo "</div>";

            echo "<div></div>";
            echo "<div></div>";


            echo "<div class=\"showDiv2\">";
              echo "<img src=\"" . htmlspecialchars($row1['pictureName']) . "\" width=\"255px\" height=\"200px\"><br>";
            echo "</div>";
            echo "<div></div>";


            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"productId\">Product ID: " . htmlspecialchars($row1['productId']) . "</h4>";
              echo "<input type=\"hidden\" name=\"productId\" class=\"inputField\" value=\"" . htmlspecialchars($row1['productId']) . "\">";
            echo "</div>";

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"productName\">Product Name: " . htmlspecialchars($row1['productName']) . "</h4>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";            
              echo "<h4 style=\"font-size:16px;\" class=\"brand\">Brand: " . htmlspecialchars($row1['brand']) . "</h4>";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"model\">Model: " . htmlspecialchars($row1['model']) . "</h4>";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"category\">Category: " . htmlspecialchars($row1['category']) . "</h4>";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"quantity\">Quantity: " . htmlspecialchars($row1['quantity']) . "</h4>";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
                echo "<h4 style=\"font-size:16px;\">Condition:" . $row1['condition'] . "</h4>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"color\">Color: " . htmlspecialchars($row1['color']) . "</h4>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"description\">Description: " . htmlspecialchars($row1['description']) . "</h4>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"manufactureDate\">Manufactured Date: " . htmlspecialchars($row1['manufactureDate']) . "</h4>";
            echo "</div>";

            echo "<div></div>";

            echo "<div></div>";
            echo "<div></div>";

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"price\">Price: " . htmlspecialchars($row1['price']) . "ETB</h4>";
            echo "</div>";

            echo "<div></div>";
            echo "<div></div>";
          }catch(mysqli_sql_exception){
            echo "Product ID not found!";
          }

            
          ?>
        </form>

          </div>
        </section>

        </section>

        <!-- Confirmation Modal -->
<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Are you sure you want to delete this item?</p>
      <form action="ItemInfo2.php" method="post">
        <input type="hidden" name="productId" value="<?php echo htmlspecialchars($row1['productId']); ?>">
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

      <script src="../../Resources/js/script2.js"></script>
      <script src="../../Resources/js/script4.js"></script>
    
</body>
</html>