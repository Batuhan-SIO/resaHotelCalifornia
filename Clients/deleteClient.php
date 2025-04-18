<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($id <= 0) {
    header("Location: listClients.php");
    exit;
}

try {
    $conn = openDatabaseConnection();

    // Vérifier si le client existe
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        header("Location: listClients.php");
        exit;
    }

    // Vérifier si le client a des réservations
    $stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE client_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    $hasReservations = ($count > 0);

    // Traitement de la suppression si confirmée
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        // Démarrer la transaction
        $conn->beginTransaction();

        try {
            // Si le client a des réservations et que l'utilisateur souhaite les supprimer aussi
            if ($hasReservations && isset($_POST['delete_reservations']) && $_POST['delete_reservations'] === 'yes') {
                $stmt = $conn->prepare("DELETE FROM reservations WHERE client_id = ?");
                $stmt->execute([$id]);
            } elseif ($hasReservations) {
                // Si le client a des réservations mais l'utilisateur ne veut pas les supprimer
                header("Location: listClients.php?error=1");
                exit;
            }

            // Supprimer le client
            $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$id]);

            // Commit des modifications
            $conn->commit();

            // Rediriger vers la liste des clients
            header("Location: listClients.php?deleted=1");
            exit;
        } catch (Exception $e) {
            // Si une erreur se produit, annuler la transaction
            $conn->rollBack();
            echo "Erreur : " . $e->getMessage();
        }
    }

    closeDatabaseConnection($conn);

} catch (Exception $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Supprimer un Client</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    
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
    <div class="container">
        <h1>Supprimer un Client</h1>
        
        <div class="warning-box">
            <p><strong>Attention :</strong> Vous êtes sur le point de supprimer le client <?= htmlspecialchars($client['nom']) ?> <?= htmlspecialchars($client['prenom']) ?>.</p>
        </div>
        
        <?php if ($hasReservations): ?>
            <div class="danger-box">
                <p><strong>Ce client a <?= $count ?> réservation(s) en cours.</strong></p>
                <p>La suppression de ce client affectera les réservations existantes.</p>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <?php if ($hasReservations): ?>
                <div class="form-check">
                    <input type="checkbox" id="delete_reservations" name="delete_reservations" value="yes">
                    <label for="delete_reservations">Supprimer également les <?= $count ?> réservation(s) associée(s) à ce client</label>
                </div>
            <?php endif; ?>
            
            <p>Êtes-vous sûr de vouloir supprimer ce client ?</p>
            
            <div class="actions">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                <a href="listClients.php" class="btn btn-primary">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
