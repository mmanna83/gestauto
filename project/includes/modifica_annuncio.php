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

// Verifica se è stato inviato l'id dell'annuncio da modificare tramite il metodo GET
if (isset($_GET['id'])) {
    // Recupera l'id dell'annuncio dall'url
    $idAnnuncio = $_GET['id'];

    // Prepara e esegui la query per recuperare i dati dell'annuncio dal database
    $query = "SELECT * FROM Annuncio WHERE IdAnnuncio = :idAnnuncio";
    $statement = $conn->prepare($query);
    $statement->bindParam(':idAnnuncio', $idAnnuncio);
    $statement->execute();
    $annuncio = $statement->fetch(PDO::FETCH_ASSOC);

    // Verifica se l'annuncio è stato trovato nel database
    if (!$annuncio) {
        // Se l'annuncio non è stato trovato, reindirizza l'utente alla pagina degli annunci con un messaggio di errore
        $_SESSION['error_message'] = "Annuncio non trovato.";
        header("Location: annunci.php");
        exit;
    }
} else {
    // Se non è stato inviato l'id dell'annuncio, reindirizza l'utente alla pagina degli annunci con un messaggio di errore
    $_SESSION['error_message'] = "ID dell'annuncio non specificato.";
    header("Location: annunci.php");
    exit;
}

// Verifica se il modulo di modifica è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera i dati inviati tramite il modulo di modifica
    $descrizione = $_POST['descrizione'];
    $prezzo = $_POST['prezzo'];

    // Prepara e esegui la query per aggiornare i dati dell'annuncio nel database
    $query = "UPDATE Annuncio SET Descrizione = :descrizione, Prezzo = :prezzo WHERE IdAnnuncio = :idAnnuncio";
    $statement = $conn->prepare($query);
    $statement->bindParam(':descrizione', $descrizione);
    $statement->bindParam(':prezzo', $prezzo);
    $statement->bindParam(':idAnnuncio', $idAnnuncio);
    $result = $statement->execute();

    // Verifica se l'aggiornamento è avvenuto con successo
    if ($result) {
        // Reindirizza l'utente alla pagina degli annunci con un messaggio di successo
        $_SESSION['success_message'] = "Annuncio modificato con successo.";
        header("Location: annunci.php");
        exit;
    } else {
        // Se si verifica un errore durante l'aggiornamento, mostra un messaggio di errore
        $_SESSION['error_message'] = "Si è verificato un errore durante la modifica dell'annuncio.";
    }
}
?>

	 <!-- Includi l'header -->
    <?php include 'includes/header.php'; ?>
    
    <div class="container">

   
        <h2>Modifica Annuncio</h2>
        <!-- Form per la modifica dell'annuncio -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $idAnnuncio); ?>">
            <div class="form-group">
                <label for="descrizione">Descrizione:</label>
                <textarea class="form-control" name="descrizione" rows="3"><?php echo $annuncio['Descrizione']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="prezzo">Prezzo:</label>
                <input type="text" class="form-control" name="prezzo" value="<?php echo $annuncio['Prezzo']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        </form>
    </div>
    
    <!-- Includi il footer -->
    <?php include 'includes/footer.php'; ?>
   