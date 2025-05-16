<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

$client_id = isset($_GET['client_id']) ? (int)$_GET['client_id'] : 0;

// Redirection si l'ID est invalide
if ($client_id <= 0) {
    header("Location: listClients.php");
    exit;
}

// Récupération des données du client
$stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    header("Location: listClients.php");
    exit;
}

// Mise à jour après soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $nombre_personnes = (int)($_POST['nombre_personnes'] ?? 1);

    // Préparation et exécution de la mise à jour
    $stmt = $conn->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ?, telephone = ?, nombre_personnes = ? WHERE client_id = ?");
    $stmt->execute([$nom, $prenom, $email, $telephone, $nombre_personnes, $client_id]);

    // Redirection après modification
    header("Location: listClients.php?success=1");
    exit;
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Modifier un client</h1>
    <form method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required value="<?= htmlspecialchars($client['nom']) ?>">
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" name="prenom" id="prenom" class="form-control" required value="<?= htmlspecialchars($client['prenom']) ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required value="<?= htmlspecialchars($client['email']) ?>">
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="<?= htmlspecialchars($client['telephone']) ?>">
        </div>
        <div class="mb-3">
            <label for="nombre_personnes" class="form-label">Nombre de personnes</label>
            <input type="number" name="nombre_personnes" id="nombre_personnes" class="form-control" required value="<?= htmlspecialchars($client['nombre_personnes']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="listClients.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
