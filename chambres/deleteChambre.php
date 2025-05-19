<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: listChambres.php");
    exit;
}

$id = (int)$_GET['id'];

$conn = openDatabaseConnection();

// Vérifie si la chambre existe
$stmtCheck = $conn->prepare("SELECT * FROM chambres WHERE chambre_id = ?");
$stmtCheck->execute([$id]);
$chambre = $stmtCheck->fetch(PDO::FETCH_ASSOC);

if (!$chambre) {
    closeDatabaseConnection($conn);
    header("Location: listChambres.php?error=notfound");
    exit;
}

// Supprime la chambre
$stmtDelete = $conn->prepare("DELETE FROM chambres WHERE chambre_id = ?");
$stmtDelete->execute([$id]);

closeDatabaseConnection($conn);

// Redirige après suppression
header("Location: listChambres.php?success=deleted");
exit;