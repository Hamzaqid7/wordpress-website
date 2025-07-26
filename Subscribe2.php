<?php
// 1. Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $User_name = $_POST['username'];
    $email = $_POST['email'];
    $Subject = $_POST['subject'];
    $Message = $_POST['messages'];

    // 2. Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // 3. DB connection parameters
    $host = "localhost";
    $db = "ecommerce1";
    $user = "root";       // use your DB username
    $pass = "";           // use your DB password

    // 4. Create MySQLi connection
    $conn = new mysqli($host, $user, $pass, $db);

    // 5. Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 6. Check if email already exists
    $check = $conn->prepare("SELECT username FROM clients WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "This email $email has already subscribed!";
        $check->close();
        $conn->close();
        exit();
    }
    $check->close();

    // 7. Insert data into 'clients' table
    $stmt = $conn->prepare("INSERT INTO clients (username, email, subjects, messages) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $User_name, $email, $Subject, $Message);

    if ($stmt->execute()) {
        echo "Thanks for reaching out! We'll get back to you shortly.";
    } else {
        echo "Something went wrong. Please try again later.";
    }

    // 8. Close connections
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>