<?php
require("funzioni/auth.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { 
            font-family: sans-serif; 
            margin: 0; 
            line-height: 1.6; 
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container { 
            border: 1px solid #ccc; 
            padding: 30px; 
            border-radius: 8px; 
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 320px;
            text-align: center;
        }
        
        .titolo-login { 
            font-weight: bold; 
            font-size: 1.4em; 
            margin-top: 0; 
            margin-bottom: 20px;
            text-transform: uppercase; 
            color: #333; 
        }
        .form-login {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-login input[type="text"],
        .form-login input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }
        input[type="submit"], .btn {
            background-color: #9c9998;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }
        input[type="submit"], .btn:hover {
            background-color: #303030;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <p class="titolo-login">Accedi</p>
        <form action="funzioni/login.php" method="post" class="form-login">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="psw" placeholder="Password" required>
            <input type="submit" name="Accedi" value="Accedi" class="btn">
        </form>
    </div>
</body>
</html>
