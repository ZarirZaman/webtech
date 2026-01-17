<?php

require_once '../Model/config.php';
require_once '../Model/auth.php';
session_start();

$error = "";


if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn,
        "SELECT * FROM admin WHERE username='$username' AND password='$password'"
    );

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
   
    <title>Admin Login</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <h2 class="auth-title">Admin Login</h2>

        <?php if ($error) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>

        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button class="btn btn-primary" name="login" style="width:100%">
                Login
            </button>
        </form>
    </div>
</div>

</body>
</html>
