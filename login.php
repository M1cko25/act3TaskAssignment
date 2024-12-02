<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to dashboard or home
    exit();
}
$is_invalid = false;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM users
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    $sql2 = sprintf("SELECT * FROM admin WHERE email = '%s'", $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();

    $result2 = $mysqli->query($sql2);
    $admin = $result2->fetch_assoc();

    if ($user) {
        
        if (password_verify($_POST["password"], $user["Password"])) {
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["ID"];
            header("Location: index.php");
            exit;
        }
    } 
    if ($admin) {
            if (password_verify($_POST["password"], $admin["Password"])) {
                session_regenerate_id();
                $_SESSION["user_id"] = $admin["Id"];
                header("Location: index.php");
                exit;
            }
    }
    
    $is_invalid = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="/SSO.js" defer type="module"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Log in</h1>
             <?php if ($is_invalid): ?>
                    <em>Invalid login</em>
              <?php endif; ?>
    <form method="post" id="loginForm">
        <div>
            <input type="email" id="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        </div>
        <div>
            <input type="password" id="password" name="password" placeholder="Password">
        </div>
    
        <button> Signup</button>
    </form>
    <p>Or Log In With Google</p>
    <button id="google-login-btn" class="google-button">Login With Google</button>
</body>
</html>