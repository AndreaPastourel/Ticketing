<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php');

// Vérification des permissions
/**
 * if (!isset($_SESSION['username']) || $_SESSION['role'] != "admin") {
 *     header("Location: " . $url . "/unauthorized.php");
 *     exit();
 * }
 */

require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/dbConnect.php');

try {
    // Requête pour récupérer les tickets
    $query = "
        SELECT 
            t.id, 
            t.dateCreation, 
            t.dateModification, 
            t.message, 
            t.etat, 
            t.reponse, 
            u.nom AS user_nom, 
            u.prenom AS user_prenom, 
            e.nom AS equipement_nom
        FROM 
            tickets t
        LEFT JOIN 
            users u ON t.id_users = u.id
        LEFT JOIN 
            equipements e ON t.id_equipement = e.id_equipement
        ORDER BY 
            t.dateCreation DESC
    ";

    $stmt = $pdo->query($query);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des tickets : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Tickets</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
    <style>
      
        .attente {
            background-color: rgb(255, 165, 0); /* Orange */
        }
        .traite {
            background-color: rgb(0, 255, 0); /* Vert */
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/headFoot/nav.php'); ?>

    <div class="crud">
        <h1>CRUD Tickets</h1>
        <p><a href="<?php echo $url . "/crud/tickets/add.php"; ?>">Créer un Nouveau Ticket</a></p>

        <!-- Tableau CRUD -->
        <table>
    <tr>
        <th>ID</th>
        <th>Utilisateur</th>
        <th>Équipement</th>
        <th>Message</th>
        <th>État</th>
        <th>Date Création</th>
        <th>Date Modification</th>
        <th>Action</th>
    </tr>
    <?php while ($res = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <?php 
            // Normalisation de l'état pour comparaison insensible à la casse
            $etat = strtolower(trim($res['etat'])); 
            $classe = ($etat === 'traité') ? 'traite' : (($etat === 'attente') ? 'attente' : '');
        ?>
        <tr class="<?php echo $classe; ?>">
            <td><?php echo htmlspecialchars($res['id']); ?></td>
            <td><?php echo htmlspecialchars($res['user_nom'] . " " . $res['user_prenom']); ?></td>
            <td><?php echo htmlspecialchars($res['equipement_nom'] ?? "Non spécifié"); ?></td>
            <td><?php echo htmlspecialchars($res['message']); ?></td>
            <td><?php echo htmlspecialchars(ucfirst($res['etat'])); ?></td>
            <td><?php echo htmlspecialchars($res['dateCreation']); ?></td>
            <td><?php echo htmlspecialchars($res['dateModification'] ?? "Non modifiée"); ?></td>
            <td>
                <a href="<?php echo $url . "/crud/tickets/edit.php?id={$res['id']}"; ?>">Modifier</a> |
                <a href="<?php echo $url . "/crud/tickets/delete.php?id={$res['id']}"; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket ?');">Supprimer</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
    </div>
</body>
</html>
