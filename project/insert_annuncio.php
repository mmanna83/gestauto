<?php
// Includi il file di connessione al database
include 'includes/db_connection.php';

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

// Verifica se sono stati inviati i dati del modulo tramite il metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i valori inviati dal modulo
    $idVeicolo = $_POST['idVeicolo'];
    $descrizione = $_POST['descrizione'];
    $prezzo = $_POST['prezzo'];

    // Inserisci i valori nel database
    $query = "INSERT INTO Annuncio (IdVeicolo, Descrizione, Prezzo) VALUES (:idVeicolo, :descrizione, :prezzo)";
    $statement = $conn->prepare($query);
    $statement->bindParam(':idVeicolo', $idVeicolo);
    $statement->bindParam(':descrizione', $descrizione);
    $statement->bindParam(':prezzo', $prezzo);
    $result = $statement->execute();

    // Verifica se l'inserimento è avvenuto con successo
    if ($result) {
        // Reindirizza l'utente alla pagina degli annunci con un messaggio di successo
        $_SESSION['success_message'] = "Annuncio aggiunto con successo.";
        header("Location: annunci.php");
        exit;
    } else {
        // Se si verifica un errore durante l'inserimento, mostra un messaggio di errore
        $error_message = "Si è verificato un errore durante l'inserimento dell'annuncio.";
    }
}

// Se si arriva a questo punto, significa che si è verificato un errore o che la pagina è stata richiamata senza invio di dati tramite POST.
// Reindirizza l'utente alla pagina degli annunci con un messaggio di errore.
$_SESSION['error_message'] = "Errore: dati non validi per l'annuncio.";
header("Location: annunci.php");
exit;
?>
