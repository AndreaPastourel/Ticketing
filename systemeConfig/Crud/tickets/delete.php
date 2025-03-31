<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/SystemeConfig/dbConnect.php');
$id = $_GET['id'];

try {
   $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = :id");
   $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: ticket.php");
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>