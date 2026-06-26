<?php
    session_start();
?>
<?php

    include ('../database/Database.php');

    if (empty($_SESSION['uname'])) { 
      header("Location: ./index.php"); // Redirect to index.php
      exit(); // Stop script execution after redirect
    }
    
    $unameS = $_SESSION['uname'];
    // $passS = $_SESSION['pass'];

    $sqlA = "SELECT * FROM admin WHERE username = '$unameS'";
    $sql1 = "SELECT * FROM shopkeepers";
    $sqlShop = "SELECT * FROM shop";
    $sqlCust = "SELECT * FROM customers";
    $sql3 = "SELECT * FROM shop WHERE username = '$unameS'";
    $sqlCount1 = "SELECT COUNT(*) AS username_count1 FROM shopkeepers";
    $sqlCount2 = "SELECT COUNT(*) AS username_count2 FROM shop";
    $sqlCount3 = "SELECT COUNT(*) AS username_count3 FROM customers";

    $resultA = mysqli_query($conn, $sqlA);
    $result1 = mysqli_query($conn, $sql1);
    $resultShop = mysqli_query($conn, $sqlShop);
    $resultShopk = mysqli_query($conn, $sql1);
    $resultCust = mysqli_query($conn, $sqlCust);
    $resultCheck = mysqli_query($conn, $sql1);
    $result3 = mysqli_query($conn, $sql3);
    // $result4 = mysqli_query($conn, $sql4);
    $resultCount1 = mysqli_query($conn, $sqlCount1);
    $resultCount2 = mysqli_query($conn, $sqlCount2);
    $resultCount3 = mysqli_query($conn, $sqlCount3);

    if (mysqli_num_rows($resultA) > 0){
      $rowA = mysqli_fetch_assoc($resultA);
    }
    else {
      echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    }

    if (mysqli_num_rows($resultShop) > 0){
      $rowShop = mysqli_fetch_assoc($resultShop);
    }
    else {
      echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    }

    // if (mysqli_num_rows($resultShopk) > 0){
    //   $rowShopk = mysqli_fetch_assoc($resultShopk);
    // }
    // else {
    //   echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    // }

    // if (mysqli_num_rows($resultCust) > 0){
    //   $rowCust = mysqli_fetch_assoc($resultCust);
    // }
    // else {
    //   echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    // }

    $userPrices = [];
    if (mysqli_num_rows($result1) > 0){
      while ($row = mysqli_fetch_assoc($result1)){
        $userPrices[] = $row['price'];
      }
    }
    else {
      echo "<h2 style=\"color:Red; text-align:center;\">No prices found</h2>";
    }

    // if (mysqli_num_rows($result3) > 0){
    //   $row3 = mysqli_fetch_assoc($result3);
    // }
    // else {
    //   echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    // }

    if (mysqli_num_rows($resultCount1) > 0 || mysqli_num_rows($resultCount2) > 0 || mysqli_num_rows($resultCount3) > 0){
      $rowCount1 = mysqli_fetch_assoc($resultCount1);
      $rowCount2 = mysqli_fetch_assoc($resultCount2);
      $rowCount3 = mysqli_fetch_assoc($resultCount3);
    }
    else {
      $rowCount1 = 0;
      $rowCount2 = 0;
      $rowCount3 = 0;
    }
    
  if (isset($_POST['submit'])){
    $name = $_POST['name'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pass = $_POST['newPass'];
    $cPass = $_POST['confPass'];
    $type = $_POST['type'];
    $rentPrice = 0;

    if (isset($_POST['typeOfShop'])) {
        $typeOfShop = $_POST['typeOfShop'];
    } else {
        $typeOfShop = '';
    }

    if (mysqli_num_rows($resultCheck) > 0) {
      $rowCheck = mysqli_fetch_assoc($resultCheck);
    } else {
      $rowCheck = '';
    }

    $name = preg_replace('/\d/', '', $name);
    $phone = preg_replace('/[a-zA-Z]+/', '', $phone);

    if ($uname != $rowCheck['username']){
      if ($type == "Shopkeeper") {
    
            if (checkPassword($pass, $cPass) == true){
                $hashP = password_hash($pass, PASSWORD_DEFAULT);

                $sql1 = "INSERT INTO shopkeepers (name,username,email,phone,password,address,type,price,typeOfShop) VALUES ('$name','$uname','$email','$phone','$hashP','$address','$type','$rentPrice','$typeOfShop')";

                if (mysqli_query($conn, $sql1)){
                    echo "<h3 style = \"color:green; text-align:center;\">Registered successfully!</h3>";
                    header("Location: " . $_SERVER['PHP_SELF']);
                    // session_destroy();
                }
                else {
                    echo "<h3 style = \"color:red; text-align:center;\">Could not insert user! Try changing your username.</h3>";
                }
            }
            else{
                echo "<h2 style=\"color:red; text-align:center;\">Passwords don't match!</h2>";
            }

      }
      else {
        echo "<h2 style=\"color:red; text-align:center\">Type not chosen</h2>";
      }
    }
    else {
      echo "<h3 style = \"color:red; text-align:center;\">This username is not available!</h3>";
    }   
}
  
if (isset($_POST['save'])) {
  $updates = [];

  // Check for new username
  if (!empty($_POST['uname'])) {
    $newUsername = mysqli_real_escape_string($conn, $_POST['uname']);
    
    // Check if the new username already exists
    $usernameCheckSql = "SELECT * FROM customers WHERE username = '$newUsername'";
    $usernameCheckResult = mysqli_query($conn, $usernameCheckSql);
    
    if (mysqli_num_rows($usernameCheckResult) > 0) {
        echo "<h3 style=\"color:red; text-align:center;\">Username already exists!</h3>";
    } else {
        $updates[] = "username='$newUsername'";
    }
  }

  if (!empty($_POST['name'])) {
    $name = $_POST['name'];
    $name = preg_replace('/\d/', '', $name);
    $updates[] = "`name`='" . mysqli_real_escape_string($conn, $name) . "'";
  }

  if (!empty($_POST['email'])) {
      $updates[] = "email='" . mysqli_real_escape_string($conn, $_POST['email']) . "'";
  }
  if (!empty($_POST['phone'])) {
      $phone = $_POST['phone'];
      $phone = preg_replace('/[a-zA-Z]+/', '', $phone);
      $updates[] = "phone='" . mysqli_real_escape_string($conn, $phone) . "'";
  }
  if (!empty($_POST['oldPassword']) && !empty($_POST['newPassword'])) {
      // Password update logic
      $oldPass = $rowA['password'];
      if (password_verify($_POST['oldPassword'], $oldPass)) {
        if ($_POST['newPassword'] == $_POST['confirmPassword']) {
          $hash = password_hash($_POST['newPassword'],PASSWORD_DEFAULT);
          $updates[] = "password='" . mysqli_real_escape_string($conn, $hash) . "'";
        }
      }
  }
  if (!empty($_POST['SmallPrice'])) {
      $updates[] = "SmallPrice='" . mysqli_real_escape_string($conn, $_POST['SmallPrice']) . "'";
  }
  if (!empty($_POST['IntermediatePrice'])) {
    $updates[] = "IntermediatePrice='" . mysqli_real_escape_string($conn, $_POST['IntermediatePrice']) . "'";
  }
  if (!empty($_POST['BigPrice'])) {
    $updates[] = "BigPrice='" . mysqli_real_escape_string($conn, $_POST['BigPrice']) . "'";
  }

  if (!empty($_POST['SmallSlots'])) {
    $updates[] = "SmallSlots='" . mysqli_real_escape_string($conn, $_POST['SmallSlots']) . "'";
  }
  if (!empty($_POST['IntermediateSlots'])) {
    $updates[] = "IntermediateSlots='" . mysqli_real_escape_string($conn, $_POST['IntermediateSlots']) . "'";
  }
  if (!empty($_POST['BigSlots'])) {
    $updates[] = "BigSlots='" . mysqli_real_escape_string($conn, $_POST['BigSlots']) . "'";
  }

  // Only proceed if there are updates
  if (count($updates) > 0) {
      $sql = "UPDATE admin SET " . implode(", ", $updates) . " WHERE username='" . mysqli_real_escape_string($conn, $_POST['username']) . "'";

      // Execute the query
      if (mysqli_query($conn, $sql)) {
        if (isset($newUsername)) {
          $_SESSION['uname'] = $newUsername; // Update session variable
        }
          echo "<h3 style=\"color:green; text-align:center;\">Record updated successfully.</h3>";
          header("Location: " . $_SERVER['PHP_SELF']);
      } else {
          echo "Error updating record: " . mysqli_error($conn);
      }
  }
}

if (isset($_POST['deleteBtn'])) {
  if (isset($_POST['usernameShopk']) || isset($_POST['usernameCust'])) {
      $usernameShopk = $_POST['usernameShopk'] ?? null; // Use null if not set
      $usernameCust = $_POST['usernameCust'] ?? null; // Use null if not set

      // Prepare delete statements
      if ($usernameShopk) {
          // Delete from shop and shopkeepers
          $sqlDeleteShop = "DELETE FROM shop WHERE username = '$usernameShopk'";
          $sqlDeleteShopkeepers = "DELETE FROM shopkeepers WHERE username = '$usernameShopk'";
          $sqlDeleteCart = "DELETE FROM cart WHERE productId IN (SELECT productId FROM shop WHERE username = '$usernameShopk')";
          $sqlDeleteMessages = "DELETE FROM messages WHERE usernameShopk = '$usernameShopk'";

          // Execute delete statements for shopkeeper
          mysqli_query($conn, $sqlDeleteCart);
          mysqli_query($conn, $sqlDeleteShop);
          mysqli_query($conn, $sqlDeleteShopkeepers);
          mysqli_query($conn, $sqlDeleteMessages);
      }

      if ($usernameCust) {
          // Delete from customers and cart
          $sqlDeleteCustomer = "DELETE FROM customers WHERE username = '$usernameCust'";
          $sqlDeleteCartCust = "DELETE FROM cart WHERE username = '$usernameCust'"; // Assuming the cart has a username
          $sqlDeleteMessagesCust = "DELETE FROM messages WHERE usernameCust = '$usernameCust'";

          // Execute delete statements for customer
          mysqli_query($conn, $sqlDeleteCartCust);
          mysqli_query($conn, $sqlDeleteCustomer);
          mysqli_query($conn, $sqlDeleteMessagesCust);
      }

      echo "<h3 style=\"color:red; text-align:center;\">Username is not set.</h3>";
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;      
  } else {
      echo "<h3 style=\"color:red; text-align:center;\">Username is not set.</h3>";
  }
}

// Assume a database connection is already established
$id = 1; // The ID of the blog entry you want to update

// Fetch the current blog data
$sqlFetch = "SELECT HomeBlog, ShopBlog, RentBlog FROM blog WHERE id = ?";
$stmtFetch = $conn->prepare($sqlFetch);
$stmtFetch->bind_param("i", $id);
$stmtFetch->execute();
$result = $stmtFetch->get_result();

if ($result->num_rows > 0) {
    $rowBlog = $result->fetch_assoc();
} else {
    echo "Blog not found!";
    exit;
}

// Check if the form is submitted
    if (isset($_POST['saveBlog'])) {
      // Prepare the update statement
      $sqlBlog = "UPDATE blog SET HomeBlog = COALESCE(NULLIF(?, ''), HomeBlog), 
                                      ShopBlog = COALESCE(NULLIF(?, ''), ShopBlog), 
                                      RentBlog = COALESCE(NULLIF(?, ''), RentBlog) 
                 WHERE id = ?";

          $homeBlog = $_POST['HomeBlog'] ?? '';
          $shopBlog = $_POST['ShopBlog'] ?? '';
          $rentBlog = $_POST['RentBlog'] ?? '';

          $stmtUpdate = $conn->prepare($sqlBlog);
          $stmtUpdate->bind_param("sssi", $homeBlog, $shopBlog, $rentBlog, $id);

            try {
              $stmtUpdate->execute();
              header("Location: " . $_SERVER['PHP_SELF']);
            } catch (mysqli_sql_exception) {
                echo "<h3 style=\"color:red; text-align:center; z-index:3000;\">Error updating blog!</h3>";
            }
          }

          if (isset($_POST['logOutBtn'])) {
            session_destroy();
            header("Location: ./index.php");
          }

  function uploadImage(){
    // Image upload code
    if (isset($_FILES["image"])) {
      $targetDirectory = "../../Resources/storage/"; // Use an absolute path if needed
      // $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
      $targetFile = $targetDirectory . uniqid() . '_' . basename($_FILES["image"]["name"]);
      // $uniqueFileName = uniqid() . '_' . basename($_FILES["image"]["name"]);
      $uploadOk = true;
      $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

      // Check if the uploaded file is an image
      $check = getimagesize($_FILES["image"]["tmp_name"]);
      if ($check === false) {
          echo "Error: Please upload a valid image file.";
          $uploadOk = false;
      }

      // Check if the file already exists
      if (file_exists($targetFile)) {
          echo "Error: File already exists. Change the image name and try again.";
          $uploadOk = false;
      }

      // Allow only specific image file formats (you can modify this array as per your requirements)
      $allowedFormats = array("jpg", "jpeg", "png", "gif");
      if (!in_array($imageFileType, $allowedFormats)) {
          echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
          $uploadOk = false;
      }

      if ($uploadOk) {
          if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
              echo "Image uploaded successfully.";
              return $targetFile;
          } else {
              echo "<h2 style=\"color:Red; text-align:center;\">Error: There was a problem uploading the image.</h2>";
              return false;
          }
      }
    }
    else{
      echo "<h2 style=\"color:Red; text-align:center;\">You did not upload an image!</h2>";
      return false;
    }
  }

  function daysPassed($otherDate) {
    // Get the current date
    $currentDate = new DateTime();
    
    // Convert the other date to DateTime object
    $otherDateTime = new DateTime($otherDate);
    
    // Calculate the target date (30 days from the update date)
    $targetDateTime = clone $otherDateTime;
    $targetDateTime->modify('+30 days');

    // Calculate the difference
    $interval = $currentDate->diff($targetDateTime);
    
    // Return the number of days remaining
    return max(0, $interval->days); // Ensure it doesn't return negative
  }

  // function countUsers($rowCount1,$rowCount3){
  //   $num = $rowCount1 + $rowCount3;
  //   $count=0;
  //   for ($i = 0; $i < count($num); $i++) {
  //     $count++;
  //   }
  // }

  function addUserPrices($userPrices){
    $count = 0; // Initialize count to zero

    // Sum all prices
    foreach ($userPrices as $price) {
        $count += (float)$price; // Add each price to the count
    }

    return $count; // Return the total

  }

  function checkPassword($firstPass, $confirmPass){
    if ($firstPass == $confirmPass){
        return true;
    }
    else{
        return false;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/css/forDashboardAdmin.css">
    <!-- <link rel="stylesheet" href="../../Resources/css/forIndex.css"> -->
    <link rel="stylesheet" href="../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Admin Dashboard - Nathaven Shopysite</title>
</head>

<body>
    <!-- <h1 style="text-align: center;">SHOP DASHBOARD</h1> -->

        <!-- <img src="../../Resources/images/imagesRentShop.jpg" alt="background" class="background_img"> -->
        
        

    <section class="main_page">
    <div class="container" id="container">
    <button class="open-menu-btn" onclick="togglePanel()"><h2>Menu</h2></button>
    
        <div class="panel" id="panel">
            <nav class="home-nav">
              <a href="index.php"><img src="../../Resources/images/logos/IClogo1.png" alt="logo"></a>
              <?php
                echo "<a href=\"dashboardAdmin.php\" style=\"text-decoration:none;\">" . "<h2 style=\"color:rgb(0, 100, 0);\">" . $rowA['name'] . "</h2>" . "</a>"; 
              ?>
            </nav>
            
             <br><br><br>
            <button onclick="changeContent('div1')"><i class=' bx bx-home'></i>Dashboard</button>
            <button onclick="changeContent('div2')"><i class="bx bx-user-plus"></i>Add User</button>
            <button onclick="changeContent('div3')"><i class="bx bx-group"></i>All Users</button>
            <button onclick="changeContent('div4')"><i class="bx bx-list-ul"></i>All Items</button>
            <button onclick="changeContent('div5')"><i class="bx bx-pencil"></i>Blog</button>
            <button onclick="changeContent('div6')"><i class=' bx bx-cog'></i>Settings</button>
        </div>
        
        <section id="div1" class="content">
          <div class="contentDiv">
          <div class="headerDash">
              <div class="logOutBtn">
                <button id="editBtn" onclick="toggleModal()">Log out</button>
              </div>
            </div>
          <h1>DASHBOARD</h1>
          <table>
                <tr>
                    <th>Username</th>
                    <th>Total Money Earned</th>
                    <th>Total Users</th>
                </tr>
                <tr>
                    <td>
                      <?php
                        echo "<h4>" . $unameS . "</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        echo "<h4>" . addUserPrices($userPrices) . "ETB</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        echo "<h4>" . $rowCount1['username_count1'] + $rowCount3['username_count3'] . "</h4>";
                        
                      ?>
                    </td>
                </tr>
                <tr>
                    <th>Number of shopkeepers</th>
                    <th>Number of customers</th>
                    <th>Total Items added</th>
                    <!--<th>Rent updated Date</th> -->
                </tr>
                <tr>
                    <td>
                      <?php
                        echo "<h4>" . $rowCount1['username_count1'] . "</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        echo "<h4>" . $rowCount3['username_count3'] . "</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        echo "<h4>" . $rowCount2['username_count2'] . "</h4>";
                      ?>
                    </td>
                    
                </tr>
          </table>
          </div>
        </section>
        
        <section id="div2" class="content">
        <div class="form-cont">
          <h1>ADD USER</h1>
          <div class="form-container" id="formContainer">
          <form id="addItemForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <table>
                <tr>
                  <td colspan="4"><input type="text" id="uname" name="uname" placeholder="Username" required></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                </tr>
                <tr>
                    <td><input type="text" id="name" name="name" required></td>
                    <td><input id="email" name="email" required></td>
                    <td><input type="text" id="phone" name="phone" required></td>
                    <td><input type="text" id="address" name="address" required></td>
                    
                </tr>
                <tr>
                    <th>Type</th>
                    <th>Type of shop</th>
                    <th>Password</th>
                    <th></th>
                    <!-- <th>Account availability</th> -->
                    
                </tr>
                <tr>

                    <td>
                        <select id="type" name="type" required>
                            <option value="Shopkeeper">Shopkeeper</option>
                        </select>
                    </td>
                    <td>
                        <select id="typeOfShop" name="typeOfShop" required>
                            <option value="Small">Small</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Big">Big</option>
                        </select>
                    </td>
                    <td>
                      <input type="password" id="newPass" name="newPass" placeholder="New" required>
                      <input type="password" id="confPass" name="confPass" placeholder="Confirm" required>
                    </td>
                </tr>
                
            </table>
            <button type="submit" name="submit">Submit</button>

        </form>
          </div>
          </div>
        </section>

        <section id="div3" class="content">
          <div class="contentDiv">
            <h1>ALL USERS</h1>
            <div id="searchCont">
                <input type="text" id="search" name="search" placeholder="Search..." onkeyup="searchUsers()">
            </div>
            <div class="contentSecDiv">
              <?php

              while ($rowShopk = mysqli_fetch_assoc($resultShopk)){
                echo "<div class=\"conts\" style=\"text-align:center;\">";
                echo "<h4 style=\"font-size:15px;\" class=\"id\">" . htmlspecialchars($rowShopk['id']) . "</h4>";
                // echo "<img src=\"" . htmlspecialchars($row['pictureName']) . "\" width=\"100%\" height=\"200px\">";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 name\">Name: " . htmlspecialchars($rowShopk['name']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 username\">Username: " . htmlspecialchars($rowShopk['username']) . "</h4>" . "<h4 style=\"font-size:15px;\">Password:--------</h4>";
                echo "<p style=\"font-size:15px;\" class=\"conts2 email\">Email: " . htmlspecialchars($rowShopk['email']) . "</p>";
                echo "<p style=\"font-size:15px;\" class=\"conts2 phone\">Phone: " . htmlspecialchars($rowShopk['phone']) . "</p>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 address\">Address: " . htmlspecialchars($rowShopk['address']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 type\">Type: " . htmlspecialchars($rowShopk['type']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 typeOfShop\">Type of shop: " . htmlspecialchars($rowShopk['typeOfShop']) . "</h4>";

              $unameCount = $rowShopk['username'];
              $sqlCount4 = "SELECT COUNT(*) AS username_count4 FROM shop WHERE username = '$unameCount'";
              $resultCount4 = mysqli_query($conn,$sqlCount4);

              if (mysqli_num_rows($resultCount4) > 0){
                $rowCount4 = mysqli_fetch_assoc($resultCount4);
              }
              else {
                $rowCount4 = 0;
              }
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 itemsAdded\">Items added: " . htmlspecialchars($rowCount4['username_count4']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 price\">Total Price: " . htmlspecialchars($rowShopk['price']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 updateDate\">Updated date: " . htmlspecialchars($rowShopk['updateDate']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 date\">Recorded date: " . htmlspecialchars($rowShopk['Date']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 availability\">Availability: " . htmlspecialchars($rowShopk['availability']) . "</h4>";
                // echo "<form action=\"dashboardAdmin.php\" method=\"POST\" style=\"display:inline;\">";
                // echo "<input type=\"hidden\" name=\"productId\" value=\"" . htmlspecialchars($row['productId']) . "\">";
                // echo "<button type=\"submit\" id=\"showBtn\">Show</button>";
                // echo "<a href=\"ItemInfo.php?productId=" . htmlspecialchars($row3['productId']) . "\"><button id=\"showBtn\">Show</button></a>";
                // echo "</form>";
                echo "<button id=\"deleteBtn\" onclick=\"toggleModal4('" . htmlspecialchars($rowShopk['username']) . "')\">Delete</button>";
                echo "</div>";
              }
              while ($rowCust = mysqli_fetch_assoc($resultCust)){
                echo "<div class=\"conts\" style=\"text-align:center;\">";
                echo "<h4 style=\"font-size:15px;\" class=\"id\">" . htmlspecialchars($rowCust['id']) . "</p>";
                // echo "<img src=\"" . htmlspecialchars($rowCust['pictureName']) . "\" width=\"100%\" height=\"200px\">";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 name\">Name: " . htmlspecialchars($rowCust['name']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 username\">Username: " . htmlspecialchars($rowCust['username']) . "</h4>" . "<h4 style=\"font-size:15px;\">Password:--------</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 email\">Email: " . htmlspecialchars($rowCust['email']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 phone\">Phone: " . htmlspecialchars($rowCust['phone']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 address\">Address: " . htmlspecialchars($rowCust['address']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 type\">Type: " . htmlspecialchars($rowCust['type']) . "</h4>";
                echo "<h4 style=\"font-size:15px;\" class=\"conts2 date\">Recorded date: " . htmlspecialchars($rowCust['Date']) . "</h4>";
                // echo "<form action=\"ItemInfo.php\" method=\"POST\" style=\"display:inline;\">";
                // echo "<input type=\"hidden\" name=\"productId\" value=\"" . htmlspecialchars($rowCust['productId']) . "\">";
                // echo "<button type=\"submit\" id=\"showBtn\">Show</button>";
                // echo "<a href=\"ItemInfo.php?productId=" . htmlspecialchars($row3['productId']) . "\"><button id=\"showBtn\">Show</button></a>";
                // echo "</form>";
                echo "<button id=\"deleteBtn\" onclick=\"toggleModal5('" . htmlspecialchars($rowCust['username']) . "')\">Delete</button>";
                echo "</div>";
              }
              ?>
            </div>
          </div>
        </section>

        <section id="div4" class="content">
          <div class="contentDiv">
            <h1>ALL ITEMS</h1>
            <div id="searchCont">
              <!-- <form action="dashboard.php" method="post"> -->
                <input type="text" id="search2" name="search" placeholder="Search..." onkeyup="searchItems2()">
                <!-- <button type="submit" name="submit2" id="searchBtn" onclick="searchItems()">Search</button> -->
              <!-- </form> -->
            </div>
            <div class="contentSecDiv">
              <?php
              while ($rowShop = mysqli_fetch_assoc($resultShop)){
                echo "<div class=\"contsAll\" style=\"text-align:center;\">";
                echo "<p style=\"font-size:12px;\" class=\"username2\">" . htmlspecialchars($rowShop['username']) . "</p>";
                echo "<p style=\"font-size:12px;\" class=\"productId2\">" . htmlspecialchars($rowShop['productId']) . "</p>";
                echo "<img src=\"" . htmlspecialchars($rowShop['pictureName']) . "\" width=\"100%\" height=\"200px\">";
                echo "<h4 style=\"font-size:16px;\" class=\"brand2\">" . htmlspecialchars($rowShop['brand']) . "</h4>";
                echo "<h4 style=\"font-size:16px;\" class=\"model2\">" . htmlspecialchars($rowShop['model']) . "</h4>" . "<p style=\"font-size:12px;\">Condition:" . $rowShop['condition'] . "</p>";
                echo "<p style=\"font-size:12px;\" class=\"category2\">Category: " . htmlspecialchars($rowShop['category']) . "</p>";
                echo "<p style=\"font-size:12px;\" class=\"quantity2\">Quantity: " . htmlspecialchars($rowShop['quantity']) . "</p>";
                echo "<h4 style=\"font-size:16px;\" class=\"price2\">" . htmlspecialchars($rowShop['price']) . "ETB</h4>";
                echo "<form action=\"ItemInfo2.php\" method=\"POST\" style=\"display:inline;\">";
                echo "<input type=\"hidden\" name=\"productId\" value=\"" . htmlspecialchars($rowShop['productId']) . "\">";
                echo "<button type=\"submit\" id=\"showBtn\">Show</button>";
                // echo "<a href=\"ItemInfo.php?productId=" . htmlspecialchars($row3['productId']) . "\"><button id=\"showBtn\">Show</button></a>";
                echo "</form>";
                echo "</div>";
              }
              ?>
            </div>
          </div>
        </section>

        <section id="div5" class="content">
        <div class="contentDivBlog" id="contentDivIdBlog">
            <!-- <div id="divBars" style="cursor:pointer; color: rgb(255, 115, 0);width:20px; :hover{color:black;}">
              <i class="fa-solid fa-bars" onclick="toggleDropdown2()" ></i>
              <button onclick="toggleDropdown()">Drop</button>
            </div> -->
            <h1>BLOG</h1>
          <!--<div class="dropdownEditBlog" style="display: none;">
            <li><button id="editBtn" onclick="showInputField2()">Edit Settings</button></li>
             <li><button id="deleteBtn" onclick="toggleModal2()">Delete Account</button></li> 
          </div>-->
      <form action="dashboardAdmin.php" method="POST">    
          <?php

          // Display the blog edit form
          echo "<div class=\"showDiv2\">";
          echo "<h4 style=\"font-size:16px;\" class=\"HomeBlog\">Home blog: </h4>";
          echo "<textarea id=\"HomeBlog\" class=\"blogClass\" name=\"HomeBlog\" placeholder=\"" . htmlspecialchars($rowBlog['HomeBlog']) . "\"></textarea>";
          echo "</div>";

          echo "<div class=\"showDiv2\">";
          echo "<h4 style=\"font-size:16px;\" class=\"ShopBlog\">Shop blog: </h4>";
          echo "<textarea id=\"ShopBlog\" class=\"blogClass\" name=\"ShopBlog\" placeholder=\"" . htmlspecialchars($rowBlog['ShopBlog']) . "\"></textarea>";
          echo "</div>";

          echo "<div class=\"showDiv2\">";
          echo "<h4 style=\"font-size:16px;\" class=\"RentBlog\">Rent blog: </h4>";
          echo "<textarea id=\"RentBlog\" class=\"blogClass\" name=\"RentBlog\" placeholder=\"" . htmlspecialchars($rowBlog['RentBlog']) . "\"></textarea>";
          echo "</div>";

          echo "<div class=\"showDiv2\"><button type=\"submit\" name=\"saveBlog\" id=\"saveBtn\">Save</button></div>";
            
          ?>
        </form>
          </div>
        </section>

        <section id="div6" class="content">
          <div class="contentDiv" id="contentDivId">
            <div id="divBars" style="cursor:pointer; color: rgb(255, 115, 0);width:20px; :hover{color:black;}">
              <i class="fa-solid fa-bars" onclick="toggleDropdown2()" ></i>
              <!-- <button onclick="toggleDropdown()">Drop</button> -->
            </div>
            <h1>SETTINGS</h1>
          <div class="dropdownEdit2" style="display: none;">
            <li><button id="editBtn" onclick="showInputField2()">Edit Settings</button></li>
            <!-- <li><button id="deleteBtn" onclick="toggleModal2()">Delete Account</button></li> -->
          </div>
      <form action="dashboardAdmin.php" method="POST">    
          <?php
            // while ($row1 = mysqli_fetch_assoc($result1)){
            // if (mysqli_fetch_assoc($result1) > 0){
          try{
            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"username\">Username: " . htmlspecialchars($rowA['username']) . "</h4>";
              echo "<input type=\"hidden\" name=\"username\" class=\"inputField2\" value=\"" . htmlspecialchars($rowA['username']) . "\">";
              echo "<input type=\"text\" name=\"uname\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['username']) . "\">";
              // echo "<input type=\"text\" name=\"newUsername\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row['username']) . "\">";
            echo "</div>";

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"name\">Name: " . htmlspecialchars($rowA['name']) . "</h4>";
              echo "<input type=\"text\" name=\"name\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['name']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"phone\">Phone: " . htmlspecialchars($rowA['phone']) . "</h4>";
              echo "<input type=\"text\" name=\"phone\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['phone']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";            
              echo "<h4 style=\"font-size:16px;\" class=\"email\">Email: " . htmlspecialchars($rowA['email']) . "</h4>";
              echo "<input type=\"email\" name=\"email\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['email']) . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"password\">Password: " . "........" . "</h4>";
              echo "<input type=\"password\" name=\"oldPassword\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . "Old password" . "\">";
              echo "<input type=\"password\" name=\"newPassword\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . "New password" . "\">";
              echo "<input type=\"password\" name=\"confirmPassword\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . "Confirm password" . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"SmallPrice\">Small shop price: " . htmlspecialchars($rowA['SmallPrice']) . "</h4>";
              echo "<input type=\"number\" name=\"SmallPrice\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['SmallPrice']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"IntermediatePrice\">Intermediate shop price: " . htmlspecialchars($rowA['IntermediatePrice']) . "</h4>";
              echo "<input type=\"number\" name=\"IntermediatePrice\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['IntermediatePrice']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"BigPrice\">Big shop price: " . htmlspecialchars($rowA['BigPrice']) . "</h4>";
              echo "<input type=\"number\" name=\"BigPrice\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['BigPrice']) . "\">";
            echo "</div>";

            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"SmallSlots\">Small slots: " . htmlspecialchars($rowA['SmallSlots']) . "</h4>";
              echo "<input type=\"number\" name=\"SmallSlots\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['SmallSlots']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"IntermediateSlots\">Intermediate slots: " . htmlspecialchars($rowA['IntermediateSlots']) . "</h4>";
              echo "<input type=\"number\" name=\"IntermediateSlots\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['IntermediateSlots']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"BigSlots\">Big slots: " . htmlspecialchars($rowA['BigSlots']) . "</h4>";
              echo "<input type=\"number\" name=\"BigSlots\" class=\"inputField2\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($rowA['BigSlots']) . "\">";
            echo "</div>";
            
            echo "<div></div>";
            echo "<div></div>";
            echo "<div></div>";
            echo "<div></div>";

            echo "<div class=\"showDiv2\"><button type=\"submit\" name=\"save\" id=\"saveBtn\">Save</button></div>";
          }catch(mysqli_sql_exception){
            echo "Username not found!";
          }

            
          ?>
        </form>
          </div>
        </section>

    </div>
    </section>
    
    <footer>
        <div class="footer-content">
          <div class="footer-section about">
            <!-- <h3>About Us</h3>
            <p>Welcome to our online shop! We offer a wide range of high-quality products for all your needs. Explore our collection and find the perfect items for you.</p>
            <div class="contact">
              <span><i class="fas fa-phone"></i> +1 (123) 456-7890</span>
              <span><i class="fas fa-envelope"></i> info@myshop.com</span>
            </div> -->
          </div>
          <div class="footer-section links">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="shopViews/shop.php">Shop</a></li>
              <li><a href="about.html">About</a></li>
              <li><a href="contact.html">Contact</a></li>
            </ul>
          </div>
          <div class="footer-section contact-form">
            <!-- <h3>Contact Us</h3>
            <form>
              <input type="text" name="name" placeholder="Your Name" />
              <input type="email" name="email" placeholder="Your Email" />
              <textarea name="message" placeholder="Your Message"></textarea>
              <button type="submit">Send</button>
            </form> -->
          </div>
        </div>
        <div class="footer-bottom">
          &copy; 2023 Nathaven. All rights reserved.
        </div>
      </footer>

      <div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Are you sure you want to log out from your account?</p>
      <form action="dashboardAdmin.php" method="post">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($rowA['username']); ?>">
        <button type="submit" id="confirmYes" name="logOutBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal()">No</button>
      </form>
    </div>
    </div>

    <div id="confirmModal2" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal2()">&times;</span>
        <p>Are you sure you want to delete this account?</p>
      <form action="dashboardAdmin.php" method="post" id="deleteForm">
        <input type="hidden" name="usernameShopk" id="modalUsernameShopk">
        <input type="hidden" name="usernameCust" id="modalUsernameCust">
        <button type="submit" id="confirmYes" name="deleteBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal2()">No</button>
      </form>
    </div>
    </div>


    <!-- <div id="confirmModal2" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal2()">&times;</span>
        <p>Are you sure you want to delete your account?</p>
      <form action="dashboard.php" method="post">
        <input type="hidden" name="username" value="">
        <button type="submit" id="confirmYes" name="deleteBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal2()">No</button>
      </form>
    </div>
    </div> -->

      <script>
    var imgBox = document.getElementById("imgBox");

    var loadFile = function(event){
      imgBox.style.backgroundImage = "url(" +URL.createObjectURL(event.target.files[0])+ ")";
    }
  </script>

  <!-- <script>
        function togglePanel() {
            var panel = document.getElementById("panel");
            // Toggle the display style of the panel
            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }
        }
    </script> -->
      <script src="../../Resources/js/script2.js"></script>

</body>
</html>