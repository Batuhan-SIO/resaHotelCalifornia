<!DOCTYPE html>

<html lang="fr">



<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion d'Hôtel</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-card {
            transition: transform 0.3s ease;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .nav-link {
            color: white !important;
            transition: opacity 0.3s ease;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .nav-link:hover {
            opacity: 0.9;
        }

        .hotel-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
        }



        .card-header {

            border-radius: 15px 15px 0 0;

        }

    </style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
crossorigin="anonymous">

</head>

<body>
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
                                class="nav-link bg-primary fw-bold  align-items-center gap-2">
                                <span class="badge rounded-pill bg-white text-primary px-3 py-1">100+</span>
                                <i class="fas fa-bed me-2"></i>
                                Gestion des Chambres
                            </a>
                            <a href="clients/listClients.php"
                                class="nav-link bg-secondary fw-bold  align-items-center gap-2">
                                <span class="badge rounded-pill bg-white text-secondary px-3 py-1">50+</span>
                                <i class="fas fa-users me-2"></i>
                                Gestion des Clients
                            </a>
                            <a href="reservations/listReservations.php"
                                class="nav-link bg-success fw-bold  align-items-center gap-2">
                                <span class="badge rounded-pill bg-white text-success px-3 py-1">75</span>
                                <i class="fas fa-calendar-check me-2"></i>
                                Gestion des Réservations
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap Bundle avec Popper -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</body>
</html>