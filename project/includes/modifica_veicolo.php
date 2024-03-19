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


$idConcessionario = $_SESSION['idConcessionario'];

 
?>

 <!-- Includi l'header -->
    <?php include 'includes/header.php'; ?>
    
       <div class="container">
        <h2>Modifica Veicolo</h2>
        <?php
        // Includi il file di connessione al database
        require_once "includes/db_connection.php";

        // Verifica se è stato inviato l'ID del veicolo da modificare
        if(isset($_GET['id']) && !empty(trim($_GET['id']))){
            // Ottieni l'ID del veicolo dalla query string
            $idVeicolo = trim($_GET['id']);

            // Prepara la query per ottenere i dati del veicolo
            $query = "SELECT * FROM Veicolo WHERE IdVeicolo = :idVeicolo";

            if($stmt = $conn->prepare($query)){
                // Collega i parametri
                $stmt->bindParam(":idVeicolo", $param_idVeicolo);

                // Imposta i parametri
                $param_idVeicolo = $idVeicolo;

                // Esegui la query
                if($stmt->execute()){
                    // Controlla se esiste un veicolo con l'ID specificato
                    if($stmt->rowCount() == 1){
                        // Estrai la riga
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $marca = $row['IdMarca'];
                        $modello = $row['IdModello'];
                        $targa = $row['Targa'];
                        $colore = $row['Colore'];
                        $motore = $row['Motore'];
                        $cv = $row['CV'];
                        $posti = $row['Posti'];
                        $cilindrata = $row['Cilindrata'];
                        $potenza = $row['Potenza'];
                    } else{
                        // URL non valido, reindirizza alla pagina di errore
                        header("location: error.php");
                        exit();
                    }
                } else{
                    echo "Oops! Qualcosa è andato storto. Per favore riprova più tardi.";
                }

                // Chiudi lo statement
                unset($stmt);
            }
        } else{
            // URL non valido, reindirizza alla pagina di errore
            header("location: error.php");
            exit();
        }
        ?>
        <form action="update_veicolo.php" method="post">
            <div class="form-group">
                <label>Marca</label>
                <input type="text" name="marca" class="form-control" value="<?php echo $marca; ?>">
            </div>
            <div class="form-group">
                <label>Modello</label>
                <input type="text" name="modello" class="form-control" value="<?php echo $modello; ?>">
            </div>
            <div class="form-group">
                <label>Targa</label>
                <input type="text" name="targa" class="form-control" value="<?php echo $targa; ?>">
            </div>
            <div class="form-group">
                <label>Colore</label>
                <input type="text" name="colore" class="form-control" value="<?php echo $colore; ?>">
            </div>
            <div class="form-group">
                <label>Motore</label>
                <input type="text" name="motore" class="form-control" value="<?php echo $motore; ?>">
            </div>
            <div class="form-group">
                <label>CV</label>
                <input type="text" name="cv" class="form-control" value="<?php echo $cv; ?>">
            </div>
            <div class="form-group">
                <label>Posti</label>
                <input type="text" name="posti" class="form-control" value="<?php echo $posti; ?>">
            </div>
            <div class="form-group">
                <label>Cilindrata</label>
                <input type="text" name="cilindrata" class="form-control" value="<?php echo $cilindrata; ?>">
            </div>
            <div class="form-group">
                <label>Potenza</label>
                <input type="text" name="potenza" class="form-control" value="<?php echo $potenza; ?>">
            </div>
            <input type="hidden" name="idVeicolo" value="<?php echo $idVeicolo; ?>">
            <input type="submit" class="btn btn-primary" value="Salva Modifiche">
            <a href="index.php" class="btn btn-secondary">Annulla</a>
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
    