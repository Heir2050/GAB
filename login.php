<?php

session_start(); // Démarrer la session

include "config.php";


$errors = [];

if (isset($_POST['login'])) {
    // Récupérer et nettoyer les entrées du formulaire
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors['email'] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Le format de l'email est invalide.";
    }

    if (empty($password)) {
        $errors['password'] = "Le mot de passe est obligatoire.";
    }

    // Si tout est correct, on continue la vérification
    if (empty($errors)) {
        // Vérifier l'email dans la base de données
        $query = $bd->prepare("SELECT * FROM users WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Vérification du mot de passe
            if (password_verify($password, $user['password'])) {
                // Mot de passe correct, démarrer la session utilisateur
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];

                // Redirection vers la page de tableau de bord (dashboard)
                header("Location: index.php");
                exit;
            } else {
                $errors['login'] = "Email ou mot de passe incorrect.";
            }
        } else {
            $errors['login'] = "Aucun compte n'est associé à cet email.";
        }
    }
}

// Afficher les erreurs s'il y en a
// if (!empty($errors)) {
//     foreach ($errors as $error) {
//         echo "<p>$error</p>";
//     }
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/index.css?v=1">
    <title>Login</title>
    <style>
        .message_text {
            color: red;
        }
    </style>
</head>
<body class="login">
    <div class="ins_login">
        <div class="cotainer">
            <form method="post">
                <h1 class="titre">Login</h1>
                <div class="input">
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Adresse email">
                    <?php if(!empty($errors)): ?>
                        <small class="message_text"><?= isset($errors['email']) ? $errors['email'] : '' ?></small>
                    <?php endif; ?>
                </div>
                <div class="input">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" placeholder="Mot de passe">
                    <?php if(!empty($errors)): ?>
                        <small class="message_text"><?= isset($errors['password']) ? $errors['password'] : '' ?></small>
                    <?php endif; ?>
                </div>
                <div class="input" style="flex-direction: row;">
                    <input type="submit" name="login" value="Login" class="login_btn">
                </div>
            </form>
        </div>
    </div>
</body>
</html>