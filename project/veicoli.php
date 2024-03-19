<?php
// Includi il file di connessione al database
require_once "includes/db_connection.php";

session_start();

// Controllo dell'autenticazione
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: auth.php");
    exit;
}

// Dichiarazione delle variabili
$marca = $modello = $colore = "";
$errors = array();

// Funzione per pulire i dati inseriti dall'utente
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Operazioni CRUD

// Aggiunta di un veicolo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_vehicle"])) {
    // Validazione e pulizia dei dati
    $marca = clean_input($_POST["marca"]);
    $modello = clean_input($_POST["modello"]);
    $colore = clean_input($_POST["colore"]);

    // Validazione dei dati
    if (empty($marca)) { array_push($errors, "Marca è richiesta"); }
    if (empty($modello)) { array_push($errors, "Modello è richiesto"); }
    if (empty($colore)) { array_push($errors, "Colore è richiesto"); }

    // Se non ci sono errori, aggiungi il veicolo al database
    if (count($errors) == 0) {
        $query = "INSERT INTO Veicoli (IdMarca, IdModello, Colore) VALUES ('$marca', '$modello', '$colore')";
        mysqli_query($conn, $query);
        header("location: veicoli.php");
        exit();
    }
}

// Eliminazione di un veicolo
if (isset($_GET["delete"]) && is_numeric($_GET["delete"])) {
    $idVeicolo = $_GET["delete"];
    $query = "DELETE FROM Veicoli WHERE IdVeicolo = $idVeicolo";
    mysqli_query($conn, $query);
    header("location: veicoli.php");
    exit();
}

// Filtri di ricerca
$where = "";
if (isset($_GET["search"])) {
    if (!empty($_GET["marca"])) { $where .= " AND IdMarca = '" . clean_input($_GET["marca"]) . "'"; }
    if (!empty($_GET["modello"])) { $where .= " AND IdModello = '" . clean_input($_GET["modello"]) . "'"; }
    if (!empty($_GET["colore"])) { $where .= " AND Colore = '" . clean_input($_GET["colore"]) . "'"; }
}

// Selezione dei veicoli dal database con filtri applicati
$query = "SELECT * FROM Veicoli WHERE 1 $where";
$result = mysqli_query($conn, $query);

// Includi l'intestazione
include_once "includes/header.php";
?>

<div class="container mt-5">
    <h2>Gestione Veicoli</h2>

    <!-- Form per aggiungere un veicolo -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" class="form-control" name="marca" id="marca">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="modello">Modello</label>
                    <input type="text" class="form-control" name="modello" id="modello">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="colore">Colore</label>
                    <input type="text" class="form-control" name="colore" id="colore">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary" name="add_vehicle">Aggiungi Veicolo</button>
            </div>
        </div>
    </form>

    <!-- Form per filtrare i veicoli -->
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" class="form-control" name="marca" id="marca" value="<?php echo isset($_GET["marca"]) ? $_GET["marca"] : ""; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="modello">Modello</label>
                    <input type="text" class="form-control" name="modello" id="modello" value="<?php echo isset($_GET["modello"]) ? $_GET["modello"] : ""; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="colore">Colore</label>
                    <input type="text" class="form-control" name="colore" id="colore" value="<?php echo isset($_GET["colore"]) ? $_GET["colore"] : ""; ?>">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary" name="search">Filtra</button>
                <a href="veicoli.php" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

   <!-- Visualizzazione dei veicoli -->
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modello</th>
                        <th>Colore</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row["IdMarca"]; ?></td>
                            <td><?php echo $row["IdModello"]; ?></td>
                            <td><?php echo $row["Colore"]; ?></td>
                            <td>
                                <a href="edit_vehicle.php?id=<?php echo $row["IdVeicolo"]; ?>" class="btn btn-primary btn-sm">Modifica</a>
                                <a href="veicoli.php?delete=<?php echo $row["IdVeicolo"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo veicolo?')">Elimina</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Codice per visualizzare la tabella dei veicoli -->

</div>

<!-- Includi il piè di pagina -->
<?php include_once "includes/footer.php"; ?>
