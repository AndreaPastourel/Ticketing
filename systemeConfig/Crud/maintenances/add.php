<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/dbConnect.php');

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $equipement = htmlspecialchars($_POST['equipement']);
    $date = htmlspecialchars($_POST['date']);
    $status = htmlspecialchars($_POST['status']);

    // Validation des champs obligatoires
    if (empty($equipement) || empty($date) || empty($status)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            // Requête d'insertion avec PDO
            $query = "INSERT INTO maintenances (id_equipement, date, status) VALUES (:equipement, :date, :status)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':equipement' => $equipement,
                ':date' => $date,
                ':status' => $status
            ]);

            $success = "Maintenance ajoutée avec succès !";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout des données : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Maintenance</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
</head>
<body>
    <!-- Navigation -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/headFoot/nav.php'); ?>

    <div class="FormCrud">
        <h1>Ajouter une maintenance</h1>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if ($success): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <table>
                <tr>
                    <td>Équipement :</td>
                    <td>
                        <select name="equipement" required>
                            <option value="">-- Sélectionner un équipement --</option>
                            <?php
                            try {
                                $equipements = $pdo->query("SELECT id_equipement, nom FROM equipement");
                                while ($equipement = $equipements->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$equipement['id_equipement']}'>" . htmlspecialchars($equipement['nom']) . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo "<option value=''>Erreur de récupération</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Date :</td>
                    <td><input type="date" name="date" required></td>
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
                        <input type="submit" name="submit" value="Ajouter" class="btn-ajouter">
                        <a href="/SystemeConfig/crud/maintenance/maintenances.php" class="btn-retour">Retour</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>