<?php
require_once '../config/db_connect.php';

require_once '../auth/authFunctions.php';

// Vérification du rôle (admin requis ici — à adapter si besoin)
if (!hasRole("directeur")) {
    $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.".hasRole("directeur"));
    header("Location: /resaHotelCalifornia/auth/login.php?message=$encodedMessage");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $tel = $_POST['telephone'];
    $nb = $_POST['nombre_personnes'];

    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO clients (nom, email, telephone, nombre_personnes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $tel, $nb]);
    closeDatabaseConnection($conn);
    header("Location: listClients.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un client</title>
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
    <h2 class="my-4">Ajouter un client</h2>
    <form method="post">
        <div class="mb-3"><label class="form-label">Nom</label><input type="text" name="nom" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Téléphone</label><input type="text" name="telephone" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Nombre de personnes</label><input type="number" name="nombre_personnes" class="form-control" required></div>
        <button type="submit" class="btn btn-primary">Valider</button>
        <a href="listClients.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
