<?php
if(session_status()!==PHP_SESSION_ACTIVE) session_start();
require_once("operazioni.php");
if(!require("auth.php")) header("Location: login.php");

if(isset($_POST["id_membro"]) && !empty($_POST["id_membro"]) && isset($_POST["id_corso"]) && !empty($_POST["id_corso"])){
    try{
        $conn=new Operazioni();
        $conn->update("iscrizioni_corsi",["id_corso"=>$_POST["id_corso"]],["id_membro"=>$_POST["id_membro"]]);
        header("Location: ../index.php");
    }catch(Exception $e){
        header("Location: ../errorpage.html");
    }
} else header("Location: ../errorpage.html");