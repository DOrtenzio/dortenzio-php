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
        try {
            $conn = new Operazioni();
            
            $sql = "SELECT i.id_istruttore, i.cognome, c.nome_corso, COUNT(ic.id_membro) AS totiscritti
                    FROM istruttori i JOIN corsi c ON i.id_istruttore = c.id_istruttore JOIN iscrizioni_corsi ic ON c.id_corso = ic.id_corso
                    GROUP BY i.id_istruttore, i.cognome, c.id_corso, c.nome_corso
                    HAVING COUNT(ic.id_membro) > 1
                    AND COUNT(ic.id_membro) = (
                        SELECT COUNT(ic2.id_membro) as num_membri
                        FROM corsi c2 JOIN iscrizioni_corsi ic2 ON c2.id_corso = ic2.id_corso
                        WHERE c2.id_istruttore = i.id_istruttore
                        GROUP BY c2.id_corso
                        ORDER BY num_membri DESC
                    );";

            $stmt = $conn->getPDO()->prepare($sql);
            $stmt->execute();
            $report = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($report)) {
                echo "<p>Nessun corso soddisfa i requisiti minimi.</p>";
            } else {
                foreach ($report as $riga) {
                    echo "<p> ISTRUTTORE: " . htmlspecialchars($riga["cognome"]) . " </p>";
                    echo "<div>";
                    echo "<p>" . htmlspecialchars($riga["nome_corso"]) . " - " . $riga["totiscritti"] . "</p>";
                    echo "</div>";
                }
            }

        } catch(Exception $e) {
            header("Location: errorpage.html");
            exit();
        }
        ?>
    </div>

    <div>
        <p>REPORT</p>
        <?php
            try {
                $conn = new Operazioni(); 
                
                $istruttori = [];
                foreach($conn->query("istruttori") as $i) {
                    $istruttori[$i["id_istruttore"]] = $i;
                }

                foreach($istruttori as $i) {
                    echo "<p><strong>ISTRUTTORE: " . htmlspecialchars($i["cognome"]) . "</strong></p>";
                    
                    $sql = 'SELECT c.nome_corso as nome_c, m.nome as nome_m, m.cognome as cognome_m
                            FROM corsi c
                            JOIN iscrizioni_corsi ic ON c.id_corso = ic.id_corso
                            JOIN membri m ON m.id_membro = ic.id_membro
                            WHERE c.id_istruttore = :id_istruttore
                            ORDER BY c.nome_corso, m.cognome';
                    
                    $stmt = $conn->getPDO()->prepare($sql);
                    $stmt->execute(['id_istruttore' => $i["id_istruttore"]]); 
                    $corsi_istruttore = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($corsi_istruttore)) {
                        echo "<p>Nessun iscritto ai corsi di questo istruttore.</p>";
                    } else {
                        foreach($corsi_istruttore as $cr) {
                            echo "<div>";
                            echo "<p>Corso: " . htmlspecialchars($cr["nome_c"]) . " - Allievo: " . htmlspecialchars($cr["nome_m"]) . " " . htmlspecialchars($cr["cognome_m"]) . "</p>";
                            echo "</div>";
                        }
                    }
                }
            } catch(Exception $e) {
                echo "<p>Errore nel caricamento del report: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        ?>
    </div>

</body>
</html>