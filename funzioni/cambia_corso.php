<?php
require_once("operazioni.php");
if (!require("auth.php")) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM - Cambia Corso</title>
    <style>
        body { font-family: sans-serif; margin: 20px; line-height: 1.6; }
        .sezione { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .titolo-sezione { font-weight: bold; font-size: 1.2em; margin-top: 0; text-transform: uppercase; color: #333; }
        .voce-elenco { margin-left: 15px; padding: 5px 0; border-bottom: 1px dashed #eee; }
        .form-inline { display: inline; }
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
    <div class="sezione">
        <p class="titolo-sezione">Cambia Corso</p>
        <form action="cambia_save.php" method="post">
        <p>ID MEMBRO</p>
        <?php  
            echo '<input type="text" name="id_membro" value="'.$_POST["id_membro"].'" readonly>';
        ?>
        <br>
        <p>SELEZIONA IL NUOVO CORSO</p>
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
    </div>
    <a href="../index.php">Torna alla Home</a>
</body>
</html>