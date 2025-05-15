<?php
// Gestion des messages d'erreur ou succès via le paramètre GET 'message'
if (isset($_GET['message'])) {
    // Nettoyer le message pour éviter les injections XSS
    $message = htmlspecialchars(urldecode($_GET['message']));

    // Afficher un alert différent selon le type de message
    if (strpos($message, 'ERREUR') !== false) {
        echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'>"
            . $message
            . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>"
            . "</div>";
    } else {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>"
            . $message
            . "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>"
            . "</div>";
    }
}
?>
