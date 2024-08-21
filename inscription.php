<?php
var_dump($_POST);
if(isset($_POST['Nom'],$_POST['Prenom'],$_POST['Telephone'], $_POST['Login'], $_POST['password']) 
&& !empty(trim($_POST['Nom']))
&& !empty(trim($_POST['Prenom']))
&& !empty(trim($_POST['Telephone']))
&& !empty(trim($_POST['Login']))
&& !empty(trim($_POST['password']))){

    $Nom = htmlspecialchars(trim($_POST['Nom']));
        $Prenom = htmlspecialchars(trim($_POST['Prenom']));
        $telephone = htmlspecialchars(trim($_POST['Telephone']));
        $login = filter_var(trim($_POST['Login']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
        if( $login===false ){
            $_SESSION['error']= "login invalide";
            
            exit();

        }
        $password_hashe=password_hash($password , PASSWORD_BCRYPT);
        include_once"connexion.php";
        try {
            $req= db->prepare("INSERT INTO employe(Nom,Prenom, Telephone, Login,password ) VALUES(?, ?,?,?,?)");
            $req->execute([$Nom,$Prenom, $Telephone,$Login,$password]);
            $_SESSION['message']= "employe inscrit avec succes";
            header('location : dashboard.php');
            exit();
        } catch (PDOExecption $e ) {
            error_log("Erreur d'inscription : " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
            
        }
}else{
    
            $_SESSION['error'] = "tout les champs sont obligatoires.";
        
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
<form method="POST" action="">
    <div>
        <label for="Nom">Nom</label>
        <input type="text" name="Nom" id="Nom">
    </div>
    <div>
        <label for="Prenom">Prenom</label>
        <input type="text" name="Prenom" id="Prenom">
    </div>
    <div>
        <label for="Telephone">telephone</label>
        <input type="text" name="Telephone" id="Telephone">
    </div>
    <div>
        <label for="Login">login</label>
        <input type="text" name="Login" id="Login">
    </div>
    <div>
        <label for="password">password</label>
        <input type="password" name="password" id="password">
    </div>
    <input type="button" value="Envoyer">
    </div>
</form>
<body>
    
</html>
