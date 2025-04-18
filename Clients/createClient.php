<?php
require_once '../config/db_connect.php';

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = validateInput($_POST['nom']);
    $prenom = validateInput($_POST['prenom']);
    $email = validateInput($_POST['email']);

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse email invalide.");
    }

    try {
        $conn = openDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO clients (nom, prenom, email) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email]);
        closeDatabaseConnection($conn);

        header("Location: listClients.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un client</title>
    
          crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2>Créer un nouveau client</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
</body>
</html>
