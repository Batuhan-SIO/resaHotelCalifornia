<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';
// Fonction pour formater les dates
function formatDate($date)
{
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}
// Récupération des réservations avec les informations des clients et des chambres
$conn = openDatabaseConnection();
$query = "SELECT r.id, r.date_arrivee, r.date_depart,
 c.nom AS client_nom, c.telephone AS client_telephone, c.email AS client_email,
c.nombre_personnes,
 ch.numero AS chambre_numero, ch.capacite AS chambre_capacite
 FROM reservations r
 JOIN clients c ON r.client_id = c.id
 JOIN chambres ch ON r.chambre_id = ch.id
 ORDER BY r.date_arrivee DESC";
$stmt = $conn->query($query);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Liste des Réservations</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 26px;
        }

        .actions {
            margin-bottom: 20px;
        }

        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
        }

        .status-past {
            color: #6c757d;
            font-weight: bold;
        }

        .status-active {
            color:rgb(6, 90, 26);
            font-weight: bold;
        }

        td .btn {
            margin-right: 5px;
        }

        .fa {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Liste des Réservations</h1>

        <div class="actions">
            <a href="createReservation.php" class="btn btn-success"><i class="fa fa-plus"></i>Nouvelle Réservation</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Chambre</th>
                    <th>Personnes</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <body>
                
                <?php if (count($reservations) > 0): ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <?php
                        $aujourd_hui = date('Y-m-d');
                        $statut = '';

                        if ($reservation['date_depart'] < $aujourd_hui) {
                            $statut_class = 'status-past';
                            $statut = 'Terminée';
                        } elseif (
                            $reservation['date_arrivee'] <= $aujourd_hui &&
                            $reservation['date_depart'] >= $aujourd_hui
                        ) {
                            $statut_class = 'status-active';
                            $statut = 'En cours';
                        } else {
                            $statut_class = '';
                            $statut = 'À venir';
                        }
                        ?>
                        <tr>
                            <td><?= $reservation['id'] ?></td>
                            <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                            <td>
                                <strong>Tél:</strong> <?= htmlspecialchars($reservation['client_telephone']) ?><br>
                                <strong>Email:</strong> <?= htmlspecialchars($reservation['client_email']) ?>
                            </td>
                            <td>N° <?= htmlspecialchars($reservation['chambre_numero']) ?> (<?= $reservation['chambre_capacite'] ?> pers.)</td>
                            <td><?= $reservation['nombre_personnes'] ?></td>
                            <td><?= formatDate($reservation['date_arrivee']) ?></td>
                            <td><?= formatDate($reservation['date_depart']) ?></td>
                            <td class="<?= $statut_class ?>"><?= $statut ?></td>
                            <td>
                                <a href="viewReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="editReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-primary">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-danger"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation?');">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Aucune réservation trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
