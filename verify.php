<?php
$mysqli = require __DIR__ . "/database.php";

if (isset($_GET['code'])) {
    $sql = "UPDATE users SET is_verified = 1 WHERE verification_code = ?";
    $stmt = $mysqli->stmt_init();
    
    if (!$stmt->prepare($sql)) {
        die("SQL error: " . $mysqli->error);
    }
    
    $stmt->bind_param("s", $_GET['code']);
    
    if ($stmt->execute()) {
        header("Location: verification-success.html");
        exit;
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
?>
