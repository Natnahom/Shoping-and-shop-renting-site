<?php
    session_start();
?>
<?php
    include ('../database/Database.php');

    if (empty($_SESSION['uname'])) { 
        header("Location: ../views/index.php"); // Redirect to index.php
        exit(); // Stop script execution after redirect
      }  

    $sqlA = "SELECT * FROM admin";
    $resultA = mysqli_query($conn, $sqlA);

    if (mysqli_num_rows($resultA) > 0){
      $rowA = mysqli_fetch_assoc($resultA);
    }
    else {
      echo "<h2 style=\"color:Red; text-align:center; position:absolute; width:100%;\">Name not found</h2>";
    }


    if (isset($_POST['submit'])){
        $uname = $_SESSION['uname'];
        $typeOfShop = $_POST['typeOfShop'];
        $availability = "On";
        $sql1 = "SELECT price, updateDate FROM shopkeepers WHERE username = '$uname'";
        
        $result1 = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result1) > 0){
            $row = mysqli_fetch_assoc($result1);

            $otherDate = $row['updateDate'];
            
            if ($typeOfShop == "Small"){
                $days = daysPassed($otherDate);

                if ($days <= 5){
                    $price = $row['price'];
                    $price += $rowA["SmallPrice"];
                    $sql2 = "UPDATE shopkeepers SET price = $price, typeOfShop = '$typeOfShop', updateDate = NOW(), availability = '$availability' WHERE username = '$uname'";
                    try{
                        mysqli_query($conn, $sql2);
                        echo "<h3 style = \"color:green; text-align:center; position:absolute; width:100%;\">Rent updated successfully!</h3>";
                        header('Location: ./login.php');

                        // session_destroy();
                    }
                    catch (mysqli_sql_exception){
                        echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">You didn't choose a price or type of shop!</h3>";

                    }
                }
                else{
                    echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">You can't pay until you reach more than 25 days!</h3>";
                }
            }
            else if ($typeOfShop == "Intermediate"){
                $days = daysPassed($otherDate);

                if ($days <= 5){
                    $price = $row['price'];
                    $price += $rowA["IntermediatePrice"];
                    $sql2 = "UPDATE shopkeepers SET price = $price, typeOfShop = '$typeOfShop', updateDate = NOW(), availability = '$availability' WHERE username = '$uname'";
                    try{
                        mysqli_query($conn, $sql2);
                        echo "<h3 style = \"color:green; text-align:center; position:absolute; width:100%;\">Rent updated successfully!</h3>";
                        header('Location: ./login.php');
                        // session_destroy();
                    }
                    catch (mysqli_sql_exception){
                        echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">You didn't choose a price or type of shop!</h3>";

                    }
                }
            }
            else if ($typeOfShop == "Big"){
                $days = daysPassed($otherDate);

                if ($days <= 5){
                    $price = $row['price'];
                    $price += $rowA["BigPrice"];
                    $sql2 = "UPDATE shopkeepers SET price = $price, typeOfShop = '$typeOfShop', updateDate = NOW(), availability = '$availability' WHERE username = '$uname'";
                    try{
                        mysqli_query($conn, $sql2);
                        echo "<h3 style = \"color:green; text-align:center; position:absolute; width:100%;\">Rent updated successfully!</h3>";
                        header('Location: ./login.php');
                        // session_destroy();
                    }
                    catch (mysqli_sql_exception){
                        echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">You didn't choose a price or type of shop!</h3>";

                    }
                }
                else{
                    echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">Couldn't update rent!</h3>";
                }
            }
            else{
                echo "<h3 style = \"color:red; text-align:center; position:absolute; width:100%;\">You didn't choose a price!</h3>";
            }
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/css/forContact.css"> 
    <link rel="stylesheet" href="../../Resources/css/forRegister.css"> 
    <script src="https://kit.fontawesome.com/8693f8f216.js" crossorigin="anonymous"></script>
    <title>Log in - Nathaven Shopysite</title>
</head>
<body>
<a href="../views/index.php"><button id="btn">Back to Home</button></a>
<section class="main-cont">
        <div class="cont cont1">
            <h2>Here to pay rent!</h2>
            <p>Complete the inputs here, chose the type of shop you want and submit. 
                <!--If you’ve forgotten your password, don’t 
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
                <h2>Rent</h2>
                <form action="rentUpdate.php" method="post">
                    <label for="typeOfShop">Type of shop: </label>
                    <select id="typeOfShop" name="typeOfShop" style="width: calc(100% - 20px);padding: 10px;border: 1px solid #ccc;border-radius: 4px;box-sizing: border-box; margin-bottom:20px;" required>
                            <option value="Small">Small shop (<?php echo $rowA["SmallPrice"]?>ETB)</option>
                            <option value="Intermediate">Intermediate shop (<?php echo $rowA["IntermediatePrice"]?>ETB)</option>
                            <option value="Big">Big shop (<?php echo $rowA["BigPrice"]?>ETB)</option>
                    </select>
                  <input type="text" name="remark" placeholder="Remark"/>
                  <button type="submit" name="submit">Submit</button>
                </form>
              </div>
        </div>

    </section>
</body>
</html>