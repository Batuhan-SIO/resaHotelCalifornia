<?php
// Fonction de connexion à la base de données qui retourne le handle
function openDatabaseConnection()
{
    $host = 'localhost';
    $port = "3308"; // ou votre port personnalisé
    $db = 'resaHotelCalifornia';
    $user = 'root';
    $pass = '';

    try {
        // Utilisation de PDO plutôt que MySQLi
        $conn = new PDO("mysql:host=$host;dbname=$db;port=$port", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}
function closeDatabaseConnection($conn)
{
    $conn = null; // Destructeur se charge de clore la connexion
}
?>