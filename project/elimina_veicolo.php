<?php
// Includi il file di connessione al database
include 'includes/db_connection.php';

// Avvia la sessione
session_start();

// Verifica se l'utente è loggato e se l'username è presente nella sessione
if (!isset($_SESSION['username'])) {
    // L'username non è presente nella sessione, quindi l'utente non è loggato
    // Reindirizza l'utente alla pagina di login
    header("Location: login.php");
    exit;
}


// Includi il file di connessione al database
require_once "includes/db_connection.php";

// Verifica se è stato inviato l'ID del veicolo da eliminare
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Preparare la dichiarazione di eliminazione
    $sql = "DELETE FROM Veicolo WHERE IdVeicolo = :idVeicolo";
    
    if($stmt = $conn->prepare($sql)){
        // Collega i parametri
        $stmt->bindParam(":idVeicolo", $param_idVeicolo);
        
        // Imposta i parametri
        $param_idVeicolo = trim($_GET["id"]);
        
        // Esegui la dichiarazione preparata
        if($stmt->execute()){
            // Record eliminato con successo, reindirizza alla pagina degli annunci
            header("location: veicoli.php");
            exit();
        } else{
            echo "Oops! Qualcosa è andato storto. Per favore riprova più tardi.";
        }
    }
     
    // Chiudi lo statement
    unset($stmt);
    
    // Chiudi la connessione
    unset($conn);
} else{
    // URL non valido, reindirizza alla pagina di errore
    header("location: error.php");
    exit();
}
?>

