<?php
$userRole = $_SESSION['role'];
?>


<nav>
    <ul>
        <!-- Liens communs à tous les utilisateurs connectés -->
        <?php if (isset($_SESSION['username']) ): ?>
            <li><a href="logout.php">Déconnexion</a></li>
        <?php endif; ?>

        <!-- Barre de navigation pour les admins -->
        <?php if ($userRole === 'admin'): ?>
            <li><a href="/systemeConfig/crud/tickets/ticket.php">Gestion des tickets</a></li>
            <li><a href="/systemeConfig/crud/equipements/equipements.php">Gestion des PCs</a></li>
            <li><a href="/systemeConfig/crud/maintenances/maintenance.php">Maintenances PCs</a></li>
            <li><a href="/systemeConfig/crud/users/users.php">Gestion des utilisateurs</a></li>
        <?php endif; ?>

        <!-- Barre de navigation pour les utilisateurs -->
       
            <li><a href="/systemeConfig/myTickets.php">Mes anciens tickets</a></li>
            <li><a href ="/systemeConfig/Action/addTicket.php">Faire un ticket</a></li>
       

        <!-- Barre de navigation pour les visiteurs non connectés -->
        <?php if (!isset($_SESSION['username'])): ?>
            <li><a href="login.php">Connexion</a></li>
        <?php endif; ?>
    </ul>
</nav>
