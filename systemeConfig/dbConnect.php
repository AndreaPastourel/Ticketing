<?php $db = "gestion";
$dbhost = "localhost";
$dbport = 3306;
$dbuser = "root";
$dbpassword = "root";

try {
    $pdo = new PDO('mysql:host=' . $dbhost . ';port=' . $dbport . ';dbname=' . $db, $dbuser, $dbpassword);
    $pdo->exec("SET CHARACTER SET utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>


