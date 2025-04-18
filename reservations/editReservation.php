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

// Méthode POST : Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)$_POST['client_id'];
    $chambre_id = (int)$_POST['chambre_id'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_depart = $_POST['date_depart'];

    // Validation des données
    $errors = [];

    if ($client_id <= 0) {
        $errors[] = "Le client est obligatoire.";
    }

    if ($chambre_id <= 0) {
        $errors[] = "La chambre est obligatoire.";
    }

    if (empty($date_arrivee)) {
        $errors[] = "La date d'arrivée est obligatoire.";
    }

    if (empty($date_depart)) {
        $errors[] = "La date de départ est obligatoire.";
    }

    if (strtotime($date_arrivee) > strtotime($date_depart)) {
        $errors[] = "La date d'arrivée doit être avant la date de départ.";
    }

    // Si pas d'erreurs, mettre à jour les données
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE reservations SET client_id = ?, chambre_id = ?, date_arrivee = ?, date_depart = ? WHERE id = ?");
        $stmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart, $id]);

        header("Location: listReservations.php?success=1");
        exit;
    }
} else {
    // Méthode GET : Récupérer les données de la réservation
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        header("Location: listReservations.php");
        exit;
    }
}

// Récupérer la liste des clients et des chambres pour les listes déroulantes
$clients = $conn->query("SELECT id, nom, prenom FROM clients")->fetchAll(PDO::FETCH_ASSOC);
$chambres = $conn->query("SELECT id, numero FROM chambres")->fetchAll(PDO::FETCH_ASSOC);

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier une Réservation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">Accueil</a>
        <a href="../chambres/listChambres.php">Chambres</a>
        <a href="../clients/listClients.php">Clients</a>
        <a href="listReservations.php">Réservations</a>
    </div>
    <div class="container">
        <h1>Modifier une Réservation</h1>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="client_id">Client :</label>
                <select name="client_id" id="client_id" required>
                    <option value="">-- Sélectionner un client --</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>" <?= $client['id'] == $reservation['client_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="chambre_id">Chambre :</label>
                <select name="chambre_id" id="chambre_id" required>
                    <option value="">-- Sélectionner une chambre --</option>
                    <?php foreach ($chambres as $chambre): ?>
                        <option value="<?= $chambre['id'] ?>" <?= $chambre['id'] == $reservation['chambre_id'] ? 'selected' : '' ?>>
                            Chambre <?= htmlspecialchars($chambre['numero']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="date_arrivee">Date d'arrivée :</label>
                <input type="date" id="date_arrivee" name="date_arrivee" value="<?= htmlspecialchars($reservation['date_arrivee']) ?>" required>
            </div>

            <div class="form-group">
                <label for="date_depart">Date de départ :</label>
                <input type="date" id="date_depart" name="date_depart" value="<?= htmlspecialchars($reservation['date_depart']) ?>" required>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="listReservations.php" class="btn btn-danger">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
