<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si toutes les données attendues sont présentes
    if (isset($_POST['Nom'], $_POST['Prenom'], $_POST['Telephone'], $_POST['Login'], $_POST['password']) 
        && !empty(trim($_POST['Nom'])) 
        && !empty(trim($_POST['Prenom'])) 
        && !empty(trim($_POST['Telephone'])) 
        && !empty(trim($_POST['Login'])) 
        && !empty(trim($_POST['password']))) {

        // Nettoyer et valider les entrées
        $Nom = htmlspecialchars(trim($_POST['Nom']));
        $Prenom = htmlspecialchars(trim($_POST['Prenom']));
        $telephone = htmlspecialchars(trim($_POST['Telephone']));
        $login = filter_var(trim($_POST['Login']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);

        // Vérification de la validité de l'email
        if ($login === false) {
            $_SESSION['error'] = "Login invalide";
            header('Location: inscription.php');
            exit();
        }

        // Chiffrer le mot de passe
        $password_hashe = password_hash($password, PASSWORD_BCRYPT);

        // Connexion à la base de données
        include_once "connexion.php";

        try {
            $req = $db->prepare("INSERT INTO employe(Nom, Prenom, Telephone, Login, password) VALUES(?, ?, ?, ?, ?)");
            $req->execute([$Nom, $Prenom, $telephone, $login, $password_hashe]);

            $_SESSION['message'] = "Employé inscrit avec succès";
            header('Location: dashboard.php');
            exit();
        } catch (PDOException $e) {
            error_log("Erreur d'inscription : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
            header('Location: inscription.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header('Location: inscription.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        /* CSS pour améliorer la présentation du formulaire */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <!-- Afficher les messages de succès ou d'erreur -->
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="message success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']); // Supprime le message après l'affichage pour éviter une boucle
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="message error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // Supprime le message après l'affichage pour éviter une boucle
        }
        ?>
        
        <div>
            <label for="Nom">Nom</label>
            <input type="text" name="Nom" id="Nom" required>
        </div>
        <div>
            <label for="Prenom">Prénom</label>
            <input type="text" name="Prenom" id="Prenom" required>
        </div>
        <div>
            <label for="Telephone">Téléphone</label>
            <input type="text" name="Telephone" id="Telephone" required>
        </div>
        <div>
            <label for="Login">Login</label>
            <input type="text" name="Login" id="Login" required>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Envoyer</button>
    </form>

</body>
</html>
