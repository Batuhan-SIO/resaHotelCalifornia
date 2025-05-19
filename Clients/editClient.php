<?php
require_once '../config/db_connect.php';

// Récupération et validation de client_id depuis GET
$client_id = isset($_GET['client_id']) ? (int)$_GET['client_id'] : 0;
if ($client_id <= 0) {
    header("Location: listClients.php?error=invalid_id");
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Préparation de la requête pour récupérer le client par client_id
    $stmt = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
    if (!$stmt) {
        throw new Exception("Erreur lors de la préparation de la requête");
    }

    if (!$stmt->execute([$client_id])) {
        throw new Exception("Erreur lors de l'exécution de la requête");
    }

    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        header("Location: listClients.php?error=not_found");
        exit;
    }

    // Traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';

        $updateStmt = $conn->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE client_id = ?");
        $updateStmt->execute([$nom, $prenom, $email, $telephone, $client_id]);

        header("Location: listClients.php?message=Modification réussie");
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
    <title>Modifier Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Modifier le client #<?= htmlspecialchars($client_id) ?></h1>

    <form method="post">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" required value="<?= htmlspecialchars($client['nom']) ?>">
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control" required value="<?= htmlspecialchars($client['prenom']) ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($client['email']) ?>">
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Téléphone</label>
            <input type="text" id="telephone" name="telephone" class="form-control" required value="<?= htmlspecialchars($client['telephone']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="listClients.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
