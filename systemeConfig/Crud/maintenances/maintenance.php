<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/dbConnect.php');

try {
    // Requête pour récupérer les données avec jointure pour l'équipement
    $query = "
        SELECT 
            m.id_maintenance, 
            e.nom AS equipement_nom, 
            m.date, 
            m.status
        FROM maintenances m
        LEFT JOIN equipements e ON m.id_equipement = e.id_equipement
        ORDER BY m.date DESC
    ";
    $stmt = $pdo->query($query);
    $maintenances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Maintenances</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
    <style>
        .terminee { background-color: rgb(0, 255, 0); }
        .en-cours { background-color: rgb(255, 165, 0); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/headFoot/nav.php'); ?>

    <div class="FormCrud">
        <h1>Liste des Maintenances</h1>
        <a href="add.php">Ajouter une Nouvelle Maintenance</a>
        <table border="1">
            <thead>
                <tr>
                    <th>ID Maintenance</th>
                    <th>Équipement</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($maintenances as $row): ?>
                <tr class="<?php echo ($row['status'] == 'fini') ? 'fini' : 'en-cours'; ?>">
                    <td><?php echo htmlspecialchars($row['id_maintenance']); ?></td>
                    <td><?php echo htmlspecialchars($row['equipement_nom'] ?? 'Non défini'); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
                    <td>
                        <a href="edite.php?id=<?php echo $row['id_maintenance']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $row['id_maintenance']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette maintenance ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    </div>
</body>
</html>