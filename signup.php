<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trackexpa"; // <-- UPDATED DB NAME

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$user = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if ($user == '' || $email == '' || $pass == '') {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Hash password
$hashed = password_hash($pass, PASSWORD_BCRYPT);

// Check duplicate user
$check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
$check->bind_param("ss", $user, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "User already exists"]);
    exit;
}

// Insert user
$stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $user, $email, $hashed);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Signup successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Signup failed"]);
}

$conn->close();
?>