<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclusion des fichiers nécessaires
require_once $_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php';
require_once($_SERVER['DOCUMENT_ROOT'] . $url . "/dbConnect.php");

// Vérification si un ID d'équipement est passé dans l'URL
if (!isset($_GET['id_equipement'])) {
    die("ID d'équipement manquant.");
}

$id = intval($_GET['id_equipement']); // ID de l'équipement à modifier
$success = "";
$error = "";

// Récupération des informations de l'équipement
try {
    $stmt = $pdo->prepare("SELECT * FROM equipement WHERE id_equipement = ?");
    $stmt->execute([$id]);
    $equipement = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipement) {
        die("Équipement non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'équipement : " . $e->getMessage());
}

// Mise à jour des informations de l'équipement
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $type = htmlspecialchars($_POST['type']);
    $marque = htmlspecialchars($_POST['marque']);
    $numeroSerie = htmlspecialchars($_POST['numeroSerie']);
    $garantie = htmlspecialchars($_POST['garantie']);

    if (empty($nom) || empty($type) || empty($marque) || empty($numeroSerie) || empty($garantie)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            // Récupération de l'ID du type à partir du nom
            $stmtType = $pdo->prepare("SELECT id_type FROM type WHERE nom = ?");
            $stmtType->execute([$type]);
            $typeData = $stmtType->fetch(PDO::FETCH_ASSOC);

            if (!$typeData) {
                $error = "Le type spécifié n'existe pas.";
            } else {
                $type_id = $typeData['id_type'];

                // Mise à jour de l'équipement
                $updateStmt = $pdo->prepare("
                    UPDATE equipement 
                    SET nom = ?, type = ?, marque = ?, numeroSerie = ?, garantie = ?
                    WHERE id_equipement = ?
                ");
                $updateStmt->execute([$nom, $type_id, $marque, $numeroSerie, $garantie, $id]);

                $success = "Les informations de l'équipement ont été mises à jour avec succès.";
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour de l'équipement : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Éditer un équipement</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
</head>
<body>
<div class="FormCrud">
    <h2>Modifier un équipement</h2>
    
    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <table>
            <tr>
                <td>Nom:</td>
                <td><input type="text" name="nom" value="<?php echo htmlspecialchars($equipement['nom']); ?>" required></td>
            </tr>
            <tr>
                <td>Type:</td>
                <td><input type="text" name="type" value="<?php echo htmlspecialchars($equipement['type']); ?>" required></td>
            </tr>
            <tr>
                <td>Marque:</td>
                <td><input type="text" name="marque" value="<?php echo htmlspecialchars($equipement['marque']); ?>" required></td>
            </tr>
            <tr>
                <td>Numéro de série:</td>
                <td><input type="text" name="numeroSerie" value="<?php echo htmlspecialchars($equipement['numeroSerie']); ?>" required></td>
            </tr>
            <tr>
                <td>Garantie:</td>
                <td><input type="text" name="garantie" value="<?php echo htmlspecialchars($equipement['garantie']); ?>" required></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" value="Mettre à jour" class="btn-ajouter">
                    
        <a href="/SystemeConfig/crud/equipements/equipements.php" class="btn-retour">Retour</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
