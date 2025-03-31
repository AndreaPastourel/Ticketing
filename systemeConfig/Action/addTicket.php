<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: /systemeConfig/login.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php';
require_once($_SERVER['DOCUMENT_ROOT'] . $url . "/dbConnect.php");

$success = "";
$error = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id']; // Récupère l'ID de l'utilisateur connecté
    $id_equipement = intval($_POST['id_equipement']);
    $objet = htmlspecialchars($_POST['objet']);
    $message = htmlspecialchars($_POST['message']);
    
    if (empty($id_equipement) || empty($objet) || empty($message)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            // Insertion du ticket dans la base de données
            $stmt = $pdo->prepare("
                INSERT INTO tickets (id_users, id_equipement, objet, message) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$user_id, $id_equipement, $objet, $message]);

            $success = "Votre ticket a été créé avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de la création du ticket : " . $e->getMessage();
        }
    }
}

// Récupération des équipements pour le formulaire
try {
    $stmt = $pdo->query("SELECT id_equipement, nom FROM equipements");
    $equipements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des équipements : " . $e->getMessage());
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
<div class="FormCrud">
    <h2>Ajouter un Ticket</h2>

    <!-- Affichage des messages -->
    <?php if ($success): ?>
        <p style="color: green;"><?php echo $success; ?></p>

        <a href="/systemeConfig/myTickets.php" class="btn-retour">Voir mes tickets</a>

    </br></br>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Formulaire -->
    <form method="POST" action="">
        <table>
            <tr>
                <td>Équipement :</td>
                <td>
                    <select name="id_equipement" required>
                        <option value="">-- Sélectionnez un équipement --</option>
                        <?php foreach ($equipements as $equipement): ?>
                            <option value="<?php echo $equipement['id_equipement']; ?>">
                                <?php echo htmlspecialchars($equipement['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Objet :</td>
                <td><input type="text" name="objet" required></td>
            </tr>
            <tr>
                <td>Message :</td>
                <td><textarea name="message" rows="5" required></textarea></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" value="Créer un ticket" class="btn-ajouter">
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
