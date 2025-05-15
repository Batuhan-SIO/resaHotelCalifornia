<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/db_connect.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } else {
        $conn = openDatabaseConnection();

        $stmt = $conn->prepare("SELECT * FROM employes WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        closeDatabaseConnection($conn);

        if ($user) {
            // COMPARAISON SIMPLE SANS password_verify
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['employes_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: ../reservations/listReservations.php?message=Connexion réussie');
                exit;
            } else {
                $errors[] = "Mot de passe incorrect.";
            }
        } else {
            $errors[] = "Utilisateur inconnu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Employé</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('fondhotel.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-login {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(255,255,255,0.3);
            color: white;
            width: 400px;
        }
        .btn-primary {
            background-color: #00BFFF;
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #009ACD;
        }
        label {
            margin-bottom: 5px;
        }
        .form-control {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="card-login">
    <h2 class="text-center mb-4">Connexion Employé 🔐</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-12">
            <label class="form-label">Nom d'utilisateur</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>

        <div class="col-12">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary mt-3 px-4">Se connecter</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
