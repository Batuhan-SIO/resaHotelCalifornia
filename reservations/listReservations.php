<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

$query = "SELECT r.id, r.date_arrivee, r.date_depart,
                 c.nom AS client_nom,
                 ch.numero AS chambre_numero, ch.capacite AS chambre_capacite,
                 r.nombre_personnes
          FROM reservations r
          JOIN clients c ON r.client_id = c.client_id
          JOIN chambres ch ON r.chambre_id = ch.chambre_id
          ORDER BY r.date_arrivee DESC";

try {
    $stmt = $conn->query($query);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des réservations : " . $e->getMessage());
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: url('../fondReservation.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: white;
        }
        .container {
            margin-top: 80px;
            background: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }
        th, td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>

<?php include_once '../asset/navbar.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">Liste des Réservations</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">✅ Réservation enregistrée avec succès.</div>
    <?php endif; ?>

    <div class="text-end mb-3">
        <a href="createReservation.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter une réservation
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Chambre</th>
                    <th>Nombre de personnes</th>
                    <th>Date d'arrivée</th>
                    <th>Date de départ</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['id']) ?></td>
                        <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                        <td>Chambre <?= htmlspecialchars($reservation['chambre_numero']) ?> (<?= htmlspecialchars($reservation['chambre_capacite']) ?> pers)</td>
                        <td><?= htmlspecialchars($reservation['nombre_personnes']) ?></td>
                        <td><?= htmlspecialchars($reservation['date_arrivee']) ?></td>
                        <td><?= htmlspecialchars($reservation['date_depart']) ?></td>
                        <td>
                            <a href="editReservation.php?id=<?= htmlspecialchars($reservation['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="deleteReservation.php?id=<?= htmlspecialchars($reservation['id']) ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer cette réservation ?');">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
