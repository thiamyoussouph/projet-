<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si toutes les données attendues sont présentes
    if (isset($_POST['Nom'], $_POST['Prenom'], $_POST['Telephone'], $_POST['email'], $_POST['password']) 
        && !empty(trim($_POST['Nom'])) 
        && !empty(trim($_POST['Prenom'])) 
        && !empty(trim($_POST['Telephone'])) 
        && !empty(trim($_POST['email'])) 
        && !empty(trim($_POST['password']))) {

        // Nettoyer et valider les entrées
        $Nom = htmlspecialchars(trim($_POST['Nom']));
        $Prenom = htmlspecialchars(trim($_POST['Prenom']));
        $telephone = htmlspecialchars(trim($_POST['Telephone']));
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);

        // Vérification de la validité de l'email
        if ($email === false) {
            $_SESSION['error'] = "email invalide";
            header('Location: inscription.php');
            exit();
        }

        // Chiffrer le mot de passe
        $password_hashe = password_hash($password, PASSWORD_BCRYPT);

        // Connexion à la base de données
        include_once "connexion.php";

        try {
            $req = $db->prepare("INSERT INTO employe(Nom, Prenom, Telephone, email, password) VALUES(?, ?, ?, ?, ?)");
            $req->execute([$Nom, $Prenom, $telephone, $email, $password_hashe]);

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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="inscription.css">
    <title>Inscription</title>
    <style>
       
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
            <label for="email">login</label>
            <input type="text" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Envoyer</button>
    </form>

</body>
</html>
