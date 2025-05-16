<?php
require_once '../config/db_connect.php';

// Récupération et validation de l'ID réservation depuis GET (paramètre 'id')
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    // Redirection ou message d'erreur si ID invalide
    header("Location: listReservations.php?error=invalid_id");
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Préparation de la requête pour récupérer la réservation par id
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Erreur lors de la préparation de la requête");
    }

    // Exécution avec le paramètre id
    if (!$stmt->execute([$id])) {
        throw new Exception("Erreur lors de l'exécution de la requête");
    }

    // Récupération des données
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        // Réservation non trouvée
        header("Location: listReservations.php?error=not_found");
        exit;
    }

    // Gestion du POST pour modifier la réservation
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : null;
        $chambre_id = isset($_POST['chambre_id']) ? (int)$_POST['chambre_id'] : null;
        $date_arrivee = $_POST['date_arrivee'] ?? null;
        $date_depart = $_POST['date_depart'] ?? null;

        // Ici tu peux ajouter des validations

        $updateStmt = $conn->prepare("UPDATE reservations SET client_id = ?, chambre_id = ?, date_arrivee = ?, date_depart = ? WHERE id = ?");
        $updateStmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart, $id]);

        header("Location: listReservations.php?message=Modification réussie");
        exit;
    }

    closeDatabaseConnection($conn);

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Modifier Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Modifier la réservation #<?= htmlspecialchars($id) ?></h1>

    <form method="post">
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <input type="number" id="client_id" name="client_id" class="form-control" required value="<?= htmlspecialchars($reservation['client_id']) ?>">
        </div>
        <div class="mb-3">
            <label for="chambre_id" class="form-label">Chambre</label>
            <input type="number" id="chambre_id" name="chambre_id" class="form-control" required value="<?= htmlspecialchars($reservation['chambre_id']) ?>">
        </div>
        <div class="mb-3">
            <label for="date_arrivee" class="form-label">Date d'arrivée</label>
            <input type="date" id="date_arrivee" name="date_arrivee" class="form-control" required value="<?= htmlspecialchars($reservation['date_arrivee']) ?>">
        </div>
        <div class="mb-3">
            <label for="date_depart" class="form-label">Date de départ</label>
            <input type="date" id="date_depart" name="date_depart" class="form-control" required value="<?= htmlspecialchars($reservation['date_depart']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
