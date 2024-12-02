<?php

session_start();
$dateToday = date("m/d/Y", time());
if (isset($_SESSION["user_id"])) {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM users
            WHERE id = {$_SESSION["user_id"]}";
            
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    if (!$user) {
        $sql2 = "SELECT * FROM admin WHERE id = {$_SESSION['user_id']}";
        $result2 = $mysqli->query($sql2);
        $admin = $result2->fetch_assoc();
    }

    $get_tasks_sql = "SELECT * FROM tasks";
    $get_tasks_result = $mysqli->query($get_tasks_sql);
    $tasks = $get_tasks_result->fetch_all(MYSQLI_ASSOC);

    if (isset($_POST['TaskName']) && isset($_POST['TaskDue'])) {
        $taskName = $_POST['TaskName'];
        $dueDate = $_POST['TaskDue'];
        $insert = "INSERT INTO tasks (Name, DueDate, Status) VALUES (?, ?, 'Pending')";
        $stmt = $mysqli->stmt_init();

        if (! $stmt->prepare($insert)) {
            die("SQL error: " . $mysqli->error);
        }
        $stmt->bind_param("ss", $taskName, $dueDate);
        $added = $stmt->execute();
        header("Location: index.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    
    <h1>Home</h1>
    
    <?php if (isset($user)): ?>
        
        <p>Hello <b><?= htmlspecialchars($user["FullName"]) ?></b></p>

        <table class="bg-white p-3 rounded-md">
            <tr class="text-black">
                <th>Task Number</th>
                <th>Task Name</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            
            <?php foreach ($tasks as $index => $task){
            echo "<tr ". "class=" . ($index % 2 == 0 ? 'text-white' : 'text-black') . ">";
                    echo "<td>" . $task['Id'] . "</td>";
                    echo "<td>" . $task['Name'] . "</td>";
                    echo "<td>" . $task['DueDate'] . "</td>";
                    echo "<td>" . $task['Status'] . "</td>";
                    if ($task['Status'] == 'Pending') {
                    echo "<td><a href='update-status.php?id=" . $task['Id'] ."'>Complete</td>";
                    } else {
                        echo "<td></td>";
                    }
                }
            echo "</tr>";
                ?>
        </table>

        <p><a href="logout.php">Log out</a></p>
    <?php elseif (isset($admin)): ?>
        <p>Hello <b><?= htmlspecialchars($admin["Name"]) ?></b></p>
        <form action="" method="post">
            <label for="TaskName">Enter a Task Name</label>
            <input class="p-1" type="text" name="TaskName" id="">
            <label for="TaskDue">Select Task Due Date</label>
            <input type="date" name="TaskDue" id="" value="<?php echo date('Y-m-d' ); ?>" >
            <br>
            <input type="submit" name="add" value="Add Task">
        </form>
        <?php if (isset($added)): ?>
            <p>Task Added</p>
            <?php endif; ?>
            <br><br>
        <table class="bg-white p-3 rounded-md">
            <tr class="text-black bg-gray-200">
                <th>Task Number</th>
                <th>Task Name</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($tasks as $index => $task){
            echo "<tr ". "class=" . ($index % 2 == 0 ? 'text-white' : 'text-black') . ">";
                    echo "<td>" . $task['Id'] . "</td>";
                    echo "<td>" . $task['Name'] . "</td>";
                    echo "<td>" . $task['DueDate'] . "</td>";
                    echo "<td>" . $task['Status'] . "</td>";
                    echo "<td><a href='delete.php?id=" . $task['Id'] . "'>Delete</a></td>";
                }
            echo "</tr>";
                ?>
        </table>
        <p><a href="logout.php">Log out</a></p>
    <?php else: ?>
        
        <p><a href="login.php">Log in</a> or <a href="/signup.html">sign up</a></p>
        
    <?php endif; ?>
    
</body>
</html>