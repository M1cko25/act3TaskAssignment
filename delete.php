<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";
    
    if (isset($_GET['id'])) {
        $taskId = $_GET['id'];
        
        $delete_sql = "DELETE FROM tasks WHERE Id = ?";
        $stmt = $mysqli->stmt_init();
        
        if ($stmt->prepare($delete_sql)) {
            $stmt->bind_param("i", $taskId);
            $stmt->execute();
            
            header("Location: index.php");
            exit;
        }
    }
}

// If not logged in, redirect to login page
header("Location: login.php");