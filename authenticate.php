<?php
session_start();

// Database configuration
$host = "localhost";
$db_user = "root"; // Default XAMPP/WAMP user
$db_pass = "";     // Default password
$db_name = "user_form";

// Create connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone_number'];
    $code = $_POST['confirmation_code'];

    // SQL to check if user exists with matching phone and code
    // Using Prepared Statements to prevent SQL Injection
    $stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE phone_number = ? AND confirmation_code = ?");
    $stmt->bind_param("ss", $phone, $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user['first_name'] . " " . $user['last_name'];
        
        echo "Welcome, " . $_SESSION['user'] . "! Login successful.";
        // header("Location: dashboard.php"); // Redirect to a protected page
    } else {
        echo "Invalid phone number or confirmation code.";
    }
    
    $stmt->close();
}
$conn->close();
?>