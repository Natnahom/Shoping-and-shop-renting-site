<?php
    include ('../database/Database.php');

    $sqlBlog = "SELECT * FROM blog";

    $resultBlog = mysqli_query($conn, $sqlBlog);

    if (mysqli_num_rows($resultBlog) > 0){
      $rowBlog = mysqli_fetch_assoc($resultBlog);
    }
    else {
      $rowBlog = "Blog";
    }


?>

<!-- Dominant colors: White, black/grey and orange -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/css/forIndex.css">
    <link rel="stylesheet" href="../../Resources/css/forFooter.css">
    <link rel="stylesheet" href="../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script> 
    <title>Nathaven Shopysite</title>
</head>
<body>
    <section class="home">
        <img src="../../Resources/images/images1.png" alt="background" class="background_img">
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
        <div class="home-cont"> 
        <h2 class="home-title">Welcome to Nathaven</h2>
        <h3 class="home-title2">Where commerce and community converge!!</h3>
        
        <p class="home-description">Nathaven is your one-stop destination for all your retail needs.<br> 
            Discover a vibrant marketplace of shops to explore, and rent the<br> 
            perfect space to grow your business.</p>
            <div class="main_btns">
                <a href="shopViews/shop.php"><button><b>BUY PRODDUCTS</b></button></a>
                <a href="rentShop.php"><button><b>RENT SHOP</b></button></a>
            </div>
        </div>
    </section>

    <div class="blog" style="text-align: center;">
        <?php
          echo $rowBlog['HomeBlog'];
        ?>
    </div>

    <section class="home-main-cont3">
      
    <div class="main-cont-words3">
        <!-- <dotlottie-player src="https://lottie.host/378119c1-bdbe-4318-95ff-c516666c7247/iGz8FCi38q.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player> -->
        <div class="main-cont-anim3">
          <dotlottie-player class="moving-line-loop" src="https://lottie.host/e292da90-e73a-4cfb-b3f7-42498a26c400/DVPcFH0yXP.json" background="transparent" speed="1" style="width: 100%; height: 300px;" loop autoplay></dotlottie-player>
        </div>
        <h2> 
          DISCOVER HOW OUR WEBSITE CAN ELEVATE YOUR LIFESTYLE. 
         </h2>
      </div>

  </section>

  <section class="home-main-cont4">

      
    <div class="lists4 main-cont-words4">
      <dotlottie-player src="https://lottie.host/b478877c-2136-4c1e-a7e8-3323a57bf632/flf7Bu0REf.json" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></dotlottie-player>
      <h3>Easy To Use</h3>
      <p>Experience a hassle-free shopping and renting 
        <br/>journey with our user-friendly platform!</p>

    </div>

    <div class="lists4 main-cont-words4">
      <dotlottie-player src="https://lottie.host/63944718-411b-45ba-9d5c-00fa215bded7/uhp9vRaAab.json" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></dotlottie-player>

      <h3>Secure</h3>
      <p>We utilize advanced SSL encryption and trusted payment gateways to ensure that 
        your personal and financial information is always protected.</p>
    </div>
    <div class="lists4 main-cont-words4">
      <dotlottie-player src="https://lottie.host/c6277db0-e647-4f90-9a87-21417da0a40d/SPGK1QNukz.json" background="transparent" speed="1" style="width: 150px; height: 150px;" loop autoplay></dotlottie-player>
      <h3>Communication With Sellers</h3>
      <p> You can easily communicate with sellers 
        to ask questions, clarify details, and negotiate terms.</p>  
    </div>


    <div class="lists4 main-cont-words4">
      <div class="container">
        <div id="rank-stamp" class="stamp">
            <i class='bx bxs-medal'></i>
        </div>
    </div>
      <h3>Reliable</h3>
      <p>We prioritize reliability and transparency in 
        every transaction. Our platform connects you directly 
        with trusted sellers, ensuring that you can shop with 
        confidence.</p>
  
    </div>

  </section>

  <section class="home-main-cont2">
    <h2>AVAILABLE SHOPS</h2>

    <div class="main-cont-anim" id="rent-Options1">
      <div class="rent-Option First">
        <img src="../../Resources/images/SmallShopImg.jpg" alt="small shop">
        <h3>SMALL <br/>SHOPS</h3>
      </div>
      <div class="rent-Option Second">
        <img src="../../Resources/images/IntermediateShopImg.jpg" alt="intermediate shop">
        <h3>INTERMEDIATE<br/> SHOPS</h3>
      </div>
      <div class="rent-Option Third">
        <img src="../../Resources/images/BigShopImg.jpg" alt="big shop">
        <h3>BIG <br/>SHOPS</h3>
      </div>
    </div>
    <div class="main-cont-words">
      <p>Discover a diverse selection of shops available 
        at your fingertips, each offering unique products 
        and services tailored to meet your needs. Whether 
        you're looking for trendy fashion boutiques, local 
        artisan stores, or essential grocery outlets, our 
        platform connects you with a variety of options. 
        Each shop is carefully curated to ensure quality and 
        reliability, making your shopping experience seamless 
        and enjoyable.</p>
    </div>
          

</section>

    <section class="home-main-cont2">

        <div class="main-cont-anim" id="rentShopDiv">
         <dotlottie-player src="https://lottie.host/07583e67-a023-4445-b991-7bee02b77f5e/dPuiMZMnLc.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
         <h2></h2>
         <a href="rentShop.php"><button><b>SET UP YOUR SHOP NOW!</b></button></a>
        </div>
              

  </section>

    <section class="home-main-cont">

        <div class="main-cont-anim">
          <dotlottie-player src="https://lottie.host/5c58c80a-2308-4c2f-912b-7ea3cfa42a67/r4kqXeuKZD.json" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>
        </div>

        <div class="main-cont-words">
          <h2>Transform your experience with our innovative solutions!</h2>
          <p>  
            Enhance productivity, and bring joy to your daily routine. 
            Join countless satisfied customers who have embraced quality and 
            style. Don’t miss out on the chance to invest in excellence!</p>
            <a href="shopViews/shop.php"><button><b>BUY PRODDUCTS</b></button></a>
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
              <a href="#"><i class=' bx bxl-instagram'></i></a>
              <a href="#"><i class=" bx bxl-telegram"></i></a>
              <a href="#"><i class=" bx bxl-linkedin"></i></a>
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

      <script>
  document.addEventListener("DOMContentLoaded", function () {
    const stamp = document.getElementById('rank-stamp');

    function animateStamp() {
        // Start the animation
        stamp.style.opacity = 1;
        stamp.style.transform = 'scale(1)';
        
        // Wait for the animation to complete
        setTimeout(() => {
            // Reverse the animation
            stamp.style.opacity = 0;
            stamp.style.transform = 'scale(0.5)';

            // Wait before starting the next loop
            setTimeout(animateStamp, 500); // Adjust timing as needed
        }, 1000); // Duration of the visible state
    }

    // Start the animation loop
    animateStamp();
});
      </script>

      <script src="../../Resources/js/script.js"></script>

</body>
</html>

<!-- <?php
    // include 'footer.html';
?> -->