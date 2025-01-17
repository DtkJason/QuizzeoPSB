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
        die("Échec de la connexion à la base de données: ");
    }
    return $conn;
}

// Vérifier si l'utilisateur est connecté en tant qu'admin, sinon rediriger vers la page de connexion
if (!isset($_SESSION["pseudo"]) || $_SESSION["role"] !== "admin") {
    header("location: Connexion.php");
    exit();
}

// Établir la connexion à la base de données
$conn = BDDconnect();

// Récupérer la liste des utilisateurs
$query = "SELECT * FROM Users";
$result = mysqli_query($conn, $query);
$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
    // Détruire la session
    session_destroy();

    // Rediriger vers la page de connexion
    header("location: Connexion.php");
    exit();
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
        <h1>Bonjour <span><?php echo ucfirst($_SESSION["pseudo"]); ?></span>, Bienvenue !</h1><br>
    
        <h3>Liste des utilisateurs</h3><br><br>
    <!-- création d'un tableau avec les données utilisateurs -->
        
        <div class ="tableau">
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
                        <a href="">Modifier</a>
                        <a href="">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
<!-- Liste des quizz & création  de quizz-->
<h3>Liste des quizz</h3>
    <a href="list_quiz.php">Voir la liste des quizz</a>

    <h3>Ajouter un quizz</h3>
    <a href="ajout_quiz.php">Ajouter un quizz</a>

    <h3>Quizz créés par le quizzeur</h3>
    <a href="user_quizzes.php">Voir les quizz créés par le quizzeur</a>
    </body>
</html>
