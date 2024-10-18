<?php
    session_start(); // Démarrer la session
    session_destroy(); // Détruire la session

    header("Location: index.php"); // Redirection vers la page de login
    exit;
?>
