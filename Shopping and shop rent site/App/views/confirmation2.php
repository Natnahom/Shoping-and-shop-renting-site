
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../Resources/images/logos/IClogo1.png">
    <link rel="stylesheet" href="../../Resources/boxicons-2.1.4/css/boxicons.min.css">
    <title>Confirmation - Nathaven Shopysite</title>
    <style>
        body{
            display: flex;
            flex-direction: column;
            text-align: center;
            align-items: center;
            justify-content: center;
        }
        h1,h2,h3{
            font-family: 'Playfair Display', Arial, Helvetica, sans-serif;
            font-weight: 700; /* Bold */
        }
        p{
            font-family: 'Lato', sans-serif;
            font-weight: 400; /* Regular */
        }
        button{
            background-color: rgb(255, 115, 0);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 15px;
            transition: 0.5s;
        }
        button:hover{
            background-color: rgb(255, 184, 126);
            color: black;
        }
    </style>
</head>
<body>

    <div style="color:green;font-size:100px;"><i class="bx bx-check-circle"></i></div>
    <h1>Deletion executed successfully!</h1>
    <h2>You will be redirected shortly.</h2>

    <script>
    // Prevent back navigation
    history.pushState(null, null, window.location.href);
    window.onpopstate = function () {
        history.pushState(null, null, window.location.href);
    };

    // Determine the redirect target based on the referrer
    var redirectTo;
    var referrer = document.referrer;

    if (referrer.includes("ItemInfo2.php")) {
        redirectTo = "dashboardAdmin.php"; // Redirect to dashboard.php if coming from ItemInfo.php
    } else {
        redirectTo = "index.php"; // Default redirect if referrer is unknown
    }

    // Automatically redirect after a few seconds
    setTimeout(function() {
        window.location.replace(redirectTo);
    }, 3000); // Redirect after 3 seconds
</script>

</body>
</html>