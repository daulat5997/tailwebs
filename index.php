<?php
session_start();
require 'db.php';

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $mysqli = getDBConnection();
    $stmt = $mysqli->prepare("SELECT * FROM teachers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['teacher_id'] = $user['id'];
        header('Location: home.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link href="assets/css/styles.css" rel="stylesheet">
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f3f3;
        }

        .login-container {
            background-color: #ffffff;
            width: 350px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            color: #e74c3c;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .login-form label {
            display: block;
            font-size: 14px;
            color: #333333;
            margin-bottom: 5px;
            text-align: left;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-form .forgot-password {
            display: inline-block;
            font-size: 12px;
            color: #3498db;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .login-form .forgot-password:hover {
            text-decoration: underline;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #333333;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-form button:hover {
            background-color: #555555;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="logo">Tailwebs</h1>
        <form method="post" action="index.php" class="login-form">
            <?php if ($error): ?>
                <div class="error-message" style="color:red;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</body>
</html>
