<?php
if(session_status()!==PHP_SESSION_ACTIVE) session_start();
require_once("operazioni.php");
if(!require("auth.php")) header("Location: ../login.php");
if(!isset($_POST["id_istruttore"]) || empty($_POST["id_istruttore"])) header("Location: errorpage.html");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM</title>
</head>
<body>
    <p>Aggiungi Iscrizione</p>
    <form action="funzioni/add_save.php" method="post">
    <select name="id_membro" id="id_membro" required>
        <label for=""></label>
        <input type="text" id="" name="nome_i" placeholder="Nome" required>
            <?php
                try{
                    $conn=new Operazioni();
                    foreach($conn->query("membri") as $m) {
                        echo '<option value="'.$m["id_membro"].'">'.$m["cognome"].'</option>';
                    }
                }catch(Exception $e){
                    header("Location: errorpage.html");
                }
            ?>
        </select>
        <select name="id_corso" id="id_corso" required>
            <?php
                try{
                    $conn=new Operazioni();
                    foreach($conn->query("corsi") as $corso) {
                        if($corso["id_istruttore"]==$_POST["id_istruttore"]) echo '<option value="'.$corso["id_corso"].'">'.$corso["nome_corso"].'</option>';
                    }
                }catch(Exception $e){
                    header("Location: errorpage.html");
                }
            ?>
        </select>
        <input type="date" name="data_iscr" required>
        <input type="time" name="orario" required>
        <input type="submit" name="Aggiungi" value="Aggiungi">
    </form>
</body>
</html>