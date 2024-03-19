<?php
session_start();

// Verifica delle credenziali di accesso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include il file di connessione al database
    require_once "includes/db_connection.php";

    // Recupera le credenziali dalla richiesta POST
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query per verificare le credenziali nel database
    $query = "SELECT * FROM Concessionario WHERE Username = '$username' AND Password = '$password'";
    $result = mysqli_query($conn, $query);

    // Verifica se le credenziali sono corrette
    if (mysqli_num_rows($result) == 1) {
        // Credenziali valide, reindirizzamento alla pagina di dashboard
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        $_SESSION["idConcessionario"] = $result['IdConcessionario'];
        header("location: index.php");
        exit;
    } else {
        // Credenziali non valide, reindirizzamento alla pagina di login con messaggio di errore
        $_SESSION["login_error"] = "Credenziali non valide. Riprova.";
        header("location: auth.php");
        exit;
    }
}
?>

