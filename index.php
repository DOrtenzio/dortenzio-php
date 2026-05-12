<?php
require_once("funzioni/operazioni.php");
if (!require("funzioni/auth.php")) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GYM - Gestione Palestra</title>
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
        <p class="titolo-sezione">Nuova Iscrizione</p>
        <form action="funzioni/add_iscritto.php" method="post">
            <label for="id_istruttore">Seleziona Istruttore:</label>
            <select name="id_istruttore" id="id_istruttore" required>
                <?php
                try {
                    $conn = new Operazioni();
                    foreach ($conn->query("istruttori") as $istr) {
                        echo '<option value="' . $istr["id_istruttore"] . '">' . $istr["cognome"] . '</option>';
                    }
                } catch (Exception $e) {
                    header("Location: errorpage.html");
                    exit();
                }
                ?>
            </select>
            <input type="submit" name="Aggiungi" value="Aggiungi">
        </form>
    </div>

    <div class="sezione">
        <p class="titolo-sezione">Elenco Corsi e Iscritti</p>
        <?php
        try {
            $conn = new Operazioni();
            $corsi = [];
            foreach ($conn->query("corsi") as $corso) {
                $corsi[$corso["id_corso"]] = $corso;
            }
            
            $membri = [];
            foreach ($conn->query("membri") as $membro) {
                $membri[$membro["id_membro"]] = $membro;
            }

            foreach ($corsi as $corso) {
                echo "<p><strong>CORSO: " . $corso["nome_corso"] . "</strong></p>";
                $iscritti = $conn->query("iscrizioni_corsi", ["id_corso" => $corso["id_corso"]]);
                
                foreach ($iscritti as $iscritto) {
                    $id_m = $iscritto["id_membro"];
                    echo "<div class='voce-elenco'>";
                    echo "<span>" . $membri[$id_m]["nome"] . " " . $membri[$id_m]["cognome"] . "</span>";
                    echo '<form action="funzioni/cambia_corso.php" method="post" class="form-inline">
                            <input type="hidden" name="id_membro" value="' . $id_m . '">
                            <input type="submit" name="Cambia Corso" value="Cambia Corso" style="margin-left:10px;">
                          </form>';
                    echo "</div>";
                }
            }
        } catch (Exception $e) {
            header("Location: errorpage.html");
            exit();
        }
        ?>
    </div>

    <div class="sezione">
        <p class="titolo-sezione">Corsi Istruttore (Maggior numero iscritti)</p>
        <?php
        try {
            $conn = new Operazioni();
            $sql = "SELECT i.id_istruttore, i.cognome, c.nome_corso, COUNT(ic.id_membro) AS totiscritti
                    FROM istruttori i JOIN corsi c ON i.id_istruttore = c.id_istruttore JOIN iscrizioni_corsi ic ON c.id_corso = ic.id_corso
                    GROUP BY i.id_istruttore, i.cognome, c.id_corso, c.nome_corso
                    HAVING COUNT(ic.id_membro) > 5
                    AND COUNT(ic.id_membro) = (
                        SELECT COUNT(ic2.id_membro) as num_membri
                        FROM corsi c2 
                        JOIN iscrizioni_corsi ic2 ON c2.id_corso = ic2.id_corso
                        WHERE c2.id_istruttore = i.id_istruttore
                        GROUP BY c2.id_corso
                        ORDER BY num_membri DESC
                        LIMIT 1
                    );";

            $stmt = $conn->getPDO()->prepare($sql);
            $stmt->execute();
            $report = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($report)) {
                echo "<p>Nessun corso soddisfa i requisiti minimi.</p>";
            } else {
                foreach ($report as $riga) {
                    echo "<p><strong>ISTRUTTORE:</strong> " . $riga["cognome"] . "</p>";
                    echo "<div class='voce-elenco'>";
                    echo "<p>Corso: " . $riga["nome_corso"] . " - Iscritti: " . $riga["totiscritti"] . "</p>";
                    echo "</div>";
                }
            }
        } catch (Exception $e) {
            header("Location: errorpage.html");
            exit();
        }
        ?>
    </div>

    <div class="sezione">
        <p class="titolo-sezione">Report Generale Istruttori</p>
        <?php
        try {
            $conn = new Operazioni();
            $istruttori = [];
            foreach ($conn->query("istruttori") as $i) {
                $istruttori[$i["id_istruttore"]] = $i;
            }

            foreach ($istruttori as $i) {
                echo "<p><strong>ISTRUTTORE: " . $i["cognome"] . "</strong></p>";
                
                $sql = 'SELECT c.nome_corso as nome_c, m.nome as nome_m, m.cognome as cognome_m
                        FROM corsi c JOIN iscrizioni_corsi ic ON c.id_corso = ic.id_corso JOIN membri m ON m.id_membro = ic.id_membro
                        WHERE c.id_istruttore = :id_istruttore
                        ORDER BY c.nome_corso, m.cognome';
                
                $stmt = $conn->getPDO()->prepare($sql);
                $stmt->execute(['id_istruttore' => $i["id_istruttore"]]);
                $corsi_istruttore = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($corsi_istruttore)) {
                    echo "<p class='voce-elenco'>Nessun iscritto ai corsi di questo istruttore.</p>";
                } else {
                    foreach ($corsi_istruttore as $cr) {
                        echo "<div class='voce-elenco'>";
                        echo "<p>Corso: " . $cr["nome_c"] . " - Allievo: " . $cr["nome_m"] . " " . $cr["cognome_m"] . "</p>";
                        echo "</div>";
                    }
                }
            }
        } catch (Exception $e) {
            echo "<p>Errore nel caricamento del report: " . $e->getMessage() . "</p>";
        }
        ?>
    </div>

</body>
</html>
