<?php
session_start();
include('config/db.php');

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) {
        $_SESSION['user'] = $username;
        
        // Meka thama redirect karana kotiya
        header("Location: dashboard.php");
        exit(); 
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nathaliya  (PVT) LTD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <nav>
        <div class="nav-container">
            <div class="logo-section">
                
                <span class="company-name">Nathaliya  (PVT) LTD</span>
            </div>
            <ul id="nav-links">
                <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#"><i class="fas fa-info-circle"></i> Support</a></li>
            </ul>
        </div>
    </nav>

    <div class="login-box">
        <div class="login-header">
            <i class="fas fa-user-circle"></i>
            <h2>User Login</h2>
        </div>
        
        <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        
        <form method="POST" id="loginForm">
            <div class="input-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username">
            </div>
            <div class="input-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password">
            </div>
            
            <div class="form-options">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit" name="login">LOGIN</button>
        </form>
    </div>

    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>