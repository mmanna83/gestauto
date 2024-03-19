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

// Verifica se l'ID della lead è stato passato nella URL
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include il file di connessione al database
    require_once "includes/db_connection.php";

    // Dichiarazione SQL per selezionare la lead dal suo ID
    $sql = "SELECT * FROM Lead WHERE IdLead = :idLead";

    if($stmt = $conn->prepare($sql)){
        // Collega i parametri
        $stmt->bindParam(":idLead", $param_idLead);

        // Imposta i parametri
        $param_idLead = trim($_GET["id"]);

        // Esegui la dichiarazione preparata
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                // Recupera la riga risultante come array associativo
                $row = $stmt->fetch();

                // Ottieni i valori dei campi della lead
                $nome = $row["Nome"];
                $cognome = $row["Cognome"];
                $mail = $row["Mail"];
                $cap = $row["Cap"];
                $telefono = $row["Telefono"];
            } else{
                // La lead non esiste
                header("location: error.php");
                exit();
            }
        } else{
            echo "Oops! Qualcosa è andato storto. Per favore riprova più tardi.";
        }

        // Chiudi lo statement
        unset($stmt);
    }

    // Chiudi la connessione
    unset($conn);
} else{
    // L'ID della lead non è stato fornito nella URL, reindirizza alla pagina di errore
    header("location: error.php");
    exit();
}
?>

	 <!-- Includi l'header -->
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
    <h2>Modifica Lead</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
        </div>
        <div class="form-group">
            <label>Cognome</label>
            <input type="text" name="cognome" class="form-control" value="<?php echo $cognome; ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="mail" class="form-control" value="<?php echo $mail; ?>">
        </div>
        <div class="form-group">
            <label>CAP</label>
            <input type="text" name="cap" class="form-control" value="<?php echo $cap; ?>">
        </div>
        <div class="form-group">
            <label>Telefono</label>
            <input type="text" name="telefono" class="form-control" value="<?php echo $telefono; ?>">
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <input type="submit" class="btn btn-primary" value="Salva Cambiamenti">
        <a href="lead.php" class="btn btn-secondary">Annulla</a>
    </form>
</div>
    
    <!-- Includi il footer -->
    <?php include 'includes/footer.php'; ?>
   