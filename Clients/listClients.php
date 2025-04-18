<?php
require_once '../config/db_connect.php';

try {
    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("SELECT * FROM clients ORDER BY nom, prenom");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Erreur de base de données : " . htmlspecialchars($e->getMessage()));
} finally {
    closeDatabaseConnection($conn);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Liste des Clients</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers la feuille de style externe -->
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        .error { color: red; padding: 10px; margin: 10px 0; }
        .success { color: green; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <?php include '../assets/navbar.php'; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="container">
        <h1>Liste des Clients</h1>

        <div class="actions">
            <a href="createClient.php" class="btn btn-success">Ajouter un Client</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clients as $unClient): ?>
                    <tr>
                        <td><?= htmlspecialchars($unClient['id']) ?></td>
                        <td><?= htmlspecialchars($unClient['nom']) ?></td>
                        <td><?= htmlspecialchars($unClient['email']) ?></td>
                        <td><?= htmlspecialchars($unClient['telephone']) ?></td>
                        <td><?= htmlspecialchars($unClient['nombre_personnes']) ?></td>
                        <td>
                            <a href="editClient.php?id=<?= htmlspecialchars($client['client_id']) ?>"><i class="fas fa-pen"></i></a>
                            <a href="deleteClient.php?id=<?= htmlspecialchars($client['client_id']) ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>
</html>
