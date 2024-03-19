<?php
// Configurazione del database
$servername = "localhost"; // Nome del server
$username = "username"; // Nome utente del database
$password = "password"; // Password del database
$dbname = "nome_database"; // Nome del database

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica della connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>


