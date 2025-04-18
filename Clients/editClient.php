<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

// Récupération de l'ID du client
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($id <= 0) {
    header("Location: listClients.php");
    exit;
}

$conn = openDatabaseConnection();

// Méthode POST : Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);

    // Validation des données
    $errors = [];

    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire.";
    }

    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    // Si pas d'erreurs, mettre à jour les données
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, prenom = ?, email = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom, $email, $id]);

        // Rediriger vers la liste des clients
        header("Location: listClients.php?success=1");
        exit;
    }
} else {
    // Méthode GET : Récupérer les données du client
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le client n'existe pas, rediriger
    if (!$client) {
        header("Location: listClients.php");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un Client</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    
</head>
<body>
    <div class="navbar">
        <a href="../index.php">Accueil</a>
        <a href="../chambres/listChambres.php">Chambres</a>
        <a href="listClients.php">Clients</a>
        <a href="../reservations/listReservations.php">Réservations</a>
    </div>
    <div class="container">
        <h1>Modifier un Client</h1>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="listClients.php" class="btn btn-danger">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>
