<?php
  session_start();
?>
<?php

    include ('../database/Database.php');

    $sqlA = "SELECT * FROM admin";
    $sqlBlog = "SELECT * FROM blog";

    $resultA = mysqli_query($conn, $sqlA);
    $resultBlog = mysqli_query($conn, $sqlBlog);

    if (mysqli_num_rows($resultA) > 0){
      $rowA = mysqli_fetch_assoc($resultA);
    }
    else {
      echo "<h2 style=\"color:Red; text-align:center;\">Name not found</h2>";
    }

    if (mysqli_num_rows($resultBlog) > 0){
      $rowBlog = mysqli_fetch_assoc($resultBlog);
    }
    else {
      $rowBlog = "Blog";
    }

  if (isset($_POST['rent1'])){
    $rent1 = $rowA["SmallPrice"];
    $_SESSION['rent'] = $rent1;
    $_SESSION['typeOfShop'] = "Small";
    header('Location: ../auth/register.php');
  }
  else if (isset($_POST['rent2'])){
    $rent2 = $rowA["IntermediatePrice"];
    $_SESSION['rent'] = $rent2;
    $_SESSION['typeOfShop'] = "Intermediate";
    header('Location: ../auth/register.php');
  }
  else if (isset($_POST['rent3'])){
    $rent3 = $rowA["BigPrice"];
    $_SESSION['rent'] = $rent3;
    $_SESSION['typeOfShop'] = "Big";
    header('Location: ../auth/register.php');
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/css/forShop.css">
    <link rel="stylesheet" href="../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../Resources/css/forRentShop.css">
    <link rel="stylesheet" href="../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Rent shops - Nathaven Shopysite</title>
</head>
<body>

    <section class="home">
        <nav class="home-nav">
            <a href="index.php"><img src="../../Resources/images/logos/logo1.png" alt="logo"></a>
            
            <ul class="dropdown-content">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.html">About us</a></li>
                <li><a href="contact.html">Contact us</a></li>
                <li><a href="../auth/login.php"><button>Log in</button></a></li>
                <li><a href="../auth/register.php"><button>Register</button></a></li>
            </ul>

            <div class="toggle_btn">
                <i class="bx bx-menu"></i>
                <!-- <i class="fa-solid fa-x"></i> -->
            </div>
                
        </nav>

        <ul class="dropdown-menu">
                <a href="index.php"><li class="links">Home</li></a>
                <a href="about.html"><li class="links">About us</li></a>
                <a href="contact.html"><li class="links">Contact us</li></a>
                <li><a href="../auth/login.php"><button>Log in</button></a></li>
                <li><a href="../auth/register.php"><button>Register</button></a></li>
        </ul>

      <div class="blog" style="text-align: center;">
      <?php
          echo $rowBlog['RentBlog'];
      ?>
      </div>
        
    </section>

    <section class="card-cont">
        <div class="card card1">
            <h2>Small shop</h2>
            <br>
            <p>
                You can rent this to get started with little items.
                This is like a regular small shop you see in real life.
            </p>
            <br>
            <ul>
                <li>Can hold <?php echo $rowA["SmallSlots"]?> items</li>
                <!-- <li>Can hold 20 items</li> -->
                <!-- <li>Can hold 20 items</li> -->
                <!-- <li>Can hold 20 items</li> -->
            </ul>
            <br>
            <h3><?php echo $rowA["SmallPrice"]?> birr/month</h3><br>
            <form action="rentShop.php" method="post">
              <button type="submit" name="rent1">Rent</button>
            </form>
        </div>
        <div class="card card2">
        <h2>Intermediate shop</h2>
        <br>
        <p>
            You can rent this to make it just like a mini market.
            This is like a regular mini market you see in real life.
        </p>
        <br>
            <ul>
                <li>Can hold <?php echo $rowA["IntermediateSlots"]?> items</li>
                <!-- <li>Can hold 20 items</li> -->
                <!-- <li>Can hold 20 items</li> -->
                <!-- <li>Can hold 20 items</li> -->
            </ul>
            <br>
            <h3><?php echo $rowA["IntermediatePrice"]?> birr/month</h3>
            <br>
            <form action="rentShop.php" method="post">
              <button type="submit" name="rent2">Rent</button>
            </form>
        </div>
        <div class="card card3">
        <h2>Big shop</h2>
        <br>
        <p>
            You can rent this to make it just like a super market.
            This is like a regular super market you see in real life.
        </p>
        <br>
            <ul>
                <li>Can hold <?php echo $rowA["BigSlots"]?> items</li>
                <!-- <li>Can hold 20 items</li> -->
                <!-- <li>Can hold 20 items</li> -->
                <!-- <li>Can hold 20 items</li> -->
            </ul>
            <br>
            <h3><?php echo $rowA["BigPrice"]?> birr/month</h3>
            <br>
            <form action="rentShop.php" method="post">
              <button type="submit" name="rent3">Rent</button>
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
    
    <script src="../../Resources/js/script.js"></script>
</body>
</html>