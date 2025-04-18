<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

// Récupération de l'ID de la réservation
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($id <= 0) {
    header("Location: listReservations.php");
    exit;
}

$conn = openDatabaseConnection();

// Récupérer les détails de la réservation
$stmt = $conn->prepare("
    SELECT r.*, c.nom AS client_nom, c.prenom AS client_prenom, ch.numero AS chambre_numero
    FROM reservations r
    JOIN clients c ON r.client_id = c.id
    JOIN chambres ch ON r.chambre_id = ch.id
    WHERE r.id = ?
");
$stmt->execute([$id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la réservation n'existe pas, rediriger
if (!$reservation) {
    header("Location: listReservations.php");
    exit;
}

// Traitement de la suppression si confirmée
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: listReservations.php?deleted=1");
    exit;
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Supprimer une Réservation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    <style>
        .danger-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Supprimer une Réservation</h1>

        <div class="danger-box">
            <p><strong>Attention :</strong> Vous êtes sur le point de supprimer la réservation suivante :</p>
            <ul>
                <li><strong>Client :</strong> <?= htmlspecialchars($reservation['client_prenom'] . ' ' . $reservation['client_nom']) ?></li>
                <li><strong>Chambre :</strong> <?= htmlspecialchars($reservation['chambre_numero']) ?></li>
                <li><strong>Date d'arrivée :</strong> <?= htmlspecialchars($reservation['date_arrivee']) ?></li>
                <li><strong>Date de départ :</strong> <?= htmlspecialchars($reservation['date_depart']) ?></li>
            </ul>
        </div>

        <form method="post">
            <p>Êtes-vous sûr de vouloir supprimer cette réservation ?</p>

            <div class="actions">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                <a href="listReservations.php" class="btn btn-primary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
