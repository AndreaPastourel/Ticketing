
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<?php 
    if (session_status() == PHP_SESSION_NONE) {
    session_start();}

    require_once ($_SERVER['DOCUMENT_ROOT'] .'/systemeConfig/headFoot/header.php');
/** 
   * if(!isset($_SESSION['username']) || $_SESSION['role']!="admin"){
    *  header("Location:".$url."/unauthorized.php");
     *  exit();
   * }
       */
    ?>
<?php
//Include the database connection file
require_once($_SERVER['DOCUMENT_ROOT'] .$url.'/dbConnect.php');

//Requete pour recuperer les utilisateurs 
$stmt=$pdo->query("SELECT * FROM users ORDER BY id");
?>

<!DOCTYPE html>
<html>



<body background='$url/imgage/bg2jpg'>
    
      <!-- header section strats -->
      <?php require_once($_SERVER['DOCUMENT_ROOT'] .$url.'/headFoot/nav.php')?>
      <!-- end header section -->
   


    <div class="crud">
    <h1>CRUD utilisateurs</h1>
    <p><a href=<?php echo  $url."/crud/users/add.php" ?>>Ajouter des utilisateurs</a></p>

    <!-- Debut du tableau crud -->
    <table>
        <tr>
            <td>ID</td>
            <td>Username</td>
            <td>Nom</td>
            <td>Prenom</td>
            <td>Password</td>
            <td>Email</td>
            <td>Role</td>
            <td>Action</td>
        </tr>
        <?php
        //boucles d'affichage
        while($res=$stmt->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>";
                echo "<td>".$res['id']."</td>";
                echo "<td>".$res['username']."</td>";
                echo "<td>".$res['nom']."</td>";
                echo "<td>".$res['prenom']."</td>";
                echo "<td>".str_repeat('*', strlen($res['mdp']))."</td>";
                echo "<td>".$res['email']."</td>";
                echo "<td>".$res['role']."</td>";
                echo "<td> <a href=\"/SystemeConfig/crud/users/edit.php?id={$res['id']}\">Modifier</a> | 
                          <a href=\"/SystemeConfig/crud/users/delete.php?id={$res['id']}\" onClick=\"return confirm('Etes vous sur de supprimer?')\">Supprimer</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <!--Fin du tableau crud-->
     
    </div>
 

 

</body>



</html>