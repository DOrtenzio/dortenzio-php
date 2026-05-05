<?php
require("funzioni/auth.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <p>Accedi</p>
    <form action="funzioni/login.php" method="post">
        <input type="text" name="username" required>
        <input type="password" name="psw" required>
        <input type="submit" name="Accedi" value="Accedi">
    </form>
</body>
</html>