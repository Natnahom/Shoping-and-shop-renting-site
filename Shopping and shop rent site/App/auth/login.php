<?php
session_start();
include ('../database/Database.php');

if (isset($_POST['submit'])) {
    // $name = $_POST['name'];
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];
    $cPass = $_POST['cPass'];
    $type = $_POST['type'];

    // Prepare statements to prevent SQL injection
    $stmt1 = $conn->prepare("SELECT name, username, password, updateDate FROM shopkeepers WHERE username = ?");
    $stmt2 = $conn->prepare("SELECT name, username, password FROM customers WHERE username = ?");
    $stmtAdmin = $conn->prepare("SELECT name, username, password FROM admin WHERE username = ?");

    // Bind parameters
    $stmt1->bind_param("s", $uname);
    $stmt2->bind_param("s", $uname);
    $stmtAdmin->bind_param("s", $uname);

    // Execute the statements
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($pass == $cPass) {
        if ($type == "Shopkeeper" && mysqli_num_rows($resultAdmin) > 0) {
            $rowAdmin = $resultAdmin->fetch_assoc();

            if (password_verify($pass, $rowAdmin['password'])) {
                $_SESSION['uname'] = $rowAdmin['username'];
                $_SESSION['pass'] = $rowAdmin['password'];
                header('Location: ../views/dashboardAdmin.php');
            } else {
                echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Username does not exist!</h2>";
            }
        } else if ($type == "Shopkeeper" && mysqli_num_rows($result1) > 0) {
            $row = $result1->fetch_assoc();
            $otherDate = $row['updateDate'];

            if (password_verify($pass, $row['password'])) {
                $days = daysPassed($otherDate);

                if ($days > 0 && $days <= 31) {
                    unset($_SESSION['usernameCust']);
                    $_SESSION['uname'] = $row['username'];
                    $_SESSION['pass'] = $row['password'];
                    header('Location: ../views/dashboard.php');
                } else {
                    $_SESSION['uname'] = $row['username'];
                    echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Your monthly rent expired! Now your account is disabled until you pay another month's rent!" . "<br><a href=\"rentUpdate.php\">Pay rent</a>" . "</h2>";
                }
            } else {
                echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Password is not correct!</h2>";
            }
        } else if ($type == "Customer" && mysqli_num_rows($result2) > 0) {
            $row = $result2->fetch_assoc();

            if (password_verify($pass, $row['password'])) {
                unset($_SESSION['uname']);
                $_SESSION['usernameCust'] = $uname;
                header('Location: ../views/shopViews/shop.php');
            } else {
                echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Password is not correct!</h2>";
            }
        } else {
            echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Username does not exist!</h2>";
        }
    } else {
        echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Passwords don't match!</h2>";
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

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/css/forContact.css"> 
    <link rel="stylesheet" href="../../Resources/css/forRegister.css">
    <link rel="stylesheet" href="../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Log in - Nathaven Shopysite</title>

    <style>

.main-cont-ID {
    width: 100%; /* Optional: Set the main container width */
    /* margin: 0 auto; Center the container */
}

.password-container, .username-container {
    position: relative;
    width: 90%; /* Ensure both containers take the same width */
    /* margin: 0 auto; Center align the containers */
}

.password-container input, .username-container input {
    width: 100%; /* Full width of the input */
    padding-right: 40px; /* Add some padding to the right for the icon */
    box-sizing: border-box; /* Include padding in the total width */
}
    .toggle-password {
    position: absolute;
    right: 10px; /* Fixed distance from the right edge */
    top: 35%; /* Center vertically */
    transform: translateY(-50%); /* Adjust vertical alignment */
    font-size: 20px; /* Icon size */
    cursor: pointer;
    /* color: #007BFF; Icon color */
    z-index: 10; /* Ensure it is above other elements */
}

    </style>
</head>
<body>
<a href="../views/index.php"><button id="btn">Back to Home</button></a>
<section class="main-cont" id="main-cont-ID">
        <div class="cont cont1">
            <h2>Welcome Back!</h2>
            <p>We’re glad to see you again! Please log in 
                to your account to enjoy seamless access to 
                your orders, rental history, and personalized 
                recommendations. Stay updated on our latest 
                offers and manage your preferences all in one 
                place. <!--If you’ve forgotten your password, don’t 
                worry—just click on the “Forgot Password?” link 
                to reset it.--> 
                Your shopping experience awaits!</p>
                <i class="bx bx-mobile"></i>+251983311590<br><br>
                <br><i class="bx bx-envelope"></i>nathavenshop@gmail.com<br><br>
                <p>Connect with us:</p>

                <div class="social">
                    <a href="#"><i class=' bx bxl-facebook'></i></a>
                    <a href="#"><i class=' bx bxl-twitter'></i></a>
                    <a href="https://www.instagram.com/nat12nahom/" target="_blank"><i class=' bx bxl-instagram'></i></a>
                    <a href="https://t.me/natnahom12" target="_blank"><i class=" bx bxl-telegram"></i></a>
                    <a href="https://www.linkedin.com/in/natnahom-asfaw/" target="_blank"><i class=" bx bxl-linkedin"></i></a>
                </div>
        </div>

        <div class="cont cont2">
            <div class="contact-form2">
                <h2>Log in</h2>
                <form action="login.php" method="post">
                  <!-- <input type="text" name="name" placeholder="Name" required/> -->
                  <!-- <input type="text" name="uname" placeholder="Username" required/> -->
                  <div class="username-container">
                    <input type="text" name="uname" placeholder="Username" required />
                </div>
                  <div class="password-container">
                <input type="password" name="pass" placeholder="Password" required id="password"/>
                <span class="toggle-password" id="togglePassword1">
                    <i class='bx bx-show' id="eyeIcon1" style="color: white; "></i>
                </span>
            </div>
            <div class="password-container">
                <input type="password" name="cPass" placeholder="Confirm password" required id="confirmPassword"/>
                <span class="toggle-password" id="togglePassword2">
                    <i class='bx bx-show' id="eyeIcon2" style="color: white; "></i>
                </span>
            </div>
                  <label>
        <input type="radio" name="type" value="Shopkeeper" required> Shopkeeper
    </label>
    <label>
        <input type="radio" name="type" value="Customer" required> Customer
    </label><br><br><br><br>
                  <!-- <a href="#" style="color:white; text-decoration:underline; font-size:11.5px;">Forgot password</a><br><br> -->
                  <button type="submit" name="submit">Log in</button>
                </form>
              </div>
        </div>

    </section>

    <script>
    document.getElementById('togglePassword1').onclick = function () {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon1');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('bx-show');
            eyeIcon.classList.add('bx-hide');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('bx-hide');
            eyeIcon.classList.add('bx-show');
        }
    };

    document.getElementById('togglePassword2').onclick = function () {
        const confirmPasswordField = document.getElementById('confirmPassword');
        const eyeIcon = document.getElementById('eyeIcon2');
        if (confirmPasswordField.type === 'password') {
            confirmPasswordField.type = 'text';
            eyeIcon.classList.remove('bx-show');
            eyeIcon.classList.add('bx-hide');
        } else {
            confirmPasswordField.type = 'password';
            eyeIcon.classList.remove('bx-hide');
            eyeIcon.classList.add('bx-show');
        }
    };
</script>

</body>
</html>