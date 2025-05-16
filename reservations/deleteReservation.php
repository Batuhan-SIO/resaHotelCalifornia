<?php
// Suppression d'une chambre avec gestion des réservations associées

// Chemin à adapter selon ton arborescence
require_once __DIR__ . '/../config/db_connect.php';

$chambre_id = isset($_GET['chambre_id']) ? (int)$_GET['chambre_id'] : 0;

if ($chambre_id <= 0) {
    header("Location: listChambres.php");
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Vérifier si la chambre existe
    $stmt = $conn->prepare("SELECT * FROM chambres WHERE chambre_id = ?");
    $stmt->execute([$chambre_id]);
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chambre) {
        header("Location: listChambres.php");
        exit;
    }

    // Vérifier si la chambre a des réservations
    // Ici on suppose que la table reservations a bien un champ chambre_id
    $stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE chambre_id = ?");
    $stmt->execute([$chambre_id]);
    $count = (int)$stmt->fetchColumn();

    $hasReservations = ($count > 0);

    // Traitement suppression
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $conn->beginTransaction();

        try {
            if ($hasReservations && isset($_POST['delete_reservations']) && $_POST['delete_reservations'] === 'yes') {
                // Supprimer les réservations liées
                // Note : ici on supprime par chambre_id, donc pas besoin de changer reservation_id en id
                $stmt = $conn->prepare("DELETE FROM reservations WHERE chambre_id = ?");
                $stmt->execute([$chambre_id]);
            } elseif ($hasReservations) {
                // Si réservations existantes et suppression non confirmée
                header("Location: listChambres.php?error=1");
                exit;
            }

            // Supprimer la chambre
            $stmt = $conn->prepare("DELETE FROM chambres WHERE chambre_id = ?");
            $stmt->execute([$chambre_id]);

            $conn->commit();

            header("Location: listChambres.php?deleted=1");
            exit;

        } catch (Exception $e) {
            $conn->rollBack();
            die("Erreur lors de la suppression : " . $e->getMessage());
        }
    }

    closeDatabaseConnection($conn);

} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Supprimer une Chambre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />
    <style>
        .warning-box {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #ffeeba;
        }
        .danger-box {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #f5c6cb;
        }
        .form-check {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Supprimer une Chambre</h1>

    <div class="warning-box">
        <p><strong>Attention :</strong> Vous êtes sur le point de supprimer la chambre numéro <?= htmlspecialchars($chambre['numero']) ?>.</p>
    </div>

    <?php if ($hasReservations): ?>
        <div class="danger-box">
            <p><strong>Cette chambre est associée à <?= $count ?> réservation(s).</strong></p>
            <p>La suppression de cette chambre affectera les réservations existantes.</p>
        </div>
    <?php endif; ?>

    <form method="post">
        <?php if ($hasReservations): ?>
            <div class="form-check">
                <input type="checkbox" id="delete_reservations" name="delete_reservations" value="yes" class="form-check-input" />
                <label for="delete_reservations" class="form-check-label">
                    Supprimer également les <?= $count ?> réservation(s) associée(s) à cette chambre
                </label>
            </div>
        <?php endif; ?>

        <p>Êtes-vous sûr de vouloir supprimer cette chambre ?</p>

        <div class="actions">
            <input type="hidden" name="confirm" value="yes" />
            <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
            <a href="listChambres.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
