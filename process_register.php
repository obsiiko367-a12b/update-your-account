<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "user_form");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $phone = $_POST['phone_number'];
    $code  = $_POST['confirmation_code'];

    // 1. Check if the phone number already exists to avoid duplicates
    $checkQuery = $conn->prepare("SELECT phone_number FROM users WHERE phone_number = ?");
    $checkQuery->bind_param("s", $phone);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "Error: This phone number is already registered.";
    } else {
        // 2. Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, phone_number, confirmation_code) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fname, $lname, $phone, $code);

        if ($stmt->execute()) {
            echo "Registration successful! <a href='login.html'>Login here</a>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $checkQuery->close();
}
$conn->close();
?>