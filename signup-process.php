<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$mysqli = require __DIR__ . "/database.php";

if (empty($_POST['name'])){
    die('Name is required');
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    die('Valid email is required');
}
if (strlen($_POST['password']) < 8) {
    die('Password must be at least 8 characters');
}
if (!preg_match('/[a-z]/i', $_POST['password'])) {
    die('Password must contain at least one letter');
}
if (!preg_match('/[0-9]/', $_POST['password'])) {
    die('Password must contain at least one number');
}
if ($_POST['password'] !== $_POST['confirm-password']) {
    die('Passwords must match');
}

$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$verification_code = bin2hex(random_bytes(16));

$sql = "INSERT INTO users (FullName, Email, Password, verification_code, is_verified) VALUES (?, ?, ?, ?, 0)";
$stmt = $mysqli->stmt_init();

if (! $stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $password_hash, $verification_code);

if ($stmt->execute()) {
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'lutiva3@gmail.com'; 
        $mail->Password = 'xogg jgjl fpvt yfac'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom($_POST['email'], "Simple Task Assignement");
        $mail->addAddress($_POST['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = "Please click the following link to verify your email: <br>
                      <a href='http://127.0.0.1:5500/signup-success.html'>Verify Email</a>";

        $mail->send();
        header("Location: verification-pending.html");
        exit;
    } catch (Exception $e) {
        die("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
} else {
    if ($mysqli->errno === 1062) {
        die("email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
?>  
