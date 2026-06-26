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

    $sqlA = "SELECT * FROM admin";
    $sql1 = "SELECT * FROM shopkeepers WHERE username = '$unameS'";
    $sql3 = "SELECT * FROM shop WHERE username = '$unameS'";
    $sqlCheck = "SELECT * FROM shop";
    $sql4 = "SELECT COUNT(*) AS username_count FROM shop WHERE username = '$unameS'";

    $resultA = mysqli_query($conn, $sqlA);
    $result1 = mysqli_query($conn, $sql1);
    $result3 = mysqli_query($conn, $sql3);
    $resultCheck = mysqli_query($conn, $sqlCheck);
    $result4 = mysqli_query($conn, $sql4);

    if (mysqli_num_rows($resultA) > 0){
      $rowA = mysqli_fetch_assoc($resultA);
    }
    else {
      echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Name not found</h2>";
    }

    if (mysqli_num_rows($result1) > 0){
      $row = mysqli_fetch_assoc($result1);
    }
    else {
      echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Name not found</h2>";
    }

    // if (mysqli_num_rows($result3) > 0){
    //   $row3 = mysqli_fetch_assoc($result3);
    // }
    // else {
    //   echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    // }

    if (mysqli_num_rows($result4) > 0){
      $row4 = mysqli_fetch_assoc($result4);
    }
    else {
      echo $row4 = 0;
    }
    // echo "<h2 style=\"color:Red; text-align:center;\">" . $unameS . "\n" . $passS . "</h2>";

    
  if (isset($_POST['submit'])){

    $productName = $_POST['productName'];
    $productId = $_POST['productId'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $condition = $_POST['condition'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $manufactureDate = $_POST['manufacturedDate'];
    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $color = $_POST['color'];
    // $imageName = $_POST['image'];

    // echo "<h2>" . $productName . $manufactureDate . $category . $condition . $imageName . "</h2>";

    // $sql2 = "INSERT INTO shop (username,productId,productName,price,description,`condition`,quantity,category,manufactureDate,model,brand,color,pictureName) VALUES('$unameS', '$productId', '$productName', '$price', '$description','$condition', '$quantity', '$category','$manufactureDate', '$model', '$brand', '$color', '$imageName')";
    if (mysqli_num_rows($resultCheck) > 0){
      $rowCheck = mysqli_fetch_assoc($resultCheck);
    }
    else {
      $rowCheck = 0;
    }

    if ($_POST['productId'] != $rowCheck['productId']){
    if ($row['typeOfShop'] == "Small"){
      if ($row4['username_count'] < $rowA['SmallSlots']){
        try{

            $sql2 = "INSERT INTO shop (username,productId,productName,price,description,`condition`,quantity,category,manufactureDate,model,brand,color,pictureName) VALUES('$unameS', '$productId', '$productName', '$price', '$description','$condition', '$quantity', '$category','$manufactureDate', '$model', '$brand', '$color', ' ')";

            if(mysqli_query($conn, $sql2)){
              $uploadedFileName = uploadImage();

              if($uploadedFileName){
                $updateImageSql = "UPDATE shop SET pictureName = '$uploadedFileName' WHERE username = '$unameS' AND productId = '$productId'";
                mysqli_query($conn, $updateImageSql);

                echo "<h3 style = \"color:green; text-align:center; position:absolute; width:100%;\">Item added successfully!</h3>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
              // session_destroy();
              }
              else {
                echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">Error with image upload!</h3>";
              }
          }
          else {
            echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">Could not insert item!</h3>";
          }
          
        }
        catch (mysqli_sql_exception){
          echo '
        <div id="overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 3000; display: flex; justify-content: center; align-items: center;">
            <div style="background-color: white; border: 1px solid #ccc; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); position: relative; text-align: center;">
                <button id="closeBtn" style="width:fit-content; position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 30px; cursor: pointer;">&times;</button><br><br>
                <h3 style="color:red;">Could not insert item! 
                <br>Try shorter answers for each options(including your image name) except the description! 
                <br>Or change product ID!</h3><br><br>
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
      else{
        echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">Your slot is full!</h3>";
      }
    }

    else if ($row['typeOfShop'] == "Intermediate"){
      if ($row4['username_count'] < $rowA['IntermediateSlots']){
        try{

            $sql2 = "INSERT INTO shop (username,productId,productName,price,description,`condition`,quantity,category,manufactureDate,model,brand,color,pictureName) VALUES('$unameS', '$productId', '$productName', '$price', '$description','$condition', '$quantity', '$category','$manufactureDate', '$model', '$brand', '$color', ' ')";

            if(mysqli_query($conn, $sql2)){
              $uploadedFileName = uploadImage();

              if($uploadedFileName){
                $updateImageSql = "UPDATE shop SET pictureName = '$uploadedFileName' WHERE username = '$unameS' AND productId = '$productId'";
                mysqli_query($conn, $updateImageSql);
                echo "<h3 style = \"color:green; text-align:center; position:absolute; width:100%;\">Item added successfully!</h3>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
              // session_destroy();
              }
              else{
                echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">Error with image!</h3>";
              }  
          }
          else {
            echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">Could not insert item!</h3>";
          }
        }
        catch (mysqli_sql_exception){
          echo '
        <div id="overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 3000; display: flex; justify-content: center; align-items: center;">
            <div style="background-color: white; border: 1px solid #ccc; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); position: relative; text-align: center;">
                <button id="closeBtn" style="width:fit-content; position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 30px; cursor: pointer;">&times;</button><br><br>
                <h3 style="color:red;">Could not insert item! 
                <br>Try shorter answers for each options(including your image name) except the description! 
                <br>Or change product ID!</h3><br><br>
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
      else{
        echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">Your slot is full!</h3>";
      }
    }
    else if ($row['typeOfShop'] == "Big"){
      if ($row4['username_count'] < $rowA['BigSlots']){
        try{

            $sql2 = "INSERT INTO shop (username,productId,productName,price,description,`condition`,quantity,category,manufactureDate,model,brand,color,pictureName) VALUES('$unameS', '$productId', '$productName', '$price', '$description','$condition', '$quantity', '$category','$manufactureDate', '$model', '$brand', '$color', ' ')";

            if(mysqli_query($conn, $sql2)){
              $uploadedFileName = uploadImage();

              if($uploadedFileName){

                $updateImageSql = "UPDATE shop SET pictureName = '$uploadedFileName' WHERE username = '$unameS' AND productId = '$productId'";
                mysqli_query($conn, $updateImageSql);
                echo "<h3 style = \"color:green; text-align:center;\">Item added successfully!</h3>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
                // session_destroy();
              }
              else{
                echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">Error with image!</h3>";
              }  
          }
          else {
            echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">Could not insert item!</h3>";
          }
        }
        catch (mysqli_sql_exception){
          echo '
        <div id="overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 3000; display: flex; justify-content: center; align-items: center;">
            <div style="background-color: white; border: 1px solid #ccc; border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); position: relative; text-align: center;">
                <button id="closeBtn" style="width:fit-content; position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 30px; cursor: pointer;">&times;</button><br><br>
                <h3 style="color:red;">Could not insert item! 
                <br>Try shorter answers for each options(including your image name) except the description! 
                <br>Or change product ID!</h3><br><br>
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
      else{
        echo "<h3 style = \"color:red; text-align:center;\">Your slot is full!</h3>";
      }
    }
  } 
  else {
    echo "<h3 style = \"color:red; text-align:center;\">This Product ID is not available!</h3>";
  }

  }
  
if (isset($_POST['save'])) {
  $updates = [];

  // Check for new username
  if (!empty($_POST['uname'])) {
    $newUsername = mysqli_real_escape_string($conn, $_POST['uname']);
    
    // Check if the new username already exists
    $usernameCheckSql = "SELECT * FROM shopkeepers WHERE username = '$newUsername'";
    $usernameCheckResult = mysqli_query($conn, $usernameCheckSql);
    
    if (mysqli_num_rows($usernameCheckResult) > 0) {
        echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">Username already exists!</h3>";
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
      $oldPass = $row['password'];
      if (password_verify($_POST['oldPassword'], $oldPass)) {
        if ($_POST['newPassword'] == $_POST['confirmPassword']) {
          $hash = password_hash($_POST['newPassword'],PASSWORD_DEFAULT);
          $updates[] = "password='" . mysqli_real_escape_string($conn, $hash) . "'";
        }
      }
  }
  if (!empty($_POST['address'])) {
      $updates[] = "address='" . mysqli_real_escape_string($conn, $_POST['address']) . "'";
  }

  // Only proceed if there are updates
  if (count($updates) > 0) {
      $sql = "UPDATE shopkeepers SET " . implode(", ", $updates) . " WHERE username='" . mysqli_real_escape_string($conn, $_POST['username']) . "'";

      // Execute the query
      if (mysqli_query($conn, $sql)) {
        if (isset($newUsername)) {    
            
            $sqlShop = "UPDATE shop SET username = '$newUsername' WHERE username='" . mysqli_real_escape_string($conn, $_POST['username']) . "'";
            mysqli_query($conn, $sqlShop);
          
            $sqlChangeUMessages = "UPDATE messages SET usernameShopk='$newUsername' WHERE usernameShopk='" . mysqli_real_escape_string($conn, $_POST['username']) . "'";
            mysqli_query($conn, $sqlChangeUMessages);

            $_SESSION['uname'] = $newUsername; // Update session variable
        }
          echo "<h3 style=\"color:green; text-align:center; position:absolute; width:100%;\">Record updated successfully.</h3>";
          header("Location: " . $_SERVER['PHP_SELF']);
      } else {
          echo "<h3 style=\"color:green; text-align:center; position:absolute; width:100%;\">Error updating record.</h3>";
      }
  }
}

if (isset($_POST['deleteBtn'])) {
  if (isset($_POST['username'])) {
      $username = mysqli_real_escape_string($conn, $_POST['username']); // Sanitize input

      // Prepare delete statements
      $sqlDelete1 = "DELETE FROM shop WHERE username = '$username'";
      $sqlDelete2 = "DELETE FROM shopkeepers WHERE username = '$username'";
      $sqlDelete3 = "DELETE FROM messages WHERE usernameShopk = '$username'";

      // Execute both delete statements
      $deletedFromShop = mysqli_query($conn, $sqlDelete1);
      $deletedFromShopkeepers = mysqli_query($conn, $sqlDelete2);
      $deletedFromMessages = mysqli_query($conn, $sqlDelete3);

      // Check if at least one delete was successful
      if ($deletedFromShop || $deletedFromShopkeepers) {
          header("Location: ./confirmation.php");
          exit();
      } else {
          echo "<h3 style=\"color:green; text-align:center; position:absolute; width:100%;\">Couldn't delete account. No records found.</h3>";
      }
  } else {
      echo "<h3 style=\"color:green; text-align:center; position:absolute; width:100%;\">Username is not set.</h3>";
  }
}

if (isset($_POST['goToAllMessages'])){      
      
  if ($unameS != null){
    header("Location: ./messages/allMessages.php");
    exit();
  }
  else if ($unameS == null){
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
              echo "Error: There was a problem uploading the image.";
              return false;
          }
      }
    }
    else{
      echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">You did not upload an image!</h2>";
      return false;
    }
  }

  function daysPassed($otherDate) {
    
    // Create a DateTime object from the provided date string
    $otherDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $otherDate);

    // Check for errors in date conversion
    if (!$otherDateTime) {
        echo "Invalid date format. Please use 'YYYY-MM-DD HH:MM:SS'.";
        return null; // Return null or handle as needed
    }

    // Get the current date
    $currentDate = new DateTime();

    // Calculate the target date (31 days from the update date)
    $targetDateTime = clone $otherDateTime;
    $targetDateTime->modify('+31 days');

    // Calculate the difference
    $interval = $currentDate->diff($targetDateTime);

    // Determine if the target date has passed
    if ($currentDate > $targetDateTime) {
        // If current date is later than target date, return negative days
        return -$interval->days; // This will return a negative number
    }

    // Return the number of days remaining
    return $interval->days;
  }

  function countItems($username){
    $count = 0;
    for ($i = 0; $i < count($username); $i++) {
      $count++;
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/css/forDashboard.css">
    <!-- <link rel="stylesheet" href="../../Resources/css/forIndex.css"> -->
    <link rel="stylesheet" href="../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Shop Dashboard - Nathaven Shopysite</title>
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
                echo "<a href=\"dashboard.php\" style=\"text-decoration:none;\">" . "<h2 style=\"color:rgb(0, 100, 0);\">" . $row['name'] . "</h2>" . "</a>"; 
              ?>
            </nav>
            
             <br><br><br>
            <button onclick="changeContent('div1')"><i class="bx bx-home"></i>Dashboard</button>
            <button onclick="changeContent('div2')"><i class="bx bx-user"></i>Profile</button>
            <button onclick="changeContent('div3')"><i class="bx bx-plus"></i>Add Items</button>
            <button onclick="changeContent('div4')"><i class="bx bx-list-ul"></i>All Items</button>
        </div>
        
        <section id="div1" class="content">
          <div class="contentDiv">
            <div class="headerDash">
              <div class="logOutBtn">
                <button id="editBtn" onclick="toggleModal2()">Log out</button>
              </div>
            </div>
          <h1>DASHBOARD</h1>
          <table>
                <tr>
                    <th>Username</th>
                    <th>Money spent</th>
                    <th>Rent updated Date</th>
                </tr>
                <tr>
                    <td>
                      <?php
                        echo "<h4>" . $unameS . "</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        echo "<h4>" . $row['price'] . "ETB</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        echo "<h4>" . $row['updateDate'] . "</h4>" . "<h4>" . (daysPassed($row['updateDate'])) . " days remaining</h4>";
                        
                      ?>
                    </td>
                </tr>
                <tr>
                    <th>Type of shop</th>
                    <th>Items added</th>
                    <th>Remaining slot for items</th>
                    <!--<th>Rent updated Date</th> -->
                </tr>
                <tr>
                    <td>
                      <?php
                        echo "<h4>" . $row['typeOfShop'] . "</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        echo "<h4>" . $row4['username_count'] . "</h4>";
                      ?>
                    </td>
                    <td>
                      <?php
                        if ($row['typeOfShop'] == "Small"){
                          echo "<h4>" . ($rowA['SmallSlots'] - $row4['username_count']) . "</h4>";
                        }
                        else if ($row['typeOfShop'] == "Intermediate"){
                          echo "<h4>" . ($rowA['IntermediateSlots'] - $row4['username_count']) . "</h4>";
                        }
                        else if ($row['typeOfShop'] == "Big"){
                          echo "<h4>" . ($rowA['BigSlots'] - $row4['username_count']) . "</h4>";
                        }
                      ?>
                    </td>
                    
                </tr>
          </table>
          </div>
        </section>
        
        <section id="div2" class="content">
          <div class="contentDiv" id="contentDivId">
            <div id="divBars" style="cursor:pointer; color: rgb(255, 115, 0);width:20px; :hover{color:black;}">
              <i class="bx bx-menu" style="font-size: 2.5rem;" onclick="toggleDropdown()"></i>
              <!-- <button onclick="toggleDropdown()">Drop</button> -->
            </div>
            <h1>PROFILE</h1>
          <div class="dropdownEdit" style="display: none;">
            <li><button id="editBtn" onclick="showInputField()">Edit Profile</button></li>
            <li><button id="deleteBtn" onclick="toggleModal()">Delete Account</button></li>
          </div>
      <form action="dashboard.php" method="POST">    
          <?php
            // while ($row1 = mysqli_fetch_assoc($result1)){
            // if (mysqli_fetch_assoc($result1) > 0){
          try{
            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"username\">Username: " . htmlspecialchars($row['username']) . "</h4>";
              echo "<input type=\"hidden\" name=\"username\" class=\"inputField\" value=\"" . htmlspecialchars($row['username']) . "\">";
              echo "<input type=\"text\" name=\"uname\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row['username']) . "\">";
              // echo "<input type=\"text\" name=\"newUsername\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row['username']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"name\">Name: " . htmlspecialchars($row['name']) . "</h4>";
              echo "<input type=\"text\" name=\"name\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row['name']) . "\">";
            echo "</div>";

            echo "<div class=\"showDiv2\">";            
              echo "<h4 style=\"font-size:16px;\" class=\"email\">Email: " . htmlspecialchars($row['email']) . "</h4>";
              echo "<input type=\"email\" name=\"email\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row['email']) . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"phone\">Phone: " . htmlspecialchars($row['phone']) . "</h4>";
              echo "<input type=\"text\" name=\"phone\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row['phone']) . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"password\">Password: " . "--------" . "</h4>";
              echo "<input type=\"password\" name=\"oldPassword\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . "Old password" . "\">";
              echo "<input type=\"password\" name=\"newPassword\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . "New password" . "\">";
              echo "<input type=\"password\" name=\"confirmPassword\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . "Confirm password" . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"address\">Address: " . htmlspecialchars($row['address']) . "</h4>";
              echo "<input type=\"text\" name=\"address\" class=\"inputField\" style=\"display:none;\" placeholder=\"" . htmlspecialchars($row['address']) . "\">";
            echo "</div>";
            
            echo "<div class=\"showDiv2\">";
                echo "<h4 style=\"font-size:16px;\" class=\"type\">Type:" . $row['type'] . "</h4>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
            echo "<h4 style=\"font-size:16px;\" class=\"typeOfShop\">Type of shop: " . htmlspecialchars($row['typeOfShop']) . "</h4>";
          echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"price\">Money Spent: " . htmlspecialchars($row['price']) . "</h4>";
            echo "</div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"updateDate\">Rent updated date: " . htmlspecialchars($row['updateDate']) . "</h4>";
            echo "</div>";

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"updateDate\">Starting date: " . htmlspecialchars($row['Date']) . "</h4>";
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

        <section id="div3" class="content">
        <div class="form-cont">
          <h1>ADD ITEM</h1>
          <div class="form-container" id="formContainer">
          <form id="addItemForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
            <table>
                <tr>
                  <td colspan="4"><input type="text" id="productId" name="productId" placeholder="Your preferred id(unique name) for your item" required></td>
                </tr>
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Category</th>
                </tr>
                <tr>
                    <td><input type="text" id="productName" name="productName" required></td>
                    <td><textarea id="description" name="description" rows="4" required></textarea></td>
                    <td><input type="number" id="quantity" name="quantity" min="1" required></td>
                    <td>
                    <select id="category" name="category" required>
                            <option value="Electronics">Electronics</option>
                            <option value="Clothing">Clothing</option>
                            <option value="Home and living">Home and living</option>
                            <option value="Sports and Outdoors">Sports and Outdoors</option>
                            <option value="Beauty and Health">Beauty and Health</option>
                            <option value="Books and Media">Books and Media</option>
                            <option value="Toys and Games">Toys and Games</option>
                            <option value="Automotive and Vehicles">Automotive and Vehicles</option>
                            <option value="Other">Other</option>
                    </select>
                    </td>
                </tr>
                <tr>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Condition</th>
                    <th>Price (ETB)</th>
                    
                </tr>
                <tr>
                    <td><input type="text" id="brand" name="brand" required></td>

                    <td><input type="text" id="model" name="model" required></td>

                    <td>
                        <select id="condition" name="condition" required>
                            <option value="New">New</option>
                            <option value="Used">Used</option>
                        </select>
                    </td>
                    <td><input type="number" id="price" name="price" min="0" step="0.01" required></td>
                    
                </tr>
                <tr>
                    <th>Color</th>
                    <th>Manufactured Date</th>
                    <td colspan="2" rowspan="4">
                      <div id="imgBox"></div>
                    </td>
                </tr>
                <tr>
                    <td>
                    <input type="text" id="color" name="color" required>
                    </td>
                    <td><input type="date" id="manufacturedDate" name="manufacturedDate" required></td>
                </tr>
                <tr>
                    <th>Upload Pictures</th>
                    <th></th>
                </tr>
                <tr>    
                    <td colspan="2"><input type="file" id="image" name="image" accept="image/*" onchange="loadFile(event)" required></td>
                </tr>
                
            </table>
            <button type="submit" name="submit">Submit</button>

        </form>
          </div>
          </div>
        </section>

        <section id="div4" class="content">
          <div class="contentDiv">
            <h1>ALL ITEMS</h1>
            <div id="searchCont">
              <!-- <form action="dashboard.php" method="post"> -->
                <input type="text" id="search" name="search" placeholder="Search..." onkeyup="searchItems()">
                <!-- <button type="submit" name="submit2" id="searchBtn" onclick="searchItems()">Search</button> -->
              <!-- </form> -->
            </div>
            <div class="contentSecDiv">
              <?php
              while ($row3 = mysqli_fetch_assoc($result3)){
                echo "<div class=\"conts\" style=\"text-align:center;\">";
                echo "<p style=\"font-size:12px;\" class=\"productId\">" . htmlspecialchars($row3['productId']) . "</p>";
                echo "<img src=\"" . htmlspecialchars($row3['pictureName']) . "\" width=\"100%\" height=\"200px\">";
                echo "<h4 style=\"font-size:16px;\" class=\"brand\">" . htmlspecialchars($row3['brand']) . "</h4>";
                echo "<h4 style=\"font-size:16px;\" class=\"model\">" . htmlspecialchars($row3['model']) . "</h4>" . "<p style=\"font-size:12px;\">Condition:" . $row3['condition'] . "</p>";
                echo "<p style=\"font-size:12px;\" class=\"category\">Category: " . htmlspecialchars($row3['category']) . "</p>";
                echo "<p style=\"font-size:12px;\" class=\"quantity\">Quantity: " . htmlspecialchars($row3['quantity']) . "</p>";
                echo "<h4 style=\"font-size:16px;\" class=\"price\">" . htmlspecialchars($row3['price']) . "ETB</h4>";
                echo "<form action=\"ItemInfo.php\" method=\"POST\" style=\"display:inline;\">";
                echo "<input type=\"hidden\" name=\"productId\" value=\"" . htmlspecialchars($row3['productId']) . "\">";
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

    <div class="showAllMessages">
          <form action="dashboard.php" method="post">
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
              <li><a href="index.php">Home</a></li>
              <li><a href="shopViews/shop.php">Shop</a></li>
              <li><a href="about.html">About</a></li>
              <li><a href="contact.html">Contact</a></li>
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
        <p>Are you sure you want to delete your account?</p>
      <form action="dashboard.php" method="post">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
        <button type="submit" id="confirmYes" name="deleteBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal()">No</button>
      </form>
    </div>
    </div>

    <div id="confirmModal2" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal2()">&times;</span>
        <p>Are you sure you want to log out from your account?</p>
      <form action="dashboard.php" method="post">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
        <button type="submit" id="confirmYes" name="logOutBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal2()">No</button>
      </form>
    </div>
    </div>

      <script>
    var imgBox = document.getElementById("imgBox");

    var loadFile = function(event){
      imgBox.style.backgroundImage = "url(" +URL.createObjectURL(event.target.files[0])+ ")";
    }
  </script>
      <script src="../../Resources/js/script2.js"></script>

</body>
</html>