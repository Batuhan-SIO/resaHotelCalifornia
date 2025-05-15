<?php
session_start();

echo "Chemin courant : " . __DIR__ . "<br>";

require_once __DIR__ . '/config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $conn = openDatabaseConnection();
        $stmt = $conn->prepare("SELECT * FROM employes WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        closeDatabaseConnection($conn);

        if ($user) {
            $passwordHash = hash('sha256', $password);
            if ($passwordHash === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: /resaHotelCalifornia/index.php");
                exit;
            }
        }
    }
    $message = urlencode("ERREUR : Nom d'utilisateur ou mot de passe incorrect");
    header("Location: login.php?message=$message");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Hôtel California</title>
    <style>
        /* Reset basique */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0D1B2A, #1B263B);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        form {
            background-color: #162447;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.6);
            width: 320px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            color: #FCA311;
            letter-spacing: 2px;
        }
        label {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1rem;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: none;
            border-radius: 6px;
            margin-top: 6px;
            font-size: 1rem;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: #162447;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            background-color: #FCA311;
            color: #162447;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background-color: #FCA311;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            color: #162447;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ffbb33;
        }
        /* Gestion message (alerte) */
        .alert {
            background-color: #ff4d4d;
            color: white;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 2px 8px rgba(255, 77, 77, 0.5);
        }
    </style>
</head>
<body>

<form method="post" action="">
    <h1>Connexion</h1>
    <?php include_once __DIR__ . '/assets/gestionMessage.php'; ?>
    <label for="username">Utilisateur :</label>
    <input type="text" id="username" name="username" required autofocus>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>

</body>
</html>
