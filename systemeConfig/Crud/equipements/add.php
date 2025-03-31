<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/SystemeConfig/headFoot/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/dbConnect.php');

$success = ""; // Initialisation du message de succès

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $type = htmlspecialchars($_POST['type']);
    $marque = htmlspecialchars($_POST['marque']);
    $numeroSerie = htmlspecialchars($_POST['numeroSerie']);
    $garantie = htmlspecialchars($_POST['garantie']);

    // Vérification des champs obligatoires
    if (empty($nom) || empty($type) || empty($marque) || empty($numeroSerie) || empty($garantie)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Requête pour insérer un équipement
        $sql = "INSERT INTO equipements (nom, type, marque, numeroSerie, garantie) VALUES (:nom, :type, :marque, :numeroSerie, :garantie)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':nom' => $nom,
                ':type' => $type,
                ':marque' => $marque,
                ':numeroSerie' => $numeroSerie,
                ':garantie' => $garantie
            ]);
            $success = "L'équipement a été ajouté avec succès !"; // Message de succès
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout de l'équipement : " . $e->getMessage();
        }
    }
}
?>

<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/headFoot/nav.php'); ?>
    <div class="FormCrud">
        <h2>Ajouter un équipement</h2>

        <!-- Affichage des messages -->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <!-- Bouton retour -->
        <p>
            <a href="/SystemeConfig/crud/equipements/equipements.php" class="btn-retour">Retour</a>
        </p>

        <form method="POST" action="">
            <table>
                <tr>
                    <td>Nom:</td>
                    <td><input type="text" name="nom" required></td>
                </tr>
                <tr>
                    <td>Type:</td>
                    <td><input type="text" name="type" required></td>
                </tr>
                <tr>
                    <td>Marque:</td>
                    <td><input type="text" name="marque" required></td>
                </tr>
                <tr>
                    <td>Numéro de série:</td>
                    <td><input type="text" name="numeroSerie" required></td>
                </tr>
                <tr>
                    <td>Garantie:</td>
                    <td><input type="text" name="garantie" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" value="Ajouter" class="btn-ajouter">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>