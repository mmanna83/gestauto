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



// Verifica se l'ID della lead è stato passato nella URL
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include il file di connessione al database
    require_once "includes/db_connection.php";

    // Dichiarazione SQL per eliminare la lead
    $sql = "DELETE FROM Lead WHERE IdLead = :idLead";

    if($stmt = $conn->prepare($sql)){
        // Collega i parametri
        $stmt->bindParam(":idLead", $param_idLead);

        // Imposta i parametri
        $param_idLead = trim($_GET["id"]);

        // Esegui la dichiarazione preparata
        if($stmt->execute()){
            // Reindirizza alla pagina lead.php dopo l'eliminazione
            header("location: lead.php");
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
    // L'ID della lead non è stato fornito nella URL, reindirizza alla pagina di errore
    header("location: error.php");
    exit();
}
?>

