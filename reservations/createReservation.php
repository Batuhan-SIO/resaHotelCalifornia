<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();

// Récupérer la liste des clients
$clients = $conn->query("SELECT id, prenom, nom FROM clients")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des chambres
$chambres = $conn->query("SELECT id, numero FROM chambres")->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)$_POST['client_id'];
    $chambre_id = (int)$_POST['chambre_id'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];

    // Validation simple
    if ($client_id <= 0) $errors[] = "Veuillez sélectionner un client.";
    if ($chambre_id <= 0) $errors[] = "Veuillez sélectionner une chambre.";
    if (empty($date_arrivee)) $errors[] = "La date d'arrivée est requise.";
    if (empty($date_depart)) $errors[] = "La date de départ est requise.";
    if (strtotime($date_arrivee) > strtotime($date_depart)) $errors[] = "La date d'arrivée doit être avant la date de départ.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO reservations (client_id, chambre_id, date_arrivee, date_depart) VALUES (?, ?, ?, ?)");
        $stmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart]);
        closeDatabaseConnection($conn);

        header("Location: listReservations.php?created=1");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Réservation</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Créer une Réservation</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="client_id" class="form-label">Client</label>
                <select name="client_id" id="client_id" class="form-select" required>
                    <option value="">-- Sélectionner un client --</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>">
                            <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="chambre_id" class="form-label">Chambre</label>
                <select name="chambre_id" id="chambre_id" class="form-select" required>
                    <option value="">-- Sélectionner une chambre --</option>
                    <?php foreach ($chambres as $chambre): ?>
                        <option value="<?= $chambre['id'] ?>">Chambre <?= htmlspecialchars($chambre['numero']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="date_arrivee" class="form-label">Date d'arrivée</label>
                <input type="date" id="date_arrivee" name="date_arrivee" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="date_depart" class="form-label">Date de départ</label>
                <input type="date" id="date_depart" name="date_depart" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
