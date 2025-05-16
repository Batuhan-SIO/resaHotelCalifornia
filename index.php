<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Système de Gestion d'Hôtel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

    <!-- Font Awesome (version 6.4 stable) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous" />

    <style>
        /* Fond noir pour toute la page */
        body {
            background-color: #000;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
        }

        /* Carte blanche centrée */
        .dashboard-card {
            transition: transform 0.3s ease;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            background-color: #fff;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        /* Style liens dans la carte */
        .nav-link {
            color: #fff !important;
            transition: opacity 0.3s ease;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .nav-link:hover {
            opacity: 0.9;
            text-decoration: none;
        }

        .hotel-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
            color: #fff;
        }

        .card-header {
            border-radius: 15px 15px 0 0;
        }
    </style>
</head>

<body>

    <!-- Navbar noire -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Hôtel California</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="chambres/listChambres.php"><i class="fas fa-bed me-1"></i> Chambres</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clients/listClients.php"><i class="fas fa-users me-1"></i> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reservations/listReservations.php"><i class="fas fa-calendar-check me-1"></i> Réservations</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="auth/login.php"><i class="fas fa-user-lock me-1"></i> Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteneur principal -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card dashboard-card shadow-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="mb-3">Système de Gestion d'Hôtel</h1>
                        <div class="text-center">
                            <i class="fas fa-hotel hotel-icon"></i>
                        </div>
                    </div>

                    <div class="card-body">
                        <nav class="nav flex-column gap-3">
                            <a href="chambres/listChambres.php"
                                class="nav-link bg-primary fw-bold d-flex align-items-center gap-2">
                                <span class="badge rounded-pill bg-white text-primary px-3 py-1">100+</span>
                                <i class="fas fa-bed me-2"></i>
                                Gestion des Chambres
                            </a>

                            <a href="clients/listClients.php"
                                class="nav-link bg-secondary fw-bold d-flex align-items-center gap-2">
                                <span class="badge rounded-pill bg-white text-secondary px-3 py-1">50+</span>
                                <i class="fas fa-users me-2"></i>
                                Gestion des Clients
                            </a>

                            <a href="reservations/listReservations.php"
                                class="nav-link bg-success fw-bold d-flex align-items-center gap-2">
                                <span class="badge rounded-pill bg-white text-success px-3 py-1">75</span>
                                <i class="fas fa-calendar-check me-2"></i>
                                Gestion des Réservations
                            </a>

                            <a href="auth/login.php"
                                class="nav-link bg-dark fw-bold d-flex align-items-center gap-2">
                                <i class="fas fa-user-lock me-2"></i>
                                Connexion Employé
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
