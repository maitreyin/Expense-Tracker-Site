<?php
session_start(); // **CRITICAL FIX 1: SESSION START ADDED**
header("Content-Type: application/json");

// **CRITICAL FIX 2: CHECK FOR LOGGED IN USER**
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}
$user_id = $_SESSION['user_id']; // Get the user ID from the session

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "trackexpa"; // **FIXED DB NAME**

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => $conn->connect_error]);
    exit;
}

$title = $_POST['title'] ?? null;
$amount = floatval($_POST['amount'] ?? 0);
$type = $_POST['type'] ?? null;
$entry_date = $_POST['entry_date'] ?? null; // **CRITICAL: Retrieve the date**

if (!$title || !$amount || !$type || !$entry_date) { // Check for date now
    echo json_encode(["status" => "error", "message" => "Missing required fields (title, amount, type, date)"]);
    exit;
}

// **CRITICAL FIX 3: INSERT query includes user_id and entry_date**
$stmt = $conn->prepare("INSERT INTO expenses (user_id, title, amount, type, entry_date) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("isdss", $user_id, $title, $amount, $type, $entry_date);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Entry added successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>