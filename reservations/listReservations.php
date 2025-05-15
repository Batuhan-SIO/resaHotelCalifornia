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
    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
            background-image: url('https://media.istockphoto.com/id/104731717/fr/photo/centre-de-vill%C3%A9giature-de-luxe.jpg?s=612x612&w=0&k=20&c=qn-Ugr3N5J_JBKZttni3vimlfBOd52jWG3FouENXye0=');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        .content-wrapper {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            max-width: 1000px;
            margin: auto;
            margin-top: 30px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Navbar identique à listChambres -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php"><i class="fas fa-hotel"></i> Hôtel California</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="../chambres/listChambres.php">Chambres</a></li>
        <li class="nav-item"><a class="nav-link" href="../clients/listClients.php">Clients</a></li>
        <li class="nav-item"><a class="nav-link active" href="listReservations.php">Réservations</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="content-wrapper">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            ✅ Réservation enregistrée avec succès.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h2 class="my-4 text-center">Liste des réservations</h2>

    <div class="text-center mb-3">
        <a href="createReservation.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter une réservation
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped text-center align-middle">
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
                            <a href="editReservation.php?id=<?= htmlspecialchars($reservation['id']) ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="deleteReservation.php?id=<?= htmlspecialchars($reservation['id']) ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Supprimer cette réservation ?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
