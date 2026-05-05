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
        <p>CORSI ISTRUTTORE</p>
        <?php
            try{
                $conn=new Operazioni();
                $stmt = $this->conn->prepare("SELECT id_corso,count(*) AS totiscritti  FROM `iscrizioni_corsi` GROUP BY id_corso HAVING COUNT(*)>5");
                $stmt->execute($valori);
                $corsi_iscrizioni=$stmt->fetchAll(PDO::FETCH_ASSOC);

                $istruttori=[];
                foreach($conn->query("istruttori") as $i) $istruttori[$i["id_istruttore"]]=$i;
                $corsi=[];
                foreach($conn->query("corsi") as $corso) $corsi[$corso["id_corso"]]=$corso;

                foreach($istruttori as $i){
                    echo "<p> ISTRUTTORE: ".$i["cognome"]." </p>";
                    $corsomax="";
                    $max=0;

                    foreach($corsi_iscrizioni as $c){
                       if($max<$c["totiscritti"]){
                            $max=$c["totiscritti"];
                            $corsomax=$c["id_corso"];
                       }
                    } 

                    if($max!==0){
                        echo "<div>";
                        echo "<p>".$corsi[$corsomax]["nome"]." - ".$max."</p>";
                        echo "</div>";
                    }else echo "Nessun corso";
                }
            }catch(Exception $e){
                header("Location: errorpage.html");
            }
            ?>
    </div>

    <div>
        <p>REPORT</p>
        <?php
            try{
                /*
                    SELECT c.nome_corso as nome_c, m.nome as nome_m, m.cognome as cognome_m
                    FROM istruttori i,iscrizioni_corsi ic,corsi c,membri m
                    WHERE i.id_istruttore=ic.id_istruttore AND c.id_corso=ic.id_corso AND m.id_membro=ic.id_membro
                    ORDER BY i.cognome,c.nome_corso
                */
                $conn=new Operazioni();
                $stmt = $this->conn->prepare("SELECT id_corso,count(*) AS totiscritti  FROM `iscrizioni_corsi` GROUP BY id_corso HAVING COUNT(*)>5");
                $stmt->execute($valori);
                $corsi=$stmt->fetchAll(PDO::FETCH_ASSOC);

                $istruttori=[];
                foreach($conn->query("istruttori") as $i) $istruttori[$i["id_istruttore"]]=$i;
                $corsi=[];
                foreach($conn->query("corsi") as $corso) $corsi[$corso["id_corso"]]=$corso;

                foreach($istruttori as $i){
                    echo "<p> ISTRUTTORE: ".$i["cognome"]." </p>";
                    $corsomax="";
                    $max=0;

                    foreach($corsi_iscrizioni as $c){
                       if($max<$c["totiscritti"]){
                            $max=$c["totiscritti"];
                            $corsomax=$c["id_corso"];
                       }
                    } 

                    if($max!==0){
                        echo "<div>";
                        echo "<p>".$corsi[$corsomax]["nome"]." - ".$max."</p>";
                        echo "</div>";
                    }else echo "Nessun corso";
                }
            }catch(Exception $e){
                header("Location: errorpage.html");
            }
            ?>
    </div>
</body>
</html>