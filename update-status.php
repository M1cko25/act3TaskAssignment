<?php
session_start();
if (isset($_SESSION["user_id"])) {
    $mysqli = require __DIR__ . "/database.php";

    if (isset($_GET['id'])) {
        $taskId = $_GET['id'];
        $update_sql = "UPDATE tasks SET Status = 'Completed' WHERE Id = ?";
        $stmt = $mysqli->stmt_init();

        if ($stmt->prepare($update_sql)) {
            $stmt->bind_param("i", $taskId);
            $stmt->execute();

            header("Location: index.php");
            exit;
        }
    }
}

header("Location: login.php");