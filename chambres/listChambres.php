<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM chambres ORDER BY numero");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des chambres</title>
    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
            margin: auto;
            margin-top: 30px;
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
        <li class="nav-item"><a class="nav-link active" href="listChambres.php">Chambres</a></li>
        <li class="nav-item"><a class="nav-link" href="../clients/listClients.php">Clients</a></li>
        <li class="nav-item"><a class="nav-link" href="../reservations/listReservations.php">Réservations</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="content-wrapper">
    <?php
    // Gestion des messages d'erreurs
    if (isset($_GET['message'])) {
        $message = htmlspecialchars(urldecode($_GET['message']));
        $alertClass = strpos($message, 'ERREUR') !== false ? 'alert-warning' : 'alert-success';
        echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
    }
    ?>

    <h2 class="my-4 text-center">Liste des chambres</h2>
    <div class="text-center mb-3">
        <a href="createChambre.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter une chambre
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Capacité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($chambres as $chambre): ?>
                <tr>
                    <td><?= $chambre['id'] ?></td>
                    <td><?= $chambre['numero'] ?></td>
                    <td><?= $chambre['capacite'] ?></td>
                    <td>
                        <a href="editChambre.php?id=<?= $chambre['id'] ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="deleteChambre.php?id=<?= $chambre['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette chambre ?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
