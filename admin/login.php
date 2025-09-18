<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $result = $conn->query("SELECT * FROM admins WHERE username='$username' AND password='$password'");
    if ($result->num_rows > 0) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Login</title></head>
<body>
<form method="POST">
    <h2>Admin Login</h2>
    <input type="text" name="username" placeholder="Username"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <button type="submit">Login</button>
    <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
</form>
</body>
</html>
