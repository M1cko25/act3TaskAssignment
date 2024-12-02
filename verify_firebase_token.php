<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$mysqli = require __DIR__ . "/database.php";


$sql = sprintf("SELECT * FROM users WHERE email = '%s'",
    $mysqli->real_escape_string($data['email']));

$result = $mysqli->query($sql);
$user = $result->fetch_assoc();

if (!$user) {
    $sql = "INSERT INTO users (email, FullName, firebase_uid) 
            VALUES (?, ?, ?)";
            
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sss", 
        $data['email'],
        $data['name'],
        $data['firebase_uid']
    );
    
    $stmt->execute();
    $user_id = $mysqli->insert_id;
} else {
    $user_id = $user['ID'];
}

$_SESSION['user_id'] = $user_id;

echo json_encode(['success' => true]);
