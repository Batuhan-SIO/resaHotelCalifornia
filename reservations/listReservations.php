<?php
// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction simple pour vérifier le rôle
function hasRole(string $role): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

require_once '../config/db_connect.php';

// Vérifier que l'utilisateur est admin, sinon redirection vers la page de login avec message
if (!hasRole("admin")) {
    $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.");
    header("Location: /resaHotelCalifornia/auth/login.php?message=$encodedMessage");
    exit;
}

// Connexion à la base de données
$conn = openDatabaseConnection();

$query = "
    SELECT r.id, r.date_arrivee, r.date_depart,
           c.nom AS client_nom,
           ch.numero AS chambre_numero, ch.capacite AS chambre_capacite,
           r.nombre_personnes
    FROM reservations r
    JOIN clients c ON r.client_id = c.client_id
    JOIN chambres ch ON r.chambre_id = ch.chambre_id
    ORDER BY r.date_arrivee DESC
";

try {
    $stmt = $conn->query($query);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des réservations : " . $e->getMessage());
}

closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Liste des Réservations</title>
    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            padding-top: 70px;
            background-image: url('https://media.istockphoto.com/id/104731717/fr/photo/centre-de-vill%C3%A9giature-de-luxe.jpg?s=612x612&w=0&k=20&c=qn-Ugr3N5J_JBKZttni3vimlfBOd52jWG3FouENXye0=');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        .content-wrapper {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            max-width: 1000px;
            margin: 30px auto;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        }
        .table th, .table td {
            vertical-align: middle;
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
        <li class="nav-item"><a class="nav-link" href="../clients/listClients.php">Clients</a></li>
        <li class="nav-item"><a class="nav-link active" href="listReservations.php">Réservations</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="content-wrapper">
    <?php if (isset($_GET['message'])):
        $message = htmlspecialchars(urldecode($_GET['message']));
        $alertClass = (stripos($message, 'ERREUR') !== false) ? 'alert-warning' : 'alert-success';
    ?>
        <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h2 class="my-4 text-center">Liste des réservations</h2>

    <div class="text-center mb-3">
        <a href="createReservation.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter une réservation
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Chambre</th>
                    <th>Personnes</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservations)): ?>
                    <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?= htmlspecialchars($reservation['id']) ?></td>
                        <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                        <td>Chambre <?= htmlspecialchars($reservation['chambre_numero']) ?> (<?= htmlspecialchars($reservation['chambre_capacite']) ?> pers)</td>
                        <td><?= htmlspecialchars($reservation['nombre_personnes']) ?></td>
                        <td><?= htmlspecialchars($reservation['date_arrivee']) ?></td>
                        <td><?= htmlspecialchars($reservation['date_depart']) ?></td>
                        <td>
                            <a href="editReservation.php?id=<?= urlencode($reservation['id']) ?>" class="btn btn-warning btn-sm" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="deleteReservation.php?id=<?= urlencode($reservation['id']) ?>" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer cette réservation ?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Aucune réservation trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
