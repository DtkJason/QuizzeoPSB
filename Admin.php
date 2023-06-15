<?php
include 'header.php';
?>
<?php
session_start();
// Fonction pour établir une connexion à la base de données
function BDDconnect() {
    $host = "127.0.0.1";
    $username = "root";
    $password = "";
    $database = "quizzeo";

    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) {
        die("Échec de la connexion à la base de données: " .mysqli_connect_error());
    }
    return $conn;
}

// Vérifier si l'utilisateur est connecté en tant qu'admin, sinon rediriger vers la page de connexion
if (!isset($_SESSION["pseudo"]) || $_SESSION["role"] !== "admin") {
    header("location: Connexion.php");
    exit();
}

if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
    // Détruire la session
    session_destroy();

    // Rediriger vers la page de connexion
    header("location: Connexion.php");
    exit();
}


// Traitement des actions de suppression des utilisateurs
if (isset($_GET['action']) && $_GET['action'] === 'deleteUser' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $conn = BDDconnect();
    $query = "DELETE FROM Users WHERE id_test = $userId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $message = "Utilisateur supprimé avec succès.";
    } else {
        $errorMessage = "Une erreur s'est produite lors de la suppression de l'utilisateur.";
    }
}
// Récupérer la liste des utilisateurs
$conn = BDDconnect();
$query = "SELECT * FROM Users";
$result = mysqli_query($conn, $query);
$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrateur</title>
</head>
<body>
    <form action="admin.php" method="post">
        <input type="hidden" name="logout" value="true">
        <button type="submit">Déconnexion</button>
    </form>
    <h1>Bonjour <span><?php echo ucfirst($_SESSION["pseudo"]); ?></span> , Bienvenue !</h1><hr>
    
    <h3>Liste des utilisateurs</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['id_test']; ?></td>
                <td><?php echo $user['pseudo']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id_test']; ?>">Modifier</a>
                    <a href="?action=deleteUser&id=<?php echo $user['id_test']; ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Liste des quizz</h3>
    <a href="quizz_list.php">Voir la liste des quizz</a>
    <h3>Créer un quizz</h3>
    <div class="créer">
        <input type="button" onclick="addquizz()" value="Créer"/><br></br>
        <form>  
            <div id="crea"></div><br>
            <button type="submit">valider la question</button>
        </form> 
    <h3>Ajouter un quizz</h3>
    <a href="ajout_quizz.php">Ajouter un quizz</a>
    <h3>Quizz créés par le quizzeur</h3>
    <a href="user_quizzes.php">Voir les quizz créés par le quizzeur</a>
</body>
<?php ?>
</html>
