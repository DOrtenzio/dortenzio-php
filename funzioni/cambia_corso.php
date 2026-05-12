<?php
if(session_status()!==PHP_SESSION_ACTIVE) session_start();
require_once("operazioni.php");
if(!require("auth.php")) header("Location: ../login.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM</title>
</head>
<body>
    <p>Cambia Corso</p>
    <form action="funzioni/cambia_save.php" method="post">
        <?php  
            echo '<input type="text" name="id_membro" value="'.$_POST["id_membro"].'" readonly>';
        ?>
        <select name="id_corso" id="id_corso" required>
            <?php
                try{
                    $conn=new Operazioni();
                    foreach($conn->query("corsi") as $corso) {
                        echo '<option value="'.$corso["id_corso"].'">'.$corso["nome_corso"].'</option>';
                    }
                }catch(Exception $e){
                    header("Location: errorpage.html");
                }
            ?>
        </select>
        <input type="submit" name="Cambia" value="Cambia">
    </form>
</body>
</html>