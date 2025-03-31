<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l'utilisateur est connecté et est un employé
if (!isset($_SESSION['id']) || !isset($_SESSION['role']) ) {
    header("Location: /systemeConfig/login.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php';
require_once($_SERVER['DOCUMENT_ROOT'] .$url . "/dbConnect.php");

// Récupération des tickets avec les détails de l'équipement
try {
    $stmt = $pdo->prepare("
        SELECT 
            t.dateCreation, 
            t.dateModification, 
            t.message, 
            t.etat, 
            t.reponse, 
            e.nom AS nom_equipement 
        FROM 
            tickets t
        JOIN 
            equipements e ON t.id_equipement = e.id_equipement
    ");
    $stmt->execute();
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des tickets : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
    
</head>
<body>
<div class="ticket">
    <h2>Liste des Tickets</h2>
    <p><a href=<?php echo  $url."/Action/addTicket.php" ?>>Ouvrir un ticket</a></p>
    <?php if (empty($tickets)): ?>
        <p>Aucun ticket à afficher.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date de Création</th>
                    <th>Message</th>
                    <th>État</th>
                    <th>Réponse</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['dateCreation']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['message']); ?></td>
                        <td>
                            <?php if ($ticket['etat'] === 'attente'): ?>
                                <span class="status-attente">Attente</span>
                            <?php else: ?>
                                <span class="status-traité">Traité</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!isset($ticket)):?>
                            <?php echo htmlspecialchars($ticket['réponse']); ?>
                            <?else : ?>
                                <?php echo "Aucune reponse pour le moment"; ?>
                            <?php endif?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
