<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $mysqli = getDBConnection();
    $stmt = $mysqli->prepare("INSERT INTO teachers (username, password) VALUES (?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            echo "Registration successful. <a href='index.php'>Login here</a>";
        } else {
            echo "Registration failed: " . $stmt->error;
        }
        $stmt->close();
    }
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
        <form method="post" action="register.php" class="login-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <div class="already-have-account">
            Already have an account? <a href="index.php">Login here</a>
        </div>
    </div>
</body>
</html>


