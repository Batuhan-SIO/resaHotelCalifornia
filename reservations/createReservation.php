<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

// Clients
$clients = $conn->query("SELECT client_id, nom FROM clients")->fetchAll(PDO::FETCH_ASSOC);
// Chambres
$chambres = $conn->query("SELECT chambre_id, numero FROM chambres")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'];
    $chambre_id = $_POST['chambre_id'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];

    $stmt = $conn->prepare("INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES (?, ?, ?, ?)");
    $stmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart]);
    closeDatabaseConnection($conn);
    header("Location: listReservations.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter une réservation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body { padding-top: 70px; background-color: #f8f9fa; }
    .container { max-width: 600px; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php"><i class="fas fa-hotel"></i> Hôtel California</a>
  </div>
</nav>

<div class="container">
  <h2 class="my-4">Ajouter une réservation</h2>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Client</label>
      <select name="client_id" class="form-select" required>
        <option value="">-- Choisir --</option>
        <?php foreach ($clients as $c): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Chambre</label>
      <select name="chambre_id" class="form-select" required>
        <option value="">-- Choisir --</option>
        <?php foreach ($chambres as $ch): ?>
        <option value="<?= $ch['id'] ?>">Chambre <?= htmlspecialchars($ch['numero']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Date d'arrivée</label>
      <input type="date" name="date_arrivee" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Date de départ</label>
      <input type="date" name="date_depart" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Réserver</button>
    <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
