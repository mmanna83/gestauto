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

// Verifica se è stato inviato l'id dell'annuncio da eliminare tramite il metodo GET
if (isset($_GET['id'])) {
    // Recupera l'id dell'annuncio dall'url
    $idAnnuncio = $_GET['id'];

    // Prepara e esegui la query per eliminare l'annuncio dal database
    $query = "DELETE FROM Annuncio WHERE IdAnnuncio = :idAnnuncio";
    $statement = $conn->prepare($query);
    $statement->bindParam(':idAnnuncio', $idAnnuncio);
    $result = $statement->execute();

    // Verifica se l'eliminazione è avvenuta con successo
    if ($result) {
        // Reindirizza l'utente alla pagina degli annunci con un messaggio di successo
        $_SESSION['success_message'] = "Annuncio eliminato con successo.";
        header("Location: annunci.php");
        exit;
    } else {
        // Se si verifica un errore durante l'eliminazione, mostra un messaggio di errore
        $_SESSION['error_message'] = "Si è verificato un errore durante l'eliminazione dell'annuncio.";
    }
}

// Se si arriva a questo punto, significa che l'operazione di eliminazione non è stata eseguita correttamente o che la pagina è stata richiamata senza l'id dell'annuncio da eliminare.
// Reindirizza l'utente alla pagina degli annunci con un messaggio di errore.
header("Location: annunci.php");
exit;
?>
