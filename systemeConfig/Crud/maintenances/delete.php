<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/SystemeConfig/dbConnect.php');

$id = $_GET['id_maintenance'];

try {
   $stmt = $pdo->prepare("DELETE FROM maintenances WHERE id_maintenance = :id_maintenance");
   $stmt->bindParam(':id_maintenance', $id);
    $stmt->execute();
    header("Location: maintenances.php");
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>