<?php
if(session_status()!==PHP_SESSION_ACTIVE) session_start();

if(isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["psw"]) && !empty($_POST["psw"])){
    if($_POST["username"]=="dortenzio" && $_POST["psw"]=="verifica"){
        $_SESSION["nomeutente"]=$_POST["username"];
        $_SESSION["psw"]=$_POST["psw"];
        header("Location: ../index.php");
    } else{
        $_SESSION["err"]="errlog";
        header("Location: ../login.php");
    }
} else header("Location: ../errorpage.html");