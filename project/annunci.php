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

// Query per ottenere gli annunci del concessionario loggato
$query = "SELECT Annuncio.*, Veicolo.*, Marca.NomeMarca, Modello.NomeModello
          FROM Annuncio
          JOIN Veicolo ON Annuncio.IdVeicolo = Veicolo.IdVeicolo
          JOIN Marca ON Veicolo.IdMarca = Marca.IdMarca
          JOIN Modello ON Veicolo.IdModello = Modello.IdModello
          WHERE Veicolo.IdConcessionario = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['idConcessionario']);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Annunci</title>
    <!-- Includi il framework Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Includi l'header -->
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h2>Gestione Annunci</h2>
        
        <!-- Link per aggiungere un nuovo annuncio -->
        <a href="aggiungi_annuncio.php" class="btn btn-primary mb-3">Aggiungi Nuovo Annuncio</a>


        <!-- Tabella degli annunci -->
        <table class="table">
            <thead>
                <tr>
                    <th>ID Annuncio</th>
                    <th>Descrizione</th>
                    <th>Prezzo</th>
                    <th>Marca</th>
                    <th>Modello</th>
                    <th>Operazioni</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['IdAnnuncio']; ?></td>
                        <td><?php echo $row['Descrizione']; ?></td>
                        <td><?php echo $row['Prezzo']; ?></td>
                        <td><?php echo $row['NomeMarca']; ?></td>
                        <td><?php echo $row['NomeModello']; ?></td>
                        <td>
                            <a href="modifica_annuncio.php?id=<?php echo $row['IdAnnuncio']; ?>" class="btn btn-sm btn-primary">Modifica</a>
                            <a href="elimina_annuncio.php?id=<?php echo $row['IdAnnuncio']; ?>" class="btn btn-sm btn-danger">Elimina</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Includi il footer -->
    <?php include 'includes/footer.php'; ?>





