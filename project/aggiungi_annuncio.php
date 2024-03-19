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
   
   
    $queryVeicoli = "SELECT v.IdVeicolo, v.Targa, m.NomeMarca, mo.NomeModello
                         FROM Veicolo v
                         INNER JOIN Marca m ON v.IdMarca = m.IdMarca
                         INNER JOIN Modello mo ON v.IdModello = mo.IdModello
                         WHERE v.IdConcessionario = :idConcessionario";
        $statementVeicoli = $conn->prepare($queryVeicoli);
        $statementVeicoli->bindParam(':idConcessionario', $idConcessionario);
        $statementVeicoli->execute();
        $veicoli = $statementVeicoli->fetchAll(PDO::FETCH_ASSOC);
    

// Includi l'intestazione della pagina
include "includes/header.php";
?>



  <div class="container">
    <h2>Aggiungi Annuncio</h2>
    <form action="insert_annuncio.php" method="POST">
        <div class="form-group">
            <label for="idVeicolo">Veicolo:</label>
            <select class="form-control" id="idVeicolo" name="idVeicolo">
                <?php foreach ($veicoli as $veicolo) { ?>
                    <option value="<?php echo $veicolo['IdVeicolo']; ?>">
                        <?php echo $veicolo['Targa'] . ' - ' . $veicolo['NomeMarca'] . ' ' . $veicolo['NomeModello']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="descrizione">Descrizione:</label>
            <textarea class="form-control" id="descrizione" name="descrizione" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="prezzo">Prezzo (Euro):</label>
            <input type="number" class="form-control" id="prezzo" name="prezzo" step="0.01">
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi Annuncio</button>
    </form>
</div>


    <!-- Includi il footer -->
    <?php include 'includes/footer.php'; ?>







