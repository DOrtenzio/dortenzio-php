<?php
if(session_status()!==PHP_SESSION_ACTIVE) session_start();

if(isset($_SESSION["nomeutente"]) && !empty($_SESSION["nomeutente"]) && $_SESSION["nomeutente"]=="dortenzio" && isset($_SESSION["psw"]) && !empty($_SESSION["psw"]) && $_SESSION["psw"]=="verifica") return true;
return false;