<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/dbConnect.php');

// Vérification de la présence d'un ID
if (!isset($_GET['id_maintenance']) || empty($_GET['id_maintenance'])) {
    die("ID de la maintenance manquant.");
}

$id = intval($_GET['id_maintenance']); // Sécurisation de l'ID

// Récupération des données de la maintenance
try {
    $stmt = $pdo->prepare("SELECT * FROM maintenances WHERE id_maintenance = :id");
    $stmt->execute([':id' => $id]);
    $maintenance = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$maintenance) {
        die("Maintenance introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Maintenance</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
</head>
<body>
    <!-- Navigation -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/headFoot/nav.php'); ?>

    <div class="FormCrud">
        <h1>Modifier une maintenance</h1>
        <form action="editActionMaint.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($maintenance['id_maintenance']); ?>">

            <table>
                <tr>
                    <td>Date :</td>
                    <td><input type="date" name="date" value="<?php echo htmlspecialchars($maintenance['date']); ?>" required></td>
                </tr>
                <tr>
                    <td>Statut :</td>
                    <td>
                        <select name="status" required>
                            <option value="">-- Sélectionner un statut --</option>
                            <option value="en cours">En cours</option>
                            <option value="terminée">Terminée</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit" class="btn-ajouter">Mettre à jour</button>
                        <a href="/SystemeConfig/crud/maintenance/maintenance.php" class="btn-retour">Retour</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>