<?php
session_start();
include ('../../database/Database.php');

if (empty($_SESSION['usernameCust']) && empty($_SESSION['uname'])) { 
    header("Location: ../index.php"); // Redirect to index.php if not logged in
    exit(); // Stop script execution after redirect
}

// Check if the session belongs to a customer or shopkeeper
if (!empty($_SESSION['usernameCust'])) {
    // User is a customer
    $usernameCust = $_SESSION['usernameCust'];

    // Get unique shopkeepers the customer has interacted with
    $sql = "SELECT DISTINCT usernameShopk, (SELECT name FROM shopkeepers WHERE username = usernameShopk) AS name FROM messages WHERE usernameCust = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usernameCust);
} else {
    // User is a shopkeeper
    $usernameShopk = $_SESSION['uname'];

    // Get unique customers the shopkeeper has interacted with
    $sql = "SELECT DISTINCT usernameCust, (SELECT name FROM customers WHERE username = usernameCust) AS name FROM messages WHERE usernameShopk = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usernameShopk);
}

$stmt->execute();
$result = $stmt->get_result();

$users = []; // Array to hold either customers or shopkeepers
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

if (isset($_POST['deleteBtn'])) {
    $usernameToDelete = $_POST['usernameShopk'] ?? $_POST['usernameCust']; // Determine which user to delete messages for
    
    // Prepare delete statements based on who the user is
    if (!empty($_SESSION['usernameCust'])) {
        // Customer is deleting messages with a shopkeeper
        $sqlDelete = "DELETE FROM messages WHERE usernameShopk = ?";
    } else {
        // Shopkeeper is deleting messages with a customer
        $sqlDelete = "DELETE FROM messages WHERE usernameCust = ?";
    }

    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("s", $usernameToDelete);
    $stmtDelete->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Close connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../Resources/images/logos/IClogo1.png">
    <title>All Shopkeepers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .shopkeeper {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background-color 0.3s;
        }
        .shopkeeper:hover {
            background-color: #f0f0f0;
        }

        #deleteBtn{
            border: 1px solid red;
            border-radius: 5px;
            color: red;
            cursor: pointer;
            font-size: 15px;
            height: fit-content;
            z-index: 1000;
        }
    
        #deleteBtn:hover{
            background-color: red;
            color: black;
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
    </style>
</head>
<body>

<div class="container">
    <h2><?php echo !empty($_SESSION['usernameCust']) ? "Shopkeepers You Interacted With" : "Customers You Interacted With"; ?></h2>
    <div id="shopkeepers">
        <?php if (empty($users)): ?>
            <p>No users found.</p>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <div class="shopkeeper" onclick="startChat('<?php echo htmlspecialchars($user['usernameShopk'] ?? $user['usernameCust']); ?>', <?php echo !empty($_SESSION['usernameCust']) ? 'true' : 'false'; ?>)">
                    <?php echo "<h3>" . htmlspecialchars($user['name']) . "</h3>"; 
                        echo "<button id=\"deleteBtn\" onclick=\"toggleModal3('" . htmlspecialchars($user['usernameShopk'] ?? $user['usernameCust']) . "'); event.stopPropagation();\">Delete</button>";
                    ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div id="confirmModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Are you sure you want to delete all the messages with this user?</p>
      <form action="allMessages.php" method="post" id="deleteForm">
        <input type="hidden" name="usernameShopk" id="modalProductId">
        <button type="submit" id="confirmYes" name="deleteBtn">Yes</button>
        <button type="button" id="confirmNo" onclick="closeModal()">No</button>
      </form>
    </div>
    </div>

<script>
    function startChat(username, isShopkeeper) {
        // Check if the user is a shopkeeper or customer and set the URL parameter accordingly
        if (isShopkeeper) {
            window.location.href = `messages.php?usernameShopk=${username}`; // Redirect to the chat with the shopkeeper
        } else {
            window.location.href = `messages.php?usernameCust=${username}`; // Redirect to the chat with the customer
        }
    }
</script>
  
<script src="../../../Resources/js/script2.js"></script>


</body>
</html>