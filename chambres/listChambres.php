<?php
// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion des fonctions d'authentification et de connexion DB
require_once '../config/db_connect.php';
require_once '../auth/authFunctions.php';

// Vérification du rôle (admin requis ici — à adapter si besoin)
if (!hasRole("directeur")) {
    $encodedMessage = urlencode("ERREUR : Vous n'avez pas les bonnes permissions.".hasRole("directeur"));
    header("Location: /resaHotelCalifornia/auth/login.php?message=$encodedMessage");
    exit;
}

// Connexion à la base de données
$conn = openDatabaseConnection();
if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

// Récupération des chambres
$stmt = $conn->query("SELECT * FROM chambres ORDER BY numero");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fermeture de la connexion
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Liste des chambres</title>
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
            max-width: 900px;
            margin: 30px auto 60px auto;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<?php include '../assets/navbar.php'; ?>

<div class="content-wrapper">

    <?php if (isset($_GET['message'])):
        $message = htmlspecialchars(urldecode($_GET['message']));
        $alertClass = (stripos($message, 'ERREUR') !== false) ? 'alert-warning' : 'alert-success';
    ?>
        <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    <?php endif; ?>

    <h2 class="text-center mb-4">Liste des chambres</h2>

    <div class="text-center mb-3">
        <a href="createChambre.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter une chambre
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Capacité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($chambres)): ?>
                    <tr><td colspan="4">Aucune chambre disponible.</td></tr>
                <?php else: ?>
                    <?php foreach ($chambres as $chambre): ?>
                        <tr>
                            <td><?= htmlspecialchars($chambre['chambre_id']) ?></td>
                            <td><?= htmlspecialchars($chambre['numero']) ?></td>
                            <td><?= htmlspecialchars($chambre['capacite']) ?></td>
                            <td>
                                <a href="editChambre.php?chambre_id=<?= urlencode($chambre['chambre_id']) ?>" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="deleteChambre.php?chambre_id=<?= urlencode($chambre['chambre_id']) ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Supprimer cette chambre ?')" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
