<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclusion de la configuration et de la connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/systemeConfig/headFoot/header.php';
require_once($_SERVER['DOCUMENT_ROOT'] .$url . "/dbConnect.php");

// Vérification si un ID d'utilisateur est passé dans l'URL
if (!isset($_GET['id'])) {
    die("ID utilisateur manquant.");
}

$id = intval($_GET['id']); // ID utilisateur à modifier
$success = "";
$error = "";

// Récupération des informations de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Utilisateur non trouvé.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
}

// Mise à jour des informations de l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['mdp'];

    if (empty($username) || empty($nom) || empty($prenom) || empty($email) || empty($role)) {
        $error = "Tous les champs sauf le mot de passe sont obligatoires.";
    } else {
        try {
            $updateStmt = $pdo->prepare("UPDATE users SET username = ?, nom = ?, prenom = ?, email = ?, role = ?, mdp = ? WHERE id = ?");
            $updateStmt->execute([$username, $nom, $prenom, $email, $role, $password, $id]);

            $success = "Les informations de l'utilisateur ont été mises à jour avec succès.";
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Éditer un utilisateur</title>
    <link rel="stylesheet" href="/systemeConfig/style.css">
</head>
<body>
<div class="FormCrud">
    <h2>Modifier un utilisateur</h2>
    
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
                <td>Nom d'utilisateur:</td>
                <td><input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required></td>
            </tr>
            <tr>
                <td>Nom:</td>
                <td><input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required></td>
            </tr>
            <tr>
                <td>Prénom:</td>
                <td><input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></td>
            </tr>
            <tr>
                <td>Rôle:</td>
                <td>
                    <select name="role" required>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="utilisateur" <?php echo $user['role'] === 'utilisateur' ? 'selected' : ''; ?>>Utilisateur</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Mot de passe (laisser vide pour ne pas modifier):</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" value="Mettre à jour" class="btn-ajouter">
                    
        <a href="/SystemeConfig/crud/users/users.php" class="btn-retour">Retour</a>
    
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
