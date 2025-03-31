
php
Copier le code
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once($_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/dbConnect.php');

// Initialisation des variables
$success = "";
$error = "";

// Vérification de l'ID dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du ticket manquant.");
}

$id = intval($_GET['id']);

// Récupération des données existantes du ticket
try {
    // Requête pour le ticket
    $stmt = $pdo->prepare("
        SELECT t.*, u.nom AS user_nom, u.prenom AS user_prenom, e.nom AS equipement_nom
        FROM ticket t
        LEFT JOIN users u ON t.id_users = u.id
        LEFT JOIN equipement e ON t.id_equipement = e.id_equipement
        WHERE t.id = :id
    ");
    $stmt->execute([':id' => $id]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        die("Ticket introuvable.");
    }

    // Requête pour la liste des utilisateurs
    $usersStmt = $pdo->query("SELECT id, nom, prenom FROM users");
    $users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

    // Requête pour la liste des équipements
    $equipementsStmt = $pdo->query("SELECT id_equipement, nom FROM equipement");
    $equipements = $equipementsStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_users = htmlspecialchars($_POST['id_users']);
    $id_equipement = htmlspecialchars($_POST['id_equipement']);
    $message = htmlspecialchars($_POST['message']);
    $etat = htmlspecialchars($_POST['etat']);
    $reponse = htmlspecialchars($_POST['reponse']);

    try {
        // Mise à jour du ticket
        $updateQuery = "
            UPDATE ticket 
            SET id_users = :id_users, 
                id_equipement = :id_equipement, 
                message = :message, 
                etat = :etat, 
                reponse = :reponse, 
                dateModification = NOW()
            WHERE id = :id
        ";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            ':id_users' => $id_users,
            ':id_equipement' => $id_equipement,
            ':message' => $message,
            ':etat' => $etat,
            ':reponse' => $reponse,
            ':id' => $id
        ]);

        $success = "Le ticket a été mis à jour avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Ticket</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
</head>
<body>
    <div class="FormCrud">
        <h1>Modifier un Ticket</h1>

        <!-- Messages de succès ou d'erreur -->
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <!-- Formulaire -->
        <form method="post" action="">
            <table>
                <tr>
                    <td>Utilisateur :</td>
                    <td>
                        <select name="id_users" required>
                            <option value="">-- Choisir un utilisateur --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>" 
                                    <?php echo ($user['id'] == $ticket['id_users']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Équipement :</td>
                    <td>
                        <select name="id_equipement" required>
                            <option value="">-- Choisir un équipement --</option>
                            <?php foreach ($equipements as $equipement): ?>
                                <option value="<?php echo $equipement['id_equipement']; ?>" 
                                    <?php echo ($equipement['id_equipement'] == $ticket['id_equipement']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($equipement['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Message :</td>
                    <td><textarea name="message" required><?php echo htmlspecialchars($ticket['message']); ?></textarea></td>
                </tr>
                <tr>
                    <td>État :</td>
                    <td>
                        <select name="etat" required>
                            <option value="attente" <?php echo ($ticket['etat'] == 'attente') ? 'selected' : ''; ?>>En attente</option>
                            <option value="traité" <?php echo ($ticket['etat'] == 'traité') ? 'selected' : ''; ?>>Traité</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Réponse :</td>
                    <td><textarea name="reponse"><?php echo htmlspecialchars($ticket['reponse']); ?></textarea></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <button type="submit" class="btn-ajouter">Mettre à jour</button>
                        <a href="listTickets.php" class="btn-retour">Retour</a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>