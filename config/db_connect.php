<?php
function openDatabaseConnection()
{
    $host = 'localhost';
    $port = "3308";
    $db = 'resaHotelCalifornia';
    $user = 'root';
    $pass = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;port=$port", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}

// ✅ Passe la connexion par référence avec & pour la fermer proprement
function closeDatabaseConnection(&$conn)
{
    $conn = null;
}
?>
