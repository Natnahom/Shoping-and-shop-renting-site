<?php
session_start();
include ('../database/Database.php');

if (isset($_POST['submit'])){
    $name = $_POST['name'];
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $pass = $_POST['pass'];
    $cPass = $_POST['cPass'];
    $type = $_POST['type'];

    $rentPrice = isset($_SESSION['rent']) ? $_SESSION['rent'] : '';
    $typeOfShop = isset($_SESSION['typeOfShop']) ? $_SESSION['typeOfShop'] : '';
    $availability = "On";

    // Sanitize inputs
    $name = preg_replace('/\d/', '', $name);
    $phone = preg_replace('/[a-zA-Z]+/', '', $phone);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h2 style=\"color:red; text-align:center; position:absolute; width:100%;\">Invalid email format!</h2>";
        exit;
    }

    if ($type == "Shopkeeper") {
        if ($rentPrice > 0){
            if (checkPassword($pass, $cPass) == true){
                $hashP = password_hash($pass, PASSWORD_DEFAULT);

                // Use prepared statements to avoid SQL injection
                $stmt = $conn->prepare("INSERT INTO shopkeepers (name, username, email, phone, password, address, type, price, typeOfShop, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $name, $uname, $email, $phone, $hashP, $address, $type, $rentPrice, $typeOfShop, $availability);

                try {
                    $stmt->execute();
                    echo "<h3 style=\"color:green; text-align:center; position:absolute; width:100%;\">Registered successfully!</h3>";
                    session_destroy();
                } catch (mysqli_sql_exception $e) {
                    echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">Could not insert user! Try changing your username.</h3>";
                }
                $stmt->close();
            } else {
                echo "<h2 style=\"color:red; text-align:center; position:absolute; width:100%;\">Passwords don't match!</h2>";
            }
        } else {
            echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">You need to choose a price first. <a href=\"../views/rentShop.php\">Choose price</a></h3>";
        }
    } else if ($type == "Customer") {
        if (checkPassword($pass, $cPass) == true){
            $hashP = password_hash($pass, PASSWORD_DEFAULT);

            // Use prepared statements to avoid SQL injection
            $stmt = $conn->prepare("INSERT INTO customers (name, username, email, phone, password, address, type) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $name, $uname, $email, $phone, $hashP, $address, $type);

            try {
                $stmt->execute();
                echo "<h3 style=\"color:green; text-align:center; position:absolute; width:100%;\">Registered successfully!</h3>";
            } catch (mysqli_sql_exception $e) {
                echo "<h3 style=\"color:red; text-align:center; position:absolute; width:100%;\">Could not insert user! Try changing your username.</h3>";
            }
            $stmt->close();
        } else {
            echo "<h2 style=\"color:red; text-align:center; position:absolute; width:100%;\">Passwords don't match!</h2>";
        }
    }
}

function checkPassword($firstPass, $confirmPass){
    return $firstPass === $confirmPass;
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
    <title>Register - Nathaven Shopysite</title>

    <style>

.popup {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        overflow-y: auto; /* Allow scrolling of the entire popup if needed */
    }
    .popup-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative; /* Ensure positioning for inner scroll */
    }
    .popup-content2 {
        max-height: 300px; /* Set a max height for the scrollable area */
        overflow-y: auto; /* Enable vertical scrolling */
        padding-right: 10px; /* Prevent content from touching the scrollbar */
        scrollbar-width: thin; /* For Firefox */
        scrollbar-color: rgb(255, 115, 0) rgba(0, 0, 0, 0.2); /* For Firefox */
    }
    .popup-content2::-webkit-scrollbar {
        width: 8px; /* Width of the scrollbar */
    }
    .popup-content2::-webkit-scrollbar-thumb {
        background: rgb(255, 115, 0); /* Color of the scrollbar thumb */
        border-radius: 10px; /* Rounded corners of the scrollbar thumb */
    }
    .popup-content2::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1); /* Color of the scrollbar track */
        border-radius: 10px; /* Rounded corners of the scrollbar track */
    }
    .close {
        cursor: pointer;
        float: right;
        font-size: 24px;
    }
    .agree-button {
        display: block;
        margin: 20px auto 0;
        padding: 10px 20px;
        /* background-color: #007BFF; */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .password-container {
    position: relative;
    width: 100%; /* Ensure the container takes full width */
}

.password-container input {
    width: 100%; /* Full width of the input */
    padding-right: 40px; /* Add some padding to the right for the icon */
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
<section class="main-cont">
        <div class="cont cont1">
            <h2>Join us</h2>
            <p>Join our community by creating an account 
                with us. As a registered member, you'll enjoy 
                exclusive access to the latest promotions, special 
                discounts, and personalized shopping experiences 
                tailored just for you. Plus, easily manage your 
                orders and rentals all in one place! Signing up 
                is quick and simple—start your journey with 
                us today and unlock a 
                world of convenience and savings!</p>
            
            <h3 style="color:red;">REMARK</h3><p>When you register do not forget your password. You can edit it after you register but if you forget your password even we cannot find it out because of your privacy and security reasons.</p>
                <i class="bx bx-mobile"></i>+251983311590<br>
                <br><i class="bx bx-envelope"></i>nathavenshop@gmail.com<br><br>
                <p>Connect with us:</p>

                <div class="social">
                    <a href="#"><i class=' bx bxl-facebook'></i></a>
                    <a href="#"><i class=' bx bxl-twitter'></i></a>
                    <a href="https://www.instagram.com/nat12nahom/" target="_blank"><i class=' bx bxl-instagram'></i></a>
                    <a href="https://t.me/natnahom12"><i class=" bx bxl-telegram" target="_blank"></i></a>
                    <a href="https://www.linkedin.com/in/natnahom-asfaw/" target="_blank"><i class=" bx bxl-linkedin"></i></a>
                </div>
                
        </div>

        <div class="cont cont2">
            <div class="contact-form2">
                <h2>Register</h2>
                <form action="register.php" method="post" id="registrationForm">
                  <input type="text" name="name" placeholder="Name" required/>
                  <input type="text" name="uname" placeholder="Preferred username" required/>
                  <!-- <input type="date" name="DofBirth" placeholder="Date of birth" required/> -->
                  <input type="email" name="email" placeholder="Email" required/>
                  <input type="tel" name="phone" placeholder="Phone" required/>
                  <input type="text" name="address" placeholder="Address" required/>
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
                
                  <button type="button" id="registerButton">Register</button>
                

                  <div class="popup" id="termsPopup">
    <div class="popup-content">
        <span class="close" id="closePopup">&times;</span>

        <div class="popup-content2">
        <h2 style="text-align: center;">Terms and Conditions</h2>
        <p><strong>Privacy and Security:</strong></p>
        <ul>
            <li>You agree not to upload inappropriate content.</li>
            <li>Inappropriate language will not be tolerated.</li>
            <li>We value your privacy and will protect your personal information.</li>
            <li>Failure to comply may result in removal from the site.</li>
            <li>Users must not engage in fraudulent activities or misrepresent their identity.</li>
            <li>Users are responsible for maintaining the confidentiality of their account information.</li>
            <li>Users must not upload, post, or transmit any content that is illegal, harmful, or offensive.</li>
            <li>All content, trademarks, and other intellectual property on the site are owned by the company or its licensors.</li>
            <li>Users are responsible for all fees associated with their transactions.</li>
            <li>Shopkeepers must pay for rented items by the agreed-upon date and in the condition specified in the rental agreement.</li>
            <li>The platform is not responsible for any damages or losses resulting from the use of the site or its services.</li>
            <li>The company reserves the right to modify these terms at any time.</li>
            <li>Any disputes arising from the use of the site will be resolved through arbitration.</li>
            <li>The company reserves the right to suspend or terminate any user's account for violations of these terms.</li>
        </ul>
        </div>
        <button type="submit" name="submit" class="agree-button" id="agreeButton">I Agree</button>
    </div>
</div>
                </form>
              </div>
        </div>

        

    </section>

    <script>
    const registerButton = document.getElementById('registerButton');
    const termsPopup = document.getElementById('termsPopup');
    const closePopup = document.getElementById('closePopup');
    const agreeButton = document.getElementById('agreeButton');
    const registrationForm = document.getElementById('registrationForm');

    registerButton.onclick = function() {
        if (registrationForm.checkValidity()) {
            termsPopup.style.display = 'flex';
        } else {
            registrationForm.reportValidity(); // Shows validation messages for required fields
        }
    };

    closePopup.onclick = function() {
        termsPopup.style.display = 'none';
    };

    agreeButton.onclick = function() {
        termsPopup.style.display = 'none';
        alert('Thank you for agreeing to the terms! Proceeding with registration.');
        registrationForm.submit(); // Submit the form
    };

    window.onclick = function(event) {
        if (event.target === termsPopup) {
            termsPopup.style.display = 'none';
        }
    };
</script>

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