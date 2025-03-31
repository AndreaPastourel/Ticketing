<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/systemeConfig/headFoot/header.php";
require_once($_SERVER['DOCUMENT_ROOT'] .$url."/dbConnect.php");

// Initialisation des messages d'erreur
$erreurUser = $erreurPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $erreurUser = "Le champ identifiant est vide.";
    } elseif (empty($_POST['password'])) {
        $erreurPassword = "Le champ mot de passe est vide.";
    } else {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST["password"]); // Mot de passe saisi par l'utilisateur

        try {
            // Préparation de la requête pour récupérer les informations de l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashed_password = $row['mdp']; // Mot de passe haché en base de données

                // Vérification du mot de passe
                if (password_verify($password, $hashed_password)) {
                    // Création de la session utilisateur
                    session_start();
                    $_SESSION['username'] = $username;
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['role'] = $row['role'];

                    // Redirection après connexion
                    header("Location: " . $url . "/index.php");
                    exit();
                } else {
                    $erreurPassword = "Mot de passe ou nom d'utilisateur incorrect.";
                }
            } else {
                $erreurUser = "Mot de passe ou nom d'utilisateur incorrect.";
            }

        } catch (PDOException $e) {
            echo "ERREUR : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="/systemeConfig/style.css"> <!-- Assurez-vous que ce fichier contient les styles -->
</head>
<body>
<div class="connexion">
    <h2>Se connecter</h2>
    <form method="POST" action="">
        <table>
            <tr>
                <td>Username</td>
                <td>
                    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    <?php if (!empty($erreurUser)): ?>
                        <span style="color: red;"><?php echo $erreurUser; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Password</td>
                <td>
                    <div class="password-container">
                        <input type="password" name="password" id="password" required>
                        <i class="far fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                    <?php if (!empty($erreurPassword)): ?>
                        <span style="color: red;"><?php echo $erreurPassword; ?></span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input type="submit" name="submit" value="Se connecter">
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>
