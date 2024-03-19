<?php
// Avvia la sessione
session_start();

// Verifica se l'utente è loggato e se l'username è presente nella sessione
if (!isset($_SESSION['username'])) {
    // L'username non è presente nella sessione, quindi l'utente non è loggato
    // Reindirizza l'utente alla pagina di login
    header("Location: auth.php");
    exit;
}

// Includi il file di connessione al database
require_once "includes/db_connection.php";

// Recupera l'id del concessionario loggato dall'array della sessione
$idConcessionario = $_SESSION['idConcessionario'];


?>

<!-- Includi l'header -->
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
    <h2>Lead</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Email</th>
                <th>Telefono</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            // Query per selezionare tutte le lead del concessionario loggato
            $sql = "SELECT * FROM Lead WHERE IdAnnuncio IN (SELECT IdAnnuncio FROM Annuncio WHERE IdVeicolo IN (SELECT IdVeicolo FROM Veicolo WHERE IdConcessionario = :idConcessionario))";

            if($stmt = $conn->prepare($sql)){
                // Collega i parametri
                $stmt->bindParam(":idConcessionario", $param_idConcessionario);

                // Imposta i parametri
                $param_idConcessionario = $_SESSION["idConcessionario"];

                // Esegui la dichiarazione preparata
                if($stmt->execute()){
                    // Conta il numero di righe restituite
                    if($stmt->rowCount() > 0){
                        // Recupera le righe risultato come un array associativo
                        while($row = $stmt->fetch()){
                            // Visualizza le informazioni della lead in una riga della tabella
                            echo "<tr>";
                            echo "<td>" . $row["Nome"] . "</td>";
                            echo "<td>" . $row["Cognome"] . "</td>";
                            echo "<td>" . $row["Mail"] . "</td>";
                            echo "<td>" . $row["Telefono"] . "</td>";
                            echo "<td>";
                            echo "<a href='crea_preventivo.php?id=" . $row['IdLead'] . "' class='btn btn-primary'>Crea Preventivo</a> ";
                            echo "<a href='modifica_lead.php?id=" . $row['IdLead'] . "' class='btn btn-warning'>Modifica Lead</a> ";
                            echo "<a href='elimina_lead.php?id=" . $row['IdLead'] . "' class='btn btn-danger'>Elimina Lead</a> ";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else{
                        // Nessuna lead trovata
                        echo "<tr><td colspan='4'>Nessuna lead trovata.</td></tr>";
                    }
                } else{
                    // Errore durante l'esecuzione della query
                    echo "<tr><td colspan='4'>Oops! Qualcosa è andato storto. Per favore riprova più tardi.</td></tr>";
                }

                // Chiudi lo statement
                unset($stmt);
            }

            // Chiudi la connessione
            unset($conn);
            ?>
        </tbody>
    </table>
</div>


<!-- Includi il piè di pagina -->
<?php include_once "includes/footer.php"; ?>
