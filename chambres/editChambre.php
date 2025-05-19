<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

// Récupération de l'ID depuis l'URL
$chambre_id = isset($_GET['chambre_id']) ? (int)$_GET['chambre_id'] : 0;

// Vérifie que l'ID est valide
if ($chambre_id <= 0) {
    header("Location: listChambres.php?error=invalid_id");
    exit;
}

// Récupération des données de la chambre
$stmt = $conn->prepare("SELECT * FROM chambres WHERE chambre_id = ?");
$stmt->execute([$chambre_id]);
$chambre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chambre) {
    header("Location: listChambres.php?error=not_found");
    exit;
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    // Mise à jour des données
    $updateStmt = $conn->prepare("UPDATE chambres SET numero = ?, capacite = ?, disponible = ? WHERE chambre_id = ?");
    $updateStmt->execute([$numero, $capacite, $disponible, $chambre_id]);

    closeDatabaseConnection($conn);
    header("Location: listChambres.php?message=modification_reussie");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php"><i class="fas fa-hotel"></i> Hôtel California</a>
  </div>
</nav>

<div class="container">
    <h2 class="my-4">Modifier la chambre</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Numéro</label>
            <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($chambre['numero']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Capacité</label>
            <input type="number" name="capacite" class="form-control" value="<?= htmlspecialchars($chambre['capacite']) ?>" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="disponible" id="disponible" <?= $chambre['disponible'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="disponible">Disponible</label>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
