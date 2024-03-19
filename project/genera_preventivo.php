<?php
// Includi il file di connessione al database
include 'includes/db_connection.php';

// Avvio della sessione
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: auth.php');
    exit;
}

// Recupera l'username dalla sessione
    $username = $_SESSION['username'];
	$idConcessionario=$_SESSION['idConcessionario'];

// Includi l'intestazione della pagina
include "includes/header.php";
?>



 <div class="wrapper">
        <h2>Genera Preventivo</h2>
        <p>Compila il form per generare un nuovo preventivo.</p>
        <?php
        // Controlla se l'ID del lead è stato fornito
        if(isset($_GET["id_lead"]) && !empty(trim($_GET["id_lead"]))){
            // Includi il file di connessione al database
            require_once "includes/db_connection.php";
            
            // Prendi l'ID del lead dalla query string
            $idLead = $_GET["id_lead"];
            
            // Query SQL per ottenere i dati del lead
            $sqlLead = "SELECT * FROM Lead WHERE IdLead = ?";
            
            if($stmtLead = $mysqli->prepare($sqlLead)){
                // Associa i parametri della query
                $stmtLead->bind_param("i", $idLead);
                
                // Esegui la query
                if($stmtLead->execute()){
                    // Memorizza il risultato
                    $stmtLead->store_result();
                    
                    // Verifica se il lead esiste
                    if($stmtLead->num_rows == 1){
                        // Associa le colonne del risultato
                        $stmtLead->bind_result($idLead, $nome, $cognome, $mail, $cap, $telefono, $idAnnuncio);
                        
                        // Estrai i dati del lead
                        $stmtLead->fetch();
                        
                        // Mostra il form per il preventivo
                        ?>
                        <form action="insert_preventivo.php" method="post">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" name="nome" value="<?php echo $nome; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Cognome</label>
                                <input type="text" name="cognome" value="<?php echo $cognome; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Mail</label>
                                <input type="email" name="mail" value="<?php echo $mail; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Cap</label>
                                <input type="text" name="cap" value="<?php echo $cap; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Telefono</label>
                                <input type="text" name="telefono" value="<?php echo $telefono; ?>" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Prezzo Offerta</label>
                                <input type="text" name="prezzo_offerta" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Data</label>
                                <input type="date" name="data" class="form-control" required>
                            </div>
                            <input type="hidden" name="id_lead" value="<?php echo $idLead; ?>">
                            <input type="submit" class="btn btn-primary" value="Genera Preventivo">
                            <a href="lead.php" class="btn btn-secondary">Annulla</a>
                        </form>
                        <?php
                    } else{
                        // Visualizza un messaggio se il lead non esiste
                        echo "<div class='alert alert-danger'>Lead non trovato.</div>";
                    }
                } else{
                    // Visualizza un messaggio se si verifica un errore nell'esecuzione della query
                    echo "<div class='alert alert-danger'>Errore nell'esecuzione della query.</div>";
                }
                
                // Chiudi la dichiarazione preparata
                $stmtLead->close();
            }
            
            // Chiudi la connessione al database
            $mysqli->close();
        } else{
            // Visualizza un messaggio se l'ID del lead non è stato fornito
            echo "<div class='alert alert-danger'>ID del lead non specificato.</div>";
        }
        ?>
    </div>


    <!-- Includi il footer -->
    <?php include 'includes/footer.php'; ?>







