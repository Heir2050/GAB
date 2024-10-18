<?php
include "header.php";
include "config.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php"); // Rediriger vers la page de login si non connecté
    exit;
}



$errors = []; // Pour stocker les erreurs

if (isset($_POST['ajouter'])) {
    // Initialisation des variables et nettoyage des entrées
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $photo = isset($_FILES['photo']) ? $_FILES['photo'] : null; // Vérifier si la photo est présente

    // Validation du nom d'utilisateur
    if (empty($username)) {
        $errors['username'] = "Le nom d'utilisateur est obligatoire.";
    } else {
        // Vérification si le nom d'utilisateur existe déjà
        $query = $bd->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $usernameExists = $query->fetchColumn();

        if ($usernameExists > 0) {
            $errors['username'] = "Ce nom d'utilisateur existe déjà.";
        }
    }

    // Validation de l'email
    if (empty($email)) {
        $errors['email'] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Le format de l'email n'est pas valide.";
    } else {
        // Vérification si l'email existe déjà
        $query = $bd->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $emailExists = $query->fetchColumn();

        if ($emailExists > 0) {
            $errors['email'] = "Cet email existe déjà.";
        }
    }

    // Validation du mot de passe
    if (empty($password)) {
        $errors['password'] = "Le mot de passe est obligatoire.";
    }

    // Validation du photo
    // if (empty($photo)) {
    //     $errors['photo'] = "Le photo est obligatoire.";
    // }

    // Gestion de la photo si elle est présente
    if ($photo && $photo['error'] == 0) {
        // Vérifier que le fichier est bien une image
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($photo['type'], $allowed_types)) {
            $errors['photo'] = "Seules les images JPEG, PNG et GIF sont autorisées.";
        } else {
            // Renommer le fichier pour éviter les conflits
            $photo_name = uniqid() . "_" . basename($photo['name']);
            $photo_path = "uploads/" . $photo_name;

            // Déplacer le fichier vers le répertoire de destination
            if (!move_uploaded_file($photo['tmp_name'], $photo_path)) {
                $errors['photo'] = "Échec de l'upload de l'image.";
            }
        }
    } else {
        $photo_path = null; // Aucun fichier uploadé
    }

    // Si tout est correct, on peut insérer dans la base de données
    if (empty($errors)) {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insertion dans la base de données
        $insert = $bd->prepare("INSERT INTO users (email, username, password, image) VALUES (:email, :username, :password, :photo)");
        $insert->bindParam(':email', $email, PDO::PARAM_STR);
        $insert->bindParam(':username', $username, PDO::PARAM_STR);
        $insert->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $insert->bindParam(':photo', $photo_path, PDO::PARAM_STR);

        $insert->execute();

        // Redirection ou message de succès
        echo "Utilisateur ajouté avec succès!";
    }
}



# Affichage des donné

$sql = "SELECT * FROM users";
$statement = $bd->query($sql);  // Executer la requette sql

// Avoire toute les banques
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

#Suppression des elements
if (isset($_GET['delete'])) {
    $delete = $bd->query("DELETE FROM contacts WHERE id_contact=".$_GET['delete']);

    header("location:index.php");
}


#Suppression des elements
if (isset($_GET['delete'])) {
    $delete = $bd->query("DELETE FROM contacts WHERE id_contact=".$_GET['delete']);

    header("location:index.php");
}

?>

<section>
    <h1 class="titre">Users</h1>
    <div class="cont" style="justify-content: center;min-height:initial;">
        <div class="row">
            <form method="POST" style="width: 500px;"  method="POST" enctype="multipart/form-data">
                <!-- <strong></strong> -->
                <h3 class="title">Ajouter un utilisateur</h3>
                <div class="column">
                    <div class="input-box">
                        <span>Photo de profile:</span>
                        <input type="file" name="photo" accept="image/*" placeholder="Ajouter un photo de profile">
                        <?php if(!empty($errors)): ?>
                            <small class="message_text"><?= isset($errors['photo']) ? $errors['photo'] : '' ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <span>Nom d'utilisateur:</span>
                        <input type="text" name="username" placeholder="Votre nom d'utilisateur">
                            <?php if(!empty($errors)): ?>
                                <small class="message_text"><?= isset($errors['username']) ? $errors['username'] : '' ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="input-box">
                        <span>Email:</span>
                        <input type="email" name="email" placeholder="Ajouter votre adresse mail">
                        <small class="message_text"><?= isset($errors['email']) ? $errors['email'] : '' ?></small>
                    </div>
                    <div class="input-box">
                        <span>Mot de passe:</span>
                        <input type="password" name="password" placeholder="Ajouter votre un mot de passe">
                        <small class="message_text"><?= isset($errors['password']) ? $errors['password'] : '' ?></small>
                    </div>
                </div>
                <button type="submit" class="btn" name="ajouter">Ajouter un utilisateur</button>
            </form>
        </div>
    </div>
</section>

<section>
    <div class="cont">
        <?php if ($statement): ?>
            <table style="width: 80%;">
                <tr>
                    <th>ID</th>
                    <th>image</th>
                    <th>username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_user']) ?></td>
                        <td>
                            <img src="<?= $user['id_user'] ?>" alt="">
                        </td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td class="ds">
                            <a href="up_users.php?update=<?= $user["id_user"]; ?>" class="bts warning" style="margin-right: .5rem;">Modifier</a>
                            <a href="a_users.php?delete=<?= $user["id_user"]; ?>" class="bts danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</section>

<?php include "footer.php"; ?>