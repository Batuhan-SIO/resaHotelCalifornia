<?php
session_start();
require_once __DIR__ . '/config/db_connect.php';



// Protection : accès uniquement connecté
if (!isset($_SESSION['user_id'])) {
    $msg = urlencode("ERREUR : Veuillez vous connecter.");
    header("Location: /resaHotelCalifornia/login.php?message=$msg");
    exit;
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

$conn = openDatabaseConnection();

if ($_SESSION['role'] === 'standard') {
    $employeId = $_SESSION['user_id'];
    $query = "SELECT r.id, r.date_arrivee, r.date_depart,
                     c.nom AS client_nom, c.telephone, c.email,
                     ch.numero AS chambre_numero
              FROM reservations r
              JOIN clients c ON r.client_id = c.id
              JOIN chambres ch ON r.chambre_id = ch.id
              WHERE r.employe_id = :employeId
              ORDER BY r.date_arrivee DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute(['employeId' => $employeId]);
} else {
    // rôle responsable : voir tout
    $query = "SELECT r.id, r.date_arrivee, r.date_depart,
                     c.nom AS client_nom, c.telephone, c.email,
                     ch.numero AS chambre_numero
              FROM reservations r
              JOIN clients c ON r.client_id = c.id
              JOIN chambres ch ON r.chambre_id = ch.id
              ORDER BY r.date_arrivee DESC";
    $stmt = $conn->query($query);
}

$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Liste des réservations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <style>
    body { padding-top: 70px; background-color: #f8f9fa; }
    .container { max-width: 1000px; }
    .table th, .table td { vertical-align: middle; }
  </style>
</head>
<body>
<?php include_once '../assets/gestionMessage.php'; ?>
<?php include_once '../assets/navbar.php'; ?>

<div class="container">
  <h2 class="my-4">Liste des réservations</h2>
  <a href="createReservation.php" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Nouvelle réservation</a>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Chambre</th>
        <th>Arrivée</th>
        <th>Départ</th>
        <th>Contact</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($reservations as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['id']) ?></td>
          <td><?= htmlspecialchars($r['client_nom']) ?></td>
          <td>Chambre <?= htmlspecialchars($r['chambre_numero']) ?></td>
          <td><?= htmlspecialchars(formatDate($r['date_arrivee'])) ?></td>
          <td><?= htmlspecialchars(formatDate($r['date_depart'])) ?></td>
          <td><?= htmlspecialchars($r['telephone']) ?><br><?= htmlspecialchars($r['email']) ?></td>
          <td>
            <a href="editReservation.php?id=<?= urlencode($r['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
            <a href="deleteReservation.php?id=<?= urlencode($r['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash-alt"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if(empty($reservations)): ?>
        <tr><td colspan="7" class="text-center">Aucune réservation trouvée.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
