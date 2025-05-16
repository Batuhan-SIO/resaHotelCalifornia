<?php
session_start(); // Démarre la session avant toute sortie HTML

require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';

// Vérifie si l'utilisateur a le rôle "directeur"
if (!hasRole("directeur")) {
    $encodedMessage = urlencode("ERREUR 😊 : Vous n'avez pas les bonnes permissions.".hasRole("directeur"));
    header("Location: /resaHotelCalifornia/auth/login.php?message=$encodedMessage");
    exit;
}

// Connexion à la base de données
$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM clients ORDER BY nom");
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 70px;
            background-image: url('https://media.istockphoto.com/id/104731717/fr/photo/centre-de-vill%C3%A9giature-de-luxe.jpg?s=612x612&w=0&k=20&c=qn-Ugr3N5J_JBKZttni3vimlfBOd52jWG3FouENXye0=');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php"><i class="fas fa-hotel"></i> Hôtel California</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="../chambres/listChambres.php">Chambres</a></li>
        <li class="nav-item"><a class="nav-link active" href="listClients.php">Clients</a></li>
        <li class="nav-item"><a class="nav-link" href="../reservations/listReservations.php">Réservations</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <?php
    if (isset($_COOKIE['message'])) {
        $message = htmlspecialchars(urldecode($_COOKIE['message']));
        $alertType = (strpos($message, 'ERREUR') !== false) ? 'warning' : 'success';
        echo "<div class='alert alert-$alertType alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
        setcookie('message', '', time() - 3600, '/'); // Supprime le cookie
    }
    ?>

    <h2 class="my-4">Liste des clients</h2>
    <a href="createClient.php" class="btn btn-success mb-3"><i class="fas fa-user-plus"></i> Ajouter un client</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($clients as $client): ?>
            <tr>
                <td><?= $client['client_id'] ?></td>
                <td><?= $client['nom'] ?></td>
                <td><?= $client['prenom'] ?></td>
                <td><?= $client['email'] ?></td>
                <td>
                    <a href="editClient.php?id=<?= $client['client_id'] ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="deleteClient.php?id=<?= $client['client_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce client ?')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
