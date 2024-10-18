<?php
include "header.php";
include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}



// Supposons que tu as récupéré les informations de l'utilisateur à partir de son ID
$id_user = $_GET['update'];
$query = $bd->prepare("SELECT * FROM users WHERE id_user = :id_user");
$query->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);




if (isset($_POST['modifier'])) {
    $id_user = $_POST['id_user'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation des champs
    $errors = [];

    if (empty($username)) {
        $errors['username'] = "Le nom d'utilisateur est obligatoire.";
    }
    
    if (empty($email)) {
        $errors['email'] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Le format de l'email n'est pas valide.";
    }

    // Si tout est correct, on peut procéder à la mise à jour
    if (empty($errors)) {
        // Préparation de la requête d'update
        $query = "UPDATE users SET username = :username, email = :email";

        // Si un nouveau mot de passe est fourni, on le met à jour
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query .= ", password = :password";
        }

        $query .= " WHERE id_user = :id_user";

        // Exécution de la requête préparée
        $update = $bd->prepare($query);
        $update->bindParam(':username', $username, PDO::PARAM_STR);
        $update->bindParam(':email', $email, PDO::PARAM_STR);

        // Si le mot de passe doit être mis à jour
        if (!empty($password)) {
            $update->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        }

        $update->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $update->execute();

        // Message de succès ou redirection
        echo "Les informations ont été mises à jour avec succès.";
    } else {
        // Afficher les erreurs
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }

    header("location:users.php");
}
?>

<section>
    <h1 class="titre">Users</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                <!-- <strong></strong> -->
                <h3 class="title">Modifier un utilisateur</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Photo de profile:</span>
                        <label for="photo">Photo actuelle :</label>
                        <?php if (!empty($user['photo'])): ?>
                            <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" alt="Photo de profil" width="100">
                        <?php else: ?>
                            <p>Aucune photo disponible</p>
                        <?php endif; ?>
                        <input type="file" name="photo" accept="image/*">
                        <?php if (!empty($errors)): ?>
                            <small class="message_text"><?= isset($errors['photo']) ? $errors['photo'] : '' ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <span>Nom d'utilisateur:</span>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                        <?php if (!empty($errors)): ?>
                            <small class="message_text"><?= isset($errors['username']) ? $errors['username'] : '' ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <span>Email:</span>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        <small class="message_text"><?= isset($errors['email']) ? $errors['email'] : '' ?></small>
                    </div>
                    <div class="input-box">
                        <span>Mot de passe:</span>
                        <input type="password" name="password" placeholder="Laisse vide si tu ne veux pas changer">
                        <small class="message_text"><?= isset($errors['password']) ? $errors['password'] : '' ?></small>
                    </div>
                </div>
                <button type="submit" class="btn" name="modifier">Ajouter un utilisateur</button>
            </form>
        </div>
    </div>
</section>

<?php

?>