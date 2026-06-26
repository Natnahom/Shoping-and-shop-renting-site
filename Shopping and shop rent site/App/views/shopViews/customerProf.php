<?php
    session_start();
?>
<?php

    include ('../../database/Database.php');

    if (empty($_SESSION['usernameCust'])) { 
      header("Location: ../index.php"); // Redirect to index.php
      exit(); // Stop script execution after redirect
    }
    
    $usernameCust = $_SESSION['usernameCust'];
    
    $sql1 = "SELECT * FROM customers WHERE username = '$usernameCust'";

    $result1 = mysqli_query($conn, $sql1);

    if (mysqli_num_rows($result1) > 0){
        $row = mysqli_fetch_assoc($result1);
    }
    else {
        echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
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
        $username = mysqli_real_escape_string($conn, $_POST['username']);
          $sql = "UPDATE customers SET " . implode(", ", $updates) . " WHERE username='" . mysqli_real_escape_string($conn, $username) . "'";
    
          // Execute the query
          if (mysqli_query($conn, $sql)) {
            if (isset($newUsername)) {
              $sqlChangeUCart = "UPDATE cart SET username='$newUsername' WHERE username='$username'";
              mysqli_query($conn, $sqlChangeUCart);
              
              $sqlChangeUMessages = "UPDATE messages SET usernameCust='$newUsername' WHERE usernameCust='$username'";
              mysqli_query($conn, $sqlChangeUMessages);

              $_SESSION['usernameCust'] = $newUsername; // Update session variable
            }
            echo "<h3 style=\"color:green; text-align:center;\">Record updated successfully.</h3>";
            header("Location: " . $_SERVER['PHP_SELF']);
          } else {
              echo "Error updating record!";
          }
      }

    }

      if (isset($_POST['logOutBtn'])) {
        session_destroy();
        header("Location: ../index.php");
      }

      if (isset($_POST['deleteBtn'])) {
        if (isset($_POST['username'])) {
            $username = mysqli_real_escape_string($conn, $_POST['username']); // Sanitize input
      
            // Prepare delete statements
            $sqlDelete1 = "DELETE FROM customers WHERE username = '$username'";
            $sqlDelete2 = "DELETE FROM cart WHERE username = '$username'";
            $sqlDelete3 = "DELETE FROM messages WHERE usernameCust = '$username'";
      
            // Execute both delete statements
            $deletedFromCustomers = mysqli_query($conn, $sqlDelete1);
            $deletedFromCart = mysqli_query($conn, $sqlDelete2);
            $deletedFromMessages = mysqli_query($conn, $sqlDelete3);
      
            // Check if at least one delete was successful
            if ($deletedFromCustomers || $deletedFromCart || $deletedFromMessages) {
                session_destroy();
                header("Location: ../confirmation.php");
                exit();
            } else {
                echo "Couldn't delete account. No records found."; // Show error if both deletions fail
            }
        } else {
            echo "Username is not set.";
        }
      }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../../Resources/css/forDashboard.css">
    <!-- <link rel="stylesheet" href="../../Resources/css/forIndex.css"> -->
    <link rel="stylesheet" href="../../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <title>Customer Profile - Nathaven Shopysite</title>
    <style>

#div2{
    display: block;
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

#div2 .bx{
  font-size: 2.5rem;
}
.social .bx{
    margin-top: 20px;
    font-size: 22px;
  }

.bx{
    color: rgb(255, 115, 0);
}

.bx:hover{
    color: black;
}
    </style>
</head>
<body>

<section id="div2" class="content">
          <div class="contentDiv" id="contentDivId">
            <div id="divBars" style="cursor:pointer; color: rgb(255, 115, 0);width:20px; :hover{color:black;}">
              <i class="bx bx-menu" onclick="toggleDropdown()" ></i>
              <!-- <button onclick="toggleDropdown()">Drop</button> -->
            </div>
            <h1>PROFILE</h1>
          <div class="dropdownEdit" style="display: none;">
            <li><button id="editBtn" onclick="showInputField()">Edit Profile</button></li>
            <li><button id="editBtn" onclick="toggleModal2()">Log out</button></li>
            <li><button id="deleteBtn" onclick="toggleModal()">Delete Account</button></li>
          </div>
      <form action="customerProf.php" method="POST">    
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

            echo "<div></div>";

            echo "<div class=\"showDiv2\">";
              echo "<h4 style=\"font-size:16px;\" class=\"updateDate\">Recorded date: " . htmlspecialchars($row['Date']) . "</h4>";
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
      <form action="customerProf.php" method="post">
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
      <form action="customerProf.php" method="post">
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($row['username']); ?>">
        <button type="submit" id="confirmYes" name="logOutBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal2()">No</button>
      </form>
    </div>
    </div>

    <script src="../../../Resources/js/script2.js"></script>

</body>
</html>