<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/dbConnect.php');

// Requête pour récupérer les équipements
$stmt = $pdo->query("
    SELECT 
        e.id_equipement, 
        e.nom AS equipement_nom, 
        t.nom AS type_nom, 
        e.marque, 
        e.numeroSerie, 
        e.garantie
    FROM equipements e
    LEFT JOIN types t ON e.type = t.id_type
    ORDER BY e.id_equipement ASC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CRUD Équipements</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
</head>
<body>
    <!-- Navigation -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/headFoot/nav.php'); ?>

    <div class="crud">
        <h1>CRUD Équipements</h1>
        <p><a href="<?php echo $url . "/crud/equipements/add.php"; ?>">Ajouter un équipement</a></p>

        <!-- Début du tableau CRUD -->
        <table>
            <tr>
                <td>ID</td>
                <td>Nom</td>
                <td>Type</td>
                <td>Marque</td>
                <td>Numéro de Série</td>
                <td>Garantie</td>
                <td>Action</td>
            </tr>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_equipement']); ?></td>
                    <td><?php echo htmlspecialchars($row['equipement_nom']); ?></td>
                    <td><?php echo htmlspecialchars($row['type_nom'] ?? "Non défini"); ?></td>
                    <td><?php echo htmlspecialchars($row['marque']); ?></td>
                    <td><?php echo htmlspecialchars($row['numeroSerie']); ?></td>
                    <td><?php echo htmlspecialchars($row['garantie'] ? $row['garantie'] . " mois" : "Non spécifiée"); ?></td>
                    <td>
                        <a href="<?php echo $url . "/crud/equipements/edit.php?id_equipement=" . $row['id_equipement']; ?>">Modifier</a> |
                        <a href="<?php echo $url . "/crud/equipements/delete.php?id_equipement=" . $row['id_equipement']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet équipement ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <!-- Fin du tableau CRUD -->
    </div>
</body>
</html>