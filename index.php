<?php
require_once("funzioni/operazioni.php");
if(!require("funzioni/auth.php")) header("Location: login.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM</title>
</head>
<body>
    <div>
        <a href="funzioni/add_iscritto.php">Aggiunbgi Iscrizione A Corso</a>
    </div>

    <div>
        <p>Corsi</p>
        <?php
                $conn=new Operazioni();
                $corsi=[];
                foreach($conn->query("corsi") as $corso) $corsi[$corso["id_corso"]]=$corso;
                $membri=[];
                foreach($conn->query("membri") as $membro) $membri[$membro["id_membro"]]=$membro;
                foreach($corsi as $corso){
                    echo "<p>".$corso[$iscritto["id_membro"]]["nome"];
                    $iscritti=$conn->query("iscrizioni_corsi",["id_corso"=>$corso["id_corso"]]);
                    foreach($iscritti as $iscritto) echo "<p>".$membri[$iscritto["id_membro"]]["nome"]." - ".$membri[$iscritto["id_membro"]]["cognome"]."</p>";
                }
            ?>
    </div>

    <div>
        <p>Corsi - Istruttori</p>
        <table>
            <tr>
                <th>Istruttore</th>
                <th>Corso Con Più Iscritti (almeno>5)</th>
            </tr>
            <?php
                $conn=new Operazioni();
                $istruttori=$conn->query("istruttori");
                foreach($conn->query("iscrizioni_corsi",[],[]) as $iscrizione){

                }
            ?>
        </table>
    </div>
</body>
</html>