<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: /systemeConfig/login.php");
    exit();
} else if ($_SESSION['role'] == "admin") {
    // Redirection pour l'admin
    header("Location: /systemeConfig/Crud/tickets/ticket.php");
    exit();
} else if ($_SESSION['role'] == "user") {
    // Redirection pour un utilisateur normal
    header("Location: /systemeConfig/myTicket.php");
    exit();
}
?>