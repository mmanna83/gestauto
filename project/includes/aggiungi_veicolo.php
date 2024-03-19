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

// Definisci le variabili per memorizzare i messaggi di errore
$uploadMsg = '';

// Verifica se il modulo di caricamento è stato inviato
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ottieni l'id del concessionario loggato
    $idConcessionario = $_SESSION['idConcessionario'];

    // Ottieni i dati inviati tramite il modulo di caricamento
    $targa = $_POST['targa'];
    $colore = $_POST['colore'];
    $motore = $_POST['motore'];
    $cv = $_POST['cv'];
    $posti = $_POST['posti'];
    $cilindrata = $_POST['cilindrata'];
    $potenza = $_POST['potenza'];

    // Prepara e esegui la query per inserire il veicolo nel database
    $query = "INSERT INTO Veicolo (IdMarca, IdModello, IdConcessionario, Targa, Colore, Motore, CV, Posti, Cilindrata, Potenza)
              VALUES (:idMarca, :idModello, :idConcessionario, :targa, :colore, :motore, :cv, :posti, :cilindrata, :potenza)";
    $statement = $conn->prepare($query);
    $statement->bindParam(':idMarca', $_POST['marca']);
    $statement->bindParam(':idModello', $_POST['modello']);
    $statement->bindParam(':idConcessionario', $idConcessionario);
    $statement->bindParam(':targa', $targa);
    $statement->bindParam(':colore', $colore);
    $statement->bindParam(':motore', $motore);
    $statement->bindParam(':cv', $cv);
    $statement->bindParam(':posti', $posti);
    $statement->bindParam(':cilindrata', $cilindrata);
    $statement->bindParam(':potenza', $potenza);
    $statement->execute();

    // Ottieni l'ID del veicolo appena inserito
    $idVeicolo = $conn->lastInsertId();

    // Caricamento delle immagini
    for ($i = 1; $i <= 3; $i++) {
        $fileName = $_FILES["foto" . $i]["name"];
        $tempName = $_FILES["foto" . $i]["tmp_name"];
        $folder = "uploads/";

        if (move_uploaded_file($tempName, $folder . $fileName)) {
            // Prepara e esegui la query per inserire i dati della foto nel database
            $query = "INSERT INTO Foto (IdVeicolo, Percorso) VALUES (:idVeicolo, :nomeFile)";
            $statement = $conn->prepare($query);
            $statement->bindParam(':idVeicolo', $idVeicolo);
            $statement->bindParam(':nomeFile', $fileName);
            $statement->execute();
        } else {
            // Se il caricamento del file fallisce, mostra un messaggio di errore
            $uploadMsg .= "Errore nel caricamento del file " . $fileName . "<br>";
        }
    }

    // Se il veicolo è stato inserito correttamente e le immagini sono state caricate, mostra un messaggio di successo
    if (empty($uploadMsg)) {
        $uploadMsg = "Veicolo inserito con successo!";
    }
}
?>

 <!-- Includi l'header -->
    <?php include 'includes/header.php'; ?>
    
        <h2>Aggiungi Veicolo</h2>
        <!-- Visualizza il messaggio di upload se presente -->
        <?php if (!empty($uploadMsg)) : ?>
            <div class="alert alert-<?php echo (empty($errorMsg)) ? 'success' : 'danger'; ?>" role="alert">
                <?php echo $uploadMsg; ?>
            </div>
        <?php endif; ?>
        <!-- Form per aggiungere un nuovo veicolo -->
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="marca">Marca:</label>
                <!-- Input per selezionare la marca -->
                <select class="form-control" id="marca" name="marca">
                    <!-- Popola il menu a tendina con le marche dal database -->
                    <?php
                    $query = "SELECT * FROM Marca";
                    $statement = $conn->query($query);
                    $marche = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($marche as $marca) {
                        echo "<option value='{$marca['IdMarca']}'>{$marca['NomeMarca']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="modello">Modello:</label>
                <!-- Input per selezionare il modello -->
                <select class="form-control" id="modello" name="modello">
                    <!-- Il menu a tendina verrà popolato dinamicamente tramite JavaScript in base alla marca selezionata -->
                </select>
            </div>
            <div class="form-group">
                <label for="targa">Targa:</label>
                <!-- Input per inserire la targa -->
                <input type="text" class="form-control" id="targa" name="targa" required>
            </div>
            <div class="form-group">
                <label for="colore">Colore:</label>
                <!-- Input per inserire il colore -->
                <input type="text" class="form-control" id="colore" name="colore" required>
            </div>
            <div class="form-group">
                <label for="motore">Motore:</label>
                <!-- Input per inserire il motore -->
                <input type="text" class="form-control" id="motore" name="motore" required>
            </div>
            <div class="form-group">
                <label for="cv">CV:</label>
                <!-- Input per inserire i cavalli -->
                <input type="number" class="form-control" id="cv" name="cv" required>
            </div>
            <div class="form-group">
                <label for="posti">Posti:</label>
                <!-- Input per inserire il numero di posti -->
                <input type="number" class="form-control" id="posti" name="posti" required>
            </div>
            <div class="form-group">
                <label for="cilindrata">Cilindrata:</label>
                <!-- Input per inserire la cilindrata -->
                <input type="number" class="form-control" id="cilindrata" name="cilindrata" required>
            </div>
            <div class="form-group">
                <label for="potenza">Potenza:</label>
                <!-- Input per inserire la potenza -->
                <input type="number" class="form-control" id="potenza" name="potenza" required>
            </div>
            <div class="form-group">
                <label for="foto1">Foto 1:</label>
                <!-- Input per selezionare la prima foto -->
                <input type="file" class="form-control-file" id="foto1" name="foto1" required>
            </div>
            <div class="form-group">
                <label for="foto2">Foto 2:</label>
                <!-- Input per selezionare la seconda foto -->
                <input type="file" class="form-control-file" id="foto2" name="foto2" required>
            </div>
            <div class="form-group">
                <label for="foto3">Foto 3:</label>
                <!-- Input per selezionare la terza foto -->
                <input type="file" class="form-control-file" id="foto3" name="foto3" required>
            </div>
            <!-- Bottone per inviare il modulo -->
            <button type="submit" class="btn btn-primary">Aggiungi Veicolo</button>
        </form>
    </div>
    
    <!-- Includi il file JavaScript di jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Quando viene selezionata una marca
            $('#marca').change(function() {
                // Ottieni l'id della marca selezionata
                var marcaId = $(this).val();
                // Esegui una richiesta AJAX per ottenere i modelli associati alla marca
                $.ajax({
                    type: 'POST',
                    url: 'get_modello.php',
                    data: {
                        marca_id: marcaId
                    },
                    success: function(response) {
                        // Aggiorna il menu a tendina dei modelli con i dati ottenuti dalla risposta AJAX
                        $('#modello').html(response);
                    }
                });
            });
        });
    </script>
    
 <!-- Includi il footer -->
    <?php include 'includes/footer.php'; ?>
    