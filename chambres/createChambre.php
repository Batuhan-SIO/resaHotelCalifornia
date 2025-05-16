<?php
require_once '../config/db_connect.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero']);
    $capacite = (int)$_POST['capacite'];

    if ($numero === '' || $capacite <= 0) {
        $error = "Veuillez renseigner un numéro valide et une capacité supérieure à 0.";
    } else {
        try {
            $conn = openDatabaseConnection();

            $stmt = $conn->prepare("INSERT INTO chambres (numero, capacite) VALUES (?, ?)");
            $stmt->execute([$numero, $capacite]);

            closeDatabaseConnection($conn);

            // Redirection avec message en GET, urlencode pour éviter problème dans l'URL
            header("Location: listChambres.php?message=" . urlencode("Chambre ajoutée avec succès"));
            exit;

        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout de la chambre : " . $e->getMessage();
            if (isset($conn)) {
                closeDatabaseConnection($conn);
            }
        }
    }
}

// Récupérer message et erreur depuis GET (sécurisé)
if (isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
}
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter une chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
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
    <h2 class="my-4">Ajouter une chambre</h2>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
        <div class="mb-3">
            <label class="form-label">Numéro</label>
            <input type="text" name="numero" class="form-control" required value="<?= isset($_POST['numero']) ? htmlspecialchars($_POST['numero']) : '' ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Capacité</label>
            <input type="number" name="capacite" class="form-control" required min="1" value="<?= isset($_POST['capacite']) ? (int)$_POST['capacite'] : '' ?>">
        </div>
        <button type="submit" class="btn btn-primary">Valider</button>
        <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
