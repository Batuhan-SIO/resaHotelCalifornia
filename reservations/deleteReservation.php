<?php
require_once '../config/db_connect.php';

if (!isset($_GET['id'])) {
    die("ID de réservation manquant.");
}

$id = (int) $_GET['id'];

$conn = openDatabaseConnection();

// Option : récupérer le client_id associé si tu veux le supprimer aussi
$stmtClient = $conn->prepare("SELECT client_id FROM reservations WHERE id = ?");
$stmtClient->execute([$id]);
$client = $stmtClient->fetch(PDO::FETCH_ASSOC);
$client_id = $client ? $client['client_id'] : null;

// Supprimer la réservation
$stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
$stmt->execute([$id]);

// Facultatif : supprimer le client aussi (attention si le client a d'autres réservations)
// $stmt = $conn->prepare("DELETE FROM clients WHERE client_id = ?");
// $stmt->execute([$client_id]);

closeDatabaseConnection($conn);

header('Location: listReservations.php');
exit();