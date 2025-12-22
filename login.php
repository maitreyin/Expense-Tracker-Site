<?php
session_start();
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trackexpa"; // **FIXED DB NAME**

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$user = $_POST['user'] ?? '';
$pass = $_POST['pass'] ?? '';

if ($user == '' || $pass == '') {
    echo json_encode(["status" => "error", "message" => "All fields required"]);
    exit;
}

// Find user
$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

$stmt->bind_result($uid, $hashed_pass);
$stmt->fetch();

// Verify password
if (password_verify($pass, $hashed_pass)) {
    $_SESSION['user_id'] = $uid; // **CRITICAL: User ID is set here**
    echo json_encode(["status" => "success", "message" => "Login successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Wrong password"]);
}

$conn->close();
?>