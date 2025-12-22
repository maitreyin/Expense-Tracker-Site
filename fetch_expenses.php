<?php
session_start(); // **CRITICAL FIX 1: SESSION START ADDED**
header("Content-Type: application/json");

// **CRITICAL FIX 2: CHECK FOR LOGGED IN USER**
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]); 
    exit;
}
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "trackexpa"); // **FIXED DB NAME**

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

// **CRITICAL FIX 3: SELECT query filters by user_id**
$stmt = $conn->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY entry_date DESC, id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>