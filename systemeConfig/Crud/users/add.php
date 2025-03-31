<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/SystemeConfig/headFoot/header.php');
require_once($_SERVER['DOCUMENT_ROOT'] .$url. '/dbConnect.php');

$success = ""; // Initialisation de la variable pour le message de succès

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);

    // Vérification des champs obligatoires
    if (empty($username) || empty($nom) || empty($prenom) || empty($password) || empty($email) || empty($role)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Requête pour insérer l'utilisateur
        $sql = "INSERT INTO users (username, nom, prenom, mdp, email, role) VALUES (:username, :nom, :prenom, :mdp, :email, :role)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([
                ':username' => $username,
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':mdp' => $hashed_password,
                ':email' => $email,
                ':role' => $role
            ]);
            $success = "L'utilisateur a été ajouté avec succès !"; // Message de succès
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }
}
?>



<body>
<?php require_once($_SERVER['DOCUMENT_ROOT'] .$url. '/headFoot/nav.php'); ?>
    <div class="FormCrud">
        <h2>Ajouter un utilisateur</h2>

        <!-- Affichage du message de succès ou d'erreur -->
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>

         <!-- Bouton retour -->
    <p>
        <a href="/SystemeConfig/crud/users/users.php" class="btn-retour">Retour</a>
    </p>

        <form method="POST" action="">
            <table>
                <tr>
                    <td>Nom d'utilisateur:</td>
                    <td><input type="text" name="username" required></td>
                </tr>
                <tr>
                    <td>Nom:</td>
                    <td><input type="text" name="nom" required></td>
                </tr>
                <tr>
                    <td>Prénom:</td>
                    <td><input type="text" name="prenom" required></td>
                </tr>
                <tr>
                    <td>Mot de passe:</td>
                    <td><input type="password" name="password" required></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" name="email" required></td>
                </tr>
                <tr>
                    <td>Rôle:</td>
                    <td>
                        <select name="role" required>
                            <option value="admin">Admin</option>
                            <option value="utilisateur">Utilisateur</option>
                        </select>
                    </td>
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