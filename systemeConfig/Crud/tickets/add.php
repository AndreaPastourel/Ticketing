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

// Gestion du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $utilisateur = htmlspecialchars(trim($_POST['utilisateur']));
    $equipement = htmlspecialchars(trim($_POST['equipement']));
    $etat = htmlspecialchars(trim($_POST['etat']));
    $message = htmlspecialchars(trim($_POST['message']));
    $reponse = htmlspecialchars(trim($_POST['reponse']));

    // Validation des champs obligatoires
    $errors = [];
    if (empty($utilisateur)) $errors[] = "Le champ Utilisateur est vide.";
    if (empty($equipement)) $errors[] = "Le champ Équipement est vide.";
    if (empty($etat)) $errors[] = "Le champ État est vide.";
    if (empty($message)) $errors[] = "Le champ Message est vide.";
    if (empty($reponse)) $errors[] = "Le champ Réponse est vide.";

    if ($errors) {
        $error = implode("<br>", $errors);
    } else {
        try {
            // Requête d'insertion
            $query = "INSERT INTO tickets (utilisateur, equipement, etat, message, reponse) 
                      VALUES (:utilisateur, :equipement, :etat, :message, :reponse)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':utilisateur' => $utilisateur,
                ':equipement' => $equipement,
                ':etat' => $etat,
                ':message' => $message,
                ':reponse' => $reponse
            ]);

            $success = "Données ajoutées avec succès !";
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
    <title>Ajouter un Ticket</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
</head>
<body>
    <!-- Navigation -->
    <?php require_once($_SERVER['DOCUMENT_ROOT'] . $url . '/headFoot/nav.php'); ?>

    <div class="FormCrud">
        <h1>Ajouter un Ticket</h1>

        <!-- Messages de succès ou d'erreur -->
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
        <form action="" method="post">
            <table>
                <tr>
                    <td>Utilisateur :</td>
                    <td><input type="text" name="utilisateur" required></td>
                </tr>
                <tr>
                    <td>Équipement :</td>
                    <td><input type="text" name="equipement" required></td>
                </tr>
                <tr>
                    <td>État :</td>
                    <td><input type="text" name="etat" required></td>
                </tr>
                <tr>
                    <td>Message :</td>
                    <td><input type="text" name="message" required></td>
                </tr>
                <tr>
                    <td>Réponse :</td>
                    <td><input type="text" name="reponse" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" name="submit" value="Ajouter" class="btn-ajouter">
                        <a href="/SystemeConfig/crud/tickets/tickets.php" class="btn-retour">Retour</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>