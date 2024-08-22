<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php');
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['email'], $_POST['password']) 
        && !empty(trim($_POST['email'])) 
        && !empty(trim($_POST['password']))) {

        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);

        if ($email === false) {
            $_SESSION['error'] = "Adresse email invalide";
            header('Location: login.php');
            exit();
        }

        include_once "connexion.php";

        try {
            $stmt = $db->prepare("SELECT id, nom, prenom, password FROM employe WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_name'] = $user['nom'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['Prenom'] = $user['prenom']; 
                    $_SESSION['Nom'] = $user['nom']; 
                    $_SESSION['logged_in'] = true;

                    header('Location: dashboard.php');
                    exit();
                } else {
                    $_SESSION['error'] = "Email ou mot de passe incorrect";
                }
            } else {
                $_SESSION['error'] = "Email ou mot de passe incorrect";
            }

        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue. Veuillez réessayer plus tard.";
        }

    } else {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
    }

    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
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
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <form method="POST" action="login.php">
        <!-- Afficher les messages d'erreur -->
        <?php
        
        if (isset($_SESSION['error'])) {
            echo '<div class="message error">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']); // Supprime le message après l'affichage pour éviter une boucle
        }
        ?>
        <h2>Connexion</h2>
        <div>
            <label for="email">Adresse email</label>
            <input type="text" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
