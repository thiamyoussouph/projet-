<?php
session_start();
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login.php');
    exit();
}

// Récupérer le nom de l'utilisateur depuis la session
$user_name = $_SESSION['prenom'] . ' ' . $_SESSION['nom'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Styles pour le menu de navigation */
        nav {
            background-color: #007bff;
            color: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            font-weight: bold;
        }

        nav ul li a:hover {
            background-color: #0056b3;
            border-radius: 4px;
        }

        .container {
            padding: 20px;
        }

        /* Responsive menu pour les petits écrans */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                align-items: flex-start;
            }

            nav ul li {
                margin-bottom: 10px;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profile.php">Mon Profil</a></li>
            <li><a href="employees.php">Employés</a></li>
            <li><a href="reports.php">Rapports</a></li>
            <li><a href="settings.php">Paramètres</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
            <li style="margin-left:auto;"><span>Bienvenue, <?php echo htmlspecialchars($user_name); ?></span></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Bienvenue sur le Tableau de Bord</h1>
        <p>Utilisez le menu ci-dessus pour naviguer entre les différentes sections du tableau de bord.</p>
    </div>
</body>
</html>