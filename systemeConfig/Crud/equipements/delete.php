<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/SystemeConfig/dbConnect.php');

$id = $_GET['id'];

try {
   $stmt = $pdo->prepare("DELETE FROM equipements WHERE id_equipement = :id");
   $stmt->bindParam(':id_equipement', $id);
    $stmt->execute();
    header("Location: equipements.php");
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>