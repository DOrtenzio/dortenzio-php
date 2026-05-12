<?php
if(session_status()!==PHP_SESSION_ACTIVE) session_start();
require_once("operazioni.php");
if(!require("auth.php")) header("Location: ../login.php");

if(isset($_POST["nome_i"]) && !empty($_POST["nome_i"]) && isset($_POST["cognome_i"]) && !empty($_POST["cognome_i"]) && isset($_POST["data_i"]) && !empty($_POST["data_i"]) && isset($_POST["tipo_i"]) && !empty($_POST["tipo_i"]) && isset($_POST["id_corso"]) && !empty($_POST["id_corso"]) && isset($_POST["data_iscr"]) && !empty($_POST["data_iscr"]) && isset($_POST["orario"]) && !empty($_POST["orario"])){
    try{
        $conn=new Operazioni();
        $pag= isset($_POST["pag_i"]) ? 1 : 0;
        //Inserimento Nuovo Membro
        $id_membro=$conn->insert("membri",["nome"=>$_POST["nome_i"],"cognome"=>$_POST["cognome_i"],"data_nascita"=>$_POST["data_i"],"tipo_abbonamento"=>$_POST["tipo_i"],"stato_pagamento"=>$pag]);
    
        //aggiunta
        $stmt = $conn->getPDO()->prepare($sql);
        $stmt->execute();
        $report = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $conn->insert("iscrizioni_corsi",["id_corso"=>$_POST["id_corso"],"id_membro"=>$id_membro,"data_iscrizione"=>$_POST["data_iscr"],"orario_preferito"=>$_POST["orario"]]);
        header("Location: ../index.php");
    }catch(Exception $e){
        header("Location: ../errorpage.html");
    }
} else header("Location: ../errorpage.html");