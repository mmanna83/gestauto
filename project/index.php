<?php
session_start();

// Controllo dell'autenticazione
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: auth.php");
    exit;
}

// Includi il file di connessione al database
require_once "includes/db_connection.php";

// Query per ottenere gli ultimi annunci inseriti dal concessionario loggato
$username = $_SESSION["username"];
$query = "SELECT * FROM Annuncio WHERE IdConcessionario = (SELECT IdConcessionario FROM Concessionario WHERE Username = '$username') ORDER BY DataInserimento DESC LIMIT 10";
$result = mysqli_query($conn, $query);

// Includi l'intestazione
include_once "includes/header.php";
?>




<div class="container mt-5">
    <h2>Ultimi Annunci</h2>
    <div class="row">
        <?php
        // Mostra gli annunci
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-md-4 mb-3">';
            echo '<div class="card">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row["Titolo"] . '</h5>';
            echo '<p class="card-text">' . $row["Descrizione"] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>



<?php
// Includi il piè di pagina
include_once "includes/footer.php";
?>




