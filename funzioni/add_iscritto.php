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
        <p>NUOVO MEMBRO</p>
        <label for="nome_i">Nome:</label>
        <input type="text" id="nome_i" name="nome_i" placeholder="Nome" required>
        <label for="cognome_i">Cognome:</label>
        <input type="text" id="cognome_i" name="cognome_i" placeholder="Cognome" required>
        <label for="data_i">Data di Nascita:</label>
        <input type="date" id="data_i" name="data_i" required>
        <label for="tipo_i">Tipo Abbonamento:</label>
        <select name="tipo_i" id="tipo_i">
            <option value="Mensile" selected>Mensile</option>
            <option value="Trimestrale">Tri-Mensile</option>
            <option value="Annuale">Annuale</option>
        </select>
        <label for="pag_i">Già Pagato?</label>
        <input type="checkbox" id="pag_i" name="pag_i">
        <br>
        <p>CORSO</p>
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