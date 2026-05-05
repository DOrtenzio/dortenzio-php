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
        <form action="funzioni/add_iscritto.php"  method="post">
            <select name="id_istruttore" id="id_istruttore" required>
                <?php
                    try{
                        $conn=new Operazioni();
                        foreach($conn->query("istruttori") as $istr) {
                            echo '<option value="'.$istr["id_istruttore"].'">'.$istr["cognome"].'</option>';
                        }
                    }catch(Exception $e){
                        header("Location: errorpage.html");
                    }
                ?>
            </select>
            <input type="submit" name="Aggiungi" value="Aggiungi">
        </form>
    </div>

    <div>
        <p>Corsi</p>
        <?php
            try{
                $conn=new Operazioni();
                $corsi=[];
                foreach($conn->query("corsi") as $corso) $corsi[$corso["id_corso"]]=$corso;
                $membri=[];
                foreach($conn->query("membri") as $membro) $membri[$membro["id_membro"]]=$membro;
                foreach($corsi as $corso){
                    echo "<p> CORSO: ".$corsi[$corso["id_corso"]]["nome_corso"]." </p>";
                    $iscritti=$conn->query("iscrizioni_corsi",["id_corso"=>$corso["id_corso"]]);
                    foreach($iscritti as $iscritto){
                        echo "<div>";
                        echo "<p>".$membri[$iscritto["id_membro"]]["nome"]." - ".$membri[$iscritto["id_membro"]]["cognome"]."</p>";
                        echo '<form action="funzioni/cambia_corso.php" method="post">
                                    <input type="hidden" name="id_membro" value="'.$iscritto["id_membro"].'">
                                    <input type="submit" name="Cambia Corso" value="Cambia Corso">
                              </form>';
                        echo "</div>";
                    } 
                }
            }catch(Exception $e){
                header("Location: errorpage.html");
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