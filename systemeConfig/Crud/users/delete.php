<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/SystemeConfig/dbConnect.php');
$id = $_GET['id'];

try {
   
    $stmt = $pdo->prepare("DELETE FROM ticket WHERE id_users = ?");
    $stmt->execute([$id]);

    // Ensuite, supprimer l'utilisateur dans la table "users"
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    // Rediriger vers la page de gestion des utilisateurs après suppression
    header("Location: /SystemeConfig/crud/users/users.php");
    exit();
} catch(PDOException $e) {
    echo "ERREUR: " . $e->getMessage();
}
?>
