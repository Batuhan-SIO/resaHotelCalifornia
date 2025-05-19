<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: listClients.php");
    exit;
}

$client_id = (int)$_GET['id'];

$conn = openDatabaseConnection();

// Vérifie si le client a des réservations
$stmt = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE client_id = ?");
$stmt->execute([$client_id]);
$reservationCount = $stmt->fetchColumn();

if ($reservationCount > 0) {
    closeDatabaseConnection($conn);
    header("Location: listClients.php?error=has_reservations");
    exit;
}

// Supprime le client
$stmt = $conn->prepare("DELETE FROM clients WHERE client_id = ?");
$stmt->execute([$client_id]);

closeDatabaseConnection($conn);
header("Location: listClients.php?success=deleted");
exit;