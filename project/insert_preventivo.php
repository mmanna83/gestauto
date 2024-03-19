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

// Verifica se il metodo di richiesta è POST
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Definisci le variabili e inizializzale con i valori del form
    $prezzoOfferta = $_POST["prezzo_offerta"];
    $data = $_POST["data"];
    $idLead = $_POST["id_lead"];
    $idConcessionario = $_SESSION["id_concessionario"]; // Assume che l'ID del concessionario sia memorizzato in sessione
    
    // Query SQL per l'inserimento del preventivo
    $sqlInsert = "INSERT INTO Preventivo (PrezzoOfferta, Data, IdConcessionario, IdLead) VALUES (?, ?, ?, ?)";
    
    if($stmt = $mysqli->prepare($sqlInsert)){
        // Associa i parametri della query
        $stmt->bind_param("dssi", $prezzoOfferta, $data, $idConcessionario, $idLead);
        
        // Esegui la query
        if($stmt->execute()){
            // Redirect alla pagina dei lead dopo l'inserimento del preventivo
            header("location: lead.php");
            exit();
        } else{
            // Visualizza un messaggio se si verifica un errore nell'esecuzione della query
            echo "<div class='alert alert-danger'>Errore nell'inserimento del preventivo.</div>";
        }
        
        // Chiudi la dichiarazione preparata
        $stmt->close();
    }
    
    // Chiudi la connessione al database
    $mysqli->close();
} else{
    // Se il metodo di richiesta non è POST, reindirizza alla pagina di errore
    header("location: error.php");
    exit();
}
?>





