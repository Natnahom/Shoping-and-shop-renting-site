<?php
session_start();
include ('../../database/Database.php');

// Check if either customer or shopkeeper is logged in
if (empty($_SESSION['usernameCust']) && empty($_SESSION['uname'])) {
    header("Location: ../index.php"); // Redirect to index.php
    exit();
}

// Determine the logged-in user and their role
$usernameCust = $_SESSION['usernameCust'] ?? null; // Use null coalescing operator
$usernameShopk = $_SESSION['uname'] ?? null;

// Get the other user's username from the GET parameter
$otherUsername = $_GET['usernameShopk'] ?? $_GET['usernameCust'] ?? null;

// Fetch messages based on the logged-in user's role
if ($usernameCust) {
    // Customer view
    $sql = "SELECT * FROM messages WHERE (usernameCust = ? AND usernameShopk = ?) OR (usernameCust = ? AND usernameShopk = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $usernameCust, $otherUsername, $otherUsername, $usernameCust);
} else if ($usernameShopk) {
    // Shopkeeper view
    $sql = "SELECT * FROM messages WHERE (usernameCust = ? AND usernameShopk = ?) OR (usernameCust = ? AND usernameShopk = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $otherUsername, $usernameShopk, $usernameShopk, $otherUsername);
} else {
    echo json_encode(['error' => 'User not found.']);
    exit;
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Handle saving a message or deleting a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->message)) {
        // Handle sending a message
        $message = $data->message;

        // Determine the usernames based on the logged-in user
        if ($usernameCust) {
            // User is a customer
            $usernameCustToSend = $usernameCust;
            $usernameShopkToSend = $data->usernameShopk ?? $otherUsername; // Ensure this is coming from the frontend
        } else if ($usernameShopk) {
            // User is a shopkeeper
            $usernameShopkToSend = $usernameShopk;
            $usernameCustToSend = $data->usernameCust ?? $otherUsername; // Ensure this is coming from the frontend
        }

        // error_log("usernameCustToSend: " . $usernameCustToSend);
        // error_log("usernameShopkToSend: " . $usernameShopkToSend);

        // Ensure at least one username is set
        if ($usernameCustToSend === null && $usernameShopkToSend === null) {
            echo json_encode(['error' => 'Both usernames are missing.']);
            exit;
        }

        // Prepare to save the message
        $sql = "INSERT INTO messages (message, usernameCust, usernameShopk) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['error' => 'Database prepare error: ' . $conn->error]);
            exit;
        }

        $stmt->bind_param("sss", $message, $usernameCustToSend, $usernameShopkToSend);
        if ($stmt->execute()) {
            $id = $stmt->insert_id; // Get the last inserted ID
            echo json_encode(['message' => $message, 'id' => $id, 'sender' => $usernameCust ? 'customer' : 'shopkeeper']);
        } else {
            echo json_encode(['error' => 'Failed to execute statement: ' . $stmt->error]);
        }
        $stmt->close();
        exit; // End script after handling the POST request
    }

    if (isset($data->id)) {
        // Handle deleting a message
        $id = $data->id;
    
        // Log the ID to check if it's being received correctly
        error_log("Attempting to delete message with ID: " . $id);
    
        // Prepare the SQL statement
        $sql = "DELETE FROM messages WHERE id = ? AND (usernameCust = ? OR usernameShopk = ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['error' => 'Database prepare error: ' . $conn->error]);
            exit;
        }
    
        // Determine the usernames based on the session
        $usernameCustToUse = $usernameCust ?? $otherUsername;
        $usernameShopkToUse = $usernameShopk ?? $otherUsername;
    
        // Log the usernames to see what is being passed
        error_log("Deleting message with ID: $id by userCust: $usernameCustToUse, userShopk: $usernameShopkToUse");
    
        // Bind parameters
        $stmt->bind_param("iss", $id, $usernameCustToUse, $usernameShopkToUse);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'No message found with that ID for the user.']);
            }
        } else {
            echo json_encode(['error' => 'Failed to delete message: ' . $stmt->error]);
        }
        $stmt->close();
        exit; // End script after handling the POST request
    }
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
    <title>Messages with <?php echo htmlspecialchars($otherUsername); ?></title>
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
            overflow: hidden;
        }
        .messages {
            padding: 20px;
            height: 400px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            position: relative;
        }
        .message.shopkeeper {
            background-color: #e0f7fa; /* Light blue for shopkeeper messages */
            text-align: center;
        }
        .message.customer {
            background-color: #ffe0b2; /* Light orange for customer messages */
            text-align: center;
        }
        .options {
            position: absolute;
            top: 5px;
            left: 5px;
            display: none;
            flex-direction: column;
            gap: 2px;
        }
        .options button {
            background-color: rgb(255, 115, 0);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px;
        }
        .options button:hover {
            background-color: #e64a19;
        }
        .input-area {
            display: flex;
            padding: 10px;
            background: #f9f9f9;
        }
        .input-area textarea {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
            resize: none; /* Prevent manual resizing */
            overflow-y: auto; /* Allow scrolling if content overflows */
        }
        .input-area button {
            padding: 10px;
            border: none;
            background: rgb(255, 115, 0);
            color: white;
            border-radius: 5px;
        }
        .input-area button:hover {
            background: #e64a19;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 style="text-align: center;">Chat with <?php echo htmlspecialchars($otherUsername); ?></h2>
    <p style="color: grey; text-align: center;">(REMARK: THIS IS NOT A MESSAGING PLATFORM TEXT IMPORTANT INFORMATION AND IF YOU WANT TO HAVE A CONVERSATION MEET EACH OTHER IN ANOTHER CHAT PLATFORM <br> YOU'LL NEED TO REFRESH THE PAGE TO SEE THE SENDERS RESPONSE!)</p>
    <div class="messages" id="messages">
        <?php foreach ($messages as $message): ?>

            <div class="message <?php echo ($message['usernameCust'] == ($usernameCust ?? null)) ? 'customer' : 'shopkeeper'; ?>" data-id="<?php echo $message['id']; ?>">
                
                <?php echo htmlspecialchars($message['message']); ?>
                <span onclick="toggleOptions(this)" style="cursor: pointer; font-size:20px; float:right; margin-left:5px;">⋮</span>
                <div class="options">
                    <button onclick="editMessage(<?php echo $message['id']; ?>)">Edit</button>
                    <button onclick="deleteMessage(<?php echo $message['id']; ?>)">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="input-area">
        <textarea id="messageInput" placeholder="Type your message... (Enter -> Send, Shift + Enter -> New Line)" rows="1"></textarea>
        <button id="sendButton">Send</button>
    </div>
</div>

<script>
    const messagesContainer = document.getElementById('messages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    sendButton.addEventListener('click', () => {
        const messageText = messageInput.value.trim();
        if (messageText) {
            sendMessage(messageText);
            messageInput.value = '';
        }
    });

    messageInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault(); // Prevent the default action (new line)
            sendMessage(messageInput.value.trim());
            messageInput.value = ''; // Clear the input
        }
    });

    function sendMessage(text) {
        const usernameShopk = '<?php echo isset($usernameShopk) ? $usernameShopk : $otherUsername; ?>'; // Shopkeeper's username
        const usernameCust = '<?php echo isset($usernameCust) ? $usernameCust : $otherUsername;?>'; // Customer's username
    const isShopkeeper = !!'<?php echo !empty($_SESSION['usernameCust']) ? "false" : "true"; ?>';

    // console.log(usernameShopk, usernameCust, isShopkeeper);
    fetch('messages.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            message: text,
            usernameShopk: isShopkeeper ? usernameShopk : null,
            usernameCust: !isShopkeeper ? usernameCust : usernameCust || usernameShopk // Ensure a valid username is sent
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            console.error('Error from server:', data.error);
            alert('Error: ' + data.error);
        } else {
            appendMessage(data.message, data.id, data.sender);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred: ' + error.message);
    });
}

    function appendMessage(text, id, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sender);
        messageDiv.setAttribute('data-id', id);
        messageDiv.innerHTML = `
            ${text}
            <span onclick="toggleOptions(this)" style="cursor: pointer; float:right; font-size:20px; margin-left:5px;">⋮</span>
            <div class="options">
                <button onclick="editMessage(${id})">Edit</button>
                <button onclick="deleteMessage(${id})">Delete</button>
            </div>
        `;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function toggleOptions(element) {
        const options = element.parentNode.querySelector('.options');
        options.style.display = options.style.display === 'block' ? 'none' : 'block';
    }

    function editMessage(id) {
        const messageDiv = document.querySelector(`.message[data-id='${id}']`);
        const text = messageDiv.firstChild.textContent.trim();
        messageInput.value = text;
        deleteMessage(id); // Optionally remove it from the DOM and database for editing
    }

    function deleteMessage(id) {
    const username = '<?php echo $usernameCust ?? $usernameShopk; ?>'; // Get the appropriate username from PHP
    const isShopkeeper = !!'<?php echo !empty($_SESSION['usernameCust']) ? "false" : "true"; ?>'; // Check if the user is a shopkeeper

    fetch(`messages.php?usernameShopk=${isShopkeeper ? username : ''}&usernameCust=${!isShopkeeper ? username : ''}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const messageDiv = document.querySelector(`.message[data-id='${id}']`);
            messagesContainer.removeChild(messageDiv);
        } else {
            console.error('Error deleting message:', data.error);
            alert('Error deleting message: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred: ' + error.message);
    });
}
</script>


</body>
</html>